<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecipeCategory extends Model
{
    //
    protected $fillable = [
        'recipe_id',
        'category_id',
    ];
    
    
}
