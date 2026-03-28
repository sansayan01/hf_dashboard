

<?php $__env->startSection('title', 'Admin Control Panel'); ?>
<?php $__env->startSection('header_title', 'Admin Hub'); ?>

<?php $__env->startSection('content'); ?>
    <div class="p-6 max-w-7xl mx-auto overflow-y-auto h-full pb-20">
        <!-- Dashboard Welcome -->
        <div class="mb-12 animate-in fade-in slide-in-from-top-4 duration-700">
            <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">System Configuration</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-2">Manage global system settings, access levels, and
                financial configurations.</p>
        </div>

        <!-- Main Hub Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- 1. Permissions -->
            <?php if(auth()->user()->hasPermission('admin.manage_roles')): ?>
            <a href="<?php echo e(route('admin.permissions.index')); ?>"
                class="group relative block p-1 bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 rounded-[2.5rem] shadow-2xl transition-all hover:scale-[1.02] hover:-translate-y-1 duration-500">
                <div class="bg-white dark:bg-darkcard rounded-[2.4rem] p-8 h-full relative overflow-hidden">
                    <!-- Shimmer Background -->
                    <div
                        class="absolute -top-12 -right-12 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000">
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div
                            class="w-16 h-16 bg-indigo-500/10 rounded-2xl flex items-center justify-center text-indigo-500 mb-6 group-hover:rotate-12 transition-transform duration-500">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21a11.955 11.955 0 01-8.618-3.04m17.236 0A11.955 11.955 0 0112 21">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-2">
                            Permissions</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-8">Configure role-based access
                            control and system permissions.</p>

                        <div
                            class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Configured
                                    Roles</span>
                                <span
                                    class="text-lg font-black text-indigo-500"><?php echo e($stats['permissions']['roles_count']); ?></span>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-500">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endif; ?>

            <!-- 2. Coupons -->
            <?php if(auth()->user()->hasPermission('admin.manage_coupons')): ?>
            <a href="<?php echo e(route('coupons.index')); ?>"
                class="group relative block p-1 bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-500 rounded-[2.5rem] shadow-2xl transition-all hover:scale-[1.02] hover:-translate-y-1 duration-500">
                <div class="bg-white dark:bg-darkcard rounded-[2.4rem] p-8 h-full relative overflow-hidden">
                    <!-- Shimmer Background -->
                    <div
                        class="absolute -top-12 -right-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000">
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div
                            class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 mb-6 group-hover:rotate-12 transition-transform duration-500">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-2">
                            Coupons</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-8">Manage registration vouchers,
                            batch mining, and usage records.</p>

                        <div
                            class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Active
                                    Codes</span>
                                <span class="text-lg font-black text-emerald-500"><?php echo e($stats['coupons']['active']); ?></span>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-500">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endif; ?>

            <!-- 3. Incentives -->
            <?php if(auth()->user()->hasPermission('admin.manage_incentives')): ?>
            <a href="<?php echo e(route('admin.incentive-configs.index')); ?>"
                class="group relative block p-1 bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 rounded-[2.5rem] shadow-2xl transition-all hover:scale-[1.02] hover:-translate-y-1 duration-500">
                <div class="bg-white dark:bg-darkcard rounded-[2.4rem] p-8 h-full relative overflow-hidden">
                    <!-- Shimmer Background -->
                    <div
                        class="absolute -top-12 -right-12 w-48 h-48 bg-amber-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000">
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div
                            class="w-16 h-16 bg-amber-500/10 rounded-2xl flex items-center justify-center text-amber-500 mb-6 group-hover:rotate-12 transition-transform duration-500">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-2">
                            Incentives</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mb-8">Set daily incentives for DM,
                            BM, RM and RO designations.</p>

                        <div
                            class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                            <div class="flex flex-col">
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Active
                                    Plans</span>
                                <span
                                    class="text-lg font-black text-amber-500"><?php echo e($stats['incentives']['global_plans']); ?></span>
                            </div>
                            <div
                                class="w-10 h-10 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 group-hover:bg-amber-500 group-hover:text-white transition-all duration-500">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            <?php endif; ?>
        </div>

        <!-- Secondary Info Section -->
        <div
            class="bg-white/30 dark:bg-darkcard/30 backdrop-blur-xl border border-slate-200 dark:border-white/5 rounded-[3rem] p-10 animate-in fade-in slide-in-from-bottom-4 duration-1000 delay-300">
            <div class="flex flex-col lg:flex-row items-center gap-10">
                <div class="lg:w-1/2">
                    <h4 class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tighter mb-4">
                        Centralized Administration</h4>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed font-medium">This panel serves as the
                        heartbeat of the Humanity Foundation Management System. All global overrides, financial structures,
                        and security protocols are synchronized here to ensure system integrity across all state and
                        district branches.</p>

                    <div class="grid grid-cols-2 gap-6 mt-8">
                        <div
                            class="p-4 bg-white/50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                            <span class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1">System
                                Health</span>
                            <span class="text-sm font-bold text-emerald-500 uppercase">Optimal</span>
                        </div>
                        <div
                            class="p-4 bg-white/50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                            <span class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Last Config
                                Sync</span>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300"><?php echo e(now()->format('H:i')); ?>

                                Today</span>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2 flex justify-center">
                    <div class="relative">
                        <div class="w-64 h-64 bg-accent/20 rounded-full blur-[80px] absolute inset-0 animate-pulse"></div>
                        <img src="<?php echo e(asset('img/hf_gold_logo.png')); ?>"
                            class="w-48 h-48 object-contain relative z-10 drop-shadow-2xl" alt="Shield">
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/admin/control_panel/index.blade.php ENDPATH**/ ?>