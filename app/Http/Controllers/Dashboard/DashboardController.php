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

        return response()->json([

            'stats' => [

                'buildings' => Building::count(),

                'rooms' => Room::count(),

                'bookings' => Booking::where('status', 'checked_in')->count(),

                'users' => User::count(),

            ],

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