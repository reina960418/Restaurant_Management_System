<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ingredients = Ingredient::all();
        return view('ingredients.index', compact('ingredients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('ingredients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ingredients|max:255',
            'unit' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        Ingredient::create($validated);

        return redirect()->route('ingredients.index')->with('success', '食材已成功建立！');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:ingredients,name,'.$ingredient->id.'|max:255',
            'unit' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'required|numeric|min:0',
        ]);

        $ingredient->update($validated);

        return redirect()->route('ingredients.index')->with('success', '食材資料已成功更新！');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return redirect()->route('ingredients.index')->with('success', '食材已成功刪除！');
    }
}
