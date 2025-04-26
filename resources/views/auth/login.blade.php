<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TastyTalks - Login</title>
  <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
  <div class="register-container">
    <div class="register-image">
      <img src="{{ asset('img/Group1.png') }}" alt="Group cooking">
    </div>

    <div class="register-form">
      <h1 class="logo">TastyTalks</h1>

      @if (session('error'))
        <div class="error-message">{{ session('error') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf
        <input
          type="email"
          name="email"
          placeholder="Email address"
          class="form-input"
          value="{{ old('email') }}"
          required
        >
        <input
          type="password"
          name="password"
          placeholder="Password"
          class="form-input"
          required
        >
        <button type="submit" class="signup-button">Log in</button>
      </form>

      <div class="divider">
        <span>or</span>
      </div>

      <div class="login-link">
        Don't have an account? <a href="{{ route('register') }}">Sign up</a>
      </div>
    </div>
  </div>
</body>
</html>
