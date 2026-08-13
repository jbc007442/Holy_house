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

        $revenue = Invoice::query();

        if ($request->filled('building_id')) {

            $revenue->whereHas('booking.room', function ($q) use ($request) {

                $q->where('building_id', $request->building_id);
            });
        }

        return response()->json([

            'stats' => [

                'buildings' => Building::count(),

                'revenue' => $revenue->sum('grand_total'),

                'rooms' => Room::count(),

                'bookings' => Booking::where('status', 'checked_in')->count(),

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