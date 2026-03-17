

<?php $__env->startSection('title', 'Patient BIN Recovery'); ?>
<?php $__env->startSection('header_title', 'Patient Recovery Bin'); ?>

<?php $__env->startSection('content'); ?>
    <div
        class="mb-6 p-4 bg-amber-500/5 border border-amber-500/10 rounded-2xl flex items-center space-x-3 text-amber-600 dark:text-amber-400">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-xs font-black uppercase tracking-widest">Note: Records in the BIN will be permanently deleted
            automatically after 30 days.</p>
    </div>

    <div
        class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 dark:border-white/5 flex items-center justify-between">
            <div>
                <h3 class="font-black text-xl text-slate-800 dark:text-white uppercase tracking-tight">Recently Deleted
                    Patients</h3>
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mt-1">Found <?php echo e($patients->total()); ?>

                    records pending deletion</p>
            </div>
            <a href="<?php echo e(route('patients.index')); ?>"
                class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 transition-all flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Registry
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-white/2 decoration-slate-200">
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Patient Detail
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Recorded By
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Deleted On
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Retention
                            Status</th>
                        <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                    <?php $__empty_1 = true; $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $deletedAt = $patient->deleted_at;
                            $daysPassed = $deletedAt->isPast() ? $deletedAt->diffInDays(now()) : 0;
                            $daysLeft = max(0, 30 - $daysPassed);
                            $percentage = ($daysLeft / 30) * 100;
                        ?>
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/2 transition-colors">
                            <td class="px-8 py-6">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 dark:text-slate-500 flex items-center justify-center font-black">
                                        <?php echo e(substr($patient->full_name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800 dark:text-white"><?php echo e($patient->full_name); ?>

                                        </p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight italic">ID:
                                            <?php echo e($patient->patient_id); ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                    <?php echo e($patient->creator->profile->full_name ?? 'System'); ?></p>
                                <p class="text-[10px] text-slate-400 uppercase font-black tracking-tighter">
                                    <?php echo e($patient->creator->employee_id ?? 'N/A'); ?></p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-sm font-black text-slate-700 dark:text-slate-200">
                                    <?php echo e($patient->deleted_at->format('d M, Y')); ?></p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">
                                    <?php echo e($patient->deleted_at->format('h:i A')); ?></p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="w-full max-w-[120px]">
                                    <div
                                        class="h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden mb-1.5">
                                        <div class="h-full <?php echo e($daysLeft < 7 ? 'bg-rose-500 shadow-[0_0_8px_rgba(244,63,94,0.4)]' : ($daysLeft < 15 ? 'bg-amber-500' : 'bg-emerald-500')); ?> transition-all"
                                            style="width: <?php echo e($percentage); ?>%"></div>
                                    </div>
                                    <p
                                        class="text-[10px] font-black <?php echo e($daysLeft < 7 ? 'text-rose-500' : 'text-slate-500'); ?> uppercase tracking-widest">
                                        <?php echo e($daysLeft); ?> Days Remaining</p>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end space-x-3">
                                    <form action="<?php echo e(route('patients.restore', $patient->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit"
                                            class="px-4 py-2 bg-accent/10 text-accent text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-accent hover:text-white transition-all border border-accent/20">
                                            Restore Record
                                        </button>
                                    </form>

                                    <?php if(auth()->user()->isSuperAdmin()): ?>
                                        <form action="<?php echo e(route('patients.force-delete', $patient->id)); ?>" method="POST"
                                            id="force-delete-<?php echo e($patient->id); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="button"
                                                onclick="confirmPermanentDelete(<?php echo e($patient->id); ?>, '<?php echo e($patient->full_name); ?>')"
                                                class="p-2.5 text-rose-500 bg-rose-500/10 hover:bg-rose-500 hover:text-white rounded-xl transition-all border border-rose-500/10">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-32 text-center">
                                <div class="max-w-xs mx-auto">
                                    <div
                                        class="w-20 h-20 bg-slate-50 dark:bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </div>
                                    <h4 class="text-slate-800 dark:text-white font-black text-lg uppercase tracking-tight">Your
                                        Bin is Empty</h4>
                                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest mt-2">No recently
                                        deleted records found.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($patients->hasPages()): ?>
            <div class="p-8 border-t border-slate-50 dark:border-white/5">
                <?php echo e($patients->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        function confirmPermanentDelete(id, name) {
            Swal.fire({
                title: 'PERMANENT DESTRUCTION',
                text: "You are about to permanently delete " + name + "'s record. This will erase all history and cannot be reversed!",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#F43F5E',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Yes, Destroy Forever',
                cancelButtonText: 'Cancel',
                background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1E293B',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('force-delete-' + id).submit();
                }
            })
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\patients\bin.blade.php ENDPATH**/ ?>