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
    }
    .login-card-wrapper {
        margin-left: -150px;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 login-card-wrapper">
            <div class="card border-0 shadow rounded-4">
                <div class="card-header bg-primary text-white py-3 border-0 rounded-top-4">
                    <h4 class="mb-0 text-center"><?php echo e(__('Verify Reset Code')); ?></h4>
                </div>

                <div class="card-body p-4">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?php echo e(route('password.code.verify.submit')); ?>">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="email" value="<?php echo e(session('email') ?? old('email')); ?>">

                        <div class="mb-4">
                            <label for="code" class="form-label text-muted">
                                <?php echo e(__('4-Digit Code')); ?>

                            </label>
                            <input id="code" type="text"
                                   class="form-control <?php $__errorArgs = ['code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?> text-center"
                                   name="code" value="<?php echo e(old('code')); ?>"
                                   required autocomplete="off" autofocus
                                   maxlength="4" pattern="\d{4}" inputmode="numeric"
                                   placeholder="Enter 4-digit code"
                                   style="letter-spacing: 0.5em; font-size: 1.2rem;">

                            <?php $__errorArgs = ['code'];
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

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold">
                                <?php echo e(__('Verify Code')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-focus and auto-advance for code input
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('code');
        if (codeInput) {
            codeInput.focus();

            // Auto-advance when 4 digits are entered
            codeInput.addEventListener('input', function() {
                if (this.value.length === 4) {
                    this.form.submit();
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\healthcare-system\resources\views/auth/passwords/verify-code.blade.php ENDPATH**/ ?>