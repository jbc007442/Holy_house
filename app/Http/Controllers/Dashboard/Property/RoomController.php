<?php
namespace App\Http\Controllers\Dashboard\Property;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingFloor;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomController extends Controller
{
    /**
     * Display a listing of rooms.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Room::with('building');

        /*
        |--------------------------------------------------------------------------
        | Building Access
        |--------------------------------------------------------------------------
        */

        $this->applyBuildingAccess($query);

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('room_number', 'like', "%{$search}%")
                    ->orWhere('floor', 'like', "%{$search}%")
                    ->orWhereHas('building', function ($b) use ($search) {
                        $b->where('name', 'like', "%{$search}%");
                    });
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Building Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('building')) {

            $query->where('building_id', $request->building);
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {

            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Ajax
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            $rooms = $query
                ->latest()
                ->paginate(10);

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            $statsQuery = Room::query();

            $this->applyBuildingAccess($statsQuery);

            return response()->json([

                'stats' => [

                    'totalRooms' => (clone $statsQuery)->count(),

                    'availableRooms' => (clone $statsQuery)
                        ->where('status', 'available')
                        ->count(),

                    'runningRooms' => (clone $statsQuery)
                        ->where('status', 'running')
                        ->count(),

                    'blockedRooms' => (clone $statsQuery)
                        ->where('status', 'blocked')
                        ->count(),

                    'maintenanceRooms' => (clone $statsQuery)
                        ->where('status', 'maintenance')
                        ->count(),

                ],

                'data' => $rooms->items(),

                'pagination' => [

                    'current_page' => $rooms->currentPage(),

                    'last_page' => $rooms->lastPage(),

                    'per_page' => $rooms->perPage(),

                    'total' => $rooms->total(),

                    'from' => $rooms->firstItem(),

                    'to' => $rooms->lastItem(),

                ],

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Buildings Dropdown
        |--------------------------------------------------------------------------
        */

        $buildingsQuery = Building::where('status', 'active');

        $this->applyBuildingAccess($buildingsQuery);

        $buildings = $buildingsQuery
            ->orderBy('name')
            ->get();

        return view(
            'dashboard.property.rooms.index',
            compact('buildings')
        );
    }

    /**
     * Show the form for creating a new room.
     */
    public function create()
    {
        $buildingsQuery = Building::where('status', 'active');

        $this->applyBuildingAccess($buildingsQuery);

        $buildings = $buildingsQuery
            ->orderBy('name')
            ->get();

        $buildingIds = $buildings->pluck('id');

        $floors = BuildingFloor::whereIn('building_id', $buildingIds)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view(
            'dashboard.property.rooms.create',
            compact('buildings', 'floors')
        );
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'building_id' => [
                'required',
                'exists:buildings,id',
            ],

            'room_number' => [
                'required',
                'string',
                'max:50',

                Rule::unique('rooms')
                    ->where(
                        fn ($query) =>
                        $query->where(
                            'building_id',
                            $request->building_id
                        )
                    ),
            ],

            'floor' => [
                'required',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'base_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:available,running,blocked,maintenance',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Building Access
        |--------------------------------------------------------------------------
        */

        $building = Building::findOrFail(
            $validated['building_id']
        );

        $this->checkBuildingAccess($building);

        /*
        |--------------------------------------------------------------------------
        | Floor Access
        |--------------------------------------------------------------------------
        */

        $floorExists = BuildingFloor::where(
            'building_id',
            $building->id
        )
            ->where('name', $validated['floor'])
            ->where('status', 'active')
            ->exists();

        if (!$floorExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'floor' => 'Selected floor does not belong to this building.',
                ]);
        }

        Room::create([

            ...$validated,

            'created_by' => auth()->id(),

            'updated_by' => auth()->id(),

        ]);

        return redirect()
            ->route('dashboard.property.rooms')
            ->with('success', 'Room created successfully.');
    }

    /**
     * Display the specified room.
     */
    public function show(string $id)
    {
        $room = Room::with('building')
            ->findOrFail($id);

        $this->checkBuildingAccess(
            $room->building
        );

        return view(
            'dashboard.property.rooms.show',
            compact('room')
        );
    }

    /**
     * Show the form for editing the room.
     */
    public function edit(string $id)
    {
        $room = Room::with('building')
            ->findOrFail($id);

        $this->checkBuildingAccess(
            $room->building
        );

        $buildingsQuery = Building::where('status', 'active');

        $this->applyBuildingAccess($buildingsQuery);

        $buildings = $buildingsQuery
            ->orderBy('name')
            ->get();

        $buildingIds = $buildings->pluck('id');

        $floors = BuildingFloor::whereIn('building_id', $buildingIds)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return view(
            'dashboard.property.rooms.edit',
            compact(
                'room',
                'buildings',
                'floors'
            )
        );
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::with('building')
            ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Current Building Access
        |--------------------------------------------------------------------------
        */

        $this->checkBuildingAccess(
            $room->building
        );

        $validated = $request->validate([

            'building_id' => [
                'required',
                'exists:buildings,id',
            ],

            'room_number' => [
                'required',
                'string',
                'max:50',

                Rule::unique('rooms')
                    ->where(
                        fn ($query) =>
                        $query->where(
                            'building_id',
                            $request->building_id
                        )
                    )
                    ->ignore($room->id),
            ],

            'floor' => [
                'required',
                'string',
                'max:100',
            ],

            'capacity' => [
                'required',
                'integer',
                'min:1',
            ],

            'base_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'status' => [
                'required',
                'in:available,running,blocked,maintenance',
            ],

            'description' => [
                'nullable',
                'string',
            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | New Building Access
        |--------------------------------------------------------------------------
        */

        $building = Building::findOrFail(
            $validated['building_id']
        );

        $this->checkBuildingAccess($building);

        /*
        |--------------------------------------------------------------------------
        | Floor Access
        |--------------------------------------------------------------------------
        */

        $floorExists = BuildingFloor::where(
            'building_id',
            $building->id
        )
            ->where('name', $validated['floor'])
            ->where('status', 'active')
            ->exists();

        if (!$floorExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'floor' => 'Selected floor does not belong to this building.',
                ]);
        }

        $room->update([

            ...$validated,

            'updated_by' => auth()->id(),

        ]);

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

        /*
        |--------------------------------------------------------------------------
        | Building Access
        |--------------------------------------------------------------------------
        */

        $this->applyBuildingAccess($query);

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('building')) {

            $query->where(
                'building_id',
                $request->building
            );
        }

        if ($request->filled('floor')) {

            $query->where(
                'floor',
                $request->floor
            );
        }

        if ($request->filled('search')) {

            $query->where(
                'room_number',
                'like',
                '%' . $request->search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Stats
        |--------------------------------------------------------------------------
        */

        $stats = [

            'available' => (clone $query)
                ->where('status', 'available')
                ->count(),

            'running' => (clone $query)
                ->where('status', 'running')
                ->count(),

            'blocked' => (clone $query)
                ->where('status', 'blocked')
                ->count(),

            'maintenance' => (clone $query)
                ->where('status', 'maintenance')
                ->count(),

        ];

        $rooms = $query
            ->orderBy('building_id')
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Ajax
        |--------------------------------------------------------------------------
        */

        if ($request->ajax()) {

            return response()->json([

                'stats' => $stats,

                'rooms' => $rooms,

            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Buildings
        |--------------------------------------------------------------------------
        */

        $buildingsQuery = Building::where('status', 'active');

        $this->applyBuildingAccess($buildingsQuery);

        $buildings = $buildingsQuery
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Floors
        |--------------------------------------------------------------------------
        */

        $buildingIds = $buildings->pluck('id');

        $floors = BuildingFloor::whereIn(
            'building_id',
            $buildingIds
        )
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->pluck('name')
            ->unique()
            ->values();

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
     * Remove the specified room.
     */
    public function destroy(string $id)
    {
        $room = Room::with('building')
            ->findOrFail($id);

        $this->checkBuildingAccess(
            $room->building
        );

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
     * Update Room Status.
     */
    public function changeStatus(
        Request $request,
        Room $room
    ) {
        $this->checkBuildingAccess(
            $room->building
        );

        $validated = $request->validate([

            'status' => [
                'required',
                'in:available,blocked,maintenance',
            ],

        ]);

        $room->update([

            'status' => $validated['status'],

            'updated_by' => auth()->id(),

        ]);

        return redirect()
            ->route(
                'dashboard.property.rooms.show',
                $room->id
            )
            ->with(
                'success',
                'Room status updated successfully.'
            );
    }

    /**
     * Get all floors for a specific building.
     */
    public function getFloors($buildingId)
    {
        $building = Building::findOrFail(
            $buildingId
        );

        $this->checkBuildingAccess(
            $building
        );

        return response()->json(

            BuildingFloor::where(
                'building_id',
                $building->id
            )
                ->where('status', 'active')
                ->orderBy('sort_order')
                ->get([
                    'id',
                    'name',
                ])
        );
    }

    /**
     * Apply building access restriction to a query.
     */
    private function applyBuildingAccess($query): void
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Super Admin and Admin
        |--------------------------------------------------------------------------
        */

        if ($user->isSuperadmin() || $user->isAdmin()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Assigned Buildings
        |--------------------------------------------------------------------------
        */

        $buildingIds = $user->buildings()
            ->pluck('buildings.id');

        $query->whereIn(
            'building_id',
            $buildingIds
        );
    }

    /**
     * Check access to a specific building.
     */
    private function checkBuildingAccess(
        Building $building
    ): void {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Super Admin and Admin
        |--------------------------------------------------------------------------
        */

        if ($user->isSuperadmin() || $user->isAdmin()) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Assigned Building
        |--------------------------------------------------------------------------
        */

        $hasAccess = $user->buildings()
            ->where(
                'buildings.id',
                $building->id
            )
            ->exists();

        if (!$hasAccess) {

            abort(
                403,
                'You do not have access to this building.'
            );
        }
    }
}