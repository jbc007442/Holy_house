<?php

namespace App\Http\Controllers\Dashboard\Bookings;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\BookingPayment;
use App\Models\BookingService;
use App\Models\Building;
use App\Models\Item;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Invoice;


class BookingController extends Controller
{
    /**
     * New Booking
     */
    public function create()
    {
        $buildings = Building::with('floors')
            ->orderBy('name')
            ->get();

        return view('dashboard.bookings.create', compact('buildings'));
    }


    /**
     *. getRooms
     */
    public function getRooms(Request $request, $buildingId)
    {
        $rooms = Room::where('building_id', $buildingId)
            ->when($request->filled('floor'), function ($query) use ($request) {
                $query->where('floor', $request->floor);
            })
            ->where(function ($query) use ($request) {

                $query->where('status', 'available');

                if ($request->filled('selected_room')) {
                    $query->orWhere('id', $request->selected_room);
                }
            })
            ->select(
                'id',
                'room_number',
                'floor',
                'base_price',
                'status'
            )
            ->orderBy('room_number')
            ->get();

        return response()->json($rooms);
    }

    /**
     * Save Booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id'      => 'required|exists:rooms,id',
            'room_rent'    => 'required|numeric|min:0',
            'amount'       => 'nullable|numeric|min:0',
            'remarks'      => 'nullable|string',
            'guests'       => 'required|array|min:1',
            'guests.*.guest_name' => 'required|string|max:255',
            'guests.*.mobile'     => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {

            $paidAmount = $request->amount ?? 0;

            $booking = Booking::create([
                'booking_no'           => 'BK-' . strtoupper(Str::random(8)),
                'room_id'              => $request->room_id,
                'check_in'             => now(),
                'check_out'            => null,

                'guest_count'          => count($request->guests),

                'room_rent'            => $request->room_rent,
                'chargeable_amount'    => 0,
                'complimentary_amount' => 0,

                'total_amount'         => $request->room_rent,
                'paid_amount'          => $paidAmount,
                'balance_amount'       => $request->room_rent - $paidAmount,

                'payment_status'       => $paidAmount == 0
                    ? 'pending'
                    : ($paidAmount >= $request->room_rent ? 'paid' : 'partial'),

                'status'               => $request->status ?? 'checked_in',

                'remarks'              => $request->remarks,

                'created_by'           => auth()->id(),
                'updated_by'           => auth()->id(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | Save Guests
        |--------------------------------------------------------------------------
        */

            foreach ($request->guests as $index => $guest) {

                $booking->guests()->create([

                    'guest_name'  => $guest['guest_name'] ?? '',

                    'mobile'      => $guest['mobile'] ?? '',

                    'id_type'     => $guest['id_type'] ?? null,

                    'id_number'   => $guest['id_number'] ?? null,

                    'nationality' => $guest['nationality'] ?? null,

                    'is_primary'  => $index === 0,

                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Save Payment
        |--------------------------------------------------------------------------
        */

            if ($paidAmount > 0) {

                $booking->payments()->create([

                    'amount'          => $paidAmount,

                    'payment_type'    => $request->payment_type,

                    'payment_method'  => $request->payment_method,

                    'transaction_no'  => $request->transaction_no,

                    'remarks'         => $request->remarks,

                    'paid_at'         => now(),

                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Update Room
        |--------------------------------------------------------------------------
        */

            $booking->room()->update([
                'status' => 'running',
            ]);

            DB::commit();

            return redirect()
                ->route('dashboard.bookings.current-stays')
                ->with('success', 'Booking created successfully.');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Booking Details
     */
    public function show(Booking $booking)
    {
        return view('dashboard.bookings.show', compact('booking'));
    }

    /**
     * Edit Booking
     */
    public function edit(string $id)
    {
        $booking = Booking::with([
            'room.building',
            'guests',
            'payments',
            'services.item',
        ])->findOrFail($id);

        $buildings = Building::orderBy('name')->get();

        $rooms = Room::orderBy('room_number')->get();

        return view('dashboard.bookings.edit', compact(
            'booking',
            'buildings',
            'rooms'
        ));
    }

    /**
     * Update Booking
     */
    public function update(Request $request, string $id)
    {

        $request->validate([
            'room_id'      => 'required|exists:rooms,id',
            'room_rent'    => 'required|numeric|min:0',
            'remarks'      => 'nullable|string',
            'guests'       => 'required|array|min:1',
            'guests.*.guest_name' => 'required|string|max:255',
            'guests.*.mobile'     => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();

        try {

            $booking = Booking::with([
                'room',
                'guests',
                'payments',
            ])->findOrFail($id);

            $oldRoomId = $booking->room_id;

            $paidAmount = $booking->payments()->sum('amount');

            $booking->update([

                'room_id'              => $request->room_id,

                'guest_count'          => count($request->guests),

                'room_rent'            => $request->room_rent,

                'total_amount'         => $request->room_rent,

                'balance_amount'       => $request->room_rent - $paidAmount,

                'payment_status'       => $paidAmount == 0
                    ? 'pending'
                    : ($paidAmount >= $request->room_rent ? 'paid' : 'partial'),

                'remarks'              => $request->remarks,
                'updated_by'           => auth()->id(),

            ]);

            /*
        |--------------------------------------------------------------------------
        | Update Guests
        |--------------------------------------------------------------------------
        */

            $booking->guests()->delete();

            foreach ($request->guests as $index => $guest) {

                $booking->guests()->create([

                    'guest_name'  => $guest['guest_name'] ?? '',

                    'mobile'      => $guest['mobile'] ?? '',

                    'id_type'     => $guest['id_type'] ?? null,

                    'id_number'   => $guest['id_number'] ?? null,

                    'nationality' => $guest['nationality'] ?? null,

                    'is_primary'  => $index === 0,

                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | Room Status
        |--------------------------------------------------------------------------
        */

            if ($oldRoomId != $request->room_id) {

                Room::where('id', $oldRoomId)->update([
                    'status' => 'available',
                ]);

                Room::where('id', $request->room_id)->update([
                    'status' => 'running',
                ]);
            }

            DB::commit();

            return redirect()
                ->route('dashboard.bookings.current-stays')
                ->with('success', 'Booking updated successfully.');
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Update Booking Services
     */
    public function updateService(Request $request, BookingService $service)
    {
        $validated = $request->validate([
            'quantity'   => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $service) {

            // Restore stock if complimentary
            if ($service->type === 'complimentary' && $service->item) {
                $service->item->increaseStock($service->quantity);

                if (!$service->item->hasStock($validated['quantity'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Insufficient stock available.'
                    ]);
                }

                $service->item->decreaseStock($validated['quantity']);

                $validated['unit_price'] = 0;
            }

            $service->update([
                'quantity'     => $validated['quantity'],
                'unit_price'   => $validated['unit_price'],
                'total_amount' => $validated['quantity'] * $validated['unit_price'],
                'updated_by'   => auth()->id(),
            ]);
        });

        return back()->with('success', 'Service updated successfully.');
    }

    /**
     * Update Invoice Details
     */
    public function updateInvoiceDetails(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'rate_type'     => 'nullable|in:EP,CP,MAP',
            'bill_to'       => 'nullable|string|max:255',
            'bill_to_gstin' => 'nullable|string|max:50',
            'hsn_code'      => 'nullable|string|max:20',
        ]);

        $booking->update([
            'rate_type'     => $validated['rate_type'],
            'bill_to'       => $validated['bill_to'],
            'bill_to_gstin' => $validated['bill_to_gstin'],
            'hsn_code'      => $validated['hsn_code'] ?: '998552',
            'updated_by'    => auth()->id(),
        ]);

        return back()->with('success', 'Invoice details updated successfully.');
    }

    /**
     * Check Out Guest
     */
    
    public function checkout(string $id)
    {
        DB::transaction(function () use ($id) {

            $booking = Booking::with('room')->findOrFail($id);

            $booking->update([
                'status'    => 'checked_out',
                'check_out' => now(),
            ]);

            $booking->room->update([
                'status' => 'available',
            ]);

            // Generate Invoice
            Invoice::firstOrCreate(
                [
                    'booking_id' => $booking->id,
                ],
                [
                    'invoice_no' => 'INV-' . str_pad(
                        Invoice::max('id') + 1,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ),
                ]
            );
        });

        return redirect()
            ->route('dashboard.bookings.current-stays')
            ->with('success', 'Guest checked out successfully.');
    }

    /**
     * Delete Booking
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Current Stays
     */
    public function currentStays()
    {
        $buildings = Building::orderBy('name')->get();

        return view('dashboard.bookings.current-stays', compact('buildings'));
    }

    /**
     * Current Stays Ajax
     */
    public function ajaxCurrentStays(Request $request)
    {
        $query = Booking::with([
            'room.building',
            'guests',
            'payments',
        ])
            ->where('status', 'checked_in');

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('booking_no', 'like', "%{$search}%")

                    ->orWhereHas('guests', function ($guest) use ($search) {

                        $guest->where('guest_name', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%");
                    });
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Building Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('building_id')) {

            $query->whereHas('room', function ($room) use ($request) {

                $room->where('building_id', $request->building_id);
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Floor Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('floor')) {

            $query->whereHas('room', function ($room) use ($request) {

                $room->where('floor', $request->floor);
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Statistics (before pagination)
    |--------------------------------------------------------------------------
    */

        $statistics = [
            'guest_count' => (clone $query)->count(),

            'running_rooms' => (clone $query)
                ->distinct('room_id')
                ->count('room_id'),

            'checkout_today' => (clone $query)
                ->whereDate('check_out', today())
                ->count(),

            'total_balance' => (clone $query)
                ->sum('balance_amount'),
        ];

        /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

        $bookings = $query
            ->latest('id')
            ->paginate(10);

        return response()->json([

            'status' => true,

            'statistics' => $statistics,

            'bookings' => $bookings->items(),

            'pagination' => [

                'current_page' => $bookings->currentPage(),

                'last_page' => $bookings->lastPage(),

                'per_page' => $bookings->perPage(),

                'total' => $bookings->total(),

                'from' => $bookings->firstItem(),

                'to' => $bookings->lastItem(),

                'links' => $bookings->toHtml(),

            ],

        ]);
    }
    /**
     * Booking History
     */
    public function history()
    {
        $buildings = Building::orderBy('name')->get();

        return view(
            'dashboard.bookings.history',
            compact('buildings')
        );
    }


    /**
     * Booking History Ajax
     */

    public function ajaxHistory(Request $request)
    {
        $query = Booking::with([
            'room.building',
            'guests',
        ])
            ->where('status', 'checked_out');

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('booking_no', 'like', "%{$search}%")

                    ->orWhereHas('guests', function ($guest) use ($search) {

                        $guest->where('guest_name', 'like', "%{$search}%")
                            ->orWhere('mobile', 'like', "%{$search}%");
                    });
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Building
    |--------------------------------------------------------------------------
    */

        if ($request->filled('building_id')) {

            $query->whereHas('room', function ($room) use ($request) {

                $room->where(
                    'building_id',
                    $request->building_id
                );
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('from')) {

            $query->whereDate(
                'check_in',
                '>=',
                $request->from
            );
        }

        if ($request->filled('to')) {

            $query->whereDate(
                'check_out',
                '<=',
                $request->to
            );
        }

        /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

        $statistics = [

            'completed' => (clone $query)->count(),

            'revenue' => (clone $query)->sum('total_amount'),

            'average_stay' => round(
                (clone $query)->get()->avg(function ($booking) {

                    return \Carbon\Carbon::parse($booking->check_in)
                        ->diffInDays($booking->check_out) + 1;
                }) ?? 0,
                1
            ),

            'checkout_today' => (clone $query)
                ->whereDate('check_out', today())
                ->count(),

        ];

        $bookings = $query
            ->latest('check_out')
            ->paginate(10);

        return response()->json([

            'statistics' => $statistics,

            'bookings' => $bookings->items(),

            'pagination' => [

                'links' => $bookings->toHtml(),

            ],

        ]);
    }

    /**
     * Bookingservice
     */
    public function services(Booking $booking)
    {
        $booking->load([
            'room.building',
            'guests',
            'services.item'
        ]);

        $items = Item::orderBy('item_name')->get();

        return view('dashboard.bookings.services', compact(
            'booking',
            'items'
        ));
    }

    /**
     * Add booking service
     */
    public function storeService(Request $request, Booking $booking)
    {
        $request->validate([
            'type'         => 'required|in:chargeable,complimentary',
            'service_name' => 'nullable|string|max:255',
            'item_id'      => 'nullable|exists:items,id',
            'quantity'     => 'required|integer|min:1',
            'unit_price'   => 'required|numeric|min:0',
            'remarks'      => 'nullable|string',
        ]);

        // Chargeable validation
        if (
            $request->type === 'chargeable' &&
            empty($request->service_name)
        ) {
            return back()->withErrors([
                'service_name' => 'Service name is required.'
            ])->withInput();
        }

        // Complimentary validation
        if (
            $request->type === 'complimentary' &&
            empty($request->item_id)
        ) {
            return back()->withErrors([
                'item_id' => 'Please select an item.'
            ])->withInput();
        }

        // Default values
        $serviceName = $request->service_name;
        $itemId = null;
        $unitPrice = $request->unit_price;

        // Complimentary Service
        if ($request->type === 'complimentary') {

            $item = Item::findOrFail($request->item_id);

            $itemId = $item->id;
            $serviceName = $item->item_name;

            // Complimentary items are always free
            $unitPrice = 0;
        }

        $quantity = (int) $request->quantity;
        $totalAmount = $quantity * $unitPrice;

        DB::transaction(function () use (
            $booking,
            $itemId,
            $serviceName,
            $quantity,
            $unitPrice,
            $totalAmount,
            $request
        ) {

            if ($request->type === 'complimentary') {

                $item = Item::findOrFail($itemId);

                if (!$item->hasStock($quantity)) {

                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Insufficient stock available.'
                    ]);
                }

                $item->decreaseStock($quantity);
            }

            BookingService::create([
                'booking_id'   => $booking->id,
                'item_id'      => $itemId,
                'type'         => $request->type,
                'service_name' => $serviceName,
                'quantity'     => $quantity,
                'unit_price'   => $unitPrice,
                'total_amount' => $totalAmount,
                'remarks'      => $request->remarks,
                'created_by'   => auth()->id(),
                'updated_by'   => auth()->id(),
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Guest service added successfully.');
    }

    /**
     * Delete
     */
    public function deleteService(BookingService $service)
    {
        DB::transaction(function () use ($service) {

            if (
                $service->type === 'complimentary' &&
                $service->item
            ) {
                $service->item->increaseStock(
                    $service->quantity
                );
            }

            $service->delete();
        });

        return back()->with(
            'success',
            'Service deleted successfully.'
        );
    }

    /**
     * Get Floors By Building
     */
    public function getFloors(Building $building)
    {
        return response()->json(
            $building->floors()
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->get([
                    'id',
                    'name',
                    'sort_order',
                ])
        );
    }

    /**
     * Show Detail quick view of a booking
     */
    public function details(Booking $booking)
    {
        $booking->load([
            'room.building',
            'guests',
            'services.item',
            'payments.creator',
            'creator',
            'updater',
        ]);

        return response()->json([
            'booking' => $booking,
        ]);
    }
}