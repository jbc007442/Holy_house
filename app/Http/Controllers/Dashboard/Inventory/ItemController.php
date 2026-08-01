<?php

namespace App\Http\Controllers\Dashboard\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\PurchaseHistory;

class ItemController extends Controller
{
    /**
     * Display all items.
     */
    public function index(Request $request)
    {
        $query = Item::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('item_name', 'like', '%' . $request->search . '%')
                    ->orWhere('category', 'like', '%' . $request->search . '%')
                    ->orWhere('unit', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $items = $query
            ->latest()
            ->get();

        $statsQuery = clone $query;

        if ($request->ajax()) {

            return response()->json([
                'stats' => [
                    'totalItems' => (clone $statsQuery)->count(),
                    'activeItems' => (clone $statsQuery)->where('status', true)->count(),
                    'inactiveItems' => (clone $statsQuery)->where('status', false)->count(),
                    'lowStockItems' => (clone $statsQuery)
                        ->whereColumn('opening_stock', '<=', 'minimum_stock')
                        ->count(),
                ],
                'data' => $items,
            ]);
        }

        $categories = Item::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view(
            'dashboard.inventory.items.index',
            compact('categories')
        );
    }

    /**
     * Show the create item form.
     */
    public function create()
    {
        return view('dashboard.inventory.items.create');
    }

    /**
     * Store a new item.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name'      => ['required', 'string', 'max:255'],
            'category'       => ['required', 'string', 'max:255'],
            'unit'           => ['required', 'string', 'max:100'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'opening_stock'  => ['required', 'integer', 'min:0'],
            'minimum_stock'  => ['required', 'integer', 'min:0'],
            'status'         => ['required', 'boolean'],
            'remarks'        => ['nullable', 'string'],
        ]);

        Item::create($validated);

        return redirect()
            ->route('dashboard.inventory.items')
            ->with('success', 'Item created successfully.');
    }

    /**
     * Display a single item.
     */
    public function show(Item $item)
    {
        return view('dashboard.inventory.items.show', compact('item'));
    }

    /**
     * Show the edit item form.
     */
    public function edit(Item $item)
    {
        return view('dashboard.inventory.items.edit', compact('item'));
    }

    /**
     * Update an existing item.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'item_name'      => ['required', 'string', 'max:255'],
            'category'       => ['required', 'string', 'max:255'],
            'unit'           => ['required', 'string', 'max:100'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'opening_stock'  => ['required', 'integer', 'min:0'],
            'minimum_stock'  => ['required', 'integer', 'min:0'],
            'status'         => ['required', 'boolean'],
            'remarks'        => ['nullable', 'string'],
        ]);

        $item->update($validated);

        return redirect()
            ->route('dashboard.inventory.items')
            ->with('success', 'Item updated successfully.');
    }

    /**
     * Delete an item.
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item deleted successfully.',
        ]);
    }

    /**
     * Manage Item
     */
    public function manage(Request $request, Item $item)
    {
        if ($request->ajax()) {

            $purchaseHistories = PurchaseHistory::where('item_id', $item->id)
                ->latest('purchase_date')
                ->get();

            return response()->json([
                'item' => $item,
                'data' => $purchaseHistories,
            ]);
        }

        return view('dashboard.inventory.items.manage', compact('item'));
    }

   
    
}