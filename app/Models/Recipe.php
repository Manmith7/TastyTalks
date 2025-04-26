<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    //
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'steps',
        'video_url',
        'cuisine_type',
        'difficulty',
        'ingredients',
        'image_url'
    ];

    protected $casts = [
        'ingredients' => 'array'
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function ingredients() { return $this->hasMany(Ingredient::class); }
    public function categories() { return $this->belongsToMany(Category::class); }
    public function comments() { return $this->hasMany(RecipeComment::class); }
    public function likes() { return $this->belongsToMany(User::class, 'recipe_likes', 'recipe_id', 'user_id'); }
    public function saves()
    {
        return $this->belongsToMany(User::class, 'recipe_saves', 'recipe_id', 'user_id');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isSavedBy(User $user)
    {
        return $this->saves()->where('user_id', $user->id)->exists();
    }
}
