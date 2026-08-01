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
    public function data()
    {
        return response()->json([

            'stats' => [

                'buildings' => Building::count(),

                'rooms' => Room::count(),

                'bookings' => Booking::count(),

                'guests' => BookingGuest::count(),

                'services' => BookingService::count(),

                'users' => User::count(),

                'items' => Item::count(),

                'currentStock' => Item::sum('opening_stock'),

                'purchaseAmount' => PurchaseHistory::sum('total_amount'),

                'stockMovements' => StockMovement::count(),

            ],

        ]);
    }
}