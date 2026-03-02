

<?php $__env->startSection('header_title', 'Finances Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto pb-12">
        <!-- Premium Header Section -->
        <div
            class="relative bg-gradient-to-r from-slate-900 via-indigo-900 to-slate-900 rounded-3xl p-8 sm:p-10 mb-8 overflow-hidden shadow-2xl shadow-indigo-900/20">
            <!-- Abstract Background Effects -->
            <div
                class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-indigo-500/20 blur-3xl mix-blend-screen">
            </div>
            <div
                class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-accent/20 blur-3xl mix-blend-screen">
            </div>
            <svg class="absolute inset-0 w-full h-full opacity-10 pointer-events-none" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="0.5" />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" />
            </svg>

            <div class="relative z-10">
                <div
                    class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/90 text-[10px] font-black uppercase tracking-widest mb-4 border border-white/10 shadow-inner">
                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Financial Management</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-3 drop-shadow-md">
                    Finances Hub
                </h2>
                <p class="text-indigo-100/80 font-medium text-sm md:text-base leading-relaxed max-w-2xl">
                    Access all financial modules and organizational accounting from a centralized location. Select a module
                    below to view detailed records and analytics.
                </p>
            </div>
        </div>

        <!-- Settings Style Directory -->
        <div
            class="bg-white dark:bg-slate-900/60 backdrop-blur-xl rounded-3xl border border-slate-200/50 dark:border-white/5 shadow-xl overflow-hidden">

            <div class="px-8 py-5 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Available
                    Modules</h3>
            </div>

            <div class="divide-y divide-slate-100 dark:divide-white/5">

                <!-- Camp Records Option -->
                <a href="<?php echo e(route('camp_records.index')); ?>"
                    class="group flex items-center justify-between p-6 sm:px-8 hover:bg-slate-50 dark:hover:bg-white/5 transition-all duration-300">
                    <div class="flex items-center space-x-5">
                        <div
                            class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-500/10 dark:to-blue-500/10 flex items-center justify-center border border-indigo-100 dark:border-indigo-500/20 shadow-sm text-indigo-500 transform group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h4
                                class="text-lg font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">
                                Camp Records</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-0.5 max-w-lg">Track
                                expenses, revenue, and patients for all health camps globally.</p>
                        </div>
                    </div>
                    <!-- Right Chev -->
                    <div
                        class="text-slate-300 dark:text-slate-600 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 group-hover:translate-x-1 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <!-- Placeholder for future modules -->
                <div class="flex items-center justify-between p-6 sm:px-8 opacity-50 cursor-not-allowed">
                    <div class="flex items-center space-x-5">
                        <div
                            class="w-14 h-14 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center border border-slate-100 dark:border-slate-700 shadow-sm text-slate-400 shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-800 dark:text-white">Detailed Reports</h4>
                            <span
                                class="inline-block mt-1 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-slate-100 dark:bg-slate-800 px-2 py-0.5 rounded-md border border-slate-200 dark:border-slate-700">Coming
                                Soon</span>
                        </div>
                    </div>
                    <div class="text-slate-300 dark:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/finances/index.blade.php ENDPATH**/ ?>