<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Supplier;
use Illuminate\Http\Request;

class StatementController extends Controller
{
    public function query()
    {
        $suppliers = Supplier::all();
        return view('statements.query', compact('suppliers'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $supplier = Supplier::find($validated['supplier_id']);
        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        $goodsReceipts = GoodsReceipt::with('items.ingredient')
            ->where('supplier_id', $supplier->id)
            ->whereBetween('receipt_date', [$startDate, $endDate])
            ->orderBy('receipt_date')
            ->get();

        return view('statements.show', compact('supplier', 'startDate', 'endDate', 'goodsReceipts'));
    }
}
