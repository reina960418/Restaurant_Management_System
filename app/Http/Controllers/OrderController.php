<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Ingredient;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('items.dish')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dishes = Dish::all();
        return view('orders.create', compact('dishes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'nullable|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.dish_id' => 'required|exists:dishes,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_at_order' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $order = Order::create([
                    'table_number' => $validated['table_number'],
                    'total_amount' => $validated['total_amount'],
                    'status' => 'completed', // Assuming order is completed upon creation
                ]);

                foreach ($validated['items'] as $item) {
                    $dish = Dish::with('ingredients')->find($item['dish_id']);

                    // Deduct stock for each ingredient in the dish
                    foreach ($dish->ingredients as $ingredient) {
                        $requiredQuantity = $ingredient->pivot->quantity * $item['quantity'];
                        
                        if ($ingredient->stock < $requiredQuantity) {
                            throw new Exception("庫存不足: " . $ingredient->name);
                        }
                        
                        $ingredient->decrement('stock', $requiredQuantity);
                    }

                    // Create the order item
                    $order->items()->create([
                        'dish_id' => $item['dish_id'],
                        'quantity' => $item['quantity'],
                        'price_at_order' => $item['price_at_order'],
                    ]);
                }
            });
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => '訂單已成功建立！'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('items.dish');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                // Restore stock for each ingredient in the order
                foreach ($order->items as $orderItem) {
                    $dish = Dish::with('ingredients')->find($orderItem->dish_id);
                    foreach ($dish->ingredients as $ingredient) {
                        $restoredQuantity = $ingredient->pivot->quantity * $orderItem->quantity;
                        $ingredient->increment('stock', $restoredQuantity);
                    }
                }
                $order->delete();
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', '刪除訂單失敗：' . $e->getMessage());
        }

        return redirect()->route('orders.index')->with('success', '訂單已成功刪除！');
    }
}
