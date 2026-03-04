<?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <div class="flex space-x-4 relative group">
        <div class="flex-shrink-0 relative z-10">
            <?php
                $colorClass = match ($activity->action) {
                    'created', 'patient_registered', 'survey_created' => 'bg-emerald-500/10 text-emerald-500 ring-emerald-500/20',
                    'approved', 'patient_restored', 'restored' => 'bg-blue-500/10 text-blue-500 ring-blue-500/20',
                    'login' => 'bg-violet-500/10 text-violet-500 ring-violet-500/20',
                    'deleted', 'patient_deleted', 'permanently_deleted' => 'bg-rose-500/10 text-rose-500 ring-rose-500/20',
                    'updated', 'patient_updated', 'survey_updated' => 'bg-amber-500/10 text-amber-500 ring-amber-500/20',
                    'appointment_created', 'appointment_updated' => 'bg-indigo-500/10 text-indigo-500 ring-indigo-500/20',
                    'appointment_completed' => 'bg-success/10 text-success ring-success/20',
                    'appointment_missed' => 'bg-orange-500/10 text-orange-500 ring-orange-500/20',
                    default => 'bg-slate-500/10 text-slate-500 ring-slate-500/20'
                };
                $icon = match ($activity->action) {
                    'created', 'patient_registered', 'survey_created' => 'M12 4v16m8-8H4',
                    'approved', 'appointment_completed' => 'M5 13l4 4L19 7',
                    'login' => 'M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1',
                    'deleted', 'patient_deleted', 'permanently_deleted' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                    'updated', 'patient_updated', 'survey_updated', 'appointment_updated' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                    'patient_restored', 'restored' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
                    'appointment_created' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                    'appointment_missed' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    default => 'M12 8v4l3 3'
                };
            ?>
            <div
                class="w-12 h-12 rounded-2xl <?php echo e($colorClass); ?> ring-1 flex items-center justify-center shadow-lg transition-transform group-hover:scale-110">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($icon); ?>">
                    </path>
                </svg>
            </div>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-black text-slate-800 dark:text-white truncate tracking-tight">
                <?php echo e($activity->description); ?>

            </p>
            <p class="text-[11px] text-slate-400 dark:text-slate-500 font-bold mt-1">
                <?php echo e($activity->performedBy->profile->full_name ?? 'System'); ?> •
                <?php echo e($activity->created_at->diffForHumans()); ?>

            </p>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <div class="text-center py-10">
        <div
            class="w-16 h-16 bg-slate-50 dark:bg-white/5 text-slate-300 dark:text-slate-700 rounded-3xl flex items-center justify-center mx-auto mb-4 border border-slate-200/5">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <p class="text-slate-400 dark:text-slate-600 text-xs font-bold uppercase tracking-widest">Quiet Day
        </p>
    </div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/dashboard/partials/timeline_items.blade.php ENDPATH**/ ?>