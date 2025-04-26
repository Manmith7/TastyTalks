<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $currentUser = auth()->user();
        
        if ($currentUser->id === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }
        
        if (!$currentUser->isFollowing($user)) {
            $currentUser->following()->attach($user->id);
            
            // Update followers and following counts
            $currentUser->increment('following_count');
            $user->increment('followers_count');
        }
        
        return back()->with('success', 'Successfully followed ' . $user->name);
    }
    
    public function unfollow(User $user)
    {
        $currentUser = auth()->user();
        
        if ($currentUser->isFollowing($user)) {
            $currentUser->following()->detach($user->id);
            
            // Update followers and following counts
            $currentUser->decrement('following_count');
            $user->decrement('followers_count');
        }
        
        return back()->with('success', 'Successfully unfollowed ' . $user->name);
    }
}
