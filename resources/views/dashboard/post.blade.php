@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <img src="{{ $recipe->user->profile_photo_url }}" alt="{{ $recipe->user->name }}" class="rounded-circle mr-2" style="width: 40px; height: 40px;">
                        <div>
                            <a href="{{ url('/user/' . $recipe->user->username) }}" class="font-weight-bold">{{ $recipe->user->name }}</a>
                            <div class="text-muted">{{ $recipe->user->username }}</div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $recipe->title }}</h5>
                    <p class="card-text">{{ $recipe->description }}</p>
                    <div class="recipe-media-container">
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
                            <div class="recipe-image" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; height: 400px;">
                                <span>No Media</span>
                            </div>
                        @endif
                    </div>
                    <div class="recipe-details mt-3">
                        <h6>Ingredients:</h6>
                        <ul>
                            @foreach($recipe->ingredients as $ingredient)
                                <li>{{ $ingredient->name }}</li>
                            @endforeach
                        </ul>
                        <h6>Categories:</h6>
                        <div class="categories">
                            @foreach($recipe->categories as $category)
                                <span class="badge badge-primary">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="recipe-actions mt-3">
                        <button class="btn btn-outline-primary like-button" data-recipe-id="{{ $recipe->id }}">
                            <i class="fas fa-heart"></i> {{ $recipe->likes->count() }} Likes
                        </button>
                        <button class="btn btn-outline-secondary comment-button" data-recipe-id="{{ $recipe->id }}">
                            <i class="fas fa-comment"></i> {{ $recipe->comments->count() }} Comments
                        </button>
                    </div>
                    <div class="comments-section mt-3" id="comments-section-{{ $recipe->id }}" style="display: none;">
                        <div class="comments-list">
                            @foreach($recipe->comments as $comment)
                                <div class="comment">
                                    <a href="{{ url('/user/' . $comment->user->username) }}" class="comment-user-name">{{ $comment->user->name }}</a>
                                    <p class="comment-text">{{ $comment->content }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="comment-form">
                            <form class="comment-submit-form" data-recipe-id="{{ $recipe->id }}">
                                @csrf
                                <div class="form-group">
                                    <textarea class="form-control" name="content" placeholder="Write a comment..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Post</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recipe Creation Modal -->
<div id="recipeModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create Recipe</h2>
            <span class="close" onclick="closeRecipeModal()">&times;</span>
        </div>
        <div class="modal-body">
            <form id="recipeForm" method="POST" action="{{ route('recipes.store') }}">
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
                    <label for="video_url">Video URL:</label>
                    <input type="url" id="video_url" name="video_url" placeholder="Enter video URL" required>
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Like button functionality
        document.querySelectorAll('.like-button').forEach(button => {
            button.addEventListener('click', function() {
                const recipeId = this.dataset.recipeId;
                fetch(`/recipes/${recipeId}/like`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const likeCount = this.querySelector('i').nextSibling;
                        likeCount.textContent = ` ${data.likes} Likes`;
                    }
                });
            });
        });

        // Comment button functionality
        document.querySelectorAll('.comment-button').forEach(button => {
            button.addEventListener('click', function() {
                const recipeId = this.dataset.recipeId;
                const commentsSection = document.getElementById(`comments-section-${recipeId}`);
                commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
            });
        });

        // Comment form submission
        document.querySelectorAll('.comment-submit-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const recipeId = this.dataset.recipeId;
                const content = this.querySelector('textarea').value;

                fetch(`/recipes/${recipeId}/comments`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ content }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const commentsList = document.querySelector(`#comments-section-${recipeId} .comments-list`);
                        const newComment = document.createElement('div');
                        newComment.className = 'comment';
                        newComment.innerHTML = `
                            <a href="/user/${data.comment.user.username}" class="comment-user-name">${data.comment.user.name}</a>
                            <p class="comment-text">${data.comment.content}</p>
                        `;
                        commentsList.appendChild(newComment);
                        this.querySelector('textarea').value = '';
                    }
                });
            });
        });
    });

    // Recipe Creation Modal Functions
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
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                window.location.reload();
            } else {
                alert(data.message || 'Error creating recipe. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting the form. Please try again.');
        })
        .finally(() => {
            // Re-enable submit button and hide loading
            submitBtn.disabled = false;
            loading.style.display = 'none';
        });
    });

    function playVideo(playButton) {
        const container = playButton.parentElement;
        const video = container.querySelector('video');
        const image = container.querySelector('.recipe-image');
        
        if (video) {
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

<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input[type="text"],
    .form-group input[type="url"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 8px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .ingredient-group {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .add-ingredient-btn {
        background-color: #4CAF50;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .remove-ingredient {
        background-color: #ff4444;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        padding: 0 8px;
    }

    .submit-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
    }

    .loading {
        text-align: center;
        margin-bottom: 10px;
    }

    .recipe-media-container {
        width: 100%;
        max-width: 800px;
        margin: 0 auto 20px;
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .recipe-media-container img,
    .recipe-media-container video {
        width: 100%;
        height: auto;
        display: block;
    }
    
    .recipe-media-container video {
        display: none;
    }
    
    .play-button {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 80px;
        height: 80px;
        background-color: rgba(0, 0, 0, 0.7);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        transition: all 0.3s ease;
        z-index: 10;
        cursor: pointer;
    }
    
    .play-button:hover {
        background-color: rgba(0, 0, 0, 0.9);
        transform: translate(-50%, -50%) scale(1.1);
    }
    
    .play-icon {
        font-size: 40px;
        margin-left: 5px; /* Slight offset to center the play icon visually */
    }
</style>
@endsection 