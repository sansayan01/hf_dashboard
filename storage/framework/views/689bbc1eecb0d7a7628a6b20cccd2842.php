

<?php $__env->startSection('title', 'Attendance Reports'); ?>
<?php $__env->startSection('header_title', 'Advanced Attendance Reports'); ?>

<?php $__env->startSection('content'); ?>
    <div class="p-6 space-y-6 max-w-7xl mx-auto">
        <!-- Filter Card -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 shadow-sm border border-slate-100 dark:border-slate-700">
            <form action="<?php echo e(route('attendance.reports')); ?>" method="GET" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Date Range -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Start
                            Date</label>
                        <input type="date" name="start_date" value="<?php echo e(request('start_date')); ?>"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-900 focus:ring-2 focus:ring-accent font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">End
                            Date</label>
                        <input type="date" name="end_date" value="<?php echo e(request('end_date')); ?>"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-900 focus:ring-2 focus:ring-accent font-bold">
                    </div>

                    <!-- User -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Filter
                            User</label>
                        <select name="user_id"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-900 focus:ring-2 focus:ring-accent font-bold">
                            <option value="">All Users</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($u->id); ?>" <?php echo e(request('user_id') == $u->id ? 'selected' : ''); ?>>
                                    <?php echo e($u->profile->full_name ?? $u->employee_id); ?> (<?php echo e($u->employee_id); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Designation -->
                    <div>
                        <label
                            class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Designation</label>
                        <select name="designation"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-900 focus:ring-2 focus:ring-accent font-bold">
                            <option value="">All Designations</option>
                            <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val); ?>" <?php echo e(request('designation') == $val ? 'selected' : ''); ?>><?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-white/10 dark:bg-slate-900 focus:ring-2 focus:ring-accent font-bold">
                            <option value="">All Statuses</option>
                            <option value="present" <?php echo e(request('status') == 'present' ? 'selected' : ''); ?>>Present</option>
                            <option value="absent" <?php echo e(request('status') == 'absent' ? 'selected' : ''); ?>>Absent</option>
                        </select>
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-4 items-center">
                    <button type="submit"
                        class="px-8 py-3 bg-accent text-white rounded-xl font-bold hover:shadow-lg transition flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span>Apply Filter</span>
                    </button>

                    <a href="<?php echo e(route('attendance.reports')); ?>"
                        class="px-8 py-3 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200 rounded-xl font-bold hover:bg-slate-200 transition">
                        Reset
                    </a>

                    <button type="button" onclick="exportCSV()"
                        class="px-8 py-3 bg-emerald-500 text-white rounded-xl font-bold hover:bg-emerald-600 transition shadow-lg shadow-emerald-500/20 flex items-center space-x-2 ml-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span>Export CSV</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Table -->
        <div
            class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-900/50">
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">User</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Designation
                            </th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Basic Inc.
                            </th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">TA</th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Activities
                            </th>
                            <th class="px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        <?php $__empty_1 = true; $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/20 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-sm font-bold text-slate-700 dark:text-slate-200"><?php echo e($a->date->format('d M, Y')); ?></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center text-accent font-bold text-xs uppercase"
                                            title="<?php echo e($a->user ? '' : 'User Deleted'); ?>">
                                            <?php echo e(substr($a->user->profile->full_name ?? ($a->user->employee_id ?? 'U'), 0, 1)); ?>

                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200 leading-none">
                                                <?php echo e($a->user->profile->full_name ?? ($a->user->employee_id ?? 'Deleted User')); ?>

                                            </p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">
                                                <?php echo e($a->user->employee_id ?? 'N/A'); ?>

                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-3 py-1 bg-slate-100 dark:bg-slate-900 text-[10px] font-black uppercase text-slate-500 rounded-full">
                                        <?php echo e($a->user ? str_replace('_', ' ', $a->user->designation) : 'N/A'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] font-black tracking-widest uppercase <?php echo e($a->status === 'present' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'); ?>">
                                        <?php echo e($a->status); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-600 dark:text-slate-400 text-sm">
                                    ₹<?php echo e(number_format($a->incentive_amount, 2)); ?></td>
                                <td class="px-6 py-4 font-bold text-slate-600 dark:text-slate-400 text-sm">
                                    ₹<?php echo e(number_format($a->ta_amount, 2)); ?></td>
                                <td class="px-6 py-4">
                                    <?php
                                        $activities = $a->medicines_amount + $a->pathology_amount + $a->membership_amount + $a->ots_amount;
                                    ?>
                                    <span
                                        class="text-sm font-bold <?php echo e($activities > 0 ? 'text-blue-500' : 'text-slate-400'); ?>">₹<?php echo e(number_format($activities, 2)); ?></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="text-sm font-black text-accent">₹<?php echo e(number_format($a->total_amount, 2)); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div
                                            class="w-16 h-16 bg-slate-50 dark:bg-slate-900 rounded-full flex items-center justify-center mb-4">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-bold uppercase tracking-wider text-xs">No attendance
                                            records found for these filters</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($attendances->hasPages()): ?>
                <div class="p-6 border-t border-slate-100 dark:border-slate-700">
                    <?php echo e($attendances->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function exportCSV() {
            // Get all form data as URL parameters
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            // Build the export URL
            const exportUrl = "<?php echo e(route('attendance.export-report')); ?>?" + params.toString();

            // Trigger download
            window.location.href = exportUrl;
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\attendance\report.blade.php ENDPATH**/ ?>