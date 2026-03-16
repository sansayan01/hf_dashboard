

<?php $__env->startSection('header_title', 'Expense Tracker'); ?>

<?php $__env->startSection('css'); ?>
    <style>
        /* ── Clean Premium Design System ── */
        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.03);
            transition: box-shadow 0.25s ease, transform 0.25s ease;
        }

        .dark .card {
            background: #1a1f2e;
            border-color: rgba(255, 255, 255, 0.06);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.15);
        }

        .card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06), 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .dark .card:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3), 0 10px 30px rgba(0, 0, 0, 0.25);
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.03);
            padding: 24px;
        }

        .dark .chart-card {
            background: #1a1f2e;
            border-color: rgba(255, 255, 255, 0.06);
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            padding: 20px 24px;
        }

        .dark .filter-bar {
            background: #1a1f2e;
            border-color: rgba(255, 255, 255, 0.06);
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
            background: rgba(15, 23, 42, 0.5);
            border-color: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
        }

        .filter-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
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
            background: rgba(15, 23, 42, 0.4);
            border-bottom-color: rgba(255, 255, 255, 0.04);
        }

        .data-table td {
            padding: 14px 20px;
            font-size: 13px;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
        }

        .dark .data-table td {
            border-bottom-color: rgba(255, 255, 255, 0.03);
        }

        .data-table tbody tr {
            transition: background 0.15s ease;
        }

        .data-table tbody tr:hover {
            background: #f8fafc;
        }

        .dark .data-table tbody tr:hover {
            background: rgba(255, 255, 255, 0.02);
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

        .trend-up {
            background: #fef2f2;
            color: #dc2626;
        }

        .dark .trend-up {
            background: rgba(220, 38, 38, 0.1);
        }

        .trend-down {
            background: #f0fdf4;
            color: #16a34a;
        }

        .dark .trend-down {
            background: rgba(22, 163, 74, 0.1);
        }

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
            background: rgba(255, 255, 255, 0.03);
        }

        .pm-bar-track {
            height: 6px;
            background: #f1f5f9;
            border-radius: 3px;
            overflow: hidden;
        }

        .dark .pm-bar-track {
            background: rgba(255, 255, 255, 0.06);
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
            box-shadow: 0 1px 3px rgba(79, 70, 229, 0.3);
        }

        .btn-primary:hover {
            background: #4338ca;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
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
            border-color: rgba(255, 255, 255, 0.06);
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        .dark .filter-panel {
            background: #1a1f2e;
            border-color: rgba(255, 255, 255, 0.06);
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
            background: rgba(255, 255, 255, 0.04);
            color: #94a3b8;
        }

        .preset-pill:hover {
            background: #e0e7ff;
            color: #4338ca;
        }

        .dark .preset-pill:hover {
            background: rgba(99, 102, 241, 0.1);
            color: #818cf8;
        }

        .preset-pill.active {
            background: #4f46e5;
            color: #fff;
            border-color: #4f46e5;
            box-shadow: 0 1px 4px rgba(79, 70, 229, 0.3);
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
            background: rgba(99, 102, 241, 0.1);
            border-color: rgba(99, 102, 241, 0.2);
            color: #818cf8;
        }

        .active-filter-tag:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #dc2626;
        }

        .dark .active-filter-tag:hover {
            background: rgba(220, 38, 38, 0.1);
            border-color: rgba(220, 38, 38, 0.2);
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
            background: rgba(15, 23, 42, 0.5);
            border-color: rgba(255, 255, 255, 0.06);
            color: #94a3b8;
        }

        .sort-btn:hover,
        .sort-btn.active {
            border-color: #6366f1;
            color: #4f46e5;
            background: #eef2ff;
        }

        .dark .sort-btn:hover,
        .dark .sort-btn.active {
            border-color: rgba(99, 102, 241, 0.3);
            color: #818cf8;
            background: rgba(99, 102, 241, 0.08);
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

        .dark .advanced-toggle {
            color: #818cf8;
        }

        .advanced-toggle:hover {
            background: #eef2ff;
        }

        .dark .advanced-toggle:hover {
            background: rgba(99, 102, 241, 0.08);
        }

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
            max-height: 600px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto pb-12">

        
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-7">
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('finances.index')); ?>" class="text-slate-400 hover:text-indigo-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Expense Tracker</h2>
                    <p class="text-xs text-slate-400 font-medium">Financial analytics & expense management</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('expenses.export', request()->query())); ?>" class="btn-secondary" id="exportBtn">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export
                </a>
                <?php if(auth()->user()->hasFinancePermission('edit')): ?>
                    <a href="<?php echo e(route('expenses.create')); ?>" class="btn-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Expense
                    </a>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
            <div class="card p-5">
                <div class="stat-label mb-2">Total Expenses</div>
                <div class="stat-value" id="totalExpensesStat">₹<?php echo e(number_format($totalExpenses, 2)); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-1" id="totalCountStat"><?php echo e($totalCount); ?> entries</div>
            </div>

            <div class="card p-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="stat-label">This Month</span>
                    <span id="monthChangeContainer">
                        <?php if($monthChange != 0): ?>
                            <span class="trend-badge <?php echo e($monthChange > 0 ? 'trend-up' : 'trend-down'); ?>">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="<?php echo e($monthChange > 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'); ?>" />
                                </svg>
                                <?php echo e(abs($monthChange)); ?>%
                            </span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="stat-value" id="thisMonthTotalStat">₹<?php echo e(number_format($thisMonthTotal, 2)); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-1">vs last month</div>
            </div>

            <div class="card p-5">
                <div class="stat-label mb-2">Average Daily</div>
                <div class="stat-value" id="avgDailyStat">₹<?php echo e(number_format($avgDailySpend, 2)); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-1">Per day spending</div>
            </div>

            <div class="card p-5">
                <div class="stat-label mb-2">Top Spend Category</div>
                <div class="stat-value" style="font-size:18px" id="topCategoryStat">
                    <?php echo e($topCategory ? $topCategory->category : 'N/A'); ?>

                </div>
                <div class="text-[11px] text-slate-400 font-medium mt-1">
                    <?php echo e($topCategory ? '₹' . number_format($topCategory->total, 2) : '—'); ?>

                </div>
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
                <div class="flex items-center justify-center" style="height: 180px;"><canvas
                        id="categoryDoughnutChart"></canvas></div>
                <?php if($categoryBreakdown->count() > 0): ?>
                    <div class="mt-4 space-y-2 max-h-[110px] overflow-y-auto">
                        <?php $pieColors = ['#4f46e5', '#7c3aed', '#a855f7', '#0891b2', '#059669', '#d97706', '#dc2626', '#db2777', '#64748b']; ?>
                        <?php $__currentLoopData = $categoryBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full flex-shrink-0"
                                        style="background:<?php echo e($pieColors[$i % count($pieColors)]); ?>"></span>
                                    <span
                                        class="font-semibold text-slate-600 dark:text-slate-300 truncate max-w-[120px]"><?php echo e($cat->category); ?></span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800 dark:text-white"
                                        style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($cat->total, 2)); ?></span>
                                    <span class="text-[10px] text-slate-400">(<?php echo e($cat->count); ?>)</span>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-7">
            
            <div class="chart-card lg:col-span-2">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <div class="chart-title mb-1">Payment Distribution</div>
                        <div class="chart-subtitle">Spend by payment method</div>
                    </div>
                </div>
                <div class="space-y-5" id="paymentBreakdownList">
                    <?php $maxPay = $paymentBreakdown->max('total'); ?>
                    <?php $__currentLoopData = $paymentBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pay): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs font-bold text-slate-700 dark:text-slate-300"><?php echo e(\App\Models\Expense::PAYMENT_METHODS[$pay->payment_method] ?? $pay->payment_method); ?></span>
                                <span class="text-xs font-black text-slate-900 dark:text-white"
                                    style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($pay->total, 2)); ?></span>
                            </div>
                            <div class="payment-bar-bg">
                                <div class="payment-bar-fill <?php echo e($pay->payment_method == 'Bank Transfer' ? 'bg-indigo-500' : ($pay->payment_method == 'Cash' ? 'bg-emerald-500' : 'bg-slate-400')); ?>"
                                    style="width:<?php echo e($maxPay > 0 ? ($pay->total / $maxPay) * 100 : 0); ?>%"></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>

            
            <div class="card overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <span class="text-xs font-black text-slate-400 uppercase tracking-widest">Recent Activity</span>
                    <span class="flex h-2 w-2 relative">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                </div>
                <div id="recentActivityList">
                    <?php if($recentExpenses->count() > 0): ?>
                        <div class="space-y-0.5">
                            <?php $__currentLoopData = $recentExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="activity-item">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">
                                            <?php echo e($recent->title); ?>

                                        </p>
                                        <p class="text-[11px] text-slate-400 font-medium"><?php echo e($recent->category); ?> ·
                                            <?php echo e($recent->expense_date->format('d M')); ?>

                                        </p>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900 dark:text-white ml-3 flex-shrink-0"
                                        style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($recent->amount, 2)); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center justify-center h-32 text-slate-400 text-xs font-medium">No entries yet
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-7">
            <div class="card p-5">
                <div class="stat-label mb-2">This Week</div>
                <div class="stat-value text-xl" id="thisWeekTotalStat">₹<?php echo e(number_format($thisWeekTotal, 2)); ?></div>
                <div class="flex items-center gap-1.5 mt-2" id="weekChangeContainer">
                    <?php if($weekChange != 0): ?>
                        <span class="trend-badge <?php echo e($weekChange > 0 ? 'trend-up' : 'trend-down'); ?>" style="font-size:10px">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="<?php echo e($weekChange > 0 ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7'); ?>" />
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
                <div class="stat-value text-xl" id="projectedMonthlyStat">₹<?php echo e(number_format($projectedMonthly, 2)); ?></div>
                <div class="text-[11px] text-slate-400 font-medium mt-2">
                    Based on <?php echo e(now()->day); ?>/<?php echo e(now()->daysInMonth); ?> days pace
                </div>
            </div>

            <div class="card p-5">
                <div class="stat-label mb-2">Expense Frequency</div>
                <div class="stat-value text-xl" id="expensesPerWeekStat"><?php echo e($expensesPerWeek); ?><span
                        class="text-sm font-semibold text-slate-400">/wk</span></div>
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
                    <div class="stat-value text-xl" id="burnPercentStat" style="color:<?php echo e($burnColor); ?>"><?php echo e($burnPercent); ?>%
                    </div>
                    <span class="text-xs font-medium text-slate-400 mb-1">of month elapsed</span>
                </div>
                <div class="w-full h-2 bg-slate-100 dark:bg-slate-700 rounded-full mt-3 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700" id="burnProgressBar"
                        style="width:<?php echo e(min($burnPercent, 100)); ?>%; background:<?php echo e($burnColor); ?>"></div>
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
                <div id="topExpensesList">
                    <?php if($topExpenses->count() > 0): ?>
                        <?php $maxTop = $topExpenses->max('amount'); ?>
                        <div class="space-y-3.5">
                            <?php $__currentLoopData = $topExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ri => $te): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-3">
                                    <span
                                        class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black
                                                                                                                            <?php echo e($ri === 0 ? 'bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'); ?>">
                                        <?php echo e($ri + 1); ?>

                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <span
                                                class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[140px]"><?php echo e($te->title); ?></span>
                                            <span class="text-xs font-bold text-slate-900 dark:text-white ml-2"
                                                style="font-variant-numeric:tabular-nums">₹<?php echo e(number_format($te->amount, 2)); ?></span>
                                        </div>
                                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full bg-indigo-500"
                                                style="width:<?php echo e($maxTop > 0 ? ($te->amount / $maxTop) * 100 : 0); ?>%"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center justify-center h-32 text-slate-400 text-xs font-medium">No data yet</div>
                    <?php endif; ?>
                </div>
            </div>

            
            <div class="chart-card">
                <div class="chart-title mb-1">Category Comparison</div>
                <div class="chart-subtitle mb-5">Last 3 months radar</div>
                <div class="flex items-center justify-center" style="height:210px"><canvas id="categoryRadarChart"></canvas>
                </div>
            </div>
        </div>

        
        <div class="filter-panel mb-5">
            <form method="GET" action="<?php echo e(route('expenses.index')); ?>" id="filterForm">
                
                <div class="px-5 pt-5 pb-4">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span class="text-sm font-bold text-slate-800 dark:text-white">Filters</span>
                            <?php
                                $activeCount = collect(['search', 'category', 'payment_method', 'date_from', 'date_to', 'date_preset', 'amount_min', 'amount_max'])
                                    ->filter(fn($k) => request()->filled($k))->count();
                            ?>
                            <?php if($activeCount > 0): ?>
                                <span id="activeCountBadge"
                                    class="px-2 py-0.5 text-[10px] font-bold bg-indigo-100 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-400 rounded-md"><?php echo e($activeCount); ?>

                                    active</span>
                            <?php else: ?>
                                <span id="activeCountBadge" class="hidden"></span>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" class="advanced-toggle" id="advToggle" onclick="toggleAdvanced()">
                                Advanced
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <?php if($activeCount > 0): ?>
                                <button type="button" onclick="clearAllExpensesFilters()"
                                    class="text-xs font-semibold text-red-500 hover:text-red-700 transition">Clear All
                                    ×</button>
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
                                onclick="setPreset('<?php echo e($val); ?>', event)">
                                <?php echo e($label); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <input type="hidden" name="date_preset" id="datePresetInput"
                            value="<?php echo e(request('date_preset', '')); ?>">
                    </div>

                    
                    <div class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[180px]">
                            <label class="filter-label">Search</label>
                            <div class="relative">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none"
                                    stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    placeholder="Search title, description, ref..." class="filter-input"
                                    style="padding-left:34px">
                            </div>
                        </div>
                        <div class="min-w-[140px]">
                            <label class="filter-label">Category</label>
                            <select name="category" class="filter-input">
                                <option value="">All Categories</option>
                                <?php $__currentLoopData = \App\Models\Expense::CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat); ?>" <?php echo e(request('category') == $cat ? 'selected' : ''); ?>><?php echo e($cat); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="min-w-[130px]">
                            <label class="filter-label">Payment</label>
                            <select name="payment_method" class="filter-input">
                                <option value="">All Methods</option>
                                <?php $__currentLoopData = \App\Models\Expense::PAYMENT_METHODS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php echo e(request('payment_method') == $key ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="min-w-[150px]">
                            <label class="filter-label">Expense By</label>
                            <input type="text" name="expense_by" value="<?php echo e(request('expense_by')); ?>"
                                list="expense-by-filter-list" placeholder="Search name..." class="filter-input">
                            <datalist id="expense-by-filter-list">
                                <?php $__currentLoopData = \App\Models\Expense::EXPENSE_BY_OPTIONS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($option); ?>">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </datalist>
                        </div>
                        <button type="submit" class="btn-primary" style="padding:8px 18px;font-size:12px">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Apply
                        </button>
                    </div>
                </div>

                
                <div class="adv-section" id="advSection">
                    <div class="px-5 pb-5 pt-2 border-t border-slate-100 dark:border-white/5">
                        <div class="flex flex-wrap items-end gap-4">
                            <div class="min-w-[120px]">
                                <label class="filter-label">Min Amount (₹)</label>
                                <input type="number" name="amount_min" value="<?php echo e(request('amount_min')); ?>" placeholder="0"
                                    step="0.01" min="0" class="filter-input" style="font-variant-numeric:tabular-nums">
                            </div>
                            <div class="min-w-[120px]">
                                <label class="filter-label">Max Amount (₹)</label>
                                <input type="number" name="amount_max" value="<?php echo e(request('amount_max')); ?>" placeholder="∞"
                                    step="0.01" min="0" class="filter-input" style="font-variant-numeric:tabular-nums">
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
                                    <option value="expense_date" <?php echo e(request('sort_by', 'expense_date') == 'expense_date' ? 'selected' : ''); ?>>Date</option>
                                    <option value="amount" <?php echo e(request('sort_by') == 'amount' ? 'selected' : ''); ?>>Amount
                                    </option>
                                    <option value="title" <?php echo e(request('sort_by') == 'title' ? 'selected' : ''); ?>>Title</option>
                                    <option value="category" <?php echo e(request('sort_by') == 'category' ? 'selected' : ''); ?>>Category
                                    </option>
                                    <option value="created_at" <?php echo e(request('sort_by') == 'created_at' ? 'selected' : ''); ?>>
                                        Created</option>
                                </select>
                            </div>
                            <div class="min-w-[100px]">
                                <label class="filter-label">Direction</label>
                                <select name="sort_dir" class="filter-input">
                                    <option value="desc" <?php echo e(request('sort_dir', 'desc') == 'desc' ? 'selected' : ''); ?>>Newest
                                        First</option>
                                    <option value="asc" <?php echo e(request('sort_dir') == 'asc' ? 'selected' : ''); ?>>Oldest First
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div id="activeFilters">
                    <?php echo $__env->make('expenses.partials.active_filters', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>
            </form>
        </div>

        
        <div class="card overflow-hidden relative" id="tableWrapper">
            
            <div id="tableLoading"
                class="absolute inset-0 bg-white/60 dark:bg-slate-900/60 backdrop-blur-[2px] z-20 flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300">
                <div class="flex flex-col items-center gap-2">
                    <div class="w-8 h-8 border-4 border-indigo-500/20 border-t-indigo-600 rounded-full animate-spin"></div>
                    <span
                        class="text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest">Updating...</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th class="text-right">Amount</th>
                            <th>Payment</th>
                            <th>Expense By</th>
                            <th class="text-center">Receipt</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="expenseTableBody">
                        <?php echo $__env->make('expenses.partials.table', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </tbody>
                </table>
            </div>

            <div id="paginationContainer">
                <?php if($expenses->hasPages()): ?>
                    <div class="px-5 py-4 border-t border-slate-100 dark:border-white/5">
                        <?php echo e($expenses->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            // Global chart instances for AJAX updates
            window.expenseCharts = {};

            // Monthly Trend
            const trendCtx = document.getElementById('monthlyTrendChart');
            if (trendCtx) {
                const trendData = <?php echo json_encode($monthlyTrend, 15, 512) ?>;
                const trendGradient = trendCtx.getContext('2d').createLinearGradient(0, 0, 0, 220);
                trendGradient.addColorStop(0, 'rgba(79,70,229,0.15)');
                trendGradient.addColorStop(1, 'rgba(79,70,229,0.01)');
                window.expenseCharts.trend = new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: trendData.map(d => d.short),
                        datasets: [{
                            data: trendData.map(d => d.total),
                            borderColor: '#4f46e5', backgroundColor: trendGradient, borderWidth: 2.5, fill: true, tension: 0.4,
                            pointBackgroundColor: '#4f46e5', pointBorderColor: isDark ? '#1a1f2e' : '#fff', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 7,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { ...tooltipStyle, callbacks: { label: ctx => '₹' + ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 }) } }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11, weight: 600 } } },
                            y: { grid: { color: gridColor }, border: { display: false }, ticks: { font: { size: 10 }, callback: v => '₹' + (v >= 1000 ? (v / 1000).toFixed(0) + 'k' : v) } }
                        }
                    }
                });
            }

            // Category Doughnut
            const doughnutCtx = document.getElementById('categoryDoughnutChart');
            if (doughnutCtx) {
                const catData = <?php echo json_encode($categoryBreakdown, 15, 512) ?>;
                const pieColors = ['#4f46e5', '#7c3aed', '#a855f7', '#0891b2', '#059669', '#d97706', '#dc2626', '#db2777', '#64748b'];
                window.expenseCharts.category = new Chart(doughnutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: catData.map(d => d.category),
                        datasets: [{ data: catData.map(d => d.total), backgroundColor: pieColors.slice(0, Math.max(catData.length, 1)), borderWidth: 0, hoverOffset: 6, spacing: 2 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '68%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { ...tooltipStyle, callbacks: { label: ctx => ctx.label + ': ₹' + ctx.parsed.toLocaleString('en-IN', { minimumFractionDigits: 2 }) } }
                        }
                    }
                });
            }

            // Daily Sparkline
            const sparkCtx = document.getElementById('dailySparkChart');
            if (sparkCtx) {
                const dailyData = <?php echo json_encode($dailySpending, 15, 512) ?>;
                window.expenseCharts.daily = new Chart(sparkCtx, {
                    type: 'bar',
                    data: {
                        labels: dailyData.map(d => d.date),
                        datasets: [{
                            data: dailyData.map(d => d.total),
                            backgroundColor: dailyData.map(d => d.total > 0 ? (isDark ? 'rgba(129,140,248,0.5)' : 'rgba(79,70,229,0.4)') : (isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.03)')),
                            borderRadius: 3, borderSkipped: false, barPercentage: 0.65,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { ...tooltipStyle, callbacks: { title: items => dailyData[items[0].dataIndex].full, label: ctx => '₹' + ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 }) } }
                        },
                        scales: {
                            x: { display: false },
                            y: { grid: { color: gridColor }, border: { display: false }, ticks: { font: { size: 10 }, callback: v => '₹' + (v >= 1000 ? (v / 1000).toFixed(0) + 'k' : v) } }
                        }
                    }
                });
            }

            // Day-of-Week Chart
            const dowCtx = document.getElementById('dayOfWeekChart');
            if (dowCtx) {
                const dowData = <?php echo json_encode($dayOfWeekSpending, 15, 512) ?>;
                const dowMax = Math.max(...dowData.map(d => d.total));
                window.expenseCharts.dow = new Chart(dowCtx, {
                    type: 'bar',
                    data: {
                        labels: dowData.map(d => d.day),
                        datasets: [{
                            data: dowData.map(d => d.total),
                            backgroundColor: dowData.map(d => {
                                const intensity = dowMax > 0 ? (d.total / dowMax) : 0;
                                return isDark ? `rgba(129,140,248,${0.15 + intensity * 0.5})` : `rgba(79,70,229,${0.15 + intensity * 0.5})`;
                            }),
                            borderRadius: 6, borderSkipped: false, barPercentage: 0.6,
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: { ...tooltipStyle, callbacks: { title: items => dowData[items[0].dataIndex].day, label: ctx => '₹' + ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 }) + ' (' + dowData[ctx.dataIndex].count + ' entries)' } }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11, weight: 600 } } },
                            y: { grid: { color: gridColor }, border: { display: false }, ticks: { font: { size: 10 }, callback: v => '₹' + (v >= 1000 ? (v / 1000).toFixed(0) + 'k' : v) } }
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
                window.expenseCharts.radar = new Chart(radarCtx, {
                    type: 'radar',
                    data: {
                        labels: allCats,
                        datasets: catTrend.map((month, idx) => ({
                            label: month.label, data: allCats.map(cat => month[cat] || 0),
                            borderColor: radarColors[idx % radarColors.length], backgroundColor: radarBg[idx % radarBg.length],
                            borderWidth: 2, pointRadius: 3, pointBackgroundColor: radarColors[idx % radarColors.length],
                        }))
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 8, boxHeight: 8, borderRadius: 4, useBorderRadius: true, font: { size: 10, weight: 600 }, padding: 12 } },
                            tooltip: { ...tooltipStyle, callbacks: { label: ctx => ctx.dataset.label + ': ₹' + (ctx.raw ? ctx.raw.toLocaleString('en-IN', { minimumFractionDigits: 2 }) : '0') } }
                        },
                        scales: {
                            r: { angleLines: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)' }, grid: { color: isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.06)' }, pointLabels: { font: { size: 9, weight: 600 }, color: textColor }, ticks: { display: false } }
                        }
                    }
                });
            }
        });

        // ── Filter Controls ──
        async function fetchResults() {
            const form = document.getElementById('filterForm');
            const overlay = document.getElementById('tableLoading');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);

            overlay.classList.remove('pointer-events-none');
            overlay.classList.add('opacity-100');

            try {
                const response = await fetch(`${window.location.pathname}?${params.toString()}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();

                // Update Table & Pagination
                document.getElementById('expenseTableBody').innerHTML = data.table_html;
                document.getElementById('paginationContainer').innerHTML = data.pagination_html;
                document.getElementById('activeFilters').innerHTML = data.active_filters_html;

                // Update Header Badge
                const badge = document.getElementById('activeCountBadge');
                if (badge) {
                    const count = data.stats.activeCount || 0;
                    if (count > 0) {
                        badge.textContent = count + ' active';
                        badge.classList.remove('hidden');
                        badge.classList.add('px-2', 'py-0.5', 'text-[10px]', 'font-bold', 'bg-indigo-100', 'text-indigo-700', 'dark:bg-indigo-500/15', 'dark:text-indigo-400', 'rounded-md');
                    } else {
                        badge.classList.add('hidden');
                    }
                }

                // Update Stats
                document.getElementById('totalExpensesStat').textContent = '₹' + data.stats.totalExpenses;
                document.getElementById('totalCountStat').textContent = data.stats.totalCount + ' entries';
                document.getElementById('thisMonthTotalStat').textContent = '₹' + data.stats.thisMonthTotal;
                document.getElementById('avgDailyStat').textContent = '₹' + data.stats.totalExpenses; // Controller sets avgDaily to total sum if filtered
                document.getElementById('topCategoryStat').textContent = data.stats.topCategory;
                document.getElementById('thisWeekTotalStat').textContent = '₹' + data.stats.thisWeekTotal;
                document.getElementById('projectedMonthlyStat').textContent = '₹' + data.stats.projectedMonthly;
                document.getElementById('expensesPerWeekStat').innerHTML = data.stats.expensesPerWeek + '<span class="text-sm font-semibold text-slate-400">/wk</span>';

                const burnVal = data.stats.burnPercent;
                const burnColor = burnVal <= 50 ? '#059669' : (burnVal <= 80 ? '#d97706' : '#dc2626');
                document.getElementById('burnPercentStat').textContent = burnVal + '%';
                document.getElementById('burnPercentStat').style.color = burnColor;
                document.getElementById('burnProgressBar').style.width = Math.min(burnVal, 100) + '%';
                document.getElementById('burnProgressBar').style.background = burnColor;

                // Update Export URL
                document.getElementById('exportBtn').href = `<?php echo e(route('expenses.export')); ?>?${params.toString()}`;

                // Update Charts
                if (window.expenseCharts.trend) {
                    window.expenseCharts.trend.data.labels = data.charts.monthlyTrend.map(d => d.short);
                    window.expenseCharts.trend.data.datasets[0].data = data.charts.monthlyTrend.map(d => d.total);
                    window.expenseCharts.trend.update('none');
                }
                if (window.expenseCharts.category) {
                    const pieColors = ['#4f46e5', '#7c3aed', '#a855f7', '#0891b2', '#059669', '#d97706', '#dc2626', '#db2777', '#64748b'];
                    window.expenseCharts.category.data.labels = data.charts.categoryBreakdown.map(d => d.category);
                    window.expenseCharts.category.data.datasets[0].data = data.charts.categoryBreakdown.map(d => d.total);
                    window.expenseCharts.category.data.datasets[0].backgroundColor = pieColors.slice(0, Math.max(data.charts.categoryBreakdown.length, 1));
                    window.expenseCharts.category.update('none');
                }
                if (window.expenseCharts.daily) {
                    window.expenseCharts.daily.data.labels = data.charts.dailySpending.map(d => d.date);
                    window.expenseCharts.daily.data.datasets[0].data = data.charts.dailySpending.map(d => d.total);
                    const isDark = document.documentElement.classList.contains('dark');
                    window.expenseCharts.daily.data.datasets[0].backgroundColor = data.charts.dailySpending.map(d => d.total > 0 ? (isDark ? 'rgba(129,140,248,0.5)' : 'rgba(79,70,229,0.4)') : (isDark ? 'rgba(255,255,255,0.03)' : 'rgba(0,0,0,0.03)'));
                    window.expenseCharts.daily.update('none');
                }
                if (window.expenseCharts.dow) {
                    const dowMax = Math.max(...data.charts.dayOfWeekSpending.map(d => d.total));
                    const isDark = document.documentElement.classList.contains('dark');
                    window.expenseCharts.dow.data.datasets[0].data = data.charts.dayOfWeekSpending.map(d => d.total);
                    window.expenseCharts.dow.data.datasets[0].backgroundColor = data.charts.dayOfWeekSpending.map(d => {
                        const intensity = dowMax > 0 ? (d.total / dowMax) : 0;
                        return isDark ? `rgba(129,140,248,${0.15 + intensity * 0.5})` : `rgba(79,70,229,${0.15 + intensity * 0.5})`;
                    });
                    window.expenseCharts.dow.update('none');
                }
                if (window.expenseCharts.radar) {
                    window.expenseCharts.radar.data.datasets.forEach((ds, idx) => {
                        if (data.charts.categoryMonthlyTrend[idx]) {
                            ds.data = window.expenseCharts.radar.data.labels.map(cat => data.charts.categoryMonthlyTrend[idx][cat] || 0);
                        }
                    });
                    window.expenseCharts.radar.update('none');
                }

                // Update Recent & Top lists
                updateList('topExpensesList', data.charts.topExpenses, true);
                updateList('recentActivityList', data.charts.recentExpenses, false);

                // Update URL bar
                history.pushState(null, '', `?${params.toString()}`);

            } catch (e) {
                console.error('Filter failed', e);
            } finally {
                overlay.classList.add('pointer-events-none');
                overlay.classList.remove('opacity-100');
            }
        }

        function updateList(id, items, isTopScale) {
            const el = document.getElementById(id);
            if (!items || items.length === 0) {
                el.innerHTML = '<div class="flex items-center justify-center h-32 text-slate-400 text-xs font-medium">No data found</div>';
                return;
            }
            if (isTopScale) {
                const max = Math.max(...items.map(i => i.amount));
                el.innerHTML = '<div class="space-y-3.5">' + items.map((item, ri) => `
                                            <div class="flex items-center gap-3">
                                                <span class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-black ${ri === 0 ? 'bg-amber-100 text-amber-600 dark:bg-amber-500/15 dark:text-amber-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400'}">${ri + 1}</span>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center justify-between mb-1">
                                                        <span class="text-xs font-semibold text-slate-700 dark:text-slate-300 truncate max-w-[140px]">${item.title}</span>
                                                        <span class="text-xs font-bold text-slate-900 dark:text-white ml-2">₹${parseFloat(item.amount).toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                                                    </div>
                                                    <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                                        <div class="h-full rounded-full bg-indigo-500" style="width:${max > 0 ? (item.amount / max) * 100 : 0}%"></div>
                                                    </div>
                                                </div>
                                            </div>`).join('') + '</div>';
            } else {
                el.innerHTML = '<div class="space-y-0.5">' + items.map(item => `
                                            <div class="activity-item">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">${item.title}</p>
                                                    <p class="text-[11px] text-slate-400 font-medium">${item.category} · ${new Date(item.expense_date).toLocaleDateString('en-IN', { day: '2-digit', month: 'short' })}</p>
                                                </div>
                                                <span class="text-sm font-bold text-slate-900 dark:text-white ml-3 flex-shrink-0">₹${parseFloat(item.amount).toLocaleString('en-IN', { minimumFractionDigits: 2 })}</span>
                                            </div>`).join('') + '</div>';
            }
        }

        function clearAllExpensesFilters() {
            const form = document.getElementById('filterForm');
            form.reset();
            form.querySelectorAll('input, select').forEach(input => {
                input.value = '';
            });
            document.getElementById('datePresetInput').value = '';
            document.querySelectorAll('.preset-pill').forEach(p => p.classList.remove('active'));
            fetchResults();
        }

        function setPreset(val, e) {
            document.getElementById('datePresetInput').value = val;
            document.querySelectorAll('.preset-pill').forEach(p => p.classList.remove('active'));
            if (e && e.target) e.target.classList.add('active');
            fetchResults();
        }

        function removeFilter(key) {
            const el = document.getElementsByName(key)[0] || document.getElementById('datePresetInput');
            if (el) {
                el.value = '';
                if (key === 'date_preset') {
                    document.querySelectorAll('.preset-pill').forEach(p => p.classList.remove('active'));
                }
                fetchResults();
            }
        }

        function toggleAdvanced() {
            const section = document.getElementById('advSection');
            const toggle = document.getElementById('advToggle');
            section.classList.toggle('open');
            toggle.classList.toggle('open');
        }

        let searchTimeout;
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('filterForm');

            // Intercept form submit (Enter key)
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                fetchResults();
            });

            form.querySelectorAll('select, input').forEach(input => {
                const isInstant = (input.type === 'text' && input.name === 'search') ||
                    (input.type === 'number');

                if (isInstant) {
                    input.addEventListener('input', () => {
                        clearTimeout(searchTimeout);
                        searchTimeout = setTimeout(fetchResults, 400);
                    });
                } else {
                    input.addEventListener('change', fetchResults);
                }
            });

            // Pagination links AJAX
            document.addEventListener('click', e => {
                const link = e.target.closest('#paginationContainer a');
                if (link) {
                    e.preventDefault();
                    const url = new URL(link.href);
                    const params = url.searchParams;
                    // Sync form inputs with pagination URL params if needed, but usually we just fetch the URL
                    window.scrollTo({ top: document.getElementById('tableWrapper').offsetTop - 100, behavior: 'smooth' });
                    fetchResultsPagination(link.href);
                }
            });
        });

        async function fetchResultsPagination(url) {
            const overlay = document.getElementById('tableLoading');
            overlay.classList.remove('pointer-events-none');
            overlay.classList.add('opacity-100');
            try {
                const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const data = await response.json();
                document.getElementById('expenseTableBody').innerHTML = data.table_html;
                document.getElementById('paginationContainer').innerHTML = data.pagination_html;
                history.pushState(null, '', url);
            } finally {
                overlay.classList.add('pointer-events-none');
                overlay.classList.remove('opacity-100');
            }
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/expenses/index.blade.php ENDPATH**/ ?>