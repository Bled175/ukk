<x-guest-layout>
    <h1 class="login-title">Masuk ke Gajiku</h1>

    <x-auth-session-status class="session-status" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="login-field">
            <label class="floating-label" for="email">Email / Username</label>
            <input id="email" class="login-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            <x-input-error :messages="$errors->get('email')" class="login-error" />
        </div>
        <div class="login-field password-field">
            <label class="floating-label" for="password">Password</label>
            <input id="password" class="login-input" type="password" name="password" required autocomplete="current-password">
            <x-input-error :messages="$errors->get('password')" class="login-error" />
        </div>

        <div class="login-actions">
            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    Lupa Password
                </a>
            @endif

            <button class="login-button" type="submit">Login</button>
        </div>
    </form>
</x-guest-layout>
