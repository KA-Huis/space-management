<!-- Session Status -->
{!! dump(session('status')) !!}

<!-- Validation Errors -->
{!! dump($errors) !!}

<form method="POST" action="{{ route('auth.login') }}">
@csrf

<!-- Email Address -->
    <div>
        <label for="email">{{ __('Email') }}</label>

        <input id="email"  type="email" name="email" value="{{ old('email') }}" required
                 autofocus>
    </div>

    <!-- Password -->
    <div class="mt-4">
        <label for="password">{{ __('Password') }}</label>

        <input id="password"
                 type="password"
                 name="password"
                 required autocomplete="current-password"/>
    </div>

    <!-- Remember Me -->
    <div class="block mt-4">
        <label for="remember_me" class="inline-flex items-center">
            <input id="remember_me" type="checkbox"
                   name="remember">
            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>
    </div>

    <div class="flex items-center justify-end mt-4">
        @if (Route::has('password.request'))
            <a href="{{ route('auth.password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif

        <button>
            {{ __('Log in') }}
        </button>
    </div>
</form>
