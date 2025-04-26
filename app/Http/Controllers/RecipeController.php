<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Services\VideoUploadService;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\RecipeCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    protected $videoUploadService;

    public function __construct(VideoUploadService $videoUploadService)
    {
        $this->videoUploadService = $videoUploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recipes = Recipe::with(['user', 'ingredients', 'categories'])
            ->latest()
            ->take(10)
            ->get();
        $categories = Category::all();
        return view('dashboard.home', compact('recipes', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('recipes.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'steps' => 'required|string',
                'video' => 'nullable|file|mimes:mp4,mov,avi|max:102400', // 100MB max
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048', // 2MB max
                'cuisine_type' => 'required|string',
                'difficulty' => 'required|string|in:easy,medium,hard',
                'ingredients' => 'required|array',
                'ingredients.*.name' => 'required|string',
                'ingredients.*.quantity' => 'required|string',
                'categories' => 'array',
                'categories.*' => 'exists:categories,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Upload thumbnail to Cloudinary
            $thumbnailUrl = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailUrl = $this->videoUploadService->uploadImage($request->file('thumbnail'));
            }

            // Upload video to Cloudinary if provided
            $videoUrl = null;
            if ($request->hasFile('video')) {
                $videoUrl = $this->videoUploadService->uploadVideo($request->file('video'));
            }

            // Create the recipe
            $recipe = Recipe::create([
                'user_id' => auth()->id(),
                'title' => $request->title,
                'description' => $request->description,
                'steps' => $request->steps,
                'video_url' => $videoUrl,
                'image_url' => $thumbnailUrl,
                'cuisine_type' => $request->cuisine_type,
                'difficulty' => $request->difficulty,
                'ingredients' => $request->ingredients
            ]);

            // Attach categories if any
            if ($request->has('categories')) {
                $recipe->categories()->attach($request->categories);
            }

            return response()->json([
                'success' => true,
                'message' => 'Recipe created successfully',
                'recipe' => $recipe
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating recipe: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        $recipe->load(['user', 'ingredients', 'categories', 'comments', 'likes']);
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Display a single post in the post view.
     */
    public function post(Recipe $recipe)
    {
        $recipe->load(['user', 'ingredients', 'categories', 'comments', 'likes']);
        return view('dashboard.post', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        $categories = Category::all();
        return view('recipes.edit', compact('recipe', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        try {
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'cuisine_type' => 'required|string|max:255',
                'difficulty' => 'required|string|max:255',
                'steps' => 'required|string',
                'video_url' => 'required|url',
                'image_url' => 'nullable|url',
                'ingredients' => 'required|array',
                'ingredients.*.name' => 'required|string',
                'ingredients.*.quantity' => 'required|string',
                'categories' => 'nullable|array',
                'categories.*' => 'exists:categories,id'
            ]);

            // Update the recipe
            $recipe->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'cuisine_type' => $validated['cuisine_type'],
                'difficulty' => $validated['difficulty'],
                'steps' => $validated['steps'],
                'video_url' => $validated['video_url'],
                'image_url' => $validated['image_url'] ?? null,
            ]);

            // Delete existing ingredients
            $recipe->ingredients()->delete();

            // Create new ingredients
            foreach ($request->ingredients as $ingredient) {
                $recipe->ingredients()->create([
                    'name' => $ingredient['name'],
                    'quantity' => $ingredient['quantity']
                ]);
            }

            // Sync categories
            $recipe->categories()->sync($request->categories ?? []);

            DB::commit();

            return redirect()->route('recipes.show', $recipe)->with('success', 'Recipe updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating recipe:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error updating recipe: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        try {
            $recipe->delete();
            return redirect()->route('recipes.index')->with('success', 'Recipe deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting recipe:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Error deleting recipe: ' . $e->getMessage());
        }
    }

    public function like(Recipe $recipe)
    {
        $user = auth()->user();
        
        if ($recipe->isLikedBy($user)) {
            $recipe->likes()->detach($user->id);
            $message = 'Recipe unliked successfully';
        } else {
            $recipe->likes()->attach($user->id);
            $message = 'Recipe liked successfully';
        }
        
        return back()->with('success', $message);
    }

    public function comment(Request $request, Recipe $recipe)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment = $recipe->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => [
                    'content' => $comment->content,
                    'created_at' => $comment->created_at
                ],
                'user' => [
                    'name' => auth()->user()->name,
                    'profile_photo_url' => auth()->user()->profile_photo_url
                ]
            ]);
        }

        return back()->with('success', 'Comment added successfully');
    }

    public function save(Recipe $recipe)
    {
        $user = auth()->user();
        
        if ($recipe->isSavedBy($user)) {
            $recipe->saves()->detach($user->id);
            $message = 'Recipe unsaved successfully';
        } else {
            $recipe->saves()->attach($user->id);
            $message = 'Recipe saved successfully';
        }
        
        return back()->with('success', $message);
    }
}
