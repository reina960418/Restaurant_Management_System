<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'unit',
        'stock',
        'price',
    ];

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'dish_ingredient')
                    ->withPivot('quantity');
    }
}
