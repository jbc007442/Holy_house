<?php

namespace App\Http\Controllers\Dashboard\Property;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Room;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $buildings = Building::withCount('rooms')
                ->when($request->search, function ($query) use ($request) {

                    $query->where(function ($q) use ($request) {

                        $q->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('code', 'like', '%' . $request->search . '%');
                    });
                })
                ->latest()
                ->paginate(1);

            return response()->json([

                'stats' => [

                    'totalBuildings' => Building::count(),

                    'activeBuildings' => Building::where('status', 'active')->count(),

                    'totalRooms' => Room::count(),

                ],

                'data' => $buildings->items(),

                'pagination' => [

                    'current_page' => $buildings->currentPage(),

                    'last_page'    => $buildings->lastPage(),

                    'per_page'     => $buildings->perPage(),

                    'total'        => $buildings->total(),

                    'from'         => $buildings->firstItem(),

                    'to'           => $buildings->lastItem(),

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
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:buildings,code',
            'floors'      => 'nullable|integer|min:1',
            'status'      => 'required|in:active,inactive',
            'address'     => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Building::create([
            ...$validated,
            'created_by' => auth()->id(),
            'updated_by' => auth()->id(),
        ]);

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

        return view('dashboard.property.buildings.show', compact('building'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $building = Building::findOrFail($id);

        return view('dashboard.property.buildings.edit', compact('building'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $building = Building::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:buildings,code,' . $building->id,
            'floors'      => 'nullable|integer|min:1',
            'status'      => 'required|in:active,inactive',
            'address'     => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        $building->update([
            ...$validated,
            'updated_by' => auth()->id(),
        ]);

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

        $building->delete();

        return response()->json([
            'success' => true,
            'message' => 'Building deleted successfully.'
        ]);
    }
}