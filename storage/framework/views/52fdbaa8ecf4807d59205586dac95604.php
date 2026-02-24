<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('header_title', 'Dashboard Overview'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $user = $user ?? auth()->user();
        $isViewAs = $isViewAs ?? false;
        $canViewReports = $canViewReports ?? false;
        $canViewDownline = $canViewDownline ?? false;
        $canApprove = $canApprove ?? false;
        $stats = $stats ?? [
            'total_downline' => 0,
            'active_downline' => 0,
            'pending_approvals' => 0,
            'direct_children' => 0
        ];
    ?>
    <?php if(isset($isViewAs) && $isViewAs): ?>
        <div
            class="bg-indigo-600 text-white rounded-2xl p-4 mb-8 shadow-lg shadow-indigo-600/20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white/10 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-sm">Viewing Dashboard As: <span
                            class="font-black underline"><?php echo e($user->profile?->full_name ?? $user->employee_id); ?></span></p>
                    <p class="text-[10px] opacity-80 uppercase tracking-widest">You are in read-only view mode</p>
                </div>
            </div>
            <a href="<?php echo e(route('dashboard')); ?>"
                class="px-4 py-2 bg-white text-indigo-600 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-indigo-50 transition border border-transparent hover:border-white/20">Back
                to My Dashboard</a>
        </div>
    <?php endif; ?>
    <!-- Stats Grid -->
    <?php if($canViewDownline): ?>
        <div
            class="grid <?php echo e(auth()->user()->isSuperAdmin() ? 'grid-cols-3' : 'grid-cols-2'); ?> md:grid-cols-<?php echo e(auth()->user()->isSuperAdmin() ? '3' : '2'); ?> lg:grid-cols-<?php echo e(auth()->user()->isSuperAdmin() ? '3' : '2'); ?> gap-2 md:gap-6 mb-2">
            <!-- Total Downline -->
            <div
                class="glass bg-white dark:bg-darkbg/40 <?php echo e(auth()->user()->isSuperAdmin() ? 'p-1.5' : 'p-2'); ?> md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-12 h-12 bg-accent/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                <div class="flex items-center justify-between mb-1 md:mb-4 relative z-10">
                    <div
                        class="w-6 h-6 md:w-12 md:h-12 bg-accent/10 text-accent dark:text-blue-400 rounded md:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-3.5 h-3.5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span
                        class="text-[7px] md:text-xs font-black text-success bg-success/10 px-1 md:px-2 py-0.5 rounded-full">+<?php echo e($stats['direct_children']); ?></span>
                </div>
                <h3
                    class="text-slate-400 dark:text-slate-500 text-[8px] md:text-sm font-black uppercase tracking-widest mb-0.5 md:mb-2 relative z-10 truncate">
                    Team</h3>
                <p class="text-sm md:text-4xl font-black text-slate-800 dark:text-blue-400 relative z-10">
                    <?php echo e(number_format($stats['total_downline'])); ?>

                </p>
            </div>

            <!-- Active Members -->
            <div
                class="glass bg-white dark:bg-darkbg/40 <?php echo e(auth()->user()->isSuperAdmin() ? 'p-1.5' : 'p-2'); ?> md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-12 h-12 bg-success/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                <div class="flex items-center justify-between mb-1 md:mb-4 relative z-10">
                    <div
                        class="w-6 h-6 md:w-12 md:h-12 bg-success/10 text-success dark:text-emerald-400 rounded md:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-3.5 h-3.5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3
                    class="text-slate-400 dark:text-slate-500 text-[8px] md:text-sm font-black uppercase tracking-widest mb-0.5 md:mb-2 relative z-10 truncate">
                    Active</h3>
                <p class="text-sm md:text-4xl font-black text-slate-800 dark:text-emerald-400 relative z-10">
                    <?php echo e(number_format($stats['active_downline'])); ?>

                </p>
            </div>

            <?php if(auth()->user()->isSuperAdmin()): ?>
                <!-- Pending Approvals -->
                <div onclick="window.location.href='<?php echo e(route('users.index', ['status' => 'pending'])); ?>'"
                    class="glass bg-white dark:bg-darkbg/40 p-1.5 md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative cursor-pointer">
                    <div class="absolute top-0 right-0 w-12 h-12 bg-pink-500/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                    <div class="flex items-center justify-between mb-1 md:mb-4 relative z-10">
                        <div
                            class="w-6 h-6 md:w-12 md:h-12 bg-pink-500/10 text-pink-500 dark:text-pink-400 rounded md:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3
                        class="text-slate-400 dark:text-slate-500 text-[8px] md:text-sm font-black uppercase tracking-widest mb-0.5 md:mb-2 relative z-10 truncate">
                        Pending</h3>
                    <p class="text-sm md:text-4xl font-black text-slate-800 dark:text-pink-400 relative z-10">
                        <?php echo e(number_format($stats['pending_approvals'])); ?>

                    </p>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Earnings Overview (RO, RM, BM, DM) -->
    <?php if(isset($earnings) && $earnings): ?>
        <div class="grid grid-cols-1 <?php echo e($user->isRO() ? 'md:grid-cols-3' : 'md:grid-cols-2'); ?> gap-2 md:gap-6 mb-8">
            <!-- Today's Earning -->
            <div
                class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-10 h-10 bg-blue-500/10 text-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-black text-blue-500 bg-blue-500/5 px-2 py-1 rounded-full uppercase tracking-widest">Today</span>
                </div>
                <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Total</h3>
                <p class="text-3xl font-black text-slate-800 dark:text-white">₹<?php echo e(number_format($earnings['today_total'], 2)); ?>

                </p>
                <div class="mt-4 grid grid-cols-2 gap-y-2 text-[10px] font-bold uppercase tracking-tight">
                    <?php if($user->isRO()): ?>
                        <div class="flex items-center text-slate-500">
                            <div class="w-2 h-2 rounded-full bg-slate-300 mr-1.5"></div>
                            TA: ₹<?php echo e(number_format($earnings['today_breakdown']['ta'], 2)); ?>

                        </div>
                    <?php endif; ?>
                    <div class="flex items-center text-emerald-500">
                        <div class="w-2 h-2 rounded-full bg-emerald-500/40 mr-1.5"></div>
                        M: ₹<?php echo e(number_format($earnings['today_breakdown']['medicines'], 2)); ?>

                    </div>
                    <div class="flex items-center text-purple-500">
                        <div class="w-2 h-2 rounded-full bg-purple-500/40 mr-1.5"></div>
                        P: ₹<?php echo e(number_format($earnings['today_breakdown']['pathology'], 2)); ?>

                    </div>
                    <div class="flex items-center text-amber-500">
                        <div class="w-2 h-2 rounded-full bg-amber-500/40 mr-1.5"></div>
                        MB: ₹<?php echo e(number_format($earnings['today_breakdown']['membership'], 2)); ?>

                    </div>
                </div>
            </div>

            <?php if($user->isRO()): ?>
                <!-- Monthly TA -->
                <div
                    class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-black text-emerald-500 bg-emerald-500/5 px-2 py-1 rounded-full uppercase tracking-widest">Monthly</span>
                    </div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">TA</h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white">₹<?php echo e(number_format($earnings['monthly_ta'], 2)); ?>

                    </p>
                    <div class="mt-4">
                        <div class="w-full bg-slate-100 dark:bg-white/5 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-[9px] text-slate-500 font-bold mt-2 uppercase tracking-widest">Travel Allowance Summary</p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Monthly Incentives -->
            <div
                class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-16 h-16 bg-indigo-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-black text-indigo-500 bg-indigo-500/5 px-2 py-1 rounded-full uppercase tracking-widest">Monthly</span>
                </div>
                <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Total Incentives</h3>
                <p class="text-3xl font-black text-slate-800 dark:text-white">
                    ₹<?php echo e(number_format($earnings['monthly_incentives'], 2)); ?></p>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 uppercase font-black tracking-widest">Med</span>
                        <span
                            class="text-xs font-bold text-slate-700 dark:text-slate-300">₹<?php echo e(number_format($earnings['monthly_breakdown']['medicines'], 2)); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 uppercase font-black tracking-widest">Path</span>
                        <span
                            class="text-xs font-bold text-slate-700 dark:text-slate-300">₹<?php echo e(number_format($earnings['monthly_breakdown']['pathology'], 2)); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 uppercase font-black tracking-widest">Mem</span>
                        <span
                            class="text-xs font-bold text-slate-700 dark:text-slate-300">₹<?php echo e(number_format($earnings['monthly_breakdown']['membership'], 2)); ?></span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 uppercase font-black tracking-widest">OTs</span>
                        <span
                            class="text-xs font-bold text-slate-700 dark:text-slate-300">₹<?php echo e(number_format($earnings['monthly_breakdown']['ots'], 2)); ?></span>
                    </div>
                </div>
                <div
                    class="mt-2 text-[8px] text-slate-500 font-bold uppercase tracking-widest border-t border-slate-100 dark:border-white/5 pt-2">
                    Performance Based Breakdown
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Reports Grid -->
    <?php if($canViewReports): ?>
        <div class="grid grid-cols-2 gap-2 md:gap-6 mb-10">
            <!-- Survey Reports -->
            <a href="<?php echo e(route('surveys.index')); ?>"
                class="block glass bg-white dark:bg-darkbg/40 p-2 md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-12 h-12 bg-indigo-500/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                <div class="flex items-center justify-between mb-2 md:mb-6 relative z-10">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 md:w-12 md:h-12 bg-indigo-500/10 text-indigo-500 rounded-lg md:rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] md:text-xs font-black uppercase tracking-widest text-slate-400">Total Surveys
                            </h3>
                            <p class="text-xl md:text-3xl font-black text-slate-800 dark:text-white">
                                <?php echo e(number_format($reports['surveys']['total'])); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 relative z-10">
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Today</p>
                        <p class="text-sm md:text-lg font-black text-indigo-500">
                            <?php echo e(number_format($reports['surveys']['daily'])); ?>

                        </p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Week</p>
                        <p class="text-sm md:text-lg font-black text-indigo-500">
                            <?php echo e(number_format($reports['surveys']['weekly'])); ?>

                        </p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Month</p>
                        <p class="text-sm md:text-lg font-black text-indigo-500">
                            <?php echo e(number_format($reports['surveys']['monthly'])); ?>

                        </p>
                    </div>
                </div>
            </a>

            <!-- Appointment Reports -->
            <a href="<?php echo e(route('appointments.all')); ?>"
                class="block glass bg-white dark:bg-darkbg/40 p-2 md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-12 h-12 bg-emerald-500/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                <div class="flex items-center justify-between mb-2 md:mb-6 relative z-10">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 md:w-12 md:h-12 bg-emerald-500/10 text-emerald-500 rounded-lg md:rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] md:text-xs font-black uppercase tracking-widest text-slate-400">Total Apps
                            </h3>
                            <p class="text-xl md:text-3xl font-black text-slate-800 dark:text-white">
                                <?php echo e(number_format($reports['appointments']['total'])); ?>

                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 relative z-10">
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Today</p>
                        <p class="text-sm md:text-lg font-black text-emerald-500">
                            <?php echo e(number_format($reports['appointments']['daily'])); ?>

                        </p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Week</p>
                        <p class="text-sm md:text-lg font-black text-emerald-500">
                            <?php echo e(number_format($reports['appointments']['weekly'])); ?>

                        </p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Month</p>
                        <p class="text-sm md:text-lg font-black text-emerald-500">
                            <?php echo e(number_format($reports['appointments']['monthly'])); ?>

                        </p>
                    </div>
                </div>
            </a>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <?php if($canViewDownline): ?>
            <!-- Hierarchy Tree Preview -->
            <div id="down-tree-card"
                class="col-span-1 lg:col-span-2 glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden flex flex-col transition-all h-[60vh] relative z-20">
                <div
                    class="p-8 border-b border-slate-200/5 flex items-center justify-between bg-white dark:bg-white/5 dark:backdrop-blur-sm">
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <h3 class="font-black text-xs md:text-xl text-slate-800 dark:text-white tracking-tight">Down Tree</h3>
                        <!-- Search Input (Hidden by default, shown in fullscreen or via better layout) -->
                        <div id="tree-search-container"
                            class="hidden md:flex items-center bg-slate-100 dark:bg-slate-800 rounded-xl px-3 py-1.5 border border-slate-200/50 dark:border-white/5 focus-within:ring-2 focus-within:ring-accent/20 transition-all">
                            <svg class="w-3.5 h-3.5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" id="tree-search-input" placeholder="Search downline..."
                                class="bg-transparent border-none p-0 text-xs text-slate-700 dark:text-slate-200 focus:ring-0 placeholder:text-slate-500 w-32 md:w-48">
                        </div>
                        <!-- Zoom Controls -->
                        <div
                            class="hidden md:flex items-center bg-slate-100 dark:bg-slate-800 rounded-lg p-1 border border-slate-200/50 dark:border-white/5">
                            <button onclick="adjustZoom(-0.1)"
                                class="p-1.5 hover:bg-white dark:hover:bg-slate-700 rounded-md transition-all text-slate-500 hover:text-accent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            <span id="zoom-level"
                                class="text-[10px] font-black text-slate-400 px-2 min-w-[40px] text-center">100%</span>
                            <button onclick="adjustZoom(0.1)"
                                class="p-1.5 hover:bg-white dark:hover:bg-slate-700 rounded-md transition-all text-slate-500 hover:text-accent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <button onclick="toggleFullscreenTree()" id="expand-btn"
                        class="text-xs font-bold text-accent dark:text-blue-400 hover:text-white hover:bg-accent px-4 py-2 rounded-xl transition-all border border-accent/20">Expand
                        Viewer</button>
                </div>
                <div class="p-6 flex-1 bg-slate-50/30 dark:bg-transparent overflow-auto relative" id="tree-canvas">
                    <div id="tree-zoom-container" class="origin-top-left transition-transform duration-200">
                        <div id="tree-root" class="space-y-2">
                            <?php if($user->isOfficeInCharge() && $user->upline): ?>
                                <?php echo $__env->make('dashboard.partials.tree_item', ['item' => $user->upline], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php else: ?>
                                <?php echo $__env->make('dashboard.partials.tree_item', ['item' => $user], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div
                    class="px-8 py-4 border-t border-slate-200/5 bg-white dark:bg-white/5 dark:backdrop-blur-sm flex items-center space-x-4">
                    <div class="flex items-center space-x-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.3)]"></div>
                        <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Active</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.3)]"></div>
                        <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Pending</span>
                    </div>
                </div>
            </div>
        <?php endif; ?>

            <!-- Recent Activity & Pending Approvals -->
            <div class="space-y-8">

                <!-- Recent Activity -->
                <div id="timeline-card"
                    class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden transition-all hover:shadow-2xl hover:shadow-accent/5 h-[60vh] flex flex-col relative z-20">
                    <div
                        class="p-6 border-b border-slate-200/5 bg-white dark:bg-white/5 dark:backdrop-blur-sm flex items-center justify-between">
                        <h3 class="font-black text-lg text-slate-800 dark:text-white tracking-tight">Timeline</h3>
                        <button onclick="toggleFullscreenTimeline()" id="expand-timeline-btn"
                            class="text-[10px] font-bold text-accent dark:text-blue-400 hover:text-white hover:bg-accent px-3 py-1.5 rounded-xl transition-all border border-accent/20">Expand
                            Viewer</button>
                    </div>
                    <div class="p-6 flex-1 overflow-y-auto space-y-6 custom-scrollbar">
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
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .tree-item .tree-children {
            transition: all 0.3s ease-in-out;
        }

        .rotate-90 {
            transform: rotate(90deg);
        }

        /* Fullscreen Mode Styles */
        #down-tree-card.fullscreen-tree {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 10000 !important;
            margin: 0 !important;
            border-radius: 0 !important;
            background: #F8FAFC !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .dark #down-tree-card.fullscreen-tree {
            background: #0F172A !important;
        }

        #down-tree-card.fullscreen-tree .p-6.flex-1 {
            height: auto !important;
            overflow-y: auto !important;
        }

        #down-tree-card.fullscreen-tree .sticky.bottom-0 {
            position: fixed !important;
            bottom: 2rem !important;
            right: 2rem !important;
            left: auto !important;
            width: auto !important;
            padding: 1rem !important;
            border-radius: 1rem !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        .dark #down-tree-card.fullscreen-tree .sticky.bottom-0 {
            border-color: rgba(255, 255, 255, 0.05) !important;
            background: rgba(30, 41, 59, 0.8) !important;
        }

        /* Fullscreen Timeline Styles */
        #timeline-card.fullscreen-timeline {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 10000 !important;
            margin: 0 !important;
            border-radius: 0 !important;
            background: #F8FAFC !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .dark #timeline-card.fullscreen-timeline {
            background: #0F172A !important;
        }

        #timeline-card.fullscreen-timeline .p-6.flex-1 {
            height: auto !important;
            overflow-y: auto !important;
        }

        /* Simple custom scrollbar */
        /* Simple custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar,
        .overflow-auto::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb,
        .overflow-auto::-webkit-scrollbar-thumb {
            background: rgba(30, 66, 255, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        #tree-zoom-container {
            transform-origin: 0 0;
            will-change: transform;
        }

        /* Highlight search result */
        @keyframes highlightPulse {
            0% {
                ring-color: rgba(60, 80, 224, 0.4);
                background-color: rgba(60, 80, 224, 0.1);
            }

            50% {
                ring-color: rgba(60, 80, 224, 0.8);
                background-color: rgba(60, 80, 224, 0.2);
            }

            100% {
                ring-color: rgba(60, 80, 224, 0.4);
                background-color: rgba(60, 80, 224, 0.1);
            }
        }

        .search-highlight {
            animation: highlightPulse 2s infinite;
            ring-width: 4px;
            border-radius: 12px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        window.APP_URL = "<?php echo e(url('/')); ?>";

        async function toggleTreeItem(event, itemId) {
            event.stopPropagation();
            const children = document.getElementById(`children-${itemId}`);
            const icon = document.getElementById(`toggle-icon-${itemId}`);

            if (children) {
                if (children.classList.contains('hidden')) {
                    // Load children via AJAX if not already loaded
                    if (children.dataset.loaded === 'false') {
                        const shimmer = children.querySelector('.tree-loading-shimmer');
                        if (shimmer) shimmer.classList.remove('hidden');
                        children.classList.remove('hidden');

                        try {
                            const response = await fetch(`${window.APP_URL}/hierarchy-children/${itemId}`);
                            if (!response.ok) {
                                throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                            }
                            const data = await response.json();

                            // Remove shimmer and add content
                            if (shimmer) shimmer.remove();
                            children.innerHTML = data.html;
                            children.dataset.loaded = 'true';
                        } catch (error) {
                            console.error('Error loading children:', error);
                            if (shimmer) shimmer.innerText = `Error: ${error.message}`;
                            return;
                        }
                    } else {
                        children.classList.remove('hidden');
                    }
                    if (icon) icon.classList.add('rotate-90');
                } else {
                    children.classList.add('hidden');
                    if (icon) icon.classList.remove('rotate-90');
                }
            }
        }

        function toggleFullscreenTree() {
            const card = document.getElementById('down-tree-card');
            const btn = document.getElementById('expand-btn');
            const isFullscreen = card.classList.toggle('fullscreen-tree');

            if (isFullscreen) {
                btn.innerText = 'Exit Fullscreen';
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            } else {
                btn.innerText = 'Expand Viewer';
                document.body.style.overflow = '';
            }
        }

        function toggleFullscreenTimeline() {
            const card = document.getElementById('timeline-card');
            const btn = document.getElementById('expand-timeline-btn');
            const isFullscreen = card.classList.toggle('fullscreen-timeline');

            if (isFullscreen) {
                btn.innerText = 'Exit Fullscreen';
                document.body.style.overflow = 'hidden';
            } else {
                btn.innerText = 'Expand Viewer';
                document.body.style.overflow = '';
            }
        }

        // Zoom Logic
        let currentZoom = 1;
        const minZoom = 0.5;
        const maxZoom = 2;
        const zoomContainer = document.getElementById('tree-zoom-container');
        const zoomLevelDisplay = document.getElementById('zoom-level');
        const canvas = document.getElementById('tree-canvas');

        function updateZoom() {
            zoomContainer.style.transform = `scale(${currentZoom})`;
            if (zoomLevelDisplay) {
                zoomLevelDisplay.innerText = `${Math.round(currentZoom * 100)}%`;
            }
        }

        function adjustZoom(delta) {
            currentZoom = Math.min(Math.max(currentZoom + delta, minZoom), maxZoom);
            updateZoom();
        }

        // Wheel Zoom (Ctrl + Scroll)
        canvas.addEventListener('wheel', (e) => {
            if (e.ctrlKey) {
                e.preventDefault();
                const delta = e.deltaY < 0 ? 0.1 : -0.1;
                adjustZoom(delta);
            }
        }, { passive: false });

        // Pinch to Zoom
        let initialDist = -1;
        canvas.addEventListener('touchstart', (e) => {
            if (e.touches.length === 2) {
                initialDist = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
            }
        });

        canvas.addEventListener('touchmove', (e) => {
            if (e.touches.length === 2 && initialDist > 0) {
                e.preventDefault();
                const currentDist = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
                const delta = (currentDist - initialDist) / 1000;
                adjustZoom(delta);
                initialDist = currentDist;
            }
        }, { passive: false });

        canvas.addEventListener('touchend', () => {
            initialDist = -1;
        });

        // Search Logic
        const searchInput = document.getElementById('tree-search-input');

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            if (query.length < 2) return;

            // Find all tree items
            const items = document.querySelectorAll('.tree-item');
            let found = false;

            items.forEach(item => {
                const nameElement = item.querySelector('span.text-xs.font-black');
                const idElement = item.querySelector('span.text-\\[9px\\]');

                const name = nameElement ? nameElement.innerText.toLowerCase() : '';
                const id = idElement ? idElement.innerText.toLowerCase() : '';

                if (name.includes(query) || id.includes(query)) {
                    if (!found) { // Scroll to the first match
                        found = true;
                        revealAndFocus(item);
                    }
                }
            });
        });

        function revealAndFocus(item) {
            // 1. Expand all parents
            let parent = item.parentElement;
            while (parent && parent.id !== 'tree-root') {
                if (parent.classList.contains('tree-children')) {
                    parent.classList.remove('hidden');
                    // Also rotate the parent icon
                    const parentId = parent.id.replace('children-', '');
                    const parentIcon = document.getElementById(`toggle-icon-${parentId}`);
                    if (parentIcon) parentIcon.classList.add('rotate-90');
                }
                parent = parent.parentElement;
            }

            // 2. Set Zoom to 100%
            currentZoom = 1;
            updateZoom();

            // 3. Scroll Into View
            item.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });

            // 4. Highlight Effect
            const header = item.querySelector('div.flex.items-center.group');
            header.classList.add('search-highlight', 'ring-2', 'ring-accent');

            setTimeout(() => {
                header.classList.remove('search-highlight', 'ring-2', 'ring-accent');
            }, 5000);
        }

        // Auto-expand root
        document.addEventListener('DOMContentLoaded', () => {
            const rootId = "<?php echo e(($user->isOfficeInCharge() && $user->upline) ? $user->upline->id : $user->id); ?>";
            const rootChildren = document.getElementById(`children-${rootId}`);
            if (rootChildren) {
                // Manually trigger toggle for first load to fetch children
                const pseudoEvent = { stopPropagation: () => { } };
                toggleTreeItem(pseudoEvent, rootId);
            }

            // Set default zoom to 100% for all devices
            currentZoom = 1.0;
            updateZoom();
        });

    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/dashboard/index.blade.php ENDPATH**/ ?>