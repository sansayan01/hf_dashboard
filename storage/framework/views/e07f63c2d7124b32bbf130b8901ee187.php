<div class="grid grid-cols-1 md:grid-cols-2 <?php echo e($user->isRO() ? 'lg:grid-cols-5' : 'lg:grid-cols-4'); ?> gap-6">
    <div class="summary-card p-6 flex flex-col items-center">
        <span class="text-emerald-600 text-3xl font-bold"><?php echo e($summary['present']); ?></span>
        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Present Days</span>
    </div>
    <div class="summary-card p-6 flex flex-col items-center">
        <span class="text-rose-600 text-3xl font-bold"><?php echo e($summary['absent']); ?></span>
        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Absent Days</span>
    </div>
    <div class="summary-card p-6 flex flex-col items-center">
        <span class="text-accent text-3xl font-bold">₹<?php echo e(number_format($summary['total_incentives'], 0)); ?></span>
        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Incentives</span>
    </div>
    <div class="summary-card p-6 flex flex-col items-center">
        <span class="text-amber-600 text-3xl font-bold">₹<?php echo e(number_format($summary['ta'], 0)); ?></span>
        <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">TA Earned</span>
    </div>
    <div
        class="summary-card p-6 flex flex-col <?php if(app()->getLocale() == 'en'): ?> lg:col-span-1 <?php endif; ?> items-center bg-accent/5 !border-accent/20">
        <span class="text-accent text-3xl font-extrabold">₹<?php echo e(number_format($summary['total'], 0)); ?></span>
        <span class="text-sm font-bold text-accent uppercase tracking-wider mt-2">Total Earned</span>
    </div>
</div>

<!-- Calendar Section -->
<div class="summary-card p-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center">
            <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight">
                    <?php echo e($targetDate->format('F Y')); ?>

                </h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Attendance Sheet</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <?php
                $prevMonth = $targetDate->copy()->subMonth();
                $nextMonth = $targetDate->copy()->addMonth();
            ?>
            <a href="<?php echo e(request()->fullUrlWithQuery(['month' => $prevMonth->month, 'year' => $prevMonth->year])); ?>"
                onclick="loadCalendar(event, this.href)"
                class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <a href="<?php echo e(request()->fullUrlWithQuery(['month' => now()->month, 'year' => now()->year])); ?>"
                onclick="loadCalendar(event, this.href)"
                class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-200 transition-all">
                Today
            </a>

            <a href="<?php echo e(request()->fullUrlWithQuery(['month' => $nextMonth->month, 'year' => $nextMonth->year])); ?>"
                onclick="loadCalendar(event, this.href)"
                class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>

    <div class="calendar-container">
        <?php
            $startOfMonth = $targetDate->copy()->startOfMonth();
            $endOfMonth = $targetDate->copy()->endOfMonth();
            $daysInMonth = $targetDate->daysInMonth;
            $startDay = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
            $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        ?>

        <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="calendar-day-label"><?php echo e($day); ?></div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <?php for($i = 0; $i < $startDay; $i++): ?>
            <div></div>
        <?php endfor; ?>

        <?php for($day = 1; $day <= $daysInMonth; $day++): ?>
            <?php
                $currentDate = $startOfMonth->copy()->addDays($day - 1);
                $dateStr = $currentDate->format('Y-m-d');

                // Robust matching: ensure we compare date strings
                $attendance = $allAttendances->first(function ($item) use ($dateStr) {
                    return $item->date->format('Y-m-d') === $dateStr;
                });

                $isToday = $currentDate->isToday();
                $isFuture = $currentDate->isFuture() && !$isToday;

                $statusClass = 'status-future';
                if ($attendance) {
                    $statusClass = $attendance->status === 'present' ? 'status-present' : 'status-absent';
                }
            ?>

            <div class="calendar-day <?php echo e($statusClass); ?> <?php echo e($isToday ? 'ring-4 ring-accent ring-offset-2' : ''); ?>"
                <?php if($attendance): ?>
                    onclick="showDetails('<?php echo e($currentDate->format('Y-m-d')); ?>', '<?php echo e($currentDate->format('d M Y')); ?>', '<?php echo e(ucfirst($attendance->status)); ?>', '<?php echo e($attendance->incentive_amount); ?>', '<?php echo e($attendance->ta_amount); ?>', '<?php echo e($attendance->medicines_amount); ?>', '<?php echo e($attendance->pathology_amount); ?>', '<?php echo e($attendance->membership_amount); ?>', '<?php echo e($attendance->ots_amount); ?>', '<?php echo e($attendance->total_amount); ?>', '<?php echo e($attendance->markedBy->profile->full_name ?? 'System'); ?>', '<?php echo e($attendance->created_at->format('H:i')); ?>', <?php echo e($user->id); ?>)"
                <?php elseif(!$isFuture): ?>
                    onclick="showDetails('<?php echo e($currentDate->format('Y-m-d')); ?>', '<?php echo e($currentDate->format('d M Y')); ?>', 'Pending/Absent', 0, 0, 0, 0, 0, 0, 0, 'N/A', 'N/A', <?php echo e($user->id); ?>)"
                <?php endif; ?>>
                <?php echo e($day); ?>

                <?php if($isToday): ?>
                    <span class="text-[8px] absolute top-1 font-black opacity-60">TODAY</span>
                <?php endif; ?>
                <?php if($attendance && $attendance->status === 'present'): ?>
                    <div class="indicator"></div>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
</div><?php /**PATH C:\xampp\htdocs\HF\resources\views/attendance/partials/calendar_content.blade.php ENDPATH**/ ?>