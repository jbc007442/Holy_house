<?php

namespace App\Http\Controllers\Dashboard\Property;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\BuildingFloor;
use App\Models\Room;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax()) {

            $query = Building::withCount('rooms');

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

                $buildingIds = $user->buildings()
                    ->pluck('buildings.id');

                $query->whereIn('id', $buildingIds);
            }

            /*
            |--------------------------------------------------------------------------
            | Search
            |--------------------------------------------------------------------------
            */

            if ($request->filled('search')) {

                $search = trim($request->search);

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', '%' . $search . '%')
                        ->orWhere('code', 'like', '%' . $search . '%');
                });
            }

            $buildings = $query
                ->latest()
                ->paginate(10);

            /*
            |--------------------------------------------------------------------------
            | Statistics
            |--------------------------------------------------------------------------
            */

            $statsQuery = Building::query();

            if (!$user->isSuperadmin() && !$user->isAdmin()) {

                $buildingIds = $user->buildings()
                    ->pluck('buildings.id');

                $statsQuery->whereIn('id', $buildingIds);
            }

            $statsBuildingIds = $statsQuery->pluck('id');

            $totalRooms = Room::whereIn(
                'building_id',
                $statsBuildingIds
            )->count();

            return response()->json([

                'stats' => [

                    'totalBuildings' => $statsQuery->count(),

                    'activeBuildings' => (clone $statsQuery)
                        ->where('status', 'active')
                        ->count(),

                    'totalRooms' => $totalRooms,

                ],

                'data' => $buildings->items(),

                'pagination' => [

                    'current_page' => $buildings->currentPage(),

                    'last_page' => $buildings->lastPage(),

                    'per_page' => $buildings->perPage(),

                    'total' => $buildings->total(),

                    'from' => $buildings->firstItem(),

                    'to' => $buildings->lastItem(),

                ],

            ]);
        }

        return view('dashboard.property.buildings.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.property.buildings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:buildings,code',
            'floors' => 'required|array|min:1',
            'floors.*' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $building = Building::create([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'status' => $validated['status'],
            'address' => $validated['address'] ?? null,
            'description' => $validated['description'] ?? null,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

        foreach ($validated['floors'] as $index => $floor) {

            $building->floors()->create([
                'name' => $floor,
                'sort_order' => $index + 1,
                'status' => 'active',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        }

        return redirect()
            ->route('dashboard.property.buildings')
            ->with('success', 'Building created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $building = Building::findOrFail($id);

        $this->checkBuildingAccess($building);

        return view(
            'dashboard.property.buildings.show',
            compact('building')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $building = Building::with('floors')->findOrFail($id);

        $this->checkBuildingAccess($building);

        return view(
            'dashboard.property.buildings.edit',
            compact('building')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $building = Building::findOrFail($id);

        $this->checkBuildingAccess($building);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:buildings,code,' . $building->id,
            'floors' => 'required|array|min:1',
            'floors.*' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $building->update([
            'name' => $validated['name'],
            'code' => $validated['code'],
            'status' => $validated['status'],
            'address' => $validated['address'] ?? null,
            'description' => $validated['description'] ?? null,
            'updated_by' => auth()->id(),
        ]);

        $building->floors()->delete();

        foreach ($validated['floors'] as $index => $floor) {

            $building->floors()->create([
                'name' => $floor,
                'sort_order' => $index + 1,
                'status' => 'active',
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);
        }

        return redirect()
            ->route('dashboard.property.buildings')
            ->with('success', 'Building updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $building = Building::findOrFail($id);

        $this->checkBuildingAccess($building);

        $building->delete();

        return response()->json([
            'success' => true,
            'message' => 'Building deleted successfully.',
        ]);
    }

    /**
     * Get Building Floors
     */
    public function getFloors(Building $building)
    {
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

    /**
     * Check whether the authenticated user can access the building.
     */
    private function checkBuildingAccess(Building $building): void
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
        | Assigned Building Check
        |--------------------------------------------------------------------------
        */

        $hasAccess = $user->buildings()
            ->where('buildings.id', $building->id)
            ->exists();

        if (!$hasAccess) {
            abort(403, 'You do not have access to this building.');
        }
    }
}