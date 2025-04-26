<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use App\Models\Recipe;

class UserController extends Controller
{
    //
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // validation + user creation logic
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed'
        ]);
    
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        auth()->login($user); // Optional: login after registration
    
        return redirect()->route('login'); // step 2
    }
    public function showLogin()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/home'); // change as needed
        }

        return back()->with('error', 'Invalid credentials');
    }

    public function profile()
    {
        $user = auth()->user();
        $userPosts = Recipe::where('user_id', $user->id)->latest()->get();
        $savedPosts = $user->savedRecipes()->latest()->get();
        
        return view('dashboard.profile', compact('user', 'userPosts', 'savedPosts'));
    }
    
    public function editProfile()
    {
        $user = auth()->user();
        return view('dashboard.edit_profile', compact('user'));
    }
    
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:1000',
            'profile_photo_url' => 'nullable|url',
            'mobile_number' => 'nullable|string|max:20',
        ]);
        
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'bio' => $request->bio,
            'profile_photo_url' => $request->profile_photo_url,
            'mobile_number' => $request->mobile_number,
        ]);
        
        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }

    public function showUserProfile($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $recipes = Recipe::where('user_id', $user->id)->latest()->get();
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $isFollowing = auth()->check() ? auth()->user()->isFollowing($user) : false;
        
        return view('dashboard.user_profile', compact('user', 'recipes', 'followersCount', 'followingCount', 'isFollowing'));
    }
}
