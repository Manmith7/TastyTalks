<!DOCTYPE html>
<html>
<head>
    <title>Create Recipe</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="url"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        input[type="file"] {
            padding: 10px;
            background-color: #f9f9f9;
        }
        textarea {
            height: 100px;
        }
        .ingredient-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .ingredient-group input {
            flex: 1;
        }
        .categories-group {
            margin: 15px 0;
        }
        .category-item {
            margin: 5px 0;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .loading {
            display: none;
            margin-top: 10px;
            color: #666;
        }
        .file-info {
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <h2>Create Recipe</h2>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div class="error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="recipeForm" method="POST" action="{{ route('recipes.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Enter recipe title" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea id="description" name="description" placeholder="Enter recipe description" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="cuisine_type">Cuisine Type:</label>
            <select id="cuisine_type" name="cuisine_type" required>
                <option value="" disabled selected>Select cuisine type</option>
                <option value="north_indian" {{ old('cuisine_type') == 'north_indian' ? 'selected' : '' }}>North Indian</option>
                <option value="south_indian" {{ old('cuisine_type') == 'south_indian' ? 'selected' : '' }}>South Indian</option>
                <option value="bengali" {{ old('cuisine_type') == 'bengali' ? 'selected' : '' }}>Bengali</option>
                <option value="hyderabadi" {{ old('cuisine_type') == 'hyderabadi' ? 'selected' : '' }}>Hyderabadi</option>
                <option value="chettinad" {{ old('cuisine_type') == 'chettinad' ? 'selected' : '' }}>Chettinad</option>
                <option value="gujarati" {{ old('cuisine_type') == 'gujarati' ? 'selected' : '' }}>Gujarati</option>
                <option value="malvani" {{ old('cuisine_type') == 'malvani' ? 'selected' : '' }}>Malvani</option>
                <option value="mughlai" {{ old('cuisine_type') == 'mughlai' ? 'selected' : '' }}>Mughlai</option>
                <option value="rajasthani" {{ old('cuisine_type') == 'rajasthani' ? 'selected' : '' }}>Rajasthani</option>
                <option value="kashmiri" {{ old('cuisine_type') == 'kashmiri' ? 'selected' : '' }}>Kashmiri</option>
            </select>
        </div>

        <div class="form-group">
            <label for="difficulty">Difficulty:</label>
            <select id="difficulty" name="difficulty" required>
                <option value="" disabled selected>Select difficulty level</option>
                <option value="easy" {{ old('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                <option value="medium" {{ old('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                <option value="hard" {{ old('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
            </select>
        </div>

        <div class="form-group">
            <label for="steps">Steps:</label>
            <textarea id="steps" name="steps" placeholder="Enter recipe steps" required>{{ old('steps') }}</textarea>
        </div>

        <div class="form-group">
            <label for="thumbnail">Recipe Thumbnail:</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*" required>
            <div class="file-info">Supported formats: JPEG, PNG, JPG (Max size: 2MB)</div>
        </div>

        <div class="form-group">
            <label for="video">Recipe Video (Optional):</label>
            <input type="file" id="video" name="video" accept="video/*">
            <div class="file-info">Supported formats: MP4, MOV, AVI (Max size: 100MB)</div>
        </div>

        <div class="form-group">
            <label for="image_url">Image URL:</label>
            <input type="url" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="Enter image URL">
        </div>

        <div class="form-group">
            <h4>Ingredients</h4>
            <div id="ingredients">
                <div class="ingredient-group">
                    <input type="text" name="ingredients[0][name]" placeholder="Ingredient Name" value="{{ old('ingredients.0.name') }}" required>
                    <input type="text" name="ingredients[0][quantity]" placeholder="Quantity" value="{{ old('ingredients.0.quantity') }}" required>
                </div>
            </div>
            <button type="button" onclick="addIngredient()">Add More Ingredient</button>
        </div>

        <div class="form-group">
            <h4>Categories</h4>
            <div class="categories-group">
                @foreach($categories as $cat)
                    <div class="category-item">
                        <label>
                            <input type="checkbox" name="categories[]" value="{{ $cat->id }}" 
                                {{ in_array($cat->id, old('categories', [])) ? 'checked' : '' }}>
                            {{ $cat->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="loading" id="loading">Submitting form...</div>
        <button type="submit" id="submitBtn">Create Recipe</button>
    </form>

    <script>
    let count = 1;
    function addIngredient() {
        const container = document.getElementById('ingredients');
        const div = document.createElement('div');
        div.className = 'ingredient-group';
        div.innerHTML = `
            <input type="text" name="ingredients[${count}][name]" placeholder="Ingredient Name" required>
            <input type="text" name="ingredients[${count}][quantity]" placeholder="Quantity" required>
        `;
        container.appendChild(div);
        count++;
    }

    // Handle form submission
    document.getElementById('recipeForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const submitBtn = document.getElementById('submitBtn');
        const loading = document.getElementById('loading');
        
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
                return response.json().then(data => {
                    throw new Error(data.message || 'Network response was not ok');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Redirect to recipes page on success
                window.location.href = '{{ route("recipes.index") }}';
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
</body>
</html>
