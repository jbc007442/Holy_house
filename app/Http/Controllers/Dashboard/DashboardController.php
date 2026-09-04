<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Building;
use App\Models\Room;
use App\Models\Booking;
use App\Models\LoginHistory;
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
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Accessible Building IDs
        |--------------------------------------------------------------------------
        */

        $buildingIds = null;

        if (!$user->isSuperadmin() && !$user->isAdmin()) {

            $buildingIds = $user->buildings()
                ->pluck('buildings.id');
        }

        /*
        |--------------------------------------------------------------------------
        | Selected Building
        |--------------------------------------------------------------------------
        */

        $selectedBuildingId = $request->filled('building_id')
            ? (int) $request->building_id
            : null;

        /*
        |--------------------------------------------------------------------------
        | Validate Selected Building Access
        |--------------------------------------------------------------------------
        */

        if (
            $selectedBuildingId !== null &&
            $buildingIds !== null &&
            !$buildingIds->contains($selectedBuildingId)
        ) {

            abort(
                403,
                'You do not have access to this building.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Login History
        |--------------------------------------------------------------------------
        */

        $loginHistory = LoginHistory::with('user')
            ->latest('login_at')
            ->paginate(10);

        /*
        |--------------------------------------------------------------------------
        | Revenue Query
        |--------------------------------------------------------------------------
        */

        $revenue = Booking::query();

        if ($selectedBuildingId !== null) {

            $revenue->whereHas('room', function ($q) use ($selectedBuildingId) {

                $q->where(
                    'building_id',
                    $selectedBuildingId
                );
            });

        } elseif ($buildingIds !== null) {

            $revenue->whereHas('room', function ($q) use ($buildingIds) {

                $q->whereIn(
                    'building_id',
                    $buildingIds
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Current Guests Query
        |--------------------------------------------------------------------------
        */

        $bookingQuery = Booking::query()
            ->where('status', 'checked_in');

        if ($selectedBuildingId !== null) {

            $bookingQuery->whereHas('room', function ($q) use ($selectedBuildingId) {

                $q->where(
                    'building_id',
                    $selectedBuildingId
                );
            });

        } elseif ($buildingIds !== null) {

            $bookingQuery->whereHas('room', function ($q) use ($buildingIds) {

                $q->whereIn(
                    'building_id',
                    $buildingIds
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Room Query
        |--------------------------------------------------------------------------
        */

        $roomQuery = Room::query();

        if ($selectedBuildingId !== null) {

            $roomQuery->where(
                'building_id',
                $selectedBuildingId
            );

        } elseif ($buildingIds !== null) {

            $roomQuery->whereIn(
                'building_id',
                $buildingIds
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Today's Expected Check-Out
        |--------------------------------------------------------------------------
        */

        $checkoutQuery = Booking::query()
            ->where('status', 'checked_in')
            ->whereDate(
                'expected_check_out',
                today()
            );

        if ($selectedBuildingId !== null) {

            $checkoutQuery->whereHas('room', function ($q) use ($selectedBuildingId) {

                $q->where(
                    'building_id',
                    $selectedBuildingId
                );
            });

        } elseif ($buildingIds !== null) {

            $checkoutQuery->whereHas('room', function ($q) use ($buildingIds) {

                $q->whereIn(
                    'building_id',
                    $buildingIds
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Building Query
        |--------------------------------------------------------------------------
        */

        $buildingQuery = Building::query();

        if ($buildingIds !== null) {

            $buildingQuery->whereIn(
                'id',
                $buildingIds
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Building List
        |--------------------------------------------------------------------------
        */

        $buildingList = (clone $buildingQuery)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        return response()->json([

            'stats' => [

                // Buildings
                'buildings' => $buildingQuery->count(),

                // Revenue
                'revenue' => $revenue->sum('room_rent'),

                // Total Rooms
                'rooms' => (clone $roomQuery)->count(),

                // Available Rooms
                'available_rooms' => (clone $roomQuery)
                    ->where('status', 'available')
                    ->count(),

                // Running Rooms
                'running_rooms' => (clone $roomQuery)
                    ->where('status', 'running')
                    ->count(),

                // Today's Expected Check-Out
                'today_checkout' => $checkoutQuery->count(),

                // Current Guests
                'bookings' => $bookingQuery->count(),

                // Users
                'users' => User::count(),

            ],

            'buildingList' => $buildingList,

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