<?php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoodsReceiptController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('suppliers', SupplierController::class);
Route::resource('ingredients', IngredientController::class);
Route::resource('purchase_orders', PurchaseOrderController::class);
Route::resource('goods_receipts', GoodsReceiptController::class);

Route::get('statements', [StatementController::class, 'query'])->name('statements.query');
Route::post('statements', [StatementController::class, 'generate'])->name('statements.generate');
