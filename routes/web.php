<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\FollowController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [UserController::class, 'show'])->name('register');
Route::post('/register', [UserController::class, 'store']);

Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/login', [UserController::class, 'authenticate']);

// Routes accessible only after authentication
Route::middleware(['auth'])->group(function () {

    Route::get('/home', [RecipeController::class, 'index'])->name('home');

    Route::get('/search', function () {
        return view('dashboard.search');
    })->name('search');

    Route::get('/explore', function () {
        return view('dashboard.explore');
    })->name('explore');

    Route::get('/notifications', function () {
        return view('dashboard.notifications');
    })->name('notifications');

    Route::get('/create-post', function () {
        return view('dashboard.create_post');
    })->name('create.post');

    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

    // Recipe routes
    Route::get('/recipes', [RecipeController::class, 'index'])->name('recipes.index');
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
    Route::get('/post/{recipe}', [RecipeController::class, 'post'])->name('post.show');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    
    // Recipe saving routes
    Route::post('/recipes/{recipe}/save', [RecipeController::class, 'save'])->name('recipes.save');
    Route::delete('/recipes/{recipe}/unsave', [RecipeController::class, 'unsave'])->name('recipes.unsave');
    
    // Recipe liking routes
    Route::post('/recipes/{recipe}/like', [RecipeController::class, 'like'])->name('recipes.like');
    Route::post('/recipes/{recipe}/comment', [RecipeController::class, 'comment'])->name('recipes.comment');
    
    // Follow routes
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');

    Route::get('/user/{username}', [UserController::class, 'showUserProfile'])->name('user.profile');
});
