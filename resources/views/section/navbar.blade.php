<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Irish+Grover&display=swap" rel="stylesheet">
  <style>
    .irish-grover-regular {
      font-family: "Irish Grover", system-ui;
      font-weight: 400;
      font-style: normal;
    }
    .material-icons {
      font-size: 24px;
      color: black;
    }
  </style>
</head>
<body>
  <div class="nav-container">
    <div class="logo">
      <h1 class="irish-grover-regular">TastyTalks</h1>
    </div>
    <div class="links">
      <ul>
        <li><a href="{{ url('/home') }}"><span class="material-icons">home</span>Home</a></li>
        <li><a href="{{ url('/search') }}"><span class="material-icons">search</span>Search</a></li>
        <li><a href="{{ url('/explore') }}"><span class="material-icons">explore</span>Explore</a></li>
        <li><a href="{{ url('/reels') }}"><span class="material-icons">movie</span>Reels</a></li>
        <li><a href="{{ url('/post') }}"><span class="material-icons">add_box</span>Post</a></li>
        <li><a href="{{ url('/notifications') }}"><span class="material-icons">notifications</span>Notification</a></li>
        <li><a href="{{ url('/profile') }}"><span class="material-icons">person</span>Profile</a></li>
        <br><br><br>
        <li><a href="#"><span class="material-icons">logout</span>Log out</a></li>
      </ul>
    </div>
  </div>
</body>
</html>

