<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Ingredient;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingPurchaseOrders = PurchaseOrder::whereIn('status', ['pending', 'partially_received'])->count();
        $lowStockIngredients = Ingredient::where('stock', '<', 10)->count(); // Assuming 10 is the threshold
        $recentGoodsReceipts = GoodsReceipt::with('supplier')->latest()->take(5)->get();

        return view('dashboard', compact(
            'pendingPurchaseOrders',
            'lowStockIngredients',
            'recentGoodsReceipts'
        ));
    }
}
