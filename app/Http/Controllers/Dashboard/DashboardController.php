<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Building;
use App\Models\Room;
use App\Models\Booking;
use App\Models\BookingGuest;
use App\Models\BookingService;
use App\Models\Item;
use App\Models\PurchaseHistory;
use App\Models\StockMovement;
use App\Models\Invoice;
use App\Models\LoginHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Home
     */
    public function profile(Request $request)
    {
        if ($request->ajax()) {

            return response()->json([
                'user' => auth()->user(),
            ]);
        }

        return view('dashboard.profile');
    }
    /**
     * Dashboard Home
     */
    public function index()
    {
        return view('dashboard.dashboard.dashboard');
    }

    /**
     * Dashboard Data (AJAX)
     */

    public function data(Request $request)
    {
        $loginHistory = LoginHistory::with('user')
            ->latest('login_at')
            ->paginate(10);

        /*
    |--------------------------------------------------------------------------
    | Revenue Query
    |--------------------------------------------------------------------------
    */

        $revenue = Booking::query();

        if ($request->filled('building_id')) {

            $revenue->whereHas('room', function ($q) use ($request) {

                $q->where('building_id', $request->building_id);
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Current Guests Query
    |--------------------------------------------------------------------------
    */

        $bookingQuery = Booking::where('status', 'checked_in');

        if ($request->filled('building_id')) {

            $bookingQuery->whereHas('room', function ($q) use ($request) {

                $q->where('building_id', $request->building_id);
            });
        }

        return response()->json([

            'stats' => [

                // Buildings
                'buildings' => Building::count(),

                // Revenue
                'revenue' => $revenue->sum('room_rent'),

                // Total Rooms
                'rooms' => Room::when($request->filled('building_id'), function ($q) use ($request) {

                    $q->where('building_id', $request->building_id);
                })->count(),

                // Available Rooms
                'available_rooms' => Room::when($request->filled('building_id'), function ($q) use ($request) {

                    $q->where('building_id', $request->building_id);
                })
                    ->where('status', 'available')
                    ->count(),

                // Running Rooms
                'running_rooms' => Room::when($request->filled('building_id'), function ($q) use ($request) {

                    $q->where('building_id', $request->building_id);
                })
                    ->where('status', 'running')
                    ->count(),

                // Today's Expected Check-Out
                'today_checkout' => Booking::when($request->filled('building_id'), function ($q) use ($request) {

                    $q->whereHas('room', function ($room) use ($request) {

                        $room->where('building_id', $request->building_id);
                    });
                })
                    ->where('status', 'checked_in')
                    ->whereDate('expected_check_out', today())
                    ->count(),

                // Current Guests
                'bookings' => $bookingQuery->count(),

                // Users
                'users' => User::count(),

            ],

            'buildingList' => Building::select('id', 'name')
                ->orderBy('name')
                ->get(),

            'loginHistory' => $loginHistory->items(),

            'pagination' => [

                'current_page' => $loginHistory->currentPage(),

                'last_page' => $loginHistory->lastPage(),

                'per_page' => $loginHistory->perPage(),

                'total' => $loginHistory->total(),

            ],

        ]);
    }

}