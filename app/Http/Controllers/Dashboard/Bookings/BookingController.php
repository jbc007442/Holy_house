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
use App\Models\HousekeepingMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Invoice;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Illuminate\Support\Facades\Log;


class BookingController extends Controller
{
    /**
     * New Booking
     */
    public function create()
    {
        $user = auth()->user();

        $query = Building::with('floors');

        /*
    |--------------------------------------------------------------------------
    | Building Access
    |--------------------------------------------------------------------------
    |
    | Super Admin and Admin can see all buildings.
    | Other users can only see their assigned buildings.
    |
    */

        if (!$user->isSuperadmin() && !$user->isAdmin()) {

            $query->whereIn(
                'id',
                $user->buildings()->pluck('buildings.id')
            );
        }

        $buildings = $query
            ->orderBy('name')
            ->get();

        return view(
            'dashboard.bookings.create',
            compact('buildings')
        );
    }


    /**
     *. getRooms
     */

    public function getRooms(Request $request, $buildingId)
    {
        $user = auth()->user();

        // Super Admin and Admin can access all buildings.
        // Other users can only access assigned buildings.
        if (!$user->isSuperadmin() && !$user->isAdmin()) {

            $hasAccess = $user->buildings()
                ->where('buildings.id', $buildingId)
                ->exists();

            if (!$hasAccess) {
                abort(403, 'You do not have access to this building.');
            }
        }

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
    // public function getRooms(Request $request, $buildingId)
    // {
    //     $rooms = Room::where('building_id', $buildingId)
    //         ->when($request->filled('floor'), function ($query) use ($request) {
    //             $query->where('floor', $request->floor);
    //         })
    //         ->where(function ($query) use ($request) {

    //             $query->where('status', 'available');

    //             if ($request->filled('selected_room')) {
    //                 $query->orWhere('id', $request->selected_room);
    //             }
    //         })
    //         ->select(
    //             'id',
    //             'room_number',
    //             'floor',
    //             'base_price',
    //             'status'
    //         )
    //         ->orderBy('room_number')
    //         ->get();

    //     return response()->json($rooms);
    // }

    /**
     *. checkBuildingAccess
     */


    private function checkBuildingAccess(Building $building): void
    {
        $user = auth()->user();

        // Super Admin and Admin can access all buildings.
        if ($user->isSuperadmin() || $user->isAdmin()) {
            return;
        }

        $hasAccess = $user->buildings()
            ->where('buildings.id', $building->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this building.');
        }
    }
    /**
     * Save Booking
     */
    public function store(Request $request)
    {
        $request->validate([
            'room_id'      => 'required|exists:rooms,id',
            'expected_check_out'    => 'required|date|after_or_equal:today',
            'expected_stay_days'    => 'required|integer|min:1',
            'room_rent'    => 'required|numeric|min:0',
            'amount'       => 'nullable|numeric|min:0',
            'remarks'      => 'nullable|string',
            'guests'       => 'required|array|min:1',
            'guests.*.guest_name' => 'required|string|max:255',
            'guests.*.mobile'     => 'nullable|string|max:20',
        ]);
        // Check building access
        $room = Room::with('building')
            ->findOrFail($request->room_id);

        $this->checkBuildingAccess($room->building);

        DB::beginTransaction();

        try {

            $paidAmount = $request->amount ?? 0;

            $booking = Booking::create([

                'booking_no' => 'BK-' . strtoupper(Str::random(8)),

                'room_id' => $request->room_id,

                // Stay Details
                'check_in' => now(),

                'expected_check_out' => $request->expected_check_out,

                'expected_stay_days' => $request->expected_stay_days,

                'check_out' => null,

                // Guests
                'guest_count' => count($request->guests),

                // Charges
                'room_rent' => $request->room_rent,

                'chargeable_amount' => 0,

                'complimentary_amount' => 0,

                'total_amount' => $request->room_rent,

                // Payment
                'paid_amount' => $paidAmount,

                'balance_amount' => $request->room_rent - $paidAmount,

                'payment_status' => $paidAmount == 0
                    ? 'pending'
                    : ($paidAmount >= $request->room_rent ? 'paid' : 'partial'),

                // Status
                'status' => $request->status ?? 'checked_in',

                // Remarks
                'remarks' => $request->remarks,

                // Audit
                'created_by' => auth()->id(),

                'updated_by' => auth()->id(),

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

                    'state'       => $guest['state'] ?? null,

                    'c_form'      => $guest['c_form'] ?? false,

                    'is_primary'  => $index === 0,

                    'created_by'  => auth()->id(),
                    'updated_by'  => auth()->id(),
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
    // public function show(Booking $booking)
    // {
    //     return view('dashboard.bookings.show', compact('booking'));
    // }

    public function show(Booking $booking)
    {
        $booking->load('room.building');

        $this->checkBuildingAccess($booking->room->building);

        return view(
            'dashboard.bookings.show',
            compact('booking')
        );
    }

    /**
     * Edit Booking
     */
    // public function edit(string $id)
    // {
    //     $booking = Booking::with([
    //         'room.building',
    //         'guests',
    //         'payments',
    //         'services.item',
    //     ])->findOrFail($id);

    //     $buildings = Building::orderBy('name')->get();

    //     $rooms = Room::orderBy('room_number')->get();

    //     return view('dashboard.bookings.edit', compact(
    //         'booking',
    //         'buildings',
    //         'rooms'
    //     ));
    // }

    public function edit(string $id)
    {
        $booking = Booking::with([
            'room.building',
            'guests',
            'payments',
            'services.item',
        ])->findOrFail($id);

        // Check access to the booking's current building
        $this->checkBuildingAccess($booking->room->building);

        $user = auth()->user();

        // Buildings available to this user
        $buildingQuery = Building::orderBy('name');

        if (!$user->isSuperadmin() && !$user->isAdmin()) {
            $buildingQuery->whereIn(
                'id',
                $user->buildings()->pluck('buildings.id')
            );
        }

        $buildings = $buildingQuery->get();

        // Rooms available to this user
        $roomQuery = Room::orderBy('room_number');

        if (!$user->isSuperadmin() && !$user->isAdmin()) {
            $roomQuery->whereIn(
                'building_id',
                $user->buildings()->pluck('buildings.id')
            );
        }

        $rooms = $roomQuery->get();

        return view('dashboard.bookings.edit', compact(
            'booking',
            'buildings',
            'rooms'
        ));
    }

    /**
     * Update Booking
     */
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'room_id' => 'required|exists:rooms,id',
    //         'room_rent' => 'required|numeric|min:0',
    //         'remarks' => 'nullable|string',

    //         'guests' => 'required|array|min:1',
    //         'guests.*.guest_name' => 'required|string|max:255',
    //         'guests.*.mobile' => 'nullable|string|max:20',
    //     ]);

    //     DB::beginTransaction();

    //     try {

    //         $booking = Booking::with([
    //             'room',
    //             'guests',
    //             'payments',
    //         ])->findOrFail($id);

    //         $oldRoomId = $booking->room_id;

    //         $paidAmount = $booking->payments()->sum('amount');

    //         $booking->update([

    //             'room_id' => $request->room_id,

    //             // Guests
    //             'guest_count' => count($request->guests),

    //             // Charges
    //             'room_rent' => $request->room_rent,
    //             'total_amount' => $request->room_rent,

    //             // Payment
    //             'balance_amount' => $request->room_rent - $paidAmount,

    //             'payment_status' => $paidAmount == 0
    //                 ? 'pending'
    //                 : ($paidAmount >= $request->room_rent ? 'paid' : 'partial'),

    //             // Remarks
    //             'remarks' => $request->remarks,

    //             // Audit
    //             'updated_by' => auth()->id(),

    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Update Guests
    //     |--------------------------------------------------------------------------
    //     */

    //         $booking->guests()->delete();

    //         foreach ($request->guests as $index => $guest) {

    //             $booking->guests()->create([

    //                 'guest_name' => $guest['guest_name'] ?? '',

    //                 'mobile' => $guest['mobile'] ?? '',

    //                 'id_type' => $guest['id_type'] ?? null,

    //                 'id_number' => $guest['id_number'] ?? null,

    //                 'nationality' => $guest['nationality'] ?? null,

    //                 'state' => $guest['state'] ?? null,

    //                 'c_form' => $guest['c_form'] ?? null,

    //                 'is_primary' => $index === 0,

    //                 'created_by' => auth()->id(),
    //                 'updated_by' => auth()->id(),
    //             ]);
    //         }

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Room Status
    //     |--------------------------------------------------------------------------
    //     */

    //         if ($oldRoomId != $request->room_id) {

    //             Room::where('id', $oldRoomId)->update([
    //                 'status' => 'available',
    //             ]);

    //             Room::where('id', $request->room_id)->update([
    //                 'status' => 'running',
    //             ]);
    //         }

    //         DB::commit();

    //         return redirect()
    //             ->route('dashboard.bookings.current-stays')
    //             ->with('success', 'Booking updated successfully.');
    //     } catch (\Throwable $e) {

    //         DB::rollBack();

    //         return back()
    //             ->withInput()
    //             ->with('error', $e->getMessage());
    //     }
    // }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'room_rent' => 'required|numeric|min:0',
            'remarks' => 'nullable|string',

            'guests' => 'required|array|min:1',
            'guests.*.guest_name' => 'required|string|max:255',
            'guests.*.mobile' => 'nullable|string|max:20',
        ]);

        // Get booking first
        $booking = Booking::with([
            'room.building',
            'guests',
            'payments',
        ])->findOrFail($id);

        // Check access to the booking's current building
        $this->checkBuildingAccess($booking->room->building);

        // Get the new room
        $newRoom = Room::with('building')
            ->findOrFail($request->room_id);

        // Check access to the new room's building
        $this->checkBuildingAccess($newRoom->building);

        DB::beginTransaction();

        try {

            $oldRoomId = $booking->room_id;

            $paidAmount = $booking->payments()->sum('amount');

            $booking->update([

                'room_id' => $request->room_id,

                // Guests
                'guest_count' => count($request->guests),

                // Charges
                'room_rent' => $request->room_rent,
                'total_amount' => $request->room_rent,

                // Payment
                'balance_amount' => $request->room_rent - $paidAmount,

                'payment_status' => $paidAmount == 0
                    ? 'pending'
                    : ($paidAmount >= $request->room_rent ? 'paid' : 'partial'),

                // Remarks
                'remarks' => $request->remarks,

                // Audit
                'updated_by' => auth()->id(),

            ]);

            /*
        |--------------------------------------------------------------------------
        | Update Guests
        |--------------------------------------------------------------------------
        */

            $booking->guests()->delete();

            foreach ($request->guests as $index => $guest) {

                $booking->guests()->create([

                    'guest_name' => $guest['guest_name'] ?? '',

                    'mobile' => $guest['mobile'] ?? '',

                    'id_type' => $guest['id_type'] ?? null,

                    'id_number' => $guest['id_number'] ?? null,

                    'nationality' => $guest['nationality'] ?? null,

                    'state' => $guest['state'] ?? null,

                    'c_form' => $guest['c_form'] ?? null,

                    'is_primary' => $index === 0,

                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
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

        // Load booking and building
        $service->load([
            'booking.room.building',
            'item',
        ]);

        // Check access to the booking's building
        $this->checkBuildingAccess(
            $service->booking->room->building
        );

        DB::transaction(function () use ($validated, $service) {

            // Restore stock if complimentary
            if (
                $service->type === 'complimentary' &&
                $service->item
            ) {

                $service->item->increaseStock(
                    $service->quantity
                );

                if (!$service->item->hasStock(
                    $validated['quantity']
                )) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'quantity' => 'Insufficient stock available.'
                    ]);
                }

                $service->item->decreaseStock(
                    $validated['quantity']
                );

                // Complimentary service must remain free
                $validated['unit_price'] = 0;
            }

            $service->update([
                'quantity' => $validated['quantity'],

                'unit_price' => $validated['unit_price'],

                'total_amount' =>
                $validated['quantity']
                    * $validated['unit_price'],

                'updated_by' => auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Service updated successfully.'
        );
    }
    // public function updateService(Request $request, BookingService $service)
    // {
    //     $validated = $request->validate([
    //         'quantity'   => 'required|integer|min:1',
    //         'unit_price' => 'required|numeric|min:0',
    //     ]);

    //     DB::transaction(function () use ($validated, $service) {

    //         // Restore stock if complimentary
    //         if ($service->type === 'complimentary' && $service->item) {
    //             $service->item->increaseStock($service->quantity);

    //             if (!$service->item->hasStock($validated['quantity'])) {
    //                 throw \Illuminate\Validation\ValidationException::withMessages([
    //                     'quantity' => 'Insufficient stock available.'
    //                 ]);
    //             }

    //             $service->item->decreaseStock($validated['quantity']);

    //             $validated['unit_price'] = 0;
    //         }

    //         $service->update([
    //             'quantity'     => $validated['quantity'],
    //             'unit_price'   => $validated['unit_price'],
    //             'total_amount' => $validated['quantity'] * $validated['unit_price'],
    //             'updated_by'   => auth()->id(),
    //         ]);
    //     });

    //     return back()->with('success', 'Service updated successfully.');
    // }

    /**
     * Update Invoice Details
     */

    public function updateInvoiceDetails(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'rate_type'        => 'nullable|in:EP,CP,MAP',
            'bill_to'          => 'nullable|string|max:255',
            'bill_to_gstin'    => 'nullable|string|max:50',
            'hsn_code'         => 'nullable|string|max:20',
            'discount'         => 'nullable|numeric|min:0',
            'discount_remark'  => 'nullable|string|max:1000',
        ]);

        $booking->update([
            'rate_type'         => $validated['rate_type'] ?? null,
            'bill_to'           => $validated['bill_to'] ?? null,
            'bill_to_gstin'     => $validated['bill_to_gstin'] ?? null,
            'hsn_code'          => $validated['hsn_code'] ?: '998552',
            'discount'          => $validated['discount'] ?? 0,
            'late_checkout_fee' => $request->late_checkout_fee ?? 0,
            'discount_remark'   => $validated['discount_remark'] ?? null,
            'updated_by'        => auth()->id(),
        ]);

        return back()->with(
            'success',
            'Invoice details updated successfully.'
        );
    }

    /**
     * Check Out Guest
     */

    // public function checkout(string $id)
    // {
    //     DB::transaction(function () use ($id) {

    //         $booking = Booking::with([
    //             'room.building',
    //             'guests',
    //             'services',
    //         ])->findOrFail($id);

    //         // Check access to the booking's building
    //         $this->checkBuildingAccess(
    //             $booking->room->building
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Checkout
    //     |--------------------------------------------------------------------------
    //     */

    //         $booking->update([
    //             'status'    => 'checked_out',
    //             'check_out' => now(),
    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Room Blocked
    //     |--------------------------------------------------------------------------
    //     */

    //         $booking->room->update([
    //             'status' => 'blocked',
    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Housekeeping Message
    //     |--------------------------------------------------------------------------
    //     */

    //         HousekeepingMessage::create([
    //             'booking_id' => $booking->id,
    //             'room_id'    => $booking->room_id,
    //             'message'    => 'Room ' . $booking->room->room_number . ' requires housekeeping after guest checkout.',
    //             'created_by' => auth()->id(),
    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Stay Days
    //     |--------------------------------------------------------------------------
    //     |
    //     | Calculate exact duration and always round UP.
    //     |
    //     | Example:
    //     | 9.0000001 days = 10 days
    //     | 9.1154629 days = 10 days
    //     |
    //     */

    //         $checkIn = Carbon::parse($booking->check_in);

    //         $checkOut = Carbon::parse($booking->check_out);

    //         $stayDays = max(
    //             1,
    //             (int) ceil(
    //                 $checkIn->diffInSeconds($checkOut) / 86400
    //             )
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Room Total
    //     |--------------------------------------------------------------------------
    //     */

    //         $roomRentTotal =
    //             (float) $booking->room_rent * $stayDays;

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Chargeable Services
    //     |--------------------------------------------------------------------------
    //     */

    //         $serviceTotal = $booking->services
    //             ->where('type', 'chargeable')
    //             ->sum('total_amount');

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Discount
    //     |--------------------------------------------------------------------------
    //     */

    //         $discount = (float) (
    //             $booking->discount ?? 0
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Sub Total
    //     |--------------------------------------------------------------------------
    //     */

    //         $subtotal =
    //             $roomRentTotal
    //             + $serviceTotal
    //             - $discount;

    //         /*
    //     |--------------------------------------------------------------------------
    //     | GST
    //     |--------------------------------------------------------------------------
    //     */

    //         $guestState = trim(
    //             $booking->guests->first()?->state ?? ''
    //         );

    //         if (strcasecmp($guestState, 'Haryana') === 0) {

    //             $cgst = round(
    //                 $subtotal * 0.025,
    //                 2
    //             );

    //             $sgst = round(
    //                 $subtotal * 0.025,
    //                 2
    //             );

    //             $igst = 0;
    //         } else {

    //             $cgst = 0;

    //             $sgst = 0;

    //             $igst = round(
    //                 $subtotal * 0.05,
    //                 2
    //             );
    //         }

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Grand Total
    //     |--------------------------------------------------------------------------
    //     */

    //         $grandTotal = round(
    //             $subtotal
    //                 + $cgst
    //                 + $sgst
    //                 + $igst,
    //             2
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Generate Invoice
    //     |--------------------------------------------------------------------------
    //     */

    //         Invoice::firstOrCreate(
    //             [
    //                 'booking_id' => $booking->id,
    //             ],
    //             [
    //                 'invoice_no' => 'INV-' . str_pad(
    //                     Invoice::max('id') + 1,
    //                     6,
    //                     '0',
    //                     STR_PAD_LEFT
    //                 ),

    //                 'grand_total' => $grandTotal,

    //                 'created_by' => auth()->id(),

    //                 'updated_by' => auth()->id(),
    //             ]
    //         );
    //     });

    //     return redirect()
    //         ->route('dashboard.bookings.current-stays')
    //         ->with(
    //             'success',
    //             'Guest checked out successfully.'
    //         );
    // }

    public function checkout(string $id)
    {
        DB::transaction(function () use ($id) {

            $booking = Booking::with([
                'room.building',
                'guests',
                'services',
            ])->findOrFail($id);

            // Check access to the booking's building
            $this->checkBuildingAccess(
                $booking->room->building
            );

            /*
        |--------------------------------------------------------------------------
        | Checkout
        |--------------------------------------------------------------------------
        */

            $booking->update([
                'status'    => 'checked_out',
                'check_out' => now(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | Room Blocked
        |--------------------------------------------------------------------------
        */

            $booking->room->update([
                'status' => 'blocked',
            ]);

            /*
        |--------------------------------------------------------------------------
        | Housekeeping Message
        |--------------------------------------------------------------------------
        */

            HousekeepingMessage::create([
                'booking_id' => $booking->id,
                'room_id'    => $booking->room_id,
                'message'    => 'Room ' . $booking->room->room_number . ' requires housekeeping after guest checkout.',
                'created_by' => auth()->id(),
            ]);

            /*
        |--------------------------------------------------------------------------
        | Stay Days
        |--------------------------------------------------------------------------
        |
        | Calculate exact duration and always round UP.
        |
        | Example:
        | 9.0000001 days = 10 days
        | 9.1154629 days = 10 days
        |
        */

            $checkIn = Carbon::parse($booking->check_in);

            $checkOut = Carbon::parse($booking->check_out);

            $stayDays = max(
                1,
                (int) ceil(
                    $checkIn->diffInSeconds($checkOut) / 86400
                )
            );

            /*
        |--------------------------------------------------------------------------
        | Room Total
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
        | Sub Total
        |--------------------------------------------------------------------------
        |
        | Room Rent
        | + Chargeable Services
        | + Late Checkout Fee
        | - Discount
        |
        */

            $subtotal =
                $roomRentTotal
                + $serviceTotal
                + $lateCheckoutFee
                - $discount;

            /*
        |--------------------------------------------------------------------------
        | GST
        |--------------------------------------------------------------------------
        */

            $guestState = trim(
                $booking->guests->first()?->state ?? ''
            );

            if (strcasecmp($guestState, 'Haryana') === 0) {

                // CGST 2.5%
                $cgst = round(
                    $subtotal * 0.025,
                    2
                );

                // SGST 2.5%
                $sgst = round(
                    $subtotal * 0.025,
                    2
                );

                $igst = 0;
            } else {

                $cgst = 0;

                $sgst = 0;

                // IGST 5%
                $igst = round(
                    $subtotal * 0.05,
                    2
                );
            }

            /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */

            $grandTotal = round(
                $subtotal
                    + $cgst
                    + $sgst
                    + $igst,
                2
            );

            /*
        |--------------------------------------------------------------------------
        | Generate Invoice
        |--------------------------------------------------------------------------
        */

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

                    'grand_total' => $grandTotal,

                    'created_by' => auth()->id(),

                    'updated_by' => auth()->id(),
                ]
            );
        });

        return redirect()
            ->route('dashboard.bookings.current-stays')
            ->with(
                'success',
                'Guest checked out successfully.'
            );
    }

    // public function checkout(string $id)
    // {
    //     DB::transaction(function () use ($id) {

    //         $booking = Booking::with([
    //             'room',
    //             'guests',
    //             'services'
    //         ])->findOrFail($id);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Checkout
    //     |--------------------------------------------------------------------------
    //     */

    //         $booking->update([
    //             'status'    => 'checked_out',
    //             'check_out' => now(),
    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Room Blocked
    //     |--------------------------------------------------------------------------
    //     */

    //         $booking->room->update([
    //             'status' => 'blocked',
    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Housekeeping Message
    //     |--------------------------------------------------------------------------
    //     */

    //         HousekeepingMessage::create([
    //             'booking_id' => $booking->id,
    //             'room_id'    => $booking->room_id,
    //             'message'    => 'Room ' . $booking->room->room_number . ' requires housekeeping after guest checkout.',
    //             'created_by' => auth()->id(),
    //         ]);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Stay Days
    //     |--------------------------------------------------------------------------
    //     |
    //     | Calculate exact duration and always round UP.
    //     |
    //     | Example:
    //     | 9.0000001 days = 10 days
    //     | 9.1154629 days = 10 days
    //     |
    //     */

    //         $checkIn = Carbon::parse($booking->check_in);
    //         $checkOut = Carbon::parse($booking->check_out);

    //         $stayDays = max(
    //             1,
    //             (int) ceil(
    //                 $checkIn->diffInSeconds($checkOut) / 86400
    //             )
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Room Total
    //     |--------------------------------------------------------------------------
    //     */

    //         $roomRentTotal = (float) $booking->room_rent * $stayDays;

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Chargeable Services
    //     |--------------------------------------------------------------------------
    //     */

    //         $serviceTotal = $booking->services
    //             ->where('type', 'chargeable')
    //             ->sum('total_amount');

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Discount
    //     |--------------------------------------------------------------------------
    //     */

    //         $discount = (float) ($booking->discount ?? 0);

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Sub Total
    //     |--------------------------------------------------------------------------
    //     */

    //         $subtotal = $roomRentTotal + $serviceTotal - $discount;

    //         /*
    //     |--------------------------------------------------------------------------
    //     | GST
    //     |--------------------------------------------------------------------------
    //     */

    //         $guestState = trim(
    //             $booking->guests->first()?->state ?? ''
    //         );

    //         if (strcasecmp($guestState, 'Haryana') === 0) {

    //             $cgst = round($subtotal * 0.025, 2);
    //             $sgst = round($subtotal * 0.025, 2);
    //             $igst = 0;
    //         } else {

    //             $cgst = 0;
    //             $sgst = 0;
    //             $igst = round($subtotal * 0.05, 2);
    //         }

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Grand Total
    //     |--------------------------------------------------------------------------
    //     */

    //         $grandTotal = round(
    //             $subtotal + $cgst + $sgst + $igst,
    //             2
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Generate Invoice
    //     |--------------------------------------------------------------------------
    //     */

    //         Invoice::firstOrCreate(
    //             [
    //                 'booking_id' => $booking->id,
    //             ],
    //             [
    //                 'invoice_no' => 'INV-' . str_pad(
    //                     Invoice::max('id') + 1,
    //                     6,
    //                     '0',
    //                     STR_PAD_LEFT
    //                 ),

    //                 'grand_total' => $grandTotal,

    //                 'created_by' => auth()->id(),
    //                 'updated_by' => auth()->id(),
    //             ]
    //         );
    //     });

    //     return redirect()
    //         ->route('dashboard.bookings.current-stays')
    //         ->with('success', 'Guest checked out successfully.');
    // }

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
    // public function currentStays()
    // {
    //     $buildings = Building::orderBy('name')->get();

    //     return view('dashboard.bookings.current-stays', compact('buildings'));
    // }

    public function currentStays()
    {
        $user = auth()->user();

        $query = Building::orderBy('name');

        // Super Admin and Admin can see all buildings.
        // Other users can only see assigned buildings.
        if (!$user->isSuperadmin() && !$user->isAdmin()) {
            $query->whereIn(
                'id',
                $user->buildings()->pluck('buildings.id')
            );
        }

        $buildings = $query->get();

        return view(
            'dashboard.bookings.current-stays',
            compact('buildings')
        );
    }

    /**
     * Current Stays Ajax
     */
    // public function ajaxCurrentStays(Request $request)
    // {
    //     $query = Booking::with([
    //         'room.building',
    //         'guests',
    //         'payments',
    //     ])
    //         ->where('status', 'checked_in');

    //     /*
    // |--------------------------------------------------------------------------
    // | Search
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('search')) {

    //         $search = trim($request->search);

    //         $query->where(function ($q) use ($search) {

    //             $q->where('booking_no', 'like', "%{$search}%")
    //                 ->orWhereHas('guests', function ($guest) use ($search) {

    //                     $guest->where('guest_name', 'like', "%{$search}%")
    //                         ->orWhere('mobile', 'like', "%{$search}%");
    //                 });
    //         });
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Building Filter
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('building_id')) {

    //         $query->whereHas('room', function ($room) use ($request) {

    //             $room->where('building_id', $request->building_id);
    //         });
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Floor Filter
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('floor')) {

    //         $query->whereHas('room', function ($room) use ($request) {

    //             $room->where('floor', $request->floor);
    //         });
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Statistics (before pagination)
    // |--------------------------------------------------------------------------
    // */

    //     $statistics = [

    //         // Total guests currently checked in
    //         'guest_count' => (clone $query)->count(),

    //         // Total occupied rooms
    //         'running_rooms' => (clone $query)
    //             ->distinct('room_id')
    //             ->count('room_id'),

    //         // Guests expected to check out today
    //         'checkout_today' => (clone $query)
    //             ->whereDate('expected_check_out', today())
    //             ->count(),

    //         // Total room rent of all current stays
    //         'total_balance' => (clone $query)
    //             ->sum('room_rent'),

    //     ];

    //     /*
    // |--------------------------------------------------------------------------
    // | Pagination
    // |--------------------------------------------------------------------------
    // */

    //     $bookings = $query
    //         ->latest('id')
    //         ->paginate(10);

    //     return response()->json([

    //         'status' => true,

    //         'statistics' => $statistics,

    //         'bookings' => $bookings->items(),

    //         'pagination' => [

    //             'current_page' => $bookings->currentPage(),

    //             'last_page' => $bookings->lastPage(),

    //             'per_page' => $bookings->perPage(),

    //             'total' => $bookings->total(),

    //             'from' => $bookings->firstItem(),

    //             'to' => $bookings->lastItem(),

    //             'links' => $bookings->toHtml(),

    //         ],

    //     ]);
    // }

    public function ajaxCurrentStays(Request $request)
    {
        $user = auth()->user();

        $query = Booking::with([
            'room.building',
            'guests',
            'payments',
        ])
            ->where('status', 'checked_in');

        /*
    |--------------------------------------------------------------------------
    | Building Access
    |--------------------------------------------------------------------------
    |
    | Super Admin and Admin can see all bookings.
    | Other users can only see bookings from assigned buildings.
    |
    */

        if (!$user->isSuperadmin() && !$user->isAdmin()) {

            $buildingIds = $user->buildings()
                ->pluck('buildings.id');

            $query->whereHas('room', function ($room) use ($buildingIds) {
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

            // Total guests currently checked in
            'guest_count' => (clone $query)->count(),

            // Total occupied rooms
            'running_rooms' => (clone $query)
                ->distinct('room_id')
                ->count('room_id'),

            // Guests expected to check out today
            'checkout_today' => (clone $query)
                ->whereDate('expected_check_out', today())
                ->count(),

            // Total room rent of all current stays
            'total_balance' => (clone $query)
                ->sum('room_rent'),

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
        $user = auth()->user();

        $query = Building::orderBy('name');

        // Super Admin and Admin can see all buildings.
        // Other users can only see assigned buildings.
        if (!$user->isSuperadmin() && !$user->isAdmin()) {
            $query->whereIn(
                'id',
                $user->buildings()->pluck('buildings.id')
            );
        }

        $buildings = $query->get();

        return view(
            'dashboard.bookings.history',
            compact('buildings')
        );
    }
    // public function history()
    // {
    //     $buildings = Building::orderBy('name')->get();

    //     return view(
    //         'dashboard.bookings.history',
    //         compact('buildings')
    //     );
    // }

    /**
     * Booking History Ajax
     */
    public function ajaxHistory(Request $request)
    {
        $query = Booking::with([
            'room.building',
            'guests',
            'services',
        ])
            ->where('status', 'checked_out');

        /*
    |--------------------------------------------------------------------------
    | Building Access
    |--------------------------------------------------------------------------
    */

        $user = auth()->user();

        // Super Admin and Admin can see all bookings.
        // Other users can only see bookings from assigned buildings.
        if (!$user->isSuperadmin() && !$user->isAdmin()) {

            $buildingIds = $user->buildings()
                ->pluck('buildings.id');

            $query->whereHas('room', function ($room) use ($buildingIds) {
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
    | Get All Matching Bookings
    |--------------------------------------------------------------------------
    */

        $allBookings = (clone $query)->get();

        /*
    |--------------------------------------------------------------------------
    | Revenue
    |--------------------------------------------------------------------------
    */

        $revenue = $allBookings->sum(function ($booking) {

            $stayDays = max(
                1,
                (int) $booking->expected_stay_days
            );

            return (float) $booking->room_rent * $stayDays;
        });

        /*
    |--------------------------------------------------------------------------
    | Average Stay
    |--------------------------------------------------------------------------
    */

        $averageStay = $allBookings->avg(function ($booking) {

            return max(
                1,
                (int) $booking->expected_stay_days
            );
        });

        /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

        $statistics = [

            'completed' => $allBookings->count(),

            'revenue' => $revenue,

            'average_stay' => round(
                $averageStay ?? 0,
                1
            ),

            'checkout_today' => (clone $query)
                ->whereDate('check_out', today())
                ->count(),

        ];

        /*
    |--------------------------------------------------------------------------
    | Pagination Size
    |--------------------------------------------------------------------------
    */

        $perPage = (int) $request->input(
            'per_page',
            10
        );

        $allowedPerPage = [
            10,
            25,
            50,
            100,
        ];

        if (!in_array($perPage, $allowedPerPage, true)) {
            $perPage = 10;
        }

        /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

        $bookings = $query
            ->latest('check_out')
            ->paginate($perPage);

        /*
    |--------------------------------------------------------------------------
    | Get Invoices
    |--------------------------------------------------------------------------
    |
    | Grand Total is already saved in invoices during checkout.
    |
    */

        $invoiceMap = Invoice::whereIn(
            'booking_id',
            $bookings->pluck('id')
        )
            ->get()
            ->keyBy('booking_id');

        /*
    |--------------------------------------------------------------------------
    | Calculate History Values
    |--------------------------------------------------------------------------
    */

        $bookings->getCollection()->transform(
            function ($booking) use ($invoiceMap) {

                /*
            |--------------------------------------------------------------------------
            | Stay Days
            |--------------------------------------------------------------------------
            */

                $checkIn = Carbon::parse(
                    $booking->check_in
                );

                $checkOut = Carbon::parse(
                    $booking->check_out
                );

                $stayDays = max(
                    1,
                    (int) ceil(
                        $checkIn->diffInSeconds($checkOut)
                            / 86400
                    )
                );

                /*
            |--------------------------------------------------------------------------
            | Room Total
            |--------------------------------------------------------------------------
            */

                $roomRentTotal =
                    (float) $booking->room_rent
                    * $stayDays;

                /*
            |--------------------------------------------------------------------------
            | Bed Services
            |--------------------------------------------------------------------------
            */

                $bedServices = $booking->services
                    ->filter(function ($service) {

                        return preg_match(
                            '/^beds?$/i',
                            trim(
                                $service->service_name ?? ''
                            )
                        );
                    });

                /*
            |--------------------------------------------------------------------------
            | Bed Quantity
            |--------------------------------------------------------------------------
            */

                $bedQuantity = $bedServices->sum(
                    function ($service) {

                        return (float) (
                            $service->quantity ?? 0
                        );
                    }
                );

                /*
            |--------------------------------------------------------------------------
            | Bed Price
            |--------------------------------------------------------------------------
            */

                $bedPrice = $bedServices->sum(
                    function ($service) {

                        return (float) (
                            $service->total_amount ?? 0
                        );
                    }
                );

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
            | Sub Total
            |--------------------------------------------------------------------------
            */

                $subtotal =
                    $roomRentTotal
                    + $serviceTotal
                    - $discount;

                /*
            |--------------------------------------------------------------------------
            | Invoice
            |--------------------------------------------------------------------------
            */

                $invoice = $invoiceMap->get(
                    $booking->id
                );

                /*
            |--------------------------------------------------------------------------
            | Grand Total
            |--------------------------------------------------------------------------
            */

                $grandTotal = $invoice
                    ? (float) $invoice->grand_total
                    : 0;

                /*
            |--------------------------------------------------------------------------
            | GST
            |--------------------------------------------------------------------------
            */

                $gst = round(
                    $grandTotal - $subtotal,
                    2
                );

                /*
            |--------------------------------------------------------------------------
            | Attach Values
            |--------------------------------------------------------------------------
            */

                $booking->total_amount =
                    $roomRentTotal;

                $booking->bed_quantity =
                    $bedQuantity;

                $booking->bed_price =
                    $bedPrice;

                $booking->gst =
                    $gst;

                $booking->grand_total =
                    $grandTotal;

                return $booking;
            }
        );

        /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

        return response()->json([

            'statistics' => $statistics,

            'bookings' => $bookings->items(),

            'pagination' => [

                'current_page' =>
                $bookings->currentPage(),

                'last_page' =>
                $bookings->lastPage(),

                'per_page' =>
                $bookings->perPage(),

                'total' =>
                $bookings->total(),

                'from' =>
                $bookings->firstItem(),

                'to' =>
                $bookings->lastItem(),

                'links' =>
                $bookings->toHtml(),

            ],

        ]);
    }
    // public function ajaxHistory(Request $request)
    // {
    //     $query = Booking::with([
    //         'room.building',
    //         'guests',
    //         'services',
    //     ])
    //         ->where('status', 'checked_out');

    //     /*
    // |--------------------------------------------------------------------------
    // | Search
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('search')) {

    //         $search = trim($request->search);

    //         $query->where(function ($q) use ($search) {

    //             $q->where('booking_no', 'like', "%{$search}%")

    //                 ->orWhereHas('guests', function ($guest) use ($search) {

    //                     $guest->where('guest_name', 'like', "%{$search}%")
    //                         ->orWhere('mobile', 'like', "%{$search}%");
    //                 });
    //         });
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Building
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('building_id')) {

    //         $query->whereHas('room', function ($room) use ($request) {

    //             $room->where(
    //                 'building_id',
    //                 $request->building_id
    //             );
    //         });
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Date Filter
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('from')) {

    //         $query->whereDate(
    //             'check_in',
    //             '>=',
    //             $request->from
    //         );
    //     }

    //     if ($request->filled('to')) {

    //         $query->whereDate(
    //             'check_out',
    //             '<=',
    //             $request->to
    //         );
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Get All Matching Bookings
    // |--------------------------------------------------------------------------
    // */

    //     $allBookings = (clone $query)->get();

    //     /*
    // |--------------------------------------------------------------------------
    // | Revenue
    // |--------------------------------------------------------------------------
    // */

    //     $revenue = $allBookings->sum(function ($booking) {

    //         $stayDays = max(
    //             1,
    //             (int) $booking->expected_stay_days
    //         );

    //         return (float) $booking->room_rent * $stayDays;
    //     });

    //     /*
    // |--------------------------------------------------------------------------
    // | Average Stay
    // |--------------------------------------------------------------------------
    // */

    //     $averageStay = $allBookings->avg(function ($booking) {

    //         return max(
    //             1,
    //             (int) $booking->expected_stay_days
    //         );
    //     });

    //     /*
    // |--------------------------------------------------------------------------
    // | Statistics
    // |--------------------------------------------------------------------------
    // */

    //     $statistics = [

    //         'completed' => $allBookings->count(),

    //         'revenue' => $revenue,

    //         'average_stay' => round(
    //             $averageStay ?? 0,
    //             1
    //         ),

    //         'checkout_today' => (clone $query)
    //             ->whereDate('check_out', today())
    //             ->count(),

    //     ];

    //     /*
    // |--------------------------------------------------------------------------
    // | Pagination Size
    // |--------------------------------------------------------------------------
    // |
    // | Available:
    // | 10, 25, 50, 100
    // |
    // */

    //     $perPage = (int) $request->input(
    //         'per_page',
    //         10
    //     );

    //     $allowedPerPage = [
    //         10,
    //         25,
    //         50,
    //         100,
    //     ];

    //     if (!in_array($perPage, $allowedPerPage, true)) {

    //         $perPage = 10;
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Pagination
    // |--------------------------------------------------------------------------
    // */

    //     $bookings = $query
    //         ->latest('check_out')
    //         ->paginate($perPage);

    //     /*
    // |--------------------------------------------------------------------------
    // | Get Invoices
    // |--------------------------------------------------------------------------
    // |
    // | Grand Total is already saved in invoices during checkout.
    // |
    // */

    //     $invoiceMap = Invoice::whereIn(
    //         'booking_id',
    //         $bookings->pluck('id')
    //     )
    //         ->get()
    //         ->keyBy('booking_id');

    //     /*
    // |--------------------------------------------------------------------------
    // | Calculate History Values
    // |--------------------------------------------------------------------------
    // */

    //     $bookings->getCollection()->transform(
    //         function ($booking) use ($invoiceMap) {

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Stay Days
    //         |--------------------------------------------------------------------------
    //         */

    //             $checkIn = Carbon::parse(
    //                 $booking->check_in
    //             );

    //             $checkOut = Carbon::parse(
    //                 $booking->check_out
    //             );

    //             $stayDays = max(
    //                 1,
    //                 (int) ceil(
    //                     $checkIn->diffInSeconds($checkOut)
    //                         / 86400
    //                 )
    //             );

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Room Total
    //         |--------------------------------------------------------------------------
    //         */

    //             $roomRentTotal =
    //                 (float) $booking->room_rent
    //                 * $stayDays;

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Bed Services
    //         |--------------------------------------------------------------------------
    //         */

    //             $bedServices = $booking->services
    //                 ->filter(function ($service) {

    //                     return preg_match(
    //                         '/^beds?$/i',
    //                         trim(
    //                             $service->service_name ?? ''
    //                         )
    //                     );
    //                 });

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Bed Quantity
    //         |--------------------------------------------------------------------------
    //         */

    //             $bedQuantity = $bedServices->sum(
    //                 function ($service) {

    //                     return (float) (
    //                         $service->quantity ?? 0
    //                     );
    //                 }
    //             );

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Bed Price
    //         |--------------------------------------------------------------------------
    //         */

    //             $bedPrice = $bedServices->sum(
    //                 function ($service) {

    //                     return (float) (
    //                         $service->total_amount ?? 0
    //                     );
    //                 }
    //             );

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Chargeable Services
    //         |--------------------------------------------------------------------------
    //         */

    //             $serviceTotal = $booking->services
    //                 ->where('type', 'chargeable')
    //                 ->sum('total_amount');

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Discount
    //         |--------------------------------------------------------------------------
    //         */

    //             $discount = (float) (
    //                 $booking->discount ?? 0
    //             );

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Sub Total
    //         |--------------------------------------------------------------------------
    //         */

    //             $subtotal =
    //                 $roomRentTotal
    //                 + $serviceTotal
    //                 - $discount;

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Invoice
    //         |--------------------------------------------------------------------------
    //         */

    //             $invoice = $invoiceMap->get(
    //                 $booking->id
    //             );

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Grand Total
    //         |--------------------------------------------------------------------------
    //         */

    //             $grandTotal = $invoice
    //                 ? (float) $invoice->grand_total
    //                 : 0;

    //             /*
    //         |--------------------------------------------------------------------------
    //         | GST
    //         |--------------------------------------------------------------------------
    //         */

    //             $gst = round(
    //                 $grandTotal - $subtotal,
    //                 2
    //             );

    //             /*
    //         |--------------------------------------------------------------------------
    //         | Attach Values
    //         |--------------------------------------------------------------------------
    //         */

    //             $booking->total_amount =
    //                 $roomRentTotal;

    //             $booking->bed_quantity =
    //                 $bedQuantity;

    //             $booking->bed_price =
    //                 $bedPrice;

    //             $booking->gst =
    //                 $gst;

    //             $booking->grand_total =
    //                 $grandTotal;

    //             return $booking;
    //         }
    //     );

    //     /*
    // |--------------------------------------------------------------------------
    // | Response
    // |--------------------------------------------------------------------------
    // */

    //     return response()->json([

    //         'statistics' => $statistics,

    //         'bookings' => $bookings->items(),

    //         'pagination' => [

    //             'current_page' =>
    //             $bookings->currentPage(),

    //             'last_page' =>
    //             $bookings->lastPage(),

    //             'per_page' =>
    //             $bookings->perPage(),

    //             'total' =>
    //             $bookings->total(),

    //             'from' =>
    //             $bookings->firstItem(),

    //             'to' =>
    //             $bookings->lastItem(),

    //             'links' =>
    //             $bookings->toHtml(),

    //         ],

    //     ]);
    // }


    /**
     * Export Booking History  
     */
    public function exportHistory(Request $request)
    {
        $query = Booking::with([
            'room.building',
            'guests',
            'services',
        ])
            ->where('status', 'checked_out');

        /*
    |--------------------------------------------------------------------------
    | Building Access
    |--------------------------------------------------------------------------
    */

        $user = auth()->user();

        // Super Admin and Admin can see all bookings.
        // Other users can only see bookings from assigned buildings.
        if (!$user->isSuperadmin() && !$user->isAdmin()) {

            $buildingIds = $user->buildings()
                ->pluck('buildings.id');

            $query->whereHas('room', function ($room) use ($buildingIds) {
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

                $q->where(
                    'booking_no',
                    'like',
                    "%{$search}%"
                )
                    ->orWhereHas('guests', function ($guest) use ($search) {

                        $guest->where(
                            'guest_name',
                            'like',
                            "%{$search}%"
                        )
                            ->orWhere(
                                'mobile',
                                'like',
                                "%{$search}%"
                            );
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
    | Date Filters
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
    | Get Bookings
    |--------------------------------------------------------------------------
    */

        $bookings = $query
            ->latest('check_out')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Invoice Map
    |--------------------------------------------------------------------------
    */

        $invoiceMap = Invoice::whereIn(
            'booking_id',
            $bookings->pluck('id')
        )
            ->get()
            ->keyBy('booking_id');

        /*
    |--------------------------------------------------------------------------
    | Create Excel
    |--------------------------------------------------------------------------
    */

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Booking History');

        /*
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    */

        $headers = [
            'Booking No',
            'Guest',
            'Mobile',
            'Building',
            'Room',
            'Check In',
            'Check Out',
            'Total Days',
            'Beds',
            'Bed Price',
            'Total',
            'GST',
            'Grand Total',
            'Status',
        ];

        $sheet->fromArray(
            $headers,
            null,
            'A1'
        );

        /*
    |--------------------------------------------------------------------------
    | Header Styling
    |--------------------------------------------------------------------------
    */

        $headerRange = 'A1:N1';

        $sheet->getStyle($headerRange)
            ->getFont()
            ->setBold(true);

        $sheet->getStyle($headerRange)
            ->getAlignment()
            ->setHorizontal(
                Alignment::HORIZONTAL_CENTER
            );

        $sheet->getStyle($headerRange)
            ->getAlignment()
            ->setVertical(
                Alignment::VERTICAL_CENTER
            );

        $sheet->getRowDimension(1)
            ->setRowHeight(25);

        /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    */

        $row = 2;

        foreach ($bookings as $booking) {

            $checkIn = Carbon::parse(
                $booking->check_in
            );

            $checkOut = Carbon::parse(
                $booking->check_out
            );

            $stayDays = max(
                1,
                (int) ceil(
                    $checkIn->diffInSeconds($checkOut) / 86400
                )
            );

            /*
        |--------------------------------------------------------------------------
        | Room Total
        |--------------------------------------------------------------------------
        */

            $roomRentTotal =
                (float) $booking->room_rent * $stayDays;

            /*
        |--------------------------------------------------------------------------
        | Beds
        |--------------------------------------------------------------------------
        */

            $bedServices = $booking->services->filter(
                function ($service) {

                    return preg_match(
                        '/^beds?$/i',
                        trim($service->service_name ?? '')
                    );
                }
            );

            $bedQuantity = $bedServices->sum(
                function ($service) {

                    return (float) (
                        $service->quantity ?? 0
                    );
                }
            );

            $bedPrice = $bedServices->sum(
                function ($service) {

                    return (float) (
                        $service->total_amount ?? 0
                    );
                }
            );

            /*
        |--------------------------------------------------------------------------
        | Services
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
        | Subtotal
        |--------------------------------------------------------------------------
        */

            $subtotal =
                $roomRentTotal
                + $serviceTotal
                - $discount;

            /*
        |--------------------------------------------------------------------------
        | Invoice
        |--------------------------------------------------------------------------
        */

            $invoice = $invoiceMap->get(
                $booking->id
            );

            $grandTotal = $invoice
                ? (float) $invoice->grand_total
                : 0;

            /*
        |--------------------------------------------------------------------------
        | GST
        |--------------------------------------------------------------------------
        */

            $gst = round(
                $grandTotal - $subtotal,
                2
            );

            /*
        |--------------------------------------------------------------------------
        | Guest
        |--------------------------------------------------------------------------
        */

            $guest = $booking->guests->first();

            /*
        |--------------------------------------------------------------------------
        | Excel Row
        |--------------------------------------------------------------------------
        */

            $sheet->fromArray([
                $booking->booking_no ?? '',
                $guest?->guest_name ?? '',
                $guest?->mobile ?? '',
                $booking->room?->building?->name ?? '',
                $booking->room?->room_number ?? '',
                $checkIn->format('d-m-Y H:i'),
                $checkOut->format('d-m-Y H:i'),
                $stayDays,
                $bedQuantity,
                $bedPrice,
                $roomRentTotal,
                $gst,
                $grandTotal,
                'Checked Out',
            ], null, 'A' . $row);

            $row++;
        }

        /*
    |--------------------------------------------------------------------------
    | Column Widths
    |--------------------------------------------------------------------------
    */

        $widths = [
            'A' => 18,
            'B' => 25,
            'C' => 16,
            'D' => 22,
            'E' => 15,
            'F' => 20,
            'G' => 20,
            'H' => 12,
            'I' => 10,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 18,
            'N' => 15,
        ];

        foreach ($widths as $column => $width) {

            $sheet
                ->getColumnDimension($column)
                ->setWidth($width);
        }

        /*
    |--------------------------------------------------------------------------
    | Number Formatting
    |--------------------------------------------------------------------------
    */

        if ($row > 2) {

            $sheet
                ->getStyle('J2:M' . ($row - 1))
                ->getNumberFormat()
                ->setFormatCode('#,##0.00');
        }

        /*
    |--------------------------------------------------------------------------
    | Freeze Header
    |--------------------------------------------------------------------------
    */

        $sheet->freezePane('A2');

        /*
    |--------------------------------------------------------------------------
    | Auto Filter
    |--------------------------------------------------------------------------
    */

        $sheet->setAutoFilter(
            'A1:N' . max(1, $row - 1)
        );

        /*
    |--------------------------------------------------------------------------
    | Download XLSX
    |--------------------------------------------------------------------------
    */

        $fileName =
            'booking-history-' .
            now()->format('Y-m-d-H-i-s') .
            '.xlsx';

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(
            function () use ($writer) {
                $writer->save('php://output');
            },
            $fileName,
            [
                'Content-Type' =>
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

                'Cache-Control' =>
                'max-age=0',
            ]
        );
    }
    // public function exportHistory(Request $request)
    // {
    //     $query = Booking::with([
    //         'room.building',
    //         'guests',
    //         'services',
    //     ])
    //         ->where('status', 'checked_out');

    //     /*
    // |--------------------------------------------------------------------------
    // | Search
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('search')) {

    //         $search = trim($request->search);

    //         $query->where(function ($q) use ($search) {

    //             $q->where(
    //                 'booking_no',
    //                 'like',
    //                 "%{$search}%"
    //             )
    //                 ->orWhereHas('guests', function ($guest) use ($search) {

    //                     $guest->where(
    //                         'guest_name',
    //                         'like',
    //                         "%{$search}%"
    //                     )
    //                         ->orWhere(
    //                             'mobile',
    //                             'like',
    //                             "%{$search}%"
    //                         );
    //                 });
    //         });
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Building
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('building_id')) {

    //         $query->whereHas('room', function ($room) use ($request) {

    //             $room->where(
    //                 'building_id',
    //                 $request->building_id
    //             );
    //         });
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Date Filters
    // |--------------------------------------------------------------------------
    // */

    //     if ($request->filled('from')) {

    //         $query->whereDate(
    //             'check_in',
    //             '>=',
    //             $request->from
    //         );
    //     }

    //     if ($request->filled('to')) {

    //         $query->whereDate(
    //             'check_out',
    //             '<=',
    //             $request->to
    //         );
    //     }

    //     $bookings = $query
    //         ->latest('check_out')
    //         ->get();

    //     /*
    // |--------------------------------------------------------------------------
    // | Invoice Map
    // |--------------------------------------------------------------------------
    // */

    //     $invoiceMap = Invoice::whereIn(
    //         'booking_id',
    //         $bookings->pluck('id')
    //     )
    //         ->get()
    //         ->keyBy('booking_id');

    //     /*
    // |--------------------------------------------------------------------------
    // | Create Excel
    // |--------------------------------------------------------------------------
    // */

    //     $spreadsheet = new Spreadsheet();

    //     $sheet = $spreadsheet->getActiveSheet();

    //     $sheet->setTitle('Booking History');

    //     /*
    // |--------------------------------------------------------------------------
    // | Header
    // |--------------------------------------------------------------------------
    // */

    //     $headers = [
    //         'Booking No',
    //         'Guest',
    //         'Mobile',
    //         'Building',
    //         'Room',
    //         'Check In',
    //         'Check Out',
    //         'Total Days',
    //         'Beds',
    //         'Bed Price',
    //         'Total',
    //         'GST',
    //         'Grand Total',
    //         'Status',
    //     ];

    //     $sheet->fromArray(
    //         $headers,
    //         null,
    //         'A1'
    //     );

    //     /*
    // |--------------------------------------------------------------------------
    // | Header Styling
    // |--------------------------------------------------------------------------
    // */

    //     $headerRange = 'A1:N1';

    //     $sheet->getStyle($headerRange)->getFont()->setBold(true);

    //     $sheet->getStyle($headerRange)
    //         ->getAlignment()
    //         ->setHorizontal(Alignment::HORIZONTAL_CENTER);

    //     $sheet->getStyle($headerRange)
    //         ->getAlignment()
    //         ->setVertical(Alignment::VERTICAL_CENTER);

    //     $sheet->getRowDimension(1)->setRowHeight(25);

    //     /*
    // |--------------------------------------------------------------------------
    // | Data
    // |--------------------------------------------------------------------------
    // */

    //     $row = 2;

    //     foreach ($bookings as $booking) {

    //         $checkIn = Carbon::parse(
    //             $booking->check_in
    //         );

    //         $checkOut = Carbon::parse(
    //             $booking->check_out
    //         );

    //         $stayDays = max(
    //             1,
    //             (int) ceil(
    //                 $checkIn->diffInSeconds($checkOut) / 86400
    //             )
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Room Total
    //     |--------------------------------------------------------------------------
    //     */

    //         $roomRentTotal =
    //             (float) $booking->room_rent * $stayDays;

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Beds
    //     |--------------------------------------------------------------------------
    //     */

    //         $bedServices = $booking->services->filter(
    //             function ($service) {

    //                 return preg_match(
    //                     '/^beds?$/i',
    //                     trim($service->service_name ?? '')
    //                 );
    //             }
    //         );

    //         $bedQuantity = $bedServices->sum(
    //             function ($service) {

    //                 return (float) (
    //                     $service->quantity ?? 0
    //                 );
    //             }
    //         );

    //         $bedPrice = $bedServices->sum(
    //             function ($service) {

    //                 return (float) (
    //                     $service->total_amount ?? 0
    //                 );
    //             }
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Services
    //     |--------------------------------------------------------------------------
    //     */

    //         $serviceTotal = $booking->services
    //             ->where('type', 'chargeable')
    //             ->sum('total_amount');

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Discount
    //     |--------------------------------------------------------------------------
    //     */

    //         $discount = (float) (
    //             $booking->discount ?? 0
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Subtotal
    //     |--------------------------------------------------------------------------
    //     */

    //         $subtotal =
    //             $roomRentTotal
    //             + $serviceTotal
    //             - $discount;

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Invoice
    //     |--------------------------------------------------------------------------
    //     */

    //         $invoice = $invoiceMap->get(
    //             $booking->id
    //         );

    //         $grandTotal = $invoice
    //             ? (float) $invoice->grand_total
    //             : 0;

    //         /*
    //     |--------------------------------------------------------------------------
    //     | GST
    //     |--------------------------------------------------------------------------
    //     */

    //         $gst = round(
    //             $grandTotal - $subtotal,
    //             2
    //         );

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Guest
    //     |--------------------------------------------------------------------------
    //     */

    //         $guest = $booking->guests->first();

    //         /*
    //     |--------------------------------------------------------------------------
    //     | Excel Row
    //     |--------------------------------------------------------------------------
    //     */

    //         $sheet->fromArray([
    //             $booking->booking_no ?? '',
    //             $guest?->guest_name ?? '',
    //             $guest?->mobile ?? '',
    //             $booking->room?->building?->name ?? '',
    //             $booking->room?->room_number ?? '',
    //             $checkIn->format('d-m-Y H:i'),
    //             $checkOut->format('d-m-Y H:i'),
    //             $stayDays,
    //             $bedQuantity,
    //             $bedPrice,
    //             $roomRentTotal,
    //             $gst,
    //             $grandTotal,
    //             'Checked Out',
    //         ], null, 'A' . $row);

    //         $row++;
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Column Widths
    // |--------------------------------------------------------------------------
    // */

    //     $widths = [
    //         'A' => 18,
    //         'B' => 25,
    //         'C' => 16,
    //         'D' => 22,
    //         'E' => 15,
    //         'F' => 20,
    //         'G' => 20,
    //         'H' => 12,
    //         'I' => 10,
    //         'J' => 15,
    //         'K' => 15,
    //         'L' => 15,
    //         'M' => 18,
    //         'N' => 15,
    //     ];

    //     foreach ($widths as $column => $width) {
    //         $sheet
    //             ->getColumnDimension($column)
    //             ->setWidth($width);
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Number Formatting
    // |--------------------------------------------------------------------------
    // */

    //     if ($row > 2) {

    //         $sheet
    //             ->getStyle('J2:M' . ($row - 1))
    //             ->getNumberFormat()
    //             ->setFormatCode('#,##0.00');
    //     }

    //     /*
    // |--------------------------------------------------------------------------
    // | Freeze Header
    // |--------------------------------------------------------------------------
    // */

    //     $sheet->freezePane('A2');

    //     /*
    // |--------------------------------------------------------------------------
    // | Auto Filter
    // |--------------------------------------------------------------------------
    // */

    //     $sheet->setAutoFilter(
    //         'A1:N' . max(1, $row - 1)
    //     );

    //     /*
    // |--------------------------------------------------------------------------
    // | Download XLSX
    // |--------------------------------------------------------------------------
    // */

    //     $fileName =
    //         'booking-history-' .
    //         now()->format('Y-m-d-H-i-s') .
    //         '.xlsx';

    //     $writer = new Xlsx($spreadsheet);

    //     return response()->streamDownload(
    //         function () use ($writer) {
    //             $writer->save('php://output');
    //         },
    //         $fileName,
    //         [
    //             'Content-Type' =>
    //             'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',

    //             'Cache-Control' =>
    //             'max-age=0',
    //         ]
    //     );
    // }

    /**
     * Bookingservice
     */

    public function services(Booking $booking)
    {
        $booking->load([
            'room.building',
            'guests',
            'services.item',
        ]);

        // Check access to the booking's building
        $this->checkBuildingAccess($booking->room->building);

        $items = Item::orderBy('item_name')->get();

        return view(
            'dashboard.bookings.services',
            compact(
                'booking',
                'items'
            )
        );
    }
    // public function services(Booking $booking)
    // {
    //     $booking->load([
    //         'room.building',
    //         'guests',
    //         'services.item'
    //     ]);

    //     $items = Item::orderBy('item_name')->get();

    //     return view('dashboard.bookings.services', compact(
    //         'booking',
    //         'items'
    //     ));
    // }

    /**
     * Add booking service
     */
    public function storeService(Request $request, Booking $booking)
    {
        $request->validate([
            'type'         => 'required|in:chargeable,complimentary,other',
            'service_name' => 'nullable|string|max:255',
            'item_id'      => 'nullable|exists:items,id',
            'quantity'     => 'required|integer|min:1',
            'unit_price'   => 'required|numeric|min:0',
            'remarks'      => 'nullable|string',
        ]);

        // Load booking building
        $booking->load('room.building');

        // Check access to the booking's building
        $this->checkBuildingAccess($booking->room->building);

        // "Other" is stored as "chargeable"
        $type = $request->type === 'other'
            ? 'chargeable'
            : $request->type;

        // Chargeable & Complimentary require an item
        if (
            $request->type !== 'other' &&
            empty($request->item_id)
        ) {
            return back()->withErrors([
                'item_id' => 'Please select an item.'
            ])->withInput();
        }

        // Other requires manual service name
        if (
            $request->type === 'other' &&
            empty($request->service_name)
        ) {
            return back()->withErrors([
                'service_name' => 'Service name is required.'
            ])->withInput();
        }

        $itemId = null;
        $serviceName = $request->service_name;
        $unitPrice = $request->unit_price;

        // Chargeable & Complimentary use Item Master
        if ($request->type !== 'other') {

            $item = Item::findOrFail($request->item_id);

            $itemId = $item->id;
            $serviceName = $item->item_name;

            if ($type === 'complimentary') {
                $unitPrice = 0;
            }
        }

        $quantity = (int) $request->quantity;
        $totalAmount = $quantity * $unitPrice;

        DB::transaction(function () use (
            $booking,
            $type,
            $itemId,
            $serviceName,
            $quantity,
            $unitPrice,
            $totalAmount,
            $request
        ) {

            // Deduct stock only for inventory items
            if ($request->type !== 'other') {

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
                'type'         => $type,
                'service_name' => $serviceName,
                'quantity'     => $quantity,
                'unit_price'   => $unitPrice,
                'total_amount' => $totalAmount,
                'remarks'      => $request->remarks,
                'created_by'   => auth()->id(),
                'updated_by'   => auth()->id(),
            ]);
        });

        return back()->with(
            'success',
            'Guest service added successfully.'
        );
    }
    // public function storeService(Request $request, Booking $booking)
    // {
    //     $request->validate([
    //         'type'         => 'required|in:chargeable,complimentary,other',
    //         'service_name' => 'nullable|string|max:255',
    //         'item_id'      => 'nullable|exists:items,id',
    //         'quantity'     => 'required|integer|min:1',
    //         'unit_price'   => 'required|numeric|min:0',
    //         'remarks'      => 'nullable|string',
    //     ]);

    //     // "Other" is stored as "chargeable"
    //     $type = $request->type === 'other'
    //         ? 'chargeable'
    //         : $request->type;

    //     // Chargeable & Complimentary require an item
    //     if (
    //         $request->type !== 'other' &&
    //         empty($request->item_id)
    //     ) {
    //         return back()->withErrors([
    //             'item_id' => 'Please select an item.'
    //         ])->withInput();
    //     }

    //     // Other requires manual service name
    //     if (
    //         $request->type === 'other' &&
    //         empty($request->service_name)
    //     ) {
    //         return back()->withErrors([
    //             'service_name' => 'Service name is required.'
    //         ])->withInput();
    //     }

    //     $itemId = null;
    //     $serviceName = $request->service_name;
    //     $unitPrice = $request->unit_price;

    //     // Chargeable & Complimentary use Item Master
    //     if ($request->type !== 'other') {

    //         $item = Item::findOrFail($request->item_id);

    //         $itemId = $item->id;
    //         $serviceName = $item->item_name;

    //         if ($type === 'complimentary') {
    //             $unitPrice = 0;
    //         }
    //     }

    //     $quantity = (int) $request->quantity;
    //     $totalAmount = $quantity * $unitPrice;

    //     DB::transaction(function () use (
    //         $booking,
    //         $type,
    //         $itemId,
    //         $serviceName,
    //         $quantity,
    //         $unitPrice,
    //         $totalAmount,
    //         $request
    //     ) {

    //         // Deduct stock only for inventory items
    //         if ($request->type !== 'other') {

    //             $item = Item::findOrFail($itemId);

    //             if (!$item->hasStock($quantity)) {
    //                 throw \Illuminate\Validation\ValidationException::withMessages([
    //                     'quantity' => 'Insufficient stock available.'
    //                 ]);
    //             }

    //             $item->decreaseStock($quantity);
    //         }

    //         BookingService::create([
    //             'booking_id'   => $booking->id,
    //             'item_id'      => $itemId,
    //             'type'         => $type, // <-- IMPORTANT
    //             'service_name' => $serviceName,
    //             'quantity'     => $quantity,
    //             'unit_price'   => $unitPrice,
    //             'total_amount' => $totalAmount,
    //             'remarks'      => $request->remarks,
    //             'created_by'   => auth()->id(),
    //             'updated_by'   => auth()->id(),
    //         ]);
    //     });

    //     return back()->with('success', 'Guest service added successfully.');
    // }

    /**
     * Delete
     */
    public function deleteService(BookingService $service)
    {
        // Load the booking and its building
        $service->load([
            'booking.room.building',
            'item',
        ]);

        // Check access to the booking's building
        $this->checkBuildingAccess($service->booking->room->building);

        DB::transaction(function () use ($service) {

            // Restore stock only if this service is linked
            // to an inventory item
            if ($service->item_id && $service->item) {

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
    // public function deleteService(BookingService $service)
    // {
    //     DB::transaction(function () use ($service) {

    //         // Restore stock only if this service is linked to an inventory item
    //         if ($service->item_id && $service->item) {

    //             $service->item->increaseStock($service->quantity);
    //         }

    //         $service->delete();
    //     });

    //     return back()->with(
    //         'success',
    //         'Service deleted successfully.'
    //     );
    // }

    /**
     * Get Floors By Building
     */

    public function getFloors(Building $building)
    {
        // Check access to this building
        $this->checkBuildingAccess($building);

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
    // public function getFloors(Building $building)
    // {
    //     return response()->json(
    //         $building->floors()
    //             ->where('status', 'active')
    //             ->orderBy('sort_order')
    //             ->get([
    //                 'id',
    //                 'name',
    //                 'sort_order',
    //             ])
    //     );
    // }

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

        // Check access to the booking's building
        $this->checkBuildingAccess($booking->room->building);

        return response()->json([
            'booking' => $booking,
        ]);
    }
    // public function details(Booking $booking)
    // {
    //     $booking->load([
    //         'room.building',
    //         'guests',
    //         'services.item',
    //         'payments.creator',
    //         'creator',
    //         'updater',
    //     ]);

    //     return response()->json([
    //         'booking' => $booking,
    //     ]);
    // }


    /**
     * Update extended date
     */
    public function updateExpectedCheckout(Request $request, $id)
    {
        $request->validate([
            'expected_check_out' => 'required|date|after_or_equal:today',
        ]);

        $booking = Booking::with('room.building')
            ->findOrFail($id);

        // Check access to the booking's building
        $this->checkBuildingAccess($booking->room->building);

        $expectedStayDays = Carbon::parse($booking->check_in)
            ->diffInDays(
                Carbon::parse($request->expected_check_out)
            );

        $booking->update([
            'expected_check_out' => $request->expected_check_out,

            'expected_stay_days' => max(
                $expectedStayDays,
                1
            ),

            'updated_by' => auth()->id(),
        ]);

        return response()->json([
            'status' => true,

            'message' => 'Stay extended successfully.',

            'expected_check_out' => $booking->expected_check_out,

            'expected_stay_days' => $booking->expected_stay_days,
        ]);
    }

    // public function updateExpectedCheckout(Request $request, $id)
    // {
    //     $request->validate([
    //         'expected_check_out' => 'required|date|after_or_equal:today',
    //     ]);

    //     $booking = Booking::findOrFail($id);

    //     $expectedStayDays = Carbon::parse($booking->check_in)
    //         ->diffInDays(Carbon::parse($request->expected_check_out));

    //     $booking->update([

    //         'expected_check_out' => $request->expected_check_out,

    //         'expected_stay_days' => max($expectedStayDays, 1),

    //         'updated_by' => auth()->id(),

    //     ]);

    //     return response()->json([

    //         'status' => true,

    //         'message' => 'Stay extended successfully.',

    //         'expected_check_out' => $booking->expected_check_out,

    //         'expected_stay_days' => $booking->expected_stay_days,

    //     ]);
    // }
}