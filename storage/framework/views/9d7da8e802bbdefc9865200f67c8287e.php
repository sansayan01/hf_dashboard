

<?php $__env->startSection('title', 'Mark Team Attendance'); ?>
<?php $__env->startSection('header_title', 'Mark Daily Attendance'); ?>

<?php $__env->startSection('content'); ?>
    <div class="p-6 max-w-5xl mx-auto overflow-y-auto h-full pb-20">
        <div
            class="bg-white dark:bg-darkcard rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-white/5">
            <div
                class="p-8 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white">Daily Roster</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1">Marking attendance for
                        <?php echo e(now()->format('l, d M Y')); ?>

                    </p>
                </div>
                <div class="px-4 py-2 bg-accent/10 rounded-2xl border border-accent/20">
                    <span class="text-accent font-bold text-sm"><?php echo e(now()->format('d M, Y')); ?></span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="bg-slate-50/50 dark:bg-white/5 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-widest font-bold">
                            <th class="px-8 py-4">Relationship Officer</th>
                            <th class="px-8 py-4 text-center">Incentive Plan</th>
                            <th class="px-8 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        <?php $__empty_1 = true; $__currentLoopData = $ros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ro): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $todayAttendance = $ro->attendances()->whereDate('date', now()->toDateString())->first();
                                $config = $ro->getCurrentIncentive();
                            ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-11 h-11 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-accent shadow-inner">
                                            <?php echo e(substr($ro->profile->full_name ?? $ro->employee_id, 0, 1)); ?>

                                        </div>
                                        <div>
                                            <p
                                                class="font-bold text-slate-800 dark:text-white group-hover:text-accent transition-colors">
                                                <?php echo e($ro->profile->full_name ?? 'N/A'); ?>

                                            </p>
                                            <div class="flex items-center space-x-2">
                                                <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">
                                                    <?php echo e($ro->employee_id); ?></span>
                                                <span
                                                    class="text-[9px] font-black px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 tracking-tighter uppercase">
                                                    <?php echo e($ro->getDesignationLabel()); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <?php if($config): ?>
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="text-sm font-bold text-slate-700 dark:text-slate-200">₹<?php echo e(number_format($config->incentive_amount)); ?>

                                                + ₹<?php echo e(number_format($config->ta_amount)); ?> TA</span>
                                            <span
                                                class="text-[10px] text-emerald-500 font-bold uppercase tracking-wider mt-1">Active
                                                Plan</span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-rose-400 text-xs font-bold italic">No config set</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center space-x-3">
                                        <?php if($todayAttendance && $todayAttendance->isLocked()): ?>
                                            <span
                                                class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest <?php echo e($todayAttendance->status === 'present' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'); ?>">
                                                <?php echo e($todayAttendance->status); ?> (LOCKED)
                                            </span>
                                        <?php else: ?>
                                            <button onclick="markAttendance(<?php echo e($ro->id); ?>, 'present')"
                                                class="attendance-btn-<?php echo e($ro->id); ?> px-5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo e(($todayAttendance && $todayAttendance->status === 'present') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white'); ?>">
                                                Present
                                            </button>
                                            <button onclick="markAttendance(<?php echo e($ro->id); ?>, 'absent')"
                                                class="attendance-btn-<?php echo e($ro->id); ?> px-5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo e(($todayAttendance && $todayAttendance->status === 'absent') ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30' : 'bg-rose-100 text-rose-600 hover:bg-rose-500 hover:text-white'); ?>">
                                                Absent
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="px-8 py-12 text-center text-slate-400 italic font-medium">No Relationship
                                    Officers found in your team for attendance marking.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        async function markAttendance(roId, status) {
            try {
                const response = await fetch('<?php echo e(route("attendance.store")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({
                        user_id: roId,
                        status: status,
                        date: '<?php echo e(now()->toDateString()); ?>'
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true,
                        background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                        color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Operation Failed',
                    text: error.message,
                    background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                });
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\attendance\mark.blade.php ENDPATH**/ ?>