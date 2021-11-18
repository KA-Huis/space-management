<div class="mb-4 text-sm text-gray-600">
    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
</div>

<!-- Validation Errors -->
{!! dump($errors) !!}

<form method="POST" action="{{ route('auth.password.confirm') }}">
@csrf

<!-- Password -->
    <div>
        <label for="password" >{{ __('Password') }}</label>

        <input id="password"
                 type="password"
                 name="password"
                 required autocomplete="current-password"/>
    </div>

    <div class="flex justify-end mt-4">
        <button>
            {{ __('Confirm') }}
        </button>
    </div>
</form>
