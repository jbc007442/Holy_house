<?php

namespace App\Http\Controllers\Dashboard\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Building;
use App\Models\StockMovement;
use App\Models\Room;
use App\Models\PurchaseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    /**
     * Display stock movements.
     */
    public function index(Request $request)
    {
        $query = StockMovement::with('item');

        if ($request->filled('search')) {
            $query->whereHas('item', function ($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('item')) {
            $query->where('item_id', $request->item);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        $movements = $query
            ->latest()
            ->paginate(10);

        if ($request->ajax()) {

            $statsQuery = clone $query;

            return response()->json([
                'stats' => [
                    'total' => (clone $statsQuery)->count(),
                    'stockOut' => (clone $statsQuery)
                        ->where('type', 'out')
                        ->count(),
                    'adjustment' => (clone $statsQuery)
                        ->where('type', 'adjustment')
                        ->count(),
                ],

                'data' => $movements->items(),

                'pagination' => [
                    'current_page' => $movements->currentPage(),
                    'last_page' => $movements->lastPage(),
                    'per_page' => $movements->perPage(),
                    'total' => $movements->total(),
                    'from' => $movements->firstItem(),
                    'to' => $movements->lastItem(),
                ]
            ]);
        }

        $items = Item::where('status', true)
            ->orderBy('item_name')
            ->get();

        return view('dashboard.inventory.stock-movement.index', compact('items'));
    }

    /**
     * Show create form.
     */
    public function create()
    {
        $items = Item::where('status', true)
            ->orderBy('item_name')
            ->get();

        $buildings = Building::with('floors')
            ->orderBy('name')
            ->get();

        return view(
            'dashboard.inventory.stock-movement.create',
            compact(
                'items',
                'buildings'
            )
        );
    }


    /**
     * Store movement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'           => ['required', 'exists:items,id'],
            'building_id'       => ['nullable', 'exists:buildings,id'],
            'building_floor_id' => ['nullable', 'exists:building_floors,id'],
            'room_id'           => ['nullable', 'exists:rooms,id'],
            'kitchen'           => ['nullable', 'boolean'],
            'other_property'    => ['nullable', 'string', 'max:255'],
            'type'              => ['required', 'in:out,adjustment'],
            'quantity'          => ['required', 'integer', 'min:1'],
            'remarks'           => ['nullable', 'string'],
        ]);

        $validated['kitchen'] = $request->boolean('kitchen');

        $item = Item::findOrFail($validated['item_id']);

        if (! $item->hasStock($validated['quantity'])) {

            return back()
                ->withInput()
                ->withErrors([
                    'quantity' => "Only {$item->current_stock} item(s) available."
                ]);
        }

        DB::transaction(function () use ($validated, $item) {

            // Deduct Stock
            $item->decreaseStock($validated['quantity']);

            // Update Item Audit
            $item->update([
                'updated_by' => auth()->id(),
            ]);

            // Create Stock Movement
            StockMovement::create([
                'item_id'            => $validated['item_id'],
                'building_id'        => $validated['building_id'] ?? null,
                'building_floor_id'  => $validated['building_floor_id'] ?? null,
                'room_id'            => $validated['room_id'] ?? null,
                'kitchen'            => $validated['kitchen'],
                'other_property'     => $validated['other_property'] ?? null,
                'type'               => $validated['type'],
                'quantity'           => $validated['quantity'],
                'remarks'            => $validated['remarks'] ?? null,
                'created_by'         => auth()->id(),
                'updated_by'         => auth()->id(),
            ]);
        });

        return redirect()
            ->route('dashboard.inventory.stock-movement')
            ->with('success', 'Stock movement created successfully.');
    }

    /**
     * Show movement.
     */
    public function show(StockMovement $stockMovement)
    {
        $stockMovement->load([
            'item',
            'building',
            'buildingFloor',
            'room',
            'creator',
        ]);

        return view(
            'dashboard.inventory.stock-movement.show',
            compact('stockMovement')
        );
    }

    /**
     * Show edit form.
     */
    public function edit(StockMovement $stockMovement)
    {
        $stockMovement->load([
            'item',
            'building.floors',
            'room',
        ]);

        $items = Item::where('status', true)
            ->orderBy('item_name')
            ->get();

        $buildings = Building::with('floors')
            ->orderBy('name')
            ->get();

        return view(
            'dashboard.inventory.stock-movement.edit',
            compact(
                'stockMovement',
                'items',
                'buildings'
            )
        );
    }

    /**
     * Update movement.
     */
    public function update(Request $request, StockMovement $stockMovement)
    {
        $validated = $request->validate([
            'item_id'           => ['required', 'exists:items,id'],
            'building_id'       => ['nullable', 'exists:buildings,id'],
            'building_floor_id' => ['nullable', 'exists:building_floors,id'],
            'room_id'           => ['nullable', 'exists:rooms,id'],
            'kitchen'           => ['nullable', 'boolean'],
            'other_property'    => ['nullable', 'string', 'max:255'],
            'type'              => ['required', 'in:out,adjustment'],
            'quantity'          => ['required', 'integer', 'min:1'],
            'remarks'           => ['nullable', 'string'],
        ]);

        $validated['kitchen'] = $request->boolean('kitchen');

        DB::transaction(function () use ($validated, $stockMovement) {

            // Restore previous stock
            $oldItem = $stockMovement->item;

            $oldItem->increaseStock($stockMovement->quantity);

            $oldItem->update([
                'updated_by' => auth()->id(),
            ]);

            // Apply new stock deduction
            $newItem = Item::findOrFail($validated['item_id']);

            if (! $newItem->hasStock($validated['quantity'])) {

                throw \Illuminate\Validation\ValidationException::withMessages([
                    'quantity' => "Only {$newItem->current_stock} item(s) available."
                ]);
            }

            $newItem->decreaseStock($validated['quantity']);

            $newItem->update([
                'updated_by' => auth()->id(),
            ]);

            // Update Stock Movement
            $stockMovement->update([
                'item_id'           => $validated['item_id'],
                'building_id'       => $validated['building_id'] ?? null,
                'building_floor_id' => $validated['building_floor_id'] ?? null,
                'room_id'           => $validated['room_id'] ?? null,
                'kitchen'           => $validated['kitchen'],
                'other_property'    => $validated['other_property'] ?? null,
                'type'              => $validated['type'],
                'quantity'          => $validated['quantity'],
                'remarks'           => $validated['remarks'] ?? null,
                'updated_by'        => auth()->id(),
            ]);
        });

        return redirect()
            ->route('dashboard.inventory.stock-movement')
            ->with('success', 'Stock movement updated successfully.');
    }

    /**
     * stockReport.
     */

    public function stockReport(Request $request)
    {
        $query = Item::query();

        if ($request->filled('search')) {
            $query->where('item_name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {

            switch ($request->status) {

                case 'instock':
                    $query->whereColumn('current_stock', '>', 'minimum_stock');
                    break;

                case 'low':
                    $query->where('current_stock', '>', 0)
                        ->whereColumn('current_stock', '<=', 'minimum_stock');
                    break;

                case 'out':
                    $query->where('current_stock', '<=', 0);
                    break;
            }
        }

        $items = $query
            ->orderBy('item_name')
            ->paginate(20);

        $categories = Item::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        if ($request->ajax()) {

            return response()->json([
                'data' => $items->items(),

                'pagination' => [
                    'current_page' => $items->currentPage(),
                    'last_page'    => $items->lastPage(),
                    'per_page'     => $items->perPage(),
                    'total'        => $items->total(),
                    'from'         => $items->firstItem(),
                    'to'           => $items->lastItem(),
                ],
            ]);
        }

        return view(
            'dashboard.inventory.stock-report',
            compact('categories')
        );
    }

    /**
     * Delete movement.
     */
    public function destroy(StockMovement $stockMovement)
    {
        DB::transaction(function () use ($stockMovement) {

            // Restore deducted stock
            $stockMovement->item->increaseStock($stockMovement->quantity);

            $stockMovement->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Stock movement deleted successfully.',
        ]);
    }

    /**
     * Delete movement.
     */
    public function stockPerItem(Item $item)
    {
        return view(
            'dashboard.inventory.stock-by-item',
            compact('item')
        );
    }

    public function stockPerItemData(Item $item)
    {
        $purchaseHistory = PurchaseHistory::where('item_id', $item->id)
            ->latest('purchase_date')
            ->get();

        $stockMovements = StockMovement::where('item_id', $item->id)
            ->latest()
            ->get();

        return response()->json([

            'item' => $item,

            'summary' => [

                'current_stock' => $item->opening_stock,

                'latest_price' => $item->purchase_price,

                'total_purchase' => $purchaseHistory->sum('quantity'),

                'total_issue' => $stockMovements
                    ->where('type', 'out')
                    ->sum('quantity'),

                'total_adjustment' => $stockMovements
                    ->where('type', 'adjustment')
                    ->sum('quantity'),

            ],

            'purchase_history' => $purchaseHistory,

            'stock_movements' => $stockMovements,

        ]);
    }

    /**
     * Get Rooms for Stock Movement
     */
    public function getStockRooms(Request $request, $buildingId)
    {
        $rooms = Room::where('building_id', $buildingId)
            ->when($request->filled('floor'), function ($query) use ($request) {
                $query->where('floor', trim($request->floor));
            })
            ->select(
                'id',
                'room_number',
                'floor',
                'base_price',
                'status'
            )
            ->orderBy('room_number')
            ->get();

        return response()->json($rooms);
    }
}