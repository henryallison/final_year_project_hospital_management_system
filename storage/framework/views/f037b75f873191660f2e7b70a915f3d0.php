<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>System Logs</h5>
                <form action="<?php echo e(url('/admin/logs/clear')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you sure you want to clear all logs?')">
                        <i class="fas fa-trash"></i> Clear Logs
                    </button>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="20%">Timestamp</th>
                            <th>Log Entry</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="<?php if(str_contains($log, 'ERROR')): ?> table-danger <?php elseif(str_contains($log, 'WARNING')): ?> table-warning <?php endif; ?>">
                                <td>
                                    <?php if(preg_match('/\[(.*?)\]/', $log, $matches)): ?>
                                        <?php echo e($matches[1]); ?>

                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <code><?php echo e($log); ?></code>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="2" class="text-center">No logs found</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\healthcare-system\resources\views/admin/logs/index.blade.php ENDPATH**/ ?>