<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dishes = Dish::all();
        return view('dishes.index', compact('dishes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ingredients = Ingredient::all();
        return view('dishes.create', compact('ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated) {
            $dish = Dish::create([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'],
            ]);

            $ingredients = [];
            foreach ($validated['ingredients'] as $ingredient) {
                $ingredients[$ingredient['ingredient_id']] = ['quantity' => $ingredient['quantity']];
            }
            $dish->ingredients()->sync($ingredients);
        });

        return response()->json(['message' => '菜色已成功建立！'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dish $dish)
    {
        $dish->load('ingredients');
        return view('dishes.show', compact('dish'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dish $dish)
    {
        $dish->load('ingredients');
        $ingredients = Ingredient::all();
        return view('dishes.edit', compact('dish', 'ingredients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dish $dish)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredient_id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($validated, $dish) {
            $dish->update([
                'name' => $validated['name'],
                'price' => $validated['price'],
                'description' => $validated['description'],
            ]);

            $ingredients = [];
            foreach ($validated['ingredients'] as $ingredient) {
                $ingredients[$ingredient['ingredient_id']] = ['quantity' => $ingredient['quantity']];
            }
            $dish->ingredients()->sync($ingredients);
        });

        return response()->json(['message' => '菜色已成功更新！']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish)
    {
        $dish->delete();
        return redirect()->route('dishes.index')->with('success', '菜色已成功刪除！');
    }
}
