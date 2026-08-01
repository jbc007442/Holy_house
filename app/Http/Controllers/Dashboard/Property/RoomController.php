<?php

namespace App\Http\Controllers\Dashboard\Property;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Room::with('building');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('room_number', 'like', "%{$search}%")
                    ->orWhere('floor', 'like', "%{$search}%")
                    ->orWhereHas('building', function ($b) use ($search) {
                        $b->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('building')) {
            $query->where('building_id', $request->building);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->latest()->get();

        if ($request->ajax()) {

            return response()->json([

                'stats' => [
                    'totalRooms' => Room::count(),
                    'availableRooms' => Room::where('status', 'available')->count(),
                    'runningRooms' => Room::where('status', 'running')->count(),
                    'blockedRooms' => Room::where('status', 'blocked')->count(),
                    'maintenanceRooms' => Room::where('status', 'maintenance')->count(),
                ],

                'data' => $rooms

            ]);
        }

        $buildings = Building::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view(
            'dashboard.property.rooms.index',
            compact('buildings')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $buildings = Building::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('dashboard.property.rooms.create', compact('buildings'));
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'building_id' => ['required', 'exists:buildings,id'],

            'room_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms')
                    ->where(fn($query) => $query->where('building_id', $request->building_id)),
            ],

            'floor' => ['required', 'string', 'max:100'],

            'capacity' => ['required', 'integer', 'min:1'],

            'base_price' => ['required', 'numeric', 'min:0'],

            'status' => ['required', 'in:available,running,blocked,maintenance'],

            'description' => ['nullable', 'string'],
        ]);

        Room::create($validated);

        return redirect()
            ->route('dashboard.property.rooms')
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::with('building')->findOrFail($id);

        return view('dashboard.property.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::findOrFail($id);

        $buildings = Building::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('dashboard.property.rooms.edit', compact('room', 'buildings'));
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'building_id' => ['required', 'exists:buildings,id'],

            'room_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('rooms')
                    ->where(fn($query) => $query->where('building_id', $request->building_id))
                    ->ignore($room->id),
            ],

            'floor' => ['required', 'string', 'max:100'],

            'capacity' => ['required', 'integer', 'min:1'],

            'base_price' => ['required', 'numeric', 'min:0'],

            'status' => ['required', 'in:available,running,blocked,maintenance'],

            'description' => ['nullable', 'string'],
        ]);

        $room->update($validated);

        return redirect()
            ->route('dashboard.property.rooms')
            ->with('success', 'Room updated successfully.');
    }

    /**
     * Room Status.
     */
    public function roomStatus(Request $request)
    {
        $query = Room::with('building');

        if ($request->filled('building')) {
            $query->where('building_id', $request->building);
        }

        if ($request->filled('floor')) {
            $query->where('floor', $request->floor);
        }

        if ($request->filled('search')) {
            $query->where('room_number', 'like', '%' . $request->search . '%');
        }

        $rooms = $query
            ->orderBy('building_id')
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get();

        $stats = [
            'available'   => (clone $query)->where('status', 'available')->count(),
            'running'     => (clone $query)->where('status', 'running')->count(),
            'blocked'     => (clone $query)->where('status', 'blocked')->count(),
            'maintenance' => (clone $query)->where('status', 'maintenance')->count(),
        ];

        if ($request->ajax()) {

            return response()->json([
                'stats' => $stats,
                'rooms' => $rooms,
            ]);
        }

        $buildings = Building::where('status', 'active')
            ->orderBy('name')
            ->get();

        $floors = Room::select('floor')
            ->distinct()
            ->orderBy('floor')
            ->pluck('floor');

        return view(
            'dashboard.property.room-status',
            compact(
                'rooms',
                'buildings',
                'floors',
                'stats'
            )
        );
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);

        $room->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Room deleted successfully.',
            ]);
        }

        return redirect()
            ->route('dashboard.property.rooms')
            ->with('success', 'Room deleted successfully.');
    }

    /**
     * Update Room Status
     */
    public function changeStatus(Request $request, Room $room)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:available,blocked,maintenance',
            ],
        ]);

        $room->update([
            'status' => $validated['status'],
        ]);

        return redirect()
            ->route('dashboard.property.rooms.show', $room->id)
            ->with('success', 'Room status updated successfully.');
    }
}