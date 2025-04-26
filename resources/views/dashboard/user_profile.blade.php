<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $user->name }} - Profile</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .profile-container {
            max-width: 935px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .profile-header {
            display: flex;
            margin-bottom: 44px;
        }

        .profile-image {
            margin-right: 30px;
            flex-shrink: 0;
        }

        .profile-image img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-info {
            flex: 1;
        }

        .profile-username {
            font-size: 28px;
            font-weight: 300;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .profile-stats {
            display: flex;
            gap: 40px;
            margin-bottom: 20px;
        }

        .stat {
            font-size: 16px;
        }

        .stat-count {
            font-weight: 600;
        }

        .profile-bio {
            margin-bottom: 20px;
        }

        .profile-name {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .profile-bio-text {
            white-space: pre-wrap;
        }

        .profile-posts {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 28px;
            margin-top: 40px;
        }

        .post-item {
            aspect-ratio: 1;
            position: relative;
            cursor: pointer;
        }

        .post-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .post-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .post-item:hover .post-overlay {
            opacity: 1;
        }

        .post-stats {
            color: white;
            display: flex;
            gap: 30px;
        }

        .post-stat {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .post-stat i {
            font-size: 20px;
        }

        .follow-btn {
            background-color: #0095f6;
            color: white;
            border: none;
            padding: 5px 24px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .following-btn {
            background-color: #efefef;
            color: black;
        }

        .profile-tabs {
            display: flex;
            justify-content: center;
            border-top: 1px solid #dbdbdb;
            margin-top: 40px;
        }

        .tab {
            padding: 20px;
            text-transform: uppercase;
            font-size: 12px;
            font-weight: 600;
            color: #8e8e8e;
            letter-spacing: 1px;
            border-top: 1px solid transparent;
            margin-top: -1px;
            cursor: pointer;
        }

        .tab.active {
            color: #262626;
            border-top-color: #262626;
        }
    </style>
</head>
<body>
    <div class="home-contianer">
        <div class="nav-bar">
            @include('section.navbar')
        </div>
        <div class="main-content">
            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-image">
                        <img src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" 
                             alt="{{ $user->name }}">
                    </div>
                    <div class="profile-info">
                        <div class="profile-username">
                            {{ $user->username }}
                            @if(auth()->id() !== $user->id)
                                @if($isFollowing)
                                    <form action="{{ route('users.unfollow', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="follow-btn following-btn">Following</button>
                                    </form>
                                @else
                                    <form action="{{ route('users.follow', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="follow-btn">Follow</button>
                                    </form>
                                @endif
                            @endif
                        </div>
                        <div class="profile-stats">
                            <div class="stat">
                                <span class="stat-count">{{ $recipes->count() }}</span> posts
                            </div>
                            <div class="stat">
                                <span class="stat-count">{{ $followersCount }}</span> followers
                            </div>
                            <div class="stat">
                                <span class="stat-count">{{ $followingCount }}</span> following
                            </div>
                        </div>
                        <div class="profile-bio">
                            <div class="profile-name">{{ $user->name }}</div>
                            <div class="profile-bio-text">{{ $user->bio }}</div>
                        </div>
                    </div>
                </div>

                <div class="profile-tabs">
                    <div class="tab active">
                        <i class="material-icons">grid_on</i> Posts
                    </div>
                </div>

                <div class="profile-posts">
                    @foreach($recipes as $recipe)
                        <div class="post-item">
                            <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}">
                            <div class="post-overlay">
                                <div class="post-stats">
                                    <div class="post-stat">
                                        <i class="material-icons">favorite</i>
                                        <span>{{ $recipe->likes()->count() }}</span>
                                    </div>
                                    <div class="post-stat">
                                        <i class="material-icons">chat_bubble</i>
                                        <span>{{ $recipe->comments()->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</body>
</html> 