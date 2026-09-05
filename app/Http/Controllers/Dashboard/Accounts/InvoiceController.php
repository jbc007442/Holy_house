<?php
namespace App\Http\Controllers\Dashboard\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Invoice Page
     */
    public function index()
    {
        return view('dashboard.accounts.invoices');
    }

    /**
     * Ajax Listing
     */
    public function ajaxInvoices(Request $request)
    {
        $user = auth()->user();

        $query = Invoice::with([
            'booking.room',
            'booking.guests',
            'booking.services',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Building Access
        |--------------------------------------------------------------------------
        |
        | Super Admin and Admin can see invoices from all buildings.
        |
        | Other users can only see invoices where the booking room
        | belongs to one of their assigned buildings.
        |
        */

        if (!$user->isSuperadmin() && !$user->isAdmin()) {

            $buildingIds = $user->buildings()
                ->pluck('buildings.id');

            $query->whereHas('booking.room', function ($room) use ($buildingIds) {

                $room->whereIn('building_id', $buildingIds);

            });
        }

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('invoice_no', 'like', "%{$search}%")

                    ->orWhereHas('booking', function ($booking) use ($search) {

                        $booking->where(
                            'booking_no',
                            'like',
                            "%{$search}%"
                        )

                            ->orWhereHas('guests', function ($guest) use ($search) {

                                $guest->where(
                                    'guest_name',
                                    'like',
                                    "%{$search}%"
                                );
                            });
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from')) {

            $query->whereDate(
                'created_at',
                '>=',
                $request->from
            );
        }

        if ($request->filled('to')) {

            $query->whereDate(
                'created_at',
                '<=',
                $request->to
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $allInvoices = (clone $query)->get();

        $statistics = [

            'total_invoice' => $allInvoices->count(),

            'revenue' => $allInvoices->sum('grand_total'),

            'tax' => $allInvoices->sum(function ($invoice) {

                $booking = $invoice->booking;

                if (!$booking) {
                    return 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Stay Days
                |--------------------------------------------------------------------------
                */

                $stayDays = max(
                    1,
                    (int) ceil(
                        \Carbon\Carbon::parse($booking->check_in)
                            ->diffInSeconds(
                                \Carbon\Carbon::parse($booking->check_out)
                            ) / 86400
                    )
                );

                /*
                |--------------------------------------------------------------------------
                | Room Rent
                |--------------------------------------------------------------------------
                */

                $roomRentTotal =
                    (float) $booking->room_rent * $stayDays;

                /*
                |--------------------------------------------------------------------------
                | Chargeable Services
                |--------------------------------------------------------------------------
                */

                $serviceTotal = $booking->services
                    ->where('type', 'chargeable')
                    ->sum('total_amount');

                /*
                |--------------------------------------------------------------------------
                | Discount
                |--------------------------------------------------------------------------
                */

                $discount = (float) (
                    $booking->discount ?? 0
                );

                /*
                |--------------------------------------------------------------------------
                | Late Checkout Fee
                |--------------------------------------------------------------------------
                */

                $lateCheckoutFee = (float) (
                    $booking->late_checkout_fee ?? 0
                );

                /*
                |--------------------------------------------------------------------------
                | Subtotal
                |--------------------------------------------------------------------------
                */

                $subtotal =
                    $roomRentTotal
                    + $serviceTotal
                    + $lateCheckoutFee
                    - $discount;

                /*
                |--------------------------------------------------------------------------
                | Tax
                |--------------------------------------------------------------------------
                */

                return round(
                    $invoice->grand_total - $subtotal,
                    2
                );
            }),

            'this_month' => $allInvoices
                ->whereBetween(
                    'created_at',
                    [
                        now()->startOfMonth(),
                        now()->endOfMonth(),
                    ]
                )
                ->count(),

        ];

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $invoices = $query
            ->latest()
            ->paginate(10);

        return response()->json([

            'status' => true,

            'statistics' => $statistics,

            'invoices' => $invoices->items(),

            'pagination' => [

                'current_page' => $invoices->currentPage(),

                'last_page' => $invoices->lastPage(),

                'per_page' => $invoices->perPage(),

                'total' => $invoices->total(),

                'from' => $invoices->firstItem(),

                'to' => $invoices->lastItem(),

            ],

        ]);
    }

    /**
     * Invoice View
     */
    public function show(Invoice $invoice)
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Building Access
        |--------------------------------------------------------------------------
        |
        | Super Admin and Admin can view any invoice.
        |
        | Other users can only view invoices belonging to
        | their assigned buildings.
        |
        */

        if (!$user->isSuperadmin() && !$user->isAdmin()) {

            $invoice->load([
                'booking.room',
            ]);

            $room = $invoice->booking?->room;

            if (!$room) {
                abort(404);
            }

            $hasAccess = $user->buildings()
                ->where('buildings.id', $room->building_id)
                ->exists();

            if (!$hasAccess) {
                abort(
                    403,
                    'You do not have access to this building.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Load Invoice Data
        |--------------------------------------------------------------------------
        */

        $invoice->load([
            'booking.room',
            'booking.guests',
            'booking.services',
        ]);

        $booking = $invoice->booking;

        /*
        |--------------------------------------------------------------------------
        | Stay Days
        |--------------------------------------------------------------------------
        */

        $stayDays = max(
            1,
            (int) ceil(
                \Carbon\Carbon::parse($booking->check_in)
                    ->diffInSeconds(
                        \Carbon\Carbon::parse($booking->check_out)
                    ) / 86400
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Room Rent
        |--------------------------------------------------------------------------
        */

        $roomRent = (float) $booking->room_rent;

        $roomRentTotal =
            $roomRent * $stayDays;

        /*
        |--------------------------------------------------------------------------
        | Chargeable Services
        |--------------------------------------------------------------------------
        */

        $chargeable = $booking->services
            ->where('type', 'chargeable')
            ->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | Late Checkout Fee
        |--------------------------------------------------------------------------
        */

        $lateCheckoutFee = (float) (
            $booking->late_checkout_fee ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Discount
        |--------------------------------------------------------------------------
        */

        $discount = (float) (
            $booking->discount ?? 0
        );

        /*
        |--------------------------------------------------------------------------
        | Subtotal
        |--------------------------------------------------------------------------
        */

        $subtotal =
            $roomRentTotal
            + $chargeable
            + $lateCheckoutFee
            - $discount;

        /*
        |--------------------------------------------------------------------------
        | GST
        |--------------------------------------------------------------------------
        */

        $gst = round(
            $invoice->grand_total - $subtotal,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */

        $grandTotal = $invoice->grand_total;

        return view(
            'dashboard.accounts.show',
            compact(
                'invoice',
                'booking',
                'roomRent',
                'roomRentTotal',
                'chargeable',
                'lateCheckoutFee',
                'discount',
                'subtotal',
                'gst',
                'grandTotal',
                'stayDays'
            )
        );
    }
}