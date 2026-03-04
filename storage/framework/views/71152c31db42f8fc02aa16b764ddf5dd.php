

<?php $__env->startSection('title', 'BIN Recovery'); ?>
<?php $__env->startSection('header_title', 'Data Recovery Bin'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-6 p-4 bg-warning/5 border border-warning/10 rounded-xl flex items-center space-x-3 text-warning">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-xs font-bold uppercase tracking-wide">Note: Records in the BIN will be permanently deleted
            automatically after 30 days.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h3 class="font-bold text-lg text-slate-800">Recently Deleted</h3>
            <p class="text-sm text-slate-500">Restore members back to active status.</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Member Detail
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Designation
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Deleted On
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Days Remaining
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php $__empty_1 = true; $__currentLoopData = $deletedUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $deletedAt = $u->deleted_at;
                            $daysPassed = $deletedAt->diffInDays(now());
                            $daysLeft = 30 - $daysPassed;
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold">
                                        <?php echo e(substr($u->profile?->full_name ?? 'Unknown', 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-400">
                                            <?php echo e($u->profile?->full_name ?? 'Unknown Profile'); ?></p>
                                        <p class="text-[10px] text-slate-300 font-bold uppercase"><?php echo e($u->employee_id); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-slate-50 text-slate-400 rounded-full text-[10px] font-black uppercase tracking-widest border border-slate-100">
                                    <?php echo e($u->getDesignationLabel()); ?>

                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-400 font-medium"><?php echo e($u->deleted_at->format('d M, Y')); ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="w-full max-w-[100px] h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-warning" style="width: <?php echo e(($daysLeft / 30) * 100); ?>%"></div>
                                </div>
                                <p class="text-[10px] font-bold text-slate-500 mt-1 uppercase"><?php echo e($daysLeft); ?> Days Left</p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <form action="<?php echo e(route('users.restore', $u->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                            class="px-4 py-1.5 bg-accent/10 text-accent text-[10px] font-black uppercase tracking-widest rounded-lg hover:bg-accent hover:text-white transition">
                                            Restore
                                        </button>
                                    </form>

                                    <?php if(auth()->user()->isSuperAdmin()): ?>
                                        <form action="<?php echo e(route('users.force-delete', $u->id)); ?>" method="POST"
                                            onsubmit="return confirm('PERMANENTLY DELETE? This cannot be undone!')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 text-danger hover:bg-danger/10 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="max-w-xs mx-auto text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    <p class="font-bold">BIN is empty.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\users\bin.blade.php ENDPATH**/ ?>