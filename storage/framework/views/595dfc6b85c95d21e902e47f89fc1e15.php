

<?php $__env->startSection('header_title', 'Expense Tracker'); ?>

<?php $__env->startSection('css'); ?>
    <style>
        /* ── Clean Premium Design System ── */
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }
        .dark .card {
            background: #1a1f2e;
            border-color: rgba(255,255,255,0.06);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2), 0 6px 20px rgba(0,0,0,0.15);
        }
        .card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.06), 0 8px 24px rgba(0,0,0,0.06);
        }
        .dark .card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.3), 0 10px 30px rgba(0,0,0,0.25);
        }

        .stat-value {
            font-variant-numeric: tabular-nums;
            font-weight: 800;
            font-size: 24px;
            color: #0f172a;
            letter-spacing: -0.02em;
        }
        .dark .stat-value {
            color: #f1f5f9;
        }

        .stat-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .chart-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.03);
            padding: 24px;
        }
        .dark .chart-card {
            background: #1a1f2e;
            border-color: rgba(255,255,255,0.06);
        }
        .chart-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .dark .chart-title {
            color: #e2e8f0;
        }
        .chart-subtitle {
            font-size: 11px;
            font-weight: 500;
            color: #94a3b8;
        }

        .filter-bar {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            padding: 20px 24px;
        }
        .dark .filter-bar {
            background: #1a1f2e;
            border-color: rgba(255,255,255,0.06);
        }
        .filter-input {
            width: 100%;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            outline: none;
            transition: all 0.2s ease;
        }
        .dark .filter-input {
            background: rgba(15,23,42,0.5);
            border-color: rgba(255,255,255,0.08);
            color: #e2e8f0;
        }
        .filter-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
        }
        .filter-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .data-table th {
            padding: 14px 20px;
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            text-align: left;
            background: #fafbfc;
            border-bottom: 1px solid #f1f5f9;
        }
        .dark .data-table th {
            background: rgba(15,23,42,0.4);
            border-bottom-color: rgba(255,255,255,0.04);
        }
        .data-table td {
            padding: 14px 20px;
            font-size: 13px;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }
        .dark .data-table td {
            border-bottom-color: rgba(255,255,255,0.03);
        }
        .data-table tbody tr {
            transition: background 0.15s ease;
        }
        .data-table tbody tr:hover {
            background: #f8fafc;
        }
        .dark .data-table tbody tr:hover {
            background: rgba(255,255,255,0.02);
        }

        .cat-badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            border: 1px solid;
        }

        .trend-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
        }
        .trend-up { background: #fef2f2; color: #dc2626; }
        .dark .trend-up { background: rgba(220,38,38,0.1); }
        .trend-down { background: #f0fdf4; color: #16a34a; }
        .dark .trend-down { background: rgba(22,163,74,0.1); }

        .activity-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            border-radius: 10px;
            transition: background 0.15s ease;
        }
        .activity-item:hover {
            background: #f8fafc;
        }
        .dark .activity-item:hover {
            background: rgba(255,255,255,0.03);
        }

        .pm-bar-track {
            height: 6px;
            background: #f1f5f9;
            border-radius: 3px;
            overflow: hidden;
        }
        .dark .pm-bar-track {
            background: rgba(255,255,255,0.06);
        }
        .pm-bar-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            background: #4f46e5;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(79,70,229,0.3);
        }
        .btn-primary:hover {
            background: #4338ca;
            box-shadow: 0 4px 12px rgba(79,70,229,0.35);
            transform: translateY(-1px);
        }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .dark .btn-secondary {
            background: #1a1f2e;
            border-color: rgba(255,255,255,0.06);
            color: #94a3b8;
        }
        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }

        /* ── Advanced Filter Controls ── */
        .filter-panel {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            overflow: hidden;
        }
        .dark .filter-panel {
            background: #1a1f2e;
            border-color: rgba(255,255,255,0.06);
        }

        .preset-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            background: #f1f5f9;
            border: 1px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            white-space: nowrap;
        }
        .dark .preset-pill {
            background: rgba(255,255,255,0.04);
            color: #94a3b8;
        }
        .preset-pill:hover {
            background: #e0e7ff;
            color: #4338ca;
        }
        .dark .preset-pill:hover {
            background: rgba(99,102,241,0.1);
            color: #818cf8;
        }
        .preset-pill.active {
            background: #4f46e5;
            color: #fff;
            border-color: #4f46e5;
            box-shadow: 0 1px 4px rgba(79,70,229,0.3);
        }
        .dark .preset-pill.active {
            background: #6366f1;
            border-color: #6366f1;
        }

        .active-filter-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            color: #4f46e5;
            background: #eef2ff;
            border: 1px solid #c7d2fe;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .dark .active-filter-tag {
            background: rgba(99,102,241,0.1);
            border-color: rgba(99,102,241,0.2);
            color: #818cf8;
        }
        .active-filter-tag:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }
        .dark .active-filter-tag:hover {
            background: rgba(220,38,38,0.1);
            border-color: rgba(220,38,38,0.2);
            color: #f87171;
        }

        .sort-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
        }
        .dark .sort-btn {
            background: rgba(15,23,42,0.5);
            border-color: rgba(255,255,255,0.06);
            color: #94a3b8;
        }
        .sort-btn:hover, .sort-btn.active {
            border-color: #6366f1;
            color: #4f46e5;
            background: #eef2ff;
        }
        .dark .sort-btn:hover, .dark .sort-btn.active {
            border-color: rgba(99,102,241,0.3);
            color: #818cf8;
            background: rgba(99,102,241,0.08);
        }

        .advanced-toggle {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #6366f1;
            background: transparent;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.15s ease;
        }
        .dark .advanced-toggle { color: #818cf8; }
        .advanced-toggle:hover { background: #eef2ff; }
        .dark .advanced-toggle:hover { background: rgba(99,102,241,0.08); }
        .advanced-toggle svg {
            transition: transform 0.2s ease;
        }
        .advanced-toggle.open svg {
            transform: rotate(180deg);
        }

        .adv-section {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
        }
        .adv-section.open {
            max-height: 300px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto pb-12">

        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('finances.index')); ?>" class="text-slate-400 hover:text-indigo-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Expense Tracker</h2>
                    <p class="text-xs text-slate-400 font-medium">Financial analytics & expense management</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('expenses.export', request()->query())); ?>" class="btn-secondary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export
                </a>
                <a href="<?php echo e(route('expenses.create')); ?>" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Add Expense
                </a>
            </div>
        </div>

        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
            <div class="card p-5">
                <div class="stat-label mb-2">Total Expenses</div>
                <div class="stat-value">₹<?php echo e(number_format($totalExpenses, 2)); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-1"><?php echo e($totalCount); ?> entries</div>
            </div>

            <div class="card p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="stat-label">This Month</span>
                    <?php if($monthChange != 0): ?>
                        <span class="trend-badge <?php echo e($monthChange > 0 ? 'trend-up' : 'trend-down'); ?>">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($monthChange > 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'); ?>"/>
                            </svg>
                            <?php echo e(abs($monthChange)); ?>%
                        </span>
                    <?php endif; ?>
                </div>
                <div class="stat-value">₹<?php echo e(number_format($thisMonthTotal, 2)); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-1"><?php echo e(now()->format('F Y')); ?></div>
            </div>

            <div class="card p-5">
                <div class="stat-label mb-2">Avg. Daily Spend</div>
                <div class="stat-value">₹<?php echo e(number_format($avgDailySpend, 2)); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-1">Last 30 days</div>
            </div>

            <div class="card p-5">
                <div class="stat-label mb-2">Top Category</div>
                <div class="text-lg font-bold text-slate-900 dark:text-white truncate"><?php echo e($topCategory->category ?? 'N/A'); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-1"><?php echo e($topCategory ? '₹' . number_format($topCategory->total, 2) : '—'); ?></div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 mb-7">
            <div class="lg:col-span-3 chart-card">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <div class="chart-title">Monthly Spending Trend</div>
                        <div class="chart-subtitle mt-0.5">Last 6 months</div>
                    </div>
                    <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400">
                        <span class="w-6 h-[3px] bg-indigo-500 rounded-full"></span> Spending
                    </div>
                </div>
                <div style="height: 220px;"><canvas id="monthlyTrendChart"></canvas></div>
            </div>

            <div class="lg:col-span-2 chart-card">
                <div class="mb-5">
                    <div class="chart-title">Category Breakdown</div>
                    <div class="chart-subtitle mt-0.5"><?php echo e($categoryBreakdown->count()); ?> categories</div>
                </div>
                <div class="flex items-center justify-center" style="height: 180px;"><canvas id="categoryDoughnutChart"></canvas></div>
                <?php if($categoryBreakdown->count() > 0): ?>
                    <div class="mt-4 space-y-2 max-h-[110px] overflow-y-auto">
                        <?php $pieColors = ['#4f46e5', '#7c3aed', '#a855f7', '#0891b2', '#059669', '#d97706', '#dc2626', '#db2777', '#64748b']; ?>
                        <?php $__currentLoopData = $categoryBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:<?php echo e($pieColors[$i % count($pieColors)]); ?>"></span>
                                    <span class="font-semibold text-slate-600 dark:text-slate-300 truncate max-w-[120px]"><?php echo e($cat->category); ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800 dark:text-white" style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($cat->total, 2)); ?></span>
                                    <span class="text-[10px] text-slate-400">(<?php echo e($cat->count); ?>)</span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">
            <div class="chart-card">
                <div class="chart-title mb-1">Payment Methods</div>
                <div class="chart-subtitle mb-5">Breakdown by type</div>
                <?php if($paymentBreakdown->count() > 0): ?>
                    <?php $maxPay = $paymentBreakdown->max('total'); ?>
                    <div class="space-y-5">
                        <?php $__currentLoopData = $paymentBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $pmBg = ['cash' => '#059669', 'upi' => '#7c3aed', 'bank_transfer' => '#2563eb', 'cheque' => '#d97706', 'other' => '#64748b'];
                                $bg = $pmBg[$pm->payment_method] ?? '#64748b';
                            ?>
                            <div>
                                <div class="flex justify-between mb-1.5">
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300"><?php echo e(\App\Models\Expense::PAYMENT_METHODS[$pm->payment_method] ?? $pm->payment_method); ?></span>
                                    <span class="text-xs font-bold text-slate-800 dark:text-white" style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($pm->total, 2)); ?></span>
                                </div>
                                <div class="pm-bar-track">
                                    <div class="pm-bar-fill" style="width:<?php echo e($maxPay > 0 ? ($pm->total / $maxPay) * 100 : 0); ?>%; background:<?php echo e($bg); ?>"></div>
                                </div>
                                <div class="text-[10px] text-slate-400 font-medium mt-1"><?php echo e($pm->count); ?> transactions</div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center h-32 text-slate-400 text-xs font-medium">No data yet</div>
                <?php endif; ?>
            </div>

            <div class="chart-card">
                <div class="chart-title mb-1">Daily Spending</div>
                <div class="chart-subtitle mb-5">Last 30 days</div>
                <div style="height: 170px;"><canvas id="dailySparkChart"></canvas></div>
            </div>

            <div class="chart-card">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <div class="chart-title">Recent Activity</div>
                        <div class="chart-subtitle mt-0.5">Latest entries</div>
                    </div>
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
                <?php if($recentExpenses->count() > 0): ?>
                    <div class="space-y-0.5">
                        <?php $__currentLoopData = $recentExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="activity-item">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-800 dark:text-white truncate"><?php echo e($recent->title); ?></p>
                                    <p class="text-[11px] text-slate-400 font-medium"><?php echo e($recent->category); ?> · <?php echo e($recent->expense_date->format('d M')); ?></p>
                                </div>
                                <span class="text-sm font-bold text-slate-900 dark:text-white ml-3 flex-shrink-0" style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($recent->amount, 2)); ?></span>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center h-32 text-slate-400 text-xs font-medium">No entries yet</div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
            <div class="card p-5">
                <div class="stat-label mb-2">This Week</div>
                <div class="stat-value text-xl">₹<?php echo e(number_format($thisWeekTotal, 2)); ?></div>
                <div class="flex items-center gap-1.5 mt-2">
                    <?php if($weekChange != 0): ?>
                        <span class="trend-badge <?php echo e($weekChange > 0 ? 'trend-up' : 'trend-down'); ?>" style="font-size:10px">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="<?php echo e($weekChange > 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'); ?>"/>
                            </svg>
                            <?php echo e(abs($weekChange)); ?>% vs last week
                        </span>
                    <?php else: ?>
                        <span class="text-[10px] text-slate-400">No change</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card p-5">
                <div class="stat-label mb-2">Projected Monthly</div>
                <div class="stat-value text-xl">₹<?php echo e(number_format($projectedMonthly, 2)); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-2">
                    Based on <?php echo e(now()->day); ?>/<?php echo e(now()->daysInMonth); ?> days pace
                </div>
            </div>

            <div class="card p-5">
                <div class="stat-label mb-2">Expense Frequency</div>
                <div class="stat-value text-xl"><?php echo e($expensesPerWeek); ?><span class="text-sm font-semibold text-slate-400">/wk</span></div>
                <div class="text-[11px] text-slate-400 font-medium mt-2">Avg. over last 3 months</div>
            </div>

            <div class="card p-5">
                <div class="stat-label mb-2">Budget Health</div>
                <?php
                    $burnPercent = $projectedMonthly > 0 && $thisMonthTotal > 0
                        ? round(($thisMonthTotal / $projectedMonthly) * 100)
                        : 0;
                    $burnColor = $burnPercent <= 50 ? '#059669' : ($burnPercent <= 80 ? '#d97706' : '#dc2626');
                ?>
                <div class="flex items-end gap-2">
                    <div class="stat-value text-xl" style="color:<?php echo e($burnColor); ?>"><?php echo e($burnPercent); ?>%</div>
                    <span class="text-xs font-medium text-slate-400 mb-1">of month elapsed</span>
                </div>
                <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full mt-3 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700" style="width:<?php echo e(min($burnPercent, 100)); ?>%; background:<?php echo e($burnColor); ?>"></div>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">
            
            <div class="chart-card">
                <div class="chart-title mb-1">Spending by Day of Week</div>
                <div class="chart-subtitle mb-5">All-time pattern analysis</div>
                <div style="height:180px"><canvas id="dayOfWeekChart"></canvas></div>
            </div>

            
            <div class="chart-card">
                <div class="chart-title mb-1">Top 5 Largest Expenses</div>
                <div class="chart-subtitle mb-4">Highest individual entries</div>
                <?php if($topExpenses->count() > 0): ?>
                    <?php $maxTop = $topExpenses->max('amount'); ?>
                    <div class="space-y-3.5">
                        <?php $__currentLoopData = $topExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ri => $te): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-3">
                                <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black
                                    <?php echo e($ri === 0 ? 'bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'); ?>">
                                    <?php echo e($ri + 1); ?>

                                </span>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[140px]"><?php echo e($te->title); ?></span>
                                        <span class="text-xs font-bold text-slate-900 dark:text-white ml-2" style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($te->amount, 2)); ?></span>
                                    </div>
                                    <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full bg-indigo-500" style="width:<?php echo e($maxTop > 0 ? ($te->amount/$maxTop)*100 : 0); ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-center h-32 text-slate-400 text-xs font-medium">No data yet</div>
                <?php endif; ?>
            </div>

            
            <div class="chart-card">
                <div class="chart-title mb-1">Category Comparison</div>
                <div class="chart-subtitle mb-5">Last 3 months radar</div>
                <div class="flex items-center justify-center" style="height:210px"><canvas id="categoryRadarChart"></canvas></div>
            </div>
        </div>

        
        <div class="filter-panel mb-5">
            <form method="GET" action="<?php echo e(route('expenses.index')); ?>" id="filterForm">
                
                <div class="px-5 pt-5 pb-4">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            <span class="text-sm font-bold text-slate-800 dark:text-white">Filters</span>
                            <?php
                                $activeCount = collect(['search','category','payment_method','date_from','date_to','date_preset','amount_min','amount_max'])
                                    ->filter(fn($k) => request()->filled($k))->count();
                            ?>
                            <?php if($activeCount > 0): ?>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400 rounded-md"><?php echo e($activeCount); ?> active</span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="advanced-toggle" id="advToggle" onclick="toggleAdvanced()">
                                Advanced
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <?php if($activeCount > 0): ?>
                                <a href="<?php echo e(route('expenses.index')); ?>" class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Clear All ×</a>
                            <?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <?php
                            $presets = [
                                '' => 'All Time',
                                'today' => 'Today',
                                '7d' => 'Last 7 Days',
                                '30d' => 'Last 30 Days',
                                '90d' => 'Last 90 Days',
                                'this_month' => 'This Month',
                                'last_month' => 'Last Month',
                                'this_year' => 'This Year',
                            ];
                        ?>
                        <?php $__currentLoopData = $presets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button type="button" class="preset-pill <?php echo e(request('date_preset', '') == $val ? 'active' : ''); ?>"
                                onclick="setPreset('<?php echo e($val); ?>')">
                                <?php echo e($label); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="date_preset" id="datePresetInput" value="<?php echo e(request('date_preset', '')); ?>">
                    </div>

                    
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[180px]">
                            <label class="filter-label">Search</label>
                            <div class="relative">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search title, description, ref..."
                                    class="filter-input" style="padding-left:34px">
                            </div>
                        </div>
                        <div class="min-w-[140px]">
                            <label class="filter-label">Category</label>
                            <select name="category" class="filter-input" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php $__currentLoopData = \App\Models\Expense::CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat); ?>" <?php echo e(request('category') == $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="min-w-[130px]">
                            <label class="filter-label">Payment</label>
                            <select name="payment_method" class="filter-input" onchange="this.form.submit()">
                                <option value="">All Methods</option>
                                <?php $__currentLoopData = \App\Models\Expense::PAYMENT_METHODS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(request('payment_method') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary" style="padding:8px 18px;font-size:12px">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            Apply
                        </button>
                    </div>
                </div>

                
                <div class="adv-section" id="advSection">
                    <div class="px-5 pb-5 pt-2 border-t border-slate-100 dark:border-white/5">
                        <div class="flex flex-wrap items-end gap-4">
                            <div class="min-w-[120px]">
                                <label class="filter-label">Min Amount (₹)</label>
                                <input type="number" name="amount_min" value="<?php echo e(request('amount_min')); ?>" placeholder="0" step="0.01" min="0"
                                    class="filter-input" style="font-variant-numeric:tabular-nums">
                            </div>
                            <div class="min-w-[120px]">
                                <label class="filter-label">Max Amount (₹)</label>
                                <input type="number" name="amount_max" value="<?php echo e(request('amount_max')); ?>" placeholder="∞" step="0.01" min="0"
                                    class="filter-input" style="font-variant-numeric:tabular-nums">
                            </div>
                            <div class="min-w-[130px]">
                                <label class="filter-label">Custom From</label>
                                <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>" class="filter-input">
                            </div>
                            <div class="min-w-[130px]">
                                <label class="filter-label">Custom To</label>
                                <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>" class="filter-input">
                            </div>
                            <div class="min-w-[130px]">
                                <label class="filter-label">Sort By</label>
                                <select name="sort_by" class="filter-input">
                                    <option value="expense_date" <?php echo e(request('sort_by','expense_date') == 'expense_date' ? 'selected' : ''); ?>>Date</option>
                                    <option value="amount" <?php echo e(request('sort_by') == 'amount' ? 'selected' : ''); ?>>Amount</option>
                                    <option value="title" <?php echo e(request('sort_by') == 'title' ? 'selected' : ''); ?>>Title</option>
                                    <option value="category" <?php echo e(request('sort_by') == 'category' ? 'selected' : ''); ?>>Category</option>
                                    <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>>Created</option>
                                </select>
                            </div>
                            <div class="min-w-[100px]">
                                <label class="filter-label">Direction</label>
                                <select name="sort_dir" class="filter-input">
                                    <option value="desc" <?php echo e(request('sort_dir','desc') == 'desc' ? 'selected' : ''); ?>>Newest First</option>
                                    <option value="asc" <?php echo e(request('sort_dir') == 'asc' ? 'selected' : ''); ?>>Oldest First</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <?php if($activeCount > 0): ?>
                    <div class="px-5 py-3 border-t border-slate-100 dark:border-white/5 flex flex-wrap items-center gap-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Active:</span>
                        <?php if(request()->filled('search')): ?>
                            <a href="<?php echo e(route('expenses.index', array_diff_key(request()->query(), ['search'=>'']))); ?>" class="active-filter-tag">
                                Search: "<?php echo e(Str::limit(request('search'), 15)); ?>" ×
                            </a>
                        <?php endif; ?>
                        <?php if(request()->filled('category')): ?>
                            <a href="<?php echo e(route('expenses.index', array_diff_key(request()->query(), ['category'=>'']))); ?>" class="active-filter-tag">
                                <?php echo e(request('category')); ?> ×
                            </a>
                        <?php endif; ?>
                        <?php if(request()->filled('payment_method')): ?>
                            <a href="<?php echo e(route('expenses.index', array_diff_key(request()->query(), ['payment_method'=>'']))); ?>" class="active-filter-tag">
                                <?php echo e(\App\Models\Expense::PAYMENT_METHODS[request('payment_method')] ?? request('payment_method')); ?> ×
                            </a>
                        <?php endif; ?>
                        <?php if(request()->filled('date_preset')): ?>
                            <a href="<?php echo e(route('expenses.index', array_diff_key(request()->query(), ['date_preset'=>'']))); ?>" class="active-filter-tag">
                                <?php echo e($presets[request('date_preset')] ?? request('date_preset')); ?> ×
                            </a>
                        <?php endif; ?>
                        <?php if(request()->filled('amount_min')): ?>
                            <a href="<?php echo e(route('expenses.index', array_diff_key(request()->query(), ['amount_min'=>'']))); ?>" class="active-filter-tag">
                                Min: ₹<?php echo e(request('amount_min')); ?> ×
                            </a>
                        <?php endif; ?>
                        <?php if(request()->filled('amount_max')): ?>
                            <a href="<?php echo e(route('expenses.index', array_diff_key(request()->query(), ['amount_max'=>'']))); ?>" class="active-filter-tag">
                                Max: ₹<?php echo e(request('amount_max')); ?> ×
                            </a>
                        <?php endif; ?>
                        <span class="ml-auto text-[10px] font-bold text-slate-400"><?php echo e($expenses->total()); ?> results</span>
                    </div>
                <?php endif; ?>
            </form>
        </div>

        
        <div class="card overflow-hidden">
            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th class="text-right">Amount</th>
                            <th>Payment</th>
                            <th class="text-center">Receipt</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $expenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $expense): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <span class="font-semibold text-slate-800 dark:text-white"><?php echo e($expense->expense_date->format('d M')); ?></span>
                                    <span class="block text-[10px] text-slate-400"><?php echo e($expense->expense_date->format('Y')); ?></span>
                                </td>
                                <td>
                                    <p class="font-semibold text-slate-800 dark:text-white"><?php echo e($expense->title); ?></p>
                                    <?php if($expense->description): ?>
                                        <p class="text-[11px] text-slate-400 truncate max-w-[200px]"><?php echo e($expense->description); ?></p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php
                                        $catStyles = [
                                            'Office Supplies' => 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                                            'Travel' => 'bg-violet-50 text-violet-600 border-violet-200 dark:bg-violet-500/10 dark:text-violet-400 dark:border-violet-500/20',
                                            'Event/Camp' => 'bg-emerald-50 text-emerald-600 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                                            'Salary/Stipend' => 'bg-indigo-50 text-indigo-600 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                                            'Utilities' => 'bg-amber-50 text-amber-600 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                            'Medical Supplies' => 'bg-red-50 text-red-600 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
                                            'Printing' => 'bg-cyan-50 text-cyan-600 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-400 dark:border-cyan-500/20',
                                            'Food & Refreshments' => 'bg-orange-50 text-orange-600 border-orange-200 dark:bg-orange-500/10 dark:text-orange-400 dark:border-orange-500/20',
                                        ];
                                        $cs = $catStyles[$expense->category] ?? 'bg-slate-50 text-slate-600 border-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:border-slate-500/20';
                                    ?>
                                    <span class="cat-badge <?php echo e($cs); ?>"><?php echo e($expense->category); ?></span>
                                </td>
                                <td class="text-right">
                                    <span class="font-bold text-slate-900 dark:text-white" style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($expense->amount, 2)); ?></span>
                                </td>
                                <td>
                                    <span class="text-xs font-semibold text-slate-600 dark:text-slate-300"><?php echo e(\App\Models\Expense::PAYMENT_METHODS[$expense->payment_method] ?? $expense->payment_method); ?></span>
                                    <?php if($expense->reference_number): ?>
                                        <span class="block text-[10px] text-slate-400">Ref: <?php echo e($expense->reference_number); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if($expense->receipt_path): ?>
                                        <a href="<?php echo e(route('storage.bridge', ['path' => $expense->receipt_path])); ?>" target="_blank"
                                            class="icon-btn bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-500/20">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-slate-300 dark:text-slate-600">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="<?php echo e(route('expenses.edit', $expense)); ?>"
                                            class="icon-btn bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-500/10 dark:hover:text-indigo-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form action="<?php echo e(route('expenses.destroy', $expense)); ?>" method="POST" onsubmit="return confirm('Delete this expense?');">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="icon-btn bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center" style="padding:48px 20px">
                                    <div class="w-14 h-14 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-7 h-7 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-400">No expenses recorded yet</p>
                                    <p class="text-xs text-slate-400 mt-1">Click "Add Expense" to get started</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($expenses->hasPages()): ?>
                <div class="px-5 py-4 border-t border-slate-100 dark:border-white/5">
                    <?php echo e($expenses->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
        const textColor = isDark ? '#94a3b8' : '#64748b';

        Chart.defaults.font.family = "'Inter','system-ui',sans-serif";
        Chart.defaults.font.weight = 500;
        Chart.defaults.color = textColor;

        const tooltipStyle = {
            backgroundColor: isDark ? '#1e293b' : '#fff',
            titleColor: isDark ? '#e2e8f0' : '#1e293b',
            bodyColor: isDark ? '#94a3b8' : '#64748b',
            borderColor: isDark ? 'rgba(255,255,255,0.08)' : '#e5e7eb',
            borderWidth: 1,
            cornerRadius: 10,
            padding: 10,
            displayColors: false,
            titleFont: { weight: 700, size: 12 },
            bodyFont: { weight: 500, size: 12 },
        };

        // Monthly Trend
        const trendCtx = document.getElementById('monthlyTrendChart');
        if (trendCtx) {
            const trendData = <?php echo json_encode($monthlyTrend, 15, 512) ?>;
            const trendGradient = trendCtx.getContext('2d').createLinearGradient(0, 0, 0, 220);
            trendGradient.addColorStop(0, 'rgba(79,70,229,0.15)');
            trendGradient.addColorStop(1, 'rgba(79,70,229,0.01)');

            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.short),
                    datasets: [{
                        data: trendData.map(d => d.total),
                        borderColor: '#4f46e5',
                        backgroundColor: trendGradient,
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: isDark ? '#1a1f2e' : '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { ...tooltipStyle, callbacks: { label: ctx => '₹' + ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 }) } }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: 600 } } },
                        y: {
                            grid: { color: gridColor },
                            border: { display: false },
                            ticks: { font: { size: 10 }, callback: v => '₹' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v) }
                        }
                    }
                }
            });
        }

        // Category Doughnut
        const doughnutCtx = document.getElementById('categoryDoughnutChart');
        if (doughnutCtx) {
            const catData = <?php echo json_encode($categoryBreakdown, 15, 512) ?>;
            const pieColors = ['#4f46e5','#7c3aed','#a855f7','#0891b2','#059669','#d97706','#dc2626','#db2777','#64748b'];
            if (catData.length > 0) {
                new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: catData.map(d => d.category),
                        datasets: [{ data: catData.map(d => d.total), backgroundColor: pieColors.slice(0, catData.length), borderWidth: 0, hoverOffset: 6, spacing: 2 }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { ...tooltipStyle, callbacks: { label: ctx => ctx.label + ': ₹' + ctx.parsed.toLocaleString('en-IN', { minimumFractionDigits: 2 }) } }
                        }
                    }
                });
            }
        }

        // Daily Sparkline
        const sparkCtx = document.getElementById('dailySparkChart');
        if (sparkCtx) {
            const dailyData = <?php echo json_encode($dailySpending, 15, 512) ?>;
            new Chart(sparkCtx, {
                type: 'bar',
                data: {
                    labels: dailyData.map(d => d.date),
                    datasets: [{
                        data: dailyData.map(d => d.total),
                        backgroundColor: dailyData.map(d => d.total > 0 ? (isDark ? 'rgba(129,140,248,0.5)' : 'rgba(79,70,229,0.4)') : (isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.03)')),
                        borderRadius: 3,
                        borderSkipped: false,
                        barPercentage: 0.65,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { ...tooltipStyle, callbacks: { title: items => dailyData[items[0].dataIndex].full, label: ctx => '₹' + ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 }) } }
                    },
                    scales: {
                        x: { display: false },
                        y: { grid: { color: gridColor }, border: { display: false }, ticks: { font: { size: 10 }, callback: v => '₹' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v) } }
                    }
                }
            });
        }

        // Day-of-Week Chart
        const dowCtx = document.getElementById('dayOfWeekChart');
        if (dowCtx) {
            const dowData = <?php echo json_encode($dayOfWeekSpending, 15, 512) ?>;
            const dowMax = Math.max(...dowData.map(d => d.total));
            new Chart(dowCtx, {
                type: 'bar',
                data: {
                    labels: dowData.map(d => d.day),
                    datasets: [{
                        data: dowData.map(d => d.total),
                        backgroundColor: dowData.map(d => {
                            const intensity = dowMax > 0 ? (d.total / dowMax) : 0;
                            return isDark
                                ? `rgba(129,140,248,${0.15 + intensity * 0.5})`
                                : `rgba(79,70,229,${0.15 + intensity * 0.5})`;
                        }),
                        borderRadius: 6,
                        borderSkipped: false,
                        barPercentage: 0.6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            ...tooltipStyle,
                            callbacks: {
                                title: items => dowData[items[0].dataIndex].day,
                                label: ctx => '₹' + ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 }) + ' (' + dowData[ctx.dataIndex].count + ' entries)'
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 11, weight: 600 } } },
                        y: {
                            grid: { color: gridColor },
                            border: { display: false },
                            ticks: { font: { size: 10 }, callback: v => '₹' + (v >= 1000 ? (v/1000).toFixed(0) + 'k' : v) }
                        }
                    }
                }
            });
        }

        // Category Radar Chart
        const radarCtx = document.getElementById('categoryRadarChart');
        if (radarCtx) {
            const catTrend = <?php echo json_encode($categoryMonthlyTrend, 15, 512) ?>;
            const allCats = <?php echo json_encode($allCategories, 15, 512) ?>;
            const radarColors = ['rgba(79,70,229,0.7)', 'rgba(139,92,246,0.7)', 'rgba(14,165,233,0.7)'];
            const radarBg = ['rgba(79,70,229,0.1)', 'rgba(139,92,246,0.1)', 'rgba(14,165,233,0.1)'];

            if (allCats.length > 0 && catTrend.length > 0) {
                new Chart(radarCtx, {
                    type: 'radar',
                    data: {
                        labels: allCats,
                        datasets: catTrend.map((month, idx) => ({
                            label: month.label,
                            data: allCats.map(cat => month[cat] || 0),
                            borderColor: radarColors[idx % radarColors.length],
                            backgroundColor: radarBg[idx % radarBg.length],
                            borderWidth: 2,
                            pointRadius: 3,
                            pointBackgroundColor: radarColors[idx % radarColors.length],
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    boxWidth: 8,
                                    boxHeight: 8,
                                    borderRadius: 4,
                                    useBorderRadius: true,
                                    font: { size: 10, weight: 600 },
                                    padding: 12,
                                }
                            },
                            tooltip: {
                                ...tooltipStyle,
                                callbacks: {
                                    label: ctx => ctx.dataset.label + ': ₹' + ctx.parsed.r
                                        ? ctx.dataset.label + ': ₹' + ctx.raw.toLocaleString('en-IN', { minimumFractionDigits: 2 })
                                        : ctx.dataset.label + ': ₹0'
                                }
                            }
                        },
                        scales: {
                            r: {
                                angleLines: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)' },
                                grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)' },
                                pointLabels: { font: { size: 9, weight: 600 }, color: textColor },
                                ticks: { display: false },
                            }
                        }
                    }
                });
            }
        }
    });

    // ── Filter Controls ──
    function setPreset(val) {
        document.getElementById('datePresetInput').value = val;
        document.querySelectorAll('.preset-pill').forEach(p => p.classList.remove('active'));
        event.target.classList.add('active');
        document.getElementById('filterForm').submit();
    }

    function toggleAdvanced() {
        const section = document.getElementById('advSection');
        const toggle = document.getElementById('advToggle');
        section.classList.toggle('open');
        toggle.classList.toggle('open');
    }

    // Auto-open advanced if advanced filters are active
    document.addEventListener('DOMContentLoaded', function() {
        const hasAdvanced = <?php echo json_encode(
            request()->filled('amount_min') || request()->filled('amount_max') ||
            request()->filled('date_from') || request()->filled('date_to') ||
            (request()->filled('sort_by') && request('sort_by') !== 'expense_date') ||
            (request()->filled('sort_dir') && request('sort_dir') !== 'desc')
        , 15, 512) ?>;
        if (hasAdvanced) {
            document.getElementById('advSection')?.classList.add('open');
            document.getElementById('advToggle')?.classList.add('open');
        }
    });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\expenses\index.blade.php ENDPATH**/ ?>