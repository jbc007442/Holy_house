<?php

namespace App\Http\Controllers;

use App\Models\HousekeepingMessage;
use App\Models\Room;
use Illuminate\Http\Request;

class HousekeepingController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Housekeeping Messages
    |--------------------------------------------------------------------------
    */

    public function alert()
    {
        $user = auth()->user();

        $messages = HousekeepingMessage::with([
            'room',
            'booking',
            'creator',
        ])
            ->whereHas('room', function ($query) use ($user) {

                $query->where('status', 'blocked');

                // Super Admin can access all buildings
                if (!$user->isSuperadmin()) {
                    $query->whereIn(
                        'building_id',
                        $user->buildings()->pluck('buildings.id')
                    );
                }
            })
            ->latest()
            ->get();

        return view(
            'dashboard.property.housekeeping.alert',
            compact('messages')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Room Status
    |--------------------------------------------------------------------------
    */

    public function updateRoomStatus(Request $request, Room $room)
    {
        $request->validate([
            'status' => [
                'required',
                'in:available,blocked',
            ],
        ]);

        $user = auth()->user();

        /*
         * Super Admin can update rooms in any building.
         *
         * Other users can only update rooms belonging
         * to their assigned buildings.
         */
        if (!$user->isSuperadmin()) {

            $hasAccess = $user->buildings()
                ->where('buildings.id', $room->building_id)
                ->exists();

            if (!$hasAccess) {
                abort(403, 'You do not have access to this building.');
            }
        }

        $room->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('dashboard.property.housekeeping.alert')
            ->with(
                'success',
                'Room ' . $room->room_number . ' status updated successfully.'
            );
    }
}