<?php $__env->startSection('content'); ?>
<style>
    body {
        background: url('/images/login-bg.jpg') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .card {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(5px);
        border: none;
    }
    /* Consistent left shift */
    .reset-card-wrapper {
        margin-left: -150px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 reset-card-wrapper">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white py-3 border-0 rounded-top-4">
                    <h4 class="mb-0 text-center"><?php echo e(__('Reset Your Password')); ?></h4>
                </div>

                <div class="card-body p-4">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(url('/password/reset')); ?>" id="password-reset-form">
                        <?php echo csrf_field(); ?>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="email" value="<?php echo e($email ?? old('email')); ?>">
                        <input type="hidden" name="token" value="<?php echo e($token ?? old('token')); ?>">

                        <!-- New Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label text-muted"><?php echo e(__('New Password')); ?></label>
                            <input id="password" type="password"
                                   class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   name="password" required autocomplete="new-password"
                                   minlength="8"
                                   placeholder="Enter new password">

                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Confirm New Password -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label text-muted"><?php echo e(__('Confirm Password')); ?></label>
                            <input id="password-confirm" type="password"
                                   class="form-control <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Confirm new password">

                            <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Display general errors -->
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <?php echo e(__('Reset Password')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('password-reset-form');

            form.addEventListener('submit', function(e) {
                // Client-side validation
                const password = document.getElementById('password').value;
                const confirmPassword = document.getElementById('password-confirm').value;

                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('Passwords do not match!');
                    return false;
                }

                if (password.length < 8) {
                    e.preventDefault();
                    alert('Password must be at least 8 characters!');
                    return false;
                }

                return true;
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\healthcare-system\resources\views/auth/passwords/reset.blade.php ENDPATH**/ ?>