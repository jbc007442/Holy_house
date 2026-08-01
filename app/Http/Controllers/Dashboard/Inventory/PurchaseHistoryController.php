<?php

namespace App\Http\Controllers\Dashboard\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\PurchaseHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseHistoryController extends Controller
{

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
    /**
     * Store Purchase
     */
    public function store(Request $request, Item $item)
    {
        $validated = $request->validate([
            'purchase_date' => 'required|date',
            'quantity'      => 'required|numeric|min:0.01',
            'total_amount'  => 'required|numeric|min:0.01',
            'remarks'       => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($validated, $item) {

            PurchaseHistory::create([
                'item_id'       => $item->id,
                'quantity'      => $validated['quantity'],
                'total_amount'  => $validated['total_amount'],
                'purchase_date' => $validated['purchase_date'],
                'remarks'       => $validated['remarks'] ?? null,
            ]);

            $item->update([
                'opening_stock' => $item->opening_stock + $validated['quantity'],
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Purchase added successfully.',
        ]);
    }
}