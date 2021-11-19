<!-- Session Status -->
<?php echo dump(session('status')); ?>


<!-- Validation Errors -->
<?php echo dump($errors); ?>


<form method="POST" action="<?php echo e(route('auth.login')); ?>">
<?php echo csrf_field(); ?>

<!-- Email Address -->
    <div>
        <label for="email"><?php echo e(__('Email')); ?></label>

        <input id="email"  type="email" name="email" value="<?php echo e(old('email')); ?>" required
                 autofocus>
    </div>

    <!-- Password -->
    <div class="mt-4">
        <label for="password"><?php echo e(__('Password')); ?></label>

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
            <span class="ml-2 text-sm text-gray-600"><?php echo e(__('Remember me')); ?></span>
        </label>
    </div>

    <div class="flex items-center justify-end mt-4">
        <?php if(Route::has('password.request')): ?>
            <a href="<?php echo e(route('auth.password.request')); ?>">
                <?php echo e(__('Forgot your password?')); ?>

            </a>
        <?php endif; ?>

        <button>
            <?php echo e(__('Log in')); ?>

        </button>
    </div>
</form>
<?php /**PATH /var/www/html/resources/views/auth/login.blade.php ENDPATH**/ ?>