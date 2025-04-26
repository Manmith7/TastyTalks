<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TastyTalks - Profile</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }
        .logo-font {
            font-family: sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings:
            'FILL' 0,
            'wght' 400,
            'GRAD' 0,
            'opsz' 24;
            font-size: 24px;
            color: black;
        }
        .home-contianer {
            height: 100vh;
            display: flex;
            flex-direction: row;
        }
        .nav-bar {
            width: 250px;
            height: 100vh;
            overflow-y: auto;
            border-right: 1px solid #eee;
        }
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .profile-header {
            padding: 20px;
            background-color: white;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .profile-photo-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 20px;
        }
        .profile-photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .profile-info {
            flex: 1;
        }
        .profile-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .profile-username {
            color: #666;
            font-size: 1.1em;
            margin-bottom: 10px;
        }
        .profile-email {
            color: #666;
            margin-bottom: 10px;
        }
        .profile-mobile {
            color: #666;
            margin-bottom: 10px;
        }
        .profile-bio {
            margin-bottom: 15px;
        }
        .profile-stats {
            margin: 15px 0;
            display: flex;
            gap: 20px;
        }
        .profile-stats span {
            color: #666;
        }
        .edit-profile-btn {
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-profile-btn:hover {
            background-color: #45a049;
        }
        .profile-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            background-color: white;
        }
        .profile-tab {
            padding: 15px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }
        .profile-tab.active {
            border-bottom: 2px solid #4CAF50;
            font-weight: bold;
        }
        .profile-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }
        .recipe-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .recipe-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .recipe-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .recipe-content {
            padding: 15px;
        }
        .recipe-title {
            font-size: 1.2em;
            margin: 0 0 10px 0;
        }
        .recipe-description {
            color: #666;
            margin-bottom: 10px;
        }
        .recipe-meta {
            display: flex;
            justify-content: space-between;
            color: #888;
            font-size: 0.9em;
        }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="home-contianer">
        <div class="nav-bar">
            @include('section.navbar')
        </div>
        
        <div class="main-content">
            <div class="profile-header">
                <div class="profile-photo-container">
                    <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                         alt="{{ $user->name }}" 
                         class="profile-photo">
                </div>
                <div class="profile-info">
                    <div class="profile-name">{{ $user->name }}</div>
                    <div class="profile-username">{{'@'.$user->username }}</div>
                    <div class="profile-email">{{ $user->email }}</div>
                    @if($user->mobile_number)
                        <div class="profile-mobile">Mobile: {{ $user->mobile_number }}</div>
                    @endif
                    <div class="profile-bio">{{ $user->bio ?? 'No bio yet.' }}</div>
                    <div class="profile-stats">
                        <span>{{ $user->followers_count }} followers</span>
                        <span>{{ $user->following_count }} following</span>
                    </div>
                    @if(auth()->id() !== $user->id)
                        @if(auth()->user()->isFollowing($user))
                            <form action="{{ route('users.unfollow', $user) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary">Unfollow</button>
                            </form>
                        @else
                            <form action="{{ route('users.follow', $user) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn">Follow</button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('profile.edit') }}" class="edit-profile-btn">Edit Profile</a>
                    @endif
                </div>
            </div>
            
            @if(session('success'))
                <div class="success" style="padding: 10px 20px; background-color: #e8f5e9; border-radius: 4px; margin: 10px 20px;">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="profile-tabs">
                <div class="profile-tab active" data-tab="my-posts">My Posts</div>
                <div class="profile-tab" data-tab="saved-posts">Saved Posts</div>
            </div>
            
            <div class="profile-content">
                <div id="my-posts" class="tab-content">
                    <div class="recipe-grid">
                        @foreach($userPosts as $recipe)
                            <div class="recipe-card">
                                @if($recipe->image_url)
                                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="recipe-image">
                                @else
                                    <div class="recipe-image" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                        <span>No Image</span>
                                    </div>
                                @endif
                                <div class="recipe-content">
                                    <h2 class="recipe-title">{{ $recipe->title }}</h2>
                                    <p class="recipe-description">{{ Str::limit($recipe->description, 100) }}</p>
                                    <div class="recipe-meta">
                                        <span>Cuisine: {{ $recipe->cuisine_type }}</span>
                                        <span>Difficulty: {{ $recipe->difficulty }}</span>
                                    </div>
                                    <div style="margin-top: 10px;">
                                        <a href="{{ route('recipes.show', $recipe) }}" class="btn">View</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($userPosts->isEmpty())
                        <p>You haven't posted any recipes yet. <a href="{{ route('recipes.create') }}">Create your first recipe</a>!</p>
                    @endif
                </div>
                
                <div id="saved-posts" class="tab-content" style="display: none;">
                    <div class="recipe-grid">
                        @foreach($savedPosts as $recipe)
                            <div class="recipe-card">
                                @if($recipe->image_url)
                                    <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="recipe-image">
                                @else
                                    <div class="recipe-image" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                        <span>No Image</span>
                                    </div>
                                @endif
                                <div class="recipe-content">
                                    <h2 class="recipe-title">{{ $recipe->title }}</h2>
                                    <p class="recipe-description">{{ Str::limit($recipe->description, 100) }}</p>
                                    <div class="recipe-meta">
                                        <span>Cuisine: {{ $recipe->cuisine_type }}</span>
                                        <span>Difficulty: {{ $recipe->difficulty }}</span>
                                    </div>
                                    <div style="margin-top: 10px;">
                                        <a href="{{ route('recipes.show', $recipe) }}" class="btn">View</a>
                                        <form action="{{ route('recipes.unsave', $recipe) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn">Unsave</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($savedPosts->isEmpty())
                        <p>You haven't saved any recipes yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.profile-tab');
            const tabContents = document.querySelectorAll('.tab-content');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const tabId = this.getAttribute('data-tab');
                    
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all tab contents
                    tabContents.forEach(content => content.style.display = 'none');
                    
                    // Show the selected tab content
                    document.getElementById(tabId).style.display = 'block';
                });
            });
        });
    </script>
</body>
</html> 