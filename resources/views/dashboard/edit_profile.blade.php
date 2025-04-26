<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>TastyTalks - Edit Profile</title>
    
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
        .edit-profile-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-top: 20px;
        }
        .edit-profile-header {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
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
        textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: inherit;
        }
        textarea {
            height: 100px;
            resize: vertical;
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
        .btn-secondary {
            background-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        .readonly-field {
            background-color: #f5f5f5;
            color: #666;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="home-contianer">
        <div class="nav-bar">
            @include('section.navbar')
        </div>
        
        <div class="main-content">
            <div class="edit-profile-container">
                <div class="edit-profile-header">
                    <h2>Edit Profile</h2>
                </div>
                
                @if($errors->any())
                    <div class="error">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                        <small>This will be your unique identifier on the platform</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" id="email" value="{{ $user->email }}" class="readonly-field" readonly>
                        <small>Email cannot be changed</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="profile_photo_url">Profile Photo URL</label>
                        <input type="url" id="profile_photo_url" name="profile_photo_url" value="{{ old('profile_photo_url', $user->profile_photo_url) }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="mobile_number">Mobile Number</label>
                        <input type="tel" id="mobile_number" name="mobile_number" value="{{ old('mobile_number', $user->mobile_number) }}" placeholder="Enter your mobile number">
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn">Save Changes</button>
                        <a href="{{ route('profile') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html> 