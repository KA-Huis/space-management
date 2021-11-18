<div class="mb-4 text-sm text-gray-600">
    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
</div>

<!-- Session Status -->
{!! dump(session('status')) !!}

<!-- Validation Errors -->
{!! dump($errors) !!}

<form method="POST" action="{{ route('auth.password.email') }}">
@csrf

<!-- Email Address -->
    <div>
        <label for="email">{{ __('Email') }}</label>

        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                 autofocus/>
    </div>

    <div class="flex items-center justify-end mt-4">
        <button type="submit">
            {{ __('Email Password Reset Link') }}
        </button>
    </div>
</form>
