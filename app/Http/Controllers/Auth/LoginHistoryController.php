<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    /**
     * Display login histories.
     */
    public function index(Request $request)
    {
        $query = LoginHistory::with('user');

        /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->whereHas('user', function ($user) use ($search) {

                    $user->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                })

                    ->orWhere('ip_address', 'like', "%{$search}%")
                    ->orWhere('browser', 'like', "%{$search}%")
                    ->orWhere('platform', 'like', "%{$search}%")
                    ->orWhere('device', 'like', "%{$search}%");
            });
        }

        /*
    |--------------------------------------------------------------------------
    | Status Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {

            if ($request->status == 'online') {

                $query->whereNull('logout_at');
            }

            if ($request->status == 'logout') {

                $query->whereNotNull('logout_at');
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Browser Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('browser')) {

            $query->where('browser', $request->browser);
        }

        /*
    |--------------------------------------------------------------------------
    | Platform Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('platform')) {

            $query->where('platform', $request->platform);
        }

        /*
    |--------------------------------------------------------------------------
    | Device Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('device')) {

            $query->where('device', $request->device);
        }

        /*
    |--------------------------------------------------------------------------
    | User Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('user')) {

            $query->where('user_id', $request->user);
        }

        /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('from')) {

            $query->whereDate('login_at', '>=', $request->from);
        }

        if ($request->filled('to')) {

            $query->whereDate('login_at', '<=', $request->to);
        }

        /*
    |--------------------------------------------------------------------------
    | Results
    |--------------------------------------------------------------------------
    */

        $histories = $query
            ->latest('login_at')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Dashboard Cards
    |--------------------------------------------------------------------------
    */

        $stats = [

            'todayLogins' => LoginHistory::whereDate('login_at', today())->count(),

            'todayLogouts' => LoginHistory::whereDate('logout_at', today())->count(),

            'onlineUsers' => LoginHistory::whereNull('logout_at')->count(),

            'totalLogins' => LoginHistory::count(),

        ];

        if ($request->ajax()) {

            return response()->json([

                'stats' => $stats,

                'html' => view(
                    'dashboard.users.ajax.loginhistorytable',
                    compact('histories')
                )->render(),

            ]);
        }

        return view('dashboard.users.loginhistory', compact('stats'));
    }
}