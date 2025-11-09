<?php

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/purchase-orders/{purchaseOrder}/items', function (PurchaseOrder $purchaseOrder) {
    return $purchaseOrder->items()->with('ingredient')->get() ?? [];
});
