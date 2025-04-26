{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <div class="register-container">
        <div class="register-image">
            <img src="{{ asset('img/Group1.png') }}" alt="Group cooking">
        </div>

        <div class="register-form">
            <h1 class="logo">
                <img src="{{ asset('img/TastyTalks.png') }}" alt="TastyTalks Logo">
            </h1>

            @if(session('error'))
                <div class="error-message">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input
                    type="email"
                    name="email"
                    placeholder="Email address"
                    value="{{ old('email') }}"
                    class="form-input"
                    required
                />
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    class="form-input"
                    required
                />
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <input
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm Password"
                    class="form-input"
                    required
                />

                <button type="submit" class="signup-button">
                    Sign up
                </button>
            </form>

            <div class="divider"><span>or</span></div>
            <div class="login-link">
                Have an account? <a href="{{ route('login') }}">Log in</a>
            </div>
        </div>
    </div>
</body>
</html>
