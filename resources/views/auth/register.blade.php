<!-- Validation Errors -->
{!! dump($errors) !!}

<form method="POST" action="{{ route('auth.register') }}">
@csrf

<!-- Name -->
    <div>
        <label for="first_name">{{ __('First name') }}</label>

        <input id="first_name" type="text" name="first_name" value="{{ old('first_name') }}" required autofocus/>
    </div>

    <div>
        <label for="last_name">{{ __('Last name') }}</label>

        <input id="last_name" type="text" name="last_name" value="{{ old('last_name') }}" required autofocus/>
    </div>

    <!-- Email Address -->
    <div class="mt-4">
        <label for="email">{{__('Email')}}</label>

        <input id="email" type="email" name="email" value="{{ old('email') }}" required/>
    </div>

    <!-- Password -->
    <div class="mt-4">
        <label for="password">{{__('Password')}}</label>

        <input id="password" class="block mt-1 w-full"
                 type="password"
                 name="password"
                 required autocomplete="new-password"/>
    </div>

    <!-- Confirm Password -->
    <div class="mt-4">
        <label for="password_confirmation">{{ __('Confirm Password') }}</label>

        <input id="password_confirmation"
                 type="password"
                 name="password_confirmation" required/>
    </div>

    <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('auth.login') }}">
            {{ __('Already registered?') }}
        </a>

        <button>
            {{ __('Register') }}
        </button>
    </div>
</form>
