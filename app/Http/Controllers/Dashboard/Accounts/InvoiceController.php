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
        $query = Invoice::with([
            'booking.room',
            'booking.guests',
            'booking.services',
        ]);

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

                        $booking->where('booking_no', 'like', "%{$search}%")

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

                $stayDays = max(
                    1,
                    \Carbon\Carbon::parse($booking->check_in)
                        ->diffInDays(\Carbon\Carbon::parse($booking->check_out))
                );

                $roomRentTotal = $booking->room_rent * $stayDays;

                $serviceTotal = $booking->services
                    ->where('type', 'chargeable')
                    ->sum('total_amount');

                $discount = $booking->discount ?? 0;

                $subtotal = $roomRentTotal + $serviceTotal - $discount;

                return round($invoice->grand_total - $subtotal, 2);
            }),

            'this_month' => $allInvoices
                ->whereBetween(
                    'created_at',
                    [
                        now()->startOfMonth(),
                        now()->endOfMonth()
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
        $invoice->load([
            'booking.room',
            'booking.guests',
            'booking.services',
        ]);

        $booking = $invoice->booking;

        $roomRent = $booking->room_rent;

        $chargeable = $booking->services
            ->where('type', 'chargeable')
            ->sum('total_amount');

        $subtotal = $roomRent + $chargeable;

        $gst = $invoice->grand_total - $subtotal;

        $grandTotal = $invoice->grand_total;

        return view('dashboard.accounts.show', compact(
            'invoice',
            'booking',
            'roomRent',
            'chargeable',
            'subtotal',
            'gst',
            'grandTotal'
        ));
    }
}