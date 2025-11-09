<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Ingredient;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $goodsReceipts = GoodsReceipt::with('supplier', 'purchaseOrder')->latest()->get();
        return view('goods_receipts.index', compact('goodsReceipts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $purchaseOrders = PurchaseOrder::with('supplier')
                                    ->whereIn('status', ['pending', 'partially_received'])
                                    ->get();
        return view('goods_receipts.create', compact('purchaseOrders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'receipt_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity_received' => 'required|numeric|min:0',
            'items.*.quantity_returned' => 'nullable|numeric|min:0',
            'items.*.return_reason' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0', // Price from PO item
        ]);

        DB::transaction(function () use ($validated) {
            $purchaseOrder = PurchaseOrder::find($validated['purchase_order_id']);

            $goodsReceipt = GoodsReceipt::create([
                'purchase_order_id' => $validated['purchase_order_id'],
                'supplier_id' => $purchaseOrder->supplier_id,
                'receipt_date' => $validated['receipt_date'],
                'notes' => $validated['notes'],
            ]);

            $allItemsReceived = true;
            foreach ($validated['items'] as $itemData) {
                $goodsReceipt->items()->create([
                    'ingredient_id' => $itemData['ingredient_id'],
                    'quantity_received' => $itemData['quantity_received'],
                    'quantity_returned' => $itemData['quantity_returned'] ?? 0,
                    'return_reason' => $itemData['return_reason'],
                    'price' => $itemData['price'],
                ]);

                // Update ingredient stock
                $ingredient = Ingredient::find($itemData['ingredient_id']);
                $ingredient->stock += ($itemData['quantity_received'] - ($itemData['quantity_returned'] ?? 0));
                $ingredient->save();

                // Check if all items for the PO are fully received
                $poItem = PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
                                            ->where('ingredient_id', $itemData['ingredient_id'])
                                            ->first();
                if ($poItem && ($itemData['quantity_received'] < $poItem->quantity)) {
                    $allItemsReceived = false;
                }
            }

            // Update Purchase Order status
            if ($allItemsReceived) {
                $purchaseOrder->status = 'completed';
            } else {
                $purchaseOrder->status = 'partially_received';
            }
            $purchaseOrder->save();
        });

        return response()->json(['message' => '進料單已成功建立！'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load('supplier', 'purchaseOrder', 'items.ingredient', 'items.purchaseOrderItem');
        return view('goods_receipts.show', compact('goodsReceipt'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load('items');
        $purchaseOrders = PurchaseOrder::with('supplier')
                                    ->whereIn('status', ['pending', 'partially_received', 'completed']) // Also include completed in case we need to edit
                                    ->get();
        return view('goods_receipts.edit', compact('goodsReceipt', 'purchaseOrders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GoodsReceipt $goodsReceipt)
    {
        $validated = $request->validate([
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'receipt_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.ingredient_id' => 'required|exists:ingredients,id',
            'items.*.quantity_received' => 'required|numeric|min:0',
            'items.*.quantity_returned' => 'nullable|numeric|min:0',
            'items.*.return_reason' => 'nullable|string',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated, $goodsReceipt) {
            // 1. Revert stock changes from the original receipt
            foreach ($goodsReceipt->items as $oldItem) {
                $ingredient = Ingredient::find($oldItem->ingredient_id);
                $ingredient->stock -= ($oldItem->quantity_received - $oldItem->quantity_returned);
                $ingredient->save();
            }

            // 2. Update the goods receipt itself
            $goodsReceipt->update([
                'receipt_date' => $validated['receipt_date'],
                'notes' => $validated['notes'],
            ]);

            // 3. Delete old items
            $goodsReceipt->items()->delete();

            // 4. Create new items and update stock
            $purchaseOrder = PurchaseOrder::find($validated['purchase_order_id']);
            $allItemsReceived = true;

            foreach ($validated['items'] as $itemData) {
                $goodsReceipt->items()->create([
                    'ingredient_id' => $itemData['ingredient_id'],
                    'quantity_received' => $itemData['quantity_received'],
                    'quantity_returned' => $itemData['quantity_returned'] ?? 0,
                    'return_reason' => $itemData['return_reason'],
                    'price' => $itemData['price'],
                ]);

                // Update ingredient stock with new values
                $ingredient = Ingredient::find($itemData['ingredient_id']);
                $ingredient->stock += ($itemData['quantity_received'] - ($itemData['quantity_returned'] ?? 0));
                $ingredient->save();

                // Check if all items for the PO are fully received
                $poItem = PurchaseOrderItem::where('purchase_order_id', $purchaseOrder->id)
                                            ->where('ingredient_id', $itemData['ingredient_id'])
                                            ->first();
                if ($poItem && ($itemData['quantity_received'] < $poItem->quantity)) {
                    $allItemsReceived = false;
                }
            }

            // 5. Update Purchase Order status
            if ($allItemsReceived) {
                $purchaseOrder->status = 'completed';
            } else {
                $purchaseOrder->status = 'partially_received';
            }
            $purchaseOrder->save();
        });

        return response()->json(['message' => '進料單已成功更新！']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GoodsReceipt $goodsReceipt)
    {
        DB::transaction(function () use ($goodsReceipt) {
            // Revert stock changes
            foreach ($goodsReceipt->items as $item) {
                $ingredient = Ingredient::find($item->ingredient_id);
                $ingredient->stock -= ($item->quantity_received - $item->quantity_returned);
                $ingredient->save();
            }

            // Revert purchase order status if it was completed by this receipt
            // This logic can be complex. For simplicity, we'll just revert to 'partially_received'
            // if the PO is 'completed'. A more robust solution would check all other receipts for this PO.
            $purchaseOrder = $goodsReceipt->purchaseOrder;
            if ($purchaseOrder->status === 'completed') {
                $purchaseOrder->status = 'partially_received';
                $purchaseOrder->save();
            }

            $goodsReceipt->delete();
        });

        return redirect()->route('goods_receipts.index')->with('success', '進料單已成功刪除！');
    }
}
