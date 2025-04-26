<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TastyTalks - Home</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
        .material-icons {
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
        .category {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            padding: 10px;
        }
        .posts {
            flex: 1;
            overflow-y: auto;
           
        }
        .post-user {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
        }
        .username {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
        }
        .profile-pic img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .user-details {
            display: flex;
            flex-direction: column;
        }
        .user-name {
            font-weight: bold;
            margin: 0;
        }
        .user-username {
            color: #666;
            font-size: 0.9em;
            margin: 0;
        }
        .follow-btn {
            padding: 6px 16px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }
        .follow-btn.following {
            background-color: #6c757d;
            color: white;
        }
        .follow-btn.follow {
            background-color: #4CAF50;
            color: white;
        }
        .follow-btn:hover {
            opacity: 0.9;
        }
        .feeds {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
        }
        
        .svgs {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .action-group {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .count-text {
            font-size: 14px;
            color: #262626;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Include the navbar -->
    
    <div class="home-contianer">
        <div class="nav-bar">
            @include('section.navbar')
        </div>
        <!-- Main Content -->
        <div class="main-content">
            <div class="category">
                <div class="cusine">
                    <select name="indian_cuisine">
                        <option value="" disabled selected>Cuisine</option>
                        <option value="north_indian">North Indian</option>
                        <option value="south_indian">South Indian</option>
                        <option value="bengali">Bengali</option>
                        <option value="hyderabadi">Hyderabadi</option>
                        <option value="chettinad">Chettinad</option>
                        <option value="gujarati">Gujarati</option>
                        <option value="malvani">Malvani</option>
                        <option value="mughlai">Mughlai</option>
                        <option value="rajasthani">Rajasthani</option>
                        <option value="kashmiri">Kashmiri</option>
                    </select>
                </div>
                <div class="ingredient">
                    <select name="ingredient">
                        <option value="" disabled selected>Ingredient</option>
                        <option value="rice">Rice</option>
                        <option value="wheat_flour">Wheat Flour</option>
                        <option value="lentils">Lentils</option>
                        <option value="chickpeas">Chickpeas</option>
                        <option value="paneer">Paneer</option>
                        <option value="spices">Mixed Spices</option>
                        <option value="ghee">Ghee</option>
                        <option value="mustard_oil">Mustard Oil</option>
                        <option value="coconut">Coconut</option>
                        <option value="green_chili">Green Chili</option>
                    </select>
                </div>
                <div class="difficulty">
                    <select name="difficulty">
                        <option value="" disabled selected>Difficulty</option>
                        <option value="easy">Easy</option>
                        <option value="medium">Medium</option>
                        <option value="hard">Hard</option>
                    </select>
                </div>
            </div>

            <div class="posts">
                @foreach($recipes as $recipe)
                    <div class="post-user">
                        <div class="username">
                            <div class="user-info">
                                <div class="profile-pic">
                                    <img src="{{ $recipe->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($recipe->user->name) }}" 
                                         alt="{{ $recipe->user->name }}">
                                </div>
                                <div class="user-details">
                                    <p class="user-name">{{ $recipe->user->name }}</p>
                                    <a href="{{ url('/user/' . $recipe->user->username) }}" class="user-username" style="text-decoration: none; color: inherit;">{{'@'.$recipe->user->username }}</a>
                                </div>
                            </div>
                            @if(auth()->id() !== $recipe->user_id)
                                @if(auth()->user()->isFollowing($recipe->user))
                                    <form action="{{ route('users.unfollow', $recipe->user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="follow-btn following">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('users.follow', $recipe->user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="follow-btn follow">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                        <div class="more">
                            <span class="material-icons">
                                more_vert
                            </span>
                        </div>
                    </div>
                    <div class="image" onclick="openRecipeModal()" style="cursor: pointer; width: 100%; height: 100%;">
                        @if($recipe->video_url)
                            <video width="100%" controls >
                                <source src="{{ $recipe->video_url }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @elseif($recipe->image_url)
                            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" style="width: 100%; height: 100%; object-fit: cover;padding:30px">
                        @endif
                    </div>
                    <div class="feeds">
                        <div class="svgs">
                            <div class="action-group">
                                <form action="{{ route('recipes.like', $recipe) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 border-0">
                                        <span class="material-icons {{ $recipe->isLikedBy(auth()->user()) ? 'text-danger' : '' }}">
                                            {{ $recipe->isLikedBy(auth()->user()) ? 'favorite' : 'favorite_border' }}
                                        </span>
                                    </button>
                                </form>
                                <span class="count-text">{{ $recipe->likes()->count() }}</span>
                            </div>

                            <div class="action-group">
                                <button type="button" class="btn btn-link p-0 border-0" onclick="toggleComments(this)">
                                    <span class="material-icons">chat_bubble_outline</span>
                                </button>
                                <span class="count-text">{{ $recipe->comments()->count() }}</span>
                            </div>

                            <div class="action-group">
                                <button type="button" class="btn btn-link p-0 border-0">
                                    <span class="material-icons" style="font-size: 28px;">share</span>
                                </button>
                                <span class="count-text">0</span>
                            </div>
                        </div>

                        <div class="action-group">
                            <form action="{{ route('recipes.save', $recipe) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 border-0">
                                    <span class="material-icons {{ $recipe->isSavedBy(auth()->user()) ? 'text-primary' : '' }}">
                                        {{ $recipe->isSavedBy(auth()->user()) ? 'bookmark' : 'bookmark_border' }}
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="comments-container" style="display: none;">
                        <div class="comments-section">
                            @foreach($recipe->comments()->latest()->get() as $comment)
                                <div class="comment">
                                    <div class="comment-header">
                                        <img src="{{ $comment->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) }}" 
                                             alt="{{ $comment->user->name }}" 
                                             class="comment-user-img">
                                        <a href="{{ url('/user/' . $comment->user->username) }}" class="comment-user-name" style="text-decoration: none; color: inherit;">{{ $comment->user->name }}</a>
                                        <span class="comment-time">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="comment-content">
                                        {{ $comment->content }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <form action="{{ route('recipes.comment', $recipe) }}" method="POST" class="comment-form" onsubmit="submitComment(event, this)">
                            @csrf
                            <textarea name="content" rows="1" placeholder="Add a comment..." oninput="autoResize(this)"></textarea>
                            <button type="submit" class="post-btn">Post</button>
                        </form>
                    </div>

                    <div class="description">
                        <h3>{{ $recipe->title }}</h3>
                        <p>{{ $recipe->description }}</p>
                        <div class="recipe-meta">
                            <span>Cuisine: {{ $recipe->cuisine_type }}</span>
                            <span>Difficulty: {{ $recipe->difficulty }}</span>
                        </div>
                    </div>
                    <br>
                @endforeach

                @if($recipes->isEmpty())
                    <p>No recipes found. <a href="{{ route('recipes.create') }}">Create your first recipe</a>!</p>
                @endif
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Toggle comments section
        function toggleComments(button) {
            // Find the recipe post container by traversing up the DOM
            const feedsElement = button.closest('.feeds');
            const recipePost = feedsElement.parentElement;
            
            // Find the comments container within this recipe post
            const commentsContainer = recipePost.querySelector('.comments-container');
            
            if (commentsContainer) {
                // Toggle the display
                const isHidden = commentsContainer.style.display === 'none' || commentsContainer.style.display === '';
                commentsContainer.style.display = isHidden ? 'block' : 'none';
                
                // Remove the scrollIntoView behavior
                // if (isHidden) {
                //     // Scroll to comments section
                //     commentsContainer.scrollIntoView({ behavior: 'smooth' });
                // }
            } else {
                console.error('Comments container not found');
            }
        }

        // Enable/disable post button based on textarea content
        document.querySelectorAll('.comment-form textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                const postButton = this.nextElementSibling;
                postButton.disabled = !this.value.trim();
                autoResize(this);
            });
        });

        // Auto-resize textarea
        function autoResize(textarea) {
            textarea.style.height = '36px';
            const newHeight = Math.min(textarea.scrollHeight, 100);
            textarea.style.height = newHeight + 'px';
        }

        // Submit comment using AJAX
        function submitComment(event, form) {
            event.preventDefault();
            
            // Get form data
            const formData = new FormData(form);
            const textarea = form.querySelector('textarea');
            const submitButton = form.querySelector('button[type="submit"]');
            
            // Disable the button and textarea while submitting
            submitButton.disabled = true;
            textarea.disabled = true;
            
            // Get the comments container and section
            const commentsContainer = form.closest('.comments-container');
            const commentsSection = commentsContainer.querySelector('.comments-section');
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Create new comment element
                    const commentHtml = `
                        <div class="comment">
                            <div class="comment-header">
                                <img src="${data.user.profile_photo_url || `https://ui-avatars.com/api/?name=${encodeURIComponent(data.user.name)}`}" 
                                     alt="${data.user.name}" 
                                     class="comment-user-img">
                                <a href="/user/${data.user.username}" class="comment-user-name" style="text-decoration: none; color: inherit;">${data.user.name}</a>
                                <span class="comment-time">just now</span>
                            </div>
                            <div class="comment-content">
                                ${data.comment.content}
                            </div>
                        </div>
                    `;
                    
                    // Add new comment to the top of the comments section
                    commentsSection.insertAdjacentHTML('afterbegin', commentHtml);
                    
                    // Clear and reset the form
                    textarea.value = '';
                    textarea.style.height = '36px';
                    
                    // Ensure comments section is visible
                    commentsContainer.style.display = 'block';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error posting your comment. Please try again.');
            })
            .finally(() => {
                // Re-enable the textarea and button
                textarea.disabled = false;
                submitButton.disabled = !textarea.value.trim();
            });
        }

        // Handle share options
        function toggleShareOptions(button) {
            const options = button.parentElement.querySelector('.share-options');
            options.classList.toggle('show');
            
            // Close when clicking outside
            document.addEventListener('click', function closeOptions(e) {
                if (!button.contains(e.target) && !options.contains(e.target)) {
                    options.classList.remove('show');
                    document.removeEventListener('click', closeOptions);
                }
            });
        }

        // Recipe Creation Modal
        function openRecipeModal() {
            document.getElementById('recipeModal').style.display = 'block';
        }

        function closeRecipeModal() {
            document.getElementById('recipeModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('recipeModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Add ingredient fields dynamically
        let ingredientCount = 1;
        function addIngredientField() {
            const ingredientsContainer = document.getElementById('ingredients-container');
            const newIngredientDiv = document.createElement('div');
            newIngredientDiv.className = 'ingredient-group';
            newIngredientDiv.innerHTML = `
                <input type="text" name="ingredients[${ingredientCount}][name]" placeholder="Ingredient Name" required>
                <input type="text" name="ingredients[${ingredientCount}][quantity]" placeholder="Quantity" required>
                <button type="button" class="remove-ingredient" onclick="removeIngredient(this)">×</button>
            `;
            ingredientsContainer.appendChild(newIngredientDiv);
            ingredientCount++;
        }

        function removeIngredient(button) {
            button.parentElement.remove();
        }

        // Handle recipe form submission
        document.getElementById('recipeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = document.getElementById('submitRecipeBtn');
            const loading = document.getElementById('recipeLoading');
            
            // Disable submit button and show loading
            submitBtn.disabled = true;
            loading.style.display = 'block';
            
            // Get form data
            const formData = new FormData(form);
            
            // Send AJAX request
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Network response was not ok');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Redirect to home page or show success message
                    window.location.reload();
                } else {
                    alert(data.message || 'Error creating recipe. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while submitting the form: ' + error.message);
            })
            .finally(() => {
                // Re-enable submit button and hide loading
                submitBtn.disabled = false;
                loading.style.display = 'none';
            });
        });
    </script>

    <!-- Recipe Creation Modal -->
    <div id="recipeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Create Recipe</h2>
                <span class="close" onclick="closeRecipeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="recipeForm" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" id="title" name="title" placeholder="Enter recipe title" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" placeholder="Enter recipe description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="cuisine_type">Cuisine Type:</label>
                        <select id="cuisine_type" name="cuisine_type" required>
                            <option value="" disabled selected>Select cuisine type</option>
                            <option value="north_indian">North Indian</option>
                            <option value="south_indian">South Indian</option>
                            <option value="bengali">Bengali</option>
                            <option value="hyderabadi">Hyderabadi</option>
                            <option value="chettinad">Chettinad</option>
                            <option value="gujarati">Gujarati</option>
                            <option value="malvani">Malvani</option>
                            <option value="mughlai">Mughlai</option>
                            <option value="rajasthani">Rajasthani</option>
                            <option value="kashmiri">Kashmiri</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="difficulty">Difficulty:</label>
                        <select id="difficulty" name="difficulty" required>
                            <option value="" disabled selected>Select difficulty</option>
                            <option value="easy">Easy</option>
                            <option value="medium">Medium</option>
                            <option value="hard">Hard</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="steps">Steps:</label>
                        <textarea id="steps" name="steps" placeholder="Enter recipe steps" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="video">Recipe Video:</label>
                        <input type="file" id="video" name="video" accept="video/*" required>
                        <small class="text-muted">Supported formats: MP4, MOV, AVI (Max size: 100MB)</small>
                    </div>

                    <div class="form-group">
                        <label for="image_url">Image URL:</label>
                        <input type="url" id="image_url" name="image_url" placeholder="Enter image URL">
                    </div>

                    <div class="form-group">
                        <h4>Ingredients</h4>
                        <div id="ingredients-container">
                            <div class="ingredient-group">
                                <input type="text" name="ingredients[0][name]" placeholder="Ingredient Name" required>
                                <input type="text" name="ingredients[0][quantity]" placeholder="Quantity" required>
                            </div>
                        </div>
                        <button type="button" class="add-ingredient-btn" onclick="addIngredientField()">Add More Ingredient</button>
                    </div>

                    <div class="form-group">
                        <h4>Categories</h4>
                        <div class="categories-group">
                            @foreach($categories ?? [] as $cat)
                                <div class="category-item">
                                    <label>
                                        <input type="checkbox" name="categories[]" value="{{ $cat->id }}">
                                        {{ $cat->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div id="recipeLoading" class="loading" style="display: none;">Submitting form...</div>
                    <button type="submit" id="submitRecipeBtn" class="submit-btn">Create Recipe</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

