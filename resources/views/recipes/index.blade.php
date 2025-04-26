<!DOCTYPE html>
<html>
<head>
    <title>Recipes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
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
            display: flex;
            flex-direction: column;
        }
        .recipe-image-container {
            width: 100%;
            height: 200px;
            overflow: hidden;
            background-color: #000;
            position: relative;
            cursor: pointer;
        }
        .recipe-image, .recipe-image-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .recipe-image-container video {
            background-color: #000;
            min-height: 200px;
            display: none;
        }
        .play-button {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            transition: all 0.3s ease;
            z-index: 10;
        }
        .play-button:hover {
            background-color: rgba(0, 0, 0, 0.9);
            transform: translate(-50%, -50%) scale(1.1);
        }
        .play-icon {
            font-size: 30px;
            margin-left: 5px; /* Slight offset to center the play icon visually */
        }
        .recipe-content {
            padding: 15px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .recipe-title {
            font-size: 1.2em;
            margin: 0 0 10px 0;
        }
        .recipe-description {
            color: #666;
            margin-bottom: 10px;
            flex-grow: 1;
        }
        .recipe-meta {
            display: flex;
            justify-content: space-between;
            color: #888;
            font-size: 0.9em;
            margin-top: auto;
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
        .btn-danger {
            background-color: #f44336;
        }
        .btn-danger:hover {
            background-color: #d32f2f;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Recipes</h1>
        <a href="{{ route('recipes.create') }}" class="btn">Create New Recipe</a>
    </div>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <div class="recipe-grid">
        @foreach($recipes as $recipe)
            <div class="recipe-card">
                <div class="recipe-image-container">
                    @if($recipe->video_url)
                        <video controls preload="metadata" style="display: none; width: 100%;">
                            <source src="{{ $recipe->video_url }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="recipe-image">
                        <div class="play-button" onclick="playVideo(this)">
                            <span class="play-icon">▶</span>
                        </div>
                    @elseif($recipe->image_url)
                        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="recipe-image">
                    @else
                        <div class="recipe-image" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                            <span>No Media</span>
                        </div>
                    @endif
                </div>
                <div class="recipe-content">
                    <h2 class="recipe-title">{{ $recipe->title }}</h2>
                    <p class="recipe-description">{{ Str::limit($recipe->description, 100) }}</p>
                    <div class="recipe-meta">
                        <span>Cuisine: {{ $recipe->cuisine_type }}</span>
                        <span>Difficulty: {{ $recipe->difficulty }}</span>
                    </div>
                    <div class="recipe-meta">
                        <span>By: {{ $recipe->user->name }}</span>
                        <span>{{ $recipe->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="actions">
                        <a href="{{ route('recipes.show', $recipe) }}" class="btn">View</a>
                        @if(auth()->id() == $recipe->user_id)
                            <a href="{{ route('recipes.edit', $recipe) }}" class="btn">Edit</a>
                            <form action="{{ route('recipes.destroy', $recipe) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this recipe?')">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($recipes->isEmpty())
        <p>No recipes found. <a href="{{ route('recipes.create') }}">Create your first recipe</a>!</p>
    @endif

    <script>
    function playVideo(playButton) {
        const container = playButton.parentElement;
        const video = container.querySelector('video');
        const image = container.querySelector('.recipe-image');
        
        if (video) {
            // Stop all other videos first
            document.querySelectorAll('video').forEach(v => {
                if (v !== video && !v.paused) {
                    v.pause();
                    v.style.display = 'none';
                    v.parentElement.querySelector('.recipe-image').style.display = 'block';
                    v.parentElement.querySelector('.play-button').style.display = 'flex';
                }
            });
            
            // Show and play this video
            video.style.display = 'block';
            image.style.display = 'none';
            playButton.style.display = 'none';
            video.play();
            
            // Add event listener to show play button again when video ends
            video.onended = function() {
                video.style.display = 'none';
                image.style.display = 'block';
                playButton.style.display = 'flex';
            };
        }
    }
    </script>
</body>
</html> 