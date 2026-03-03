

<?php $__env->startSection('title', 'Camp Records'); ?>
<?php $__env->startSection('header_title', 'Camp Records Management'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-7xl mx-auto pb-12">
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

            <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-8">
                <div class="max-w-2xl">
                    <div
                        class="inline-flex items-center space-x-2 bg-white/10 backdrop-blur-md px-3 py-1 rounded-full text-white/90 text-[10px] font-black uppercase tracking-widest mb-4 border border-white/10 shadow-inner">
                        <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span>Financial Overview</span>
                    </div>
                    <h2 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-3 drop-shadow-md">
                        Camp Records Directory
                    </h2>
                    <p class="text-indigo-100/80 font-medium text-sm md:text-base leading-relaxed">
                        Manage and analyze the financial and demographic data of all held health camps. Track profitability,
                        expenses, and patient reach in one unified dashboard.
                    </p>

                    <div class="mt-6 flex items-center space-x-4">
                        <div class="flex items-center space-x-3 bg-white/5 rounded-2xl p-3 border border-white/10">
                            <div class="w-10 h-10 rounded-xl bg-accent/20 flex items-center justify-center text-accent">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-white/50 font-bold uppercase tracking-wider">Total Camps</div>
                                <div class="text-xl font-black text-white leading-none mt-0.5"><?php echo e($records->count()); ?></div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 bg-white/5 rounded-2xl p-3 border border-white/10">
                            <div
                                class="w-10 h-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-[10px] text-white/50 font-bold uppercase tracking-wider">Net Profit Total
                                </div>
                                <div class="text-xl font-black text-white leading-none mt-0.5">
                                    ₹<?php echo e(number_format($records->sum('profit') - $records->sum('expenses'), 0)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="<?php echo e(route('camp_records.create')); ?>"
                    class="group inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-accent to-blue-500 text-white px-8 py-4 rounded-2xl font-black text-sm shadow-xl shadow-accent/30 hover:shadow-2xl hover:shadow-accent/40 hover:-translate-y-1 transition-all active:scale-95 duration-300 shrink-0">
                    <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span>Add Record</span>
                </a>
            </div>
        </div>

        <?php if(session('success')): ?>
            <div class="mb-8">
                <div
                    class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 px-6 py-4 rounded-2xl font-bold text-sm flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span><?php echo e(session('success')); ?></span>
                </div>
            </div>
        <?php endif; ?>

        
        <div class="mb-8" id="filter-wrapper">
            
            <button
                onclick="document.getElementById('filter-panel').classList.toggle('hidden'); document.getElementById('filter-wrapper').querySelector('.chevron-icon').classList.toggle('rotate-180'); this.classList.toggle('rounded-b-none'); this.classList.toggle('border-b-0');"
                type="button"
                class="w-full flex items-center justify-between bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/60 dark:border-white/10 rounded-2xl px-6 py-4 hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-all duration-300 group shadow-sm hover:shadow-md <?php echo e(request()->hasAny(['search', 'date_from', 'date_to', 'location', 'rm', 'doctor', 'profit_status', 'min_patients']) ? 'rounded-b-none border-b-0' : ''); ?>">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/20">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-sm font-bold text-slate-700 dark:text-white">Advanced Filters</span>
                        <?php
                            $activeFilters = collect(['search', 'date_from', 'date_to', 'location', 'rm', 'doctor', 'profit_status', 'min_patients'])
                                ->filter(fn($f) => request()->filled($f))->count();
                        ?>
                        <?php if($activeFilters > 0): ?>
                            <span
                                class="ml-2 px-2 py-0.5 bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] font-black"><?php echo e($activeFilters); ?>

                                active</span>
                        <?php endif; ?>
                    </div>
                </div>
                <svg class="chevron-icon w-5 h-5 text-slate-400 transition-transform duration-300 <?php echo e(request()->hasAny(['search', 'date_from', 'date_to', 'location', 'rm', 'doctor', 'profit_status', 'min_patients']) ? 'rotate-180' : ''); ?>"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            
            <div id="filter-panel"
                class="bg-white/80 dark:bg-slate-900/60 backdrop-blur-xl border border-slate-200/60 dark:border-white/10 border-t-0 rounded-b-2xl px-6 pb-6 pt-2 shadow-lg <?php echo e(request()->hasAny(['search', 'date_from', 'date_to', 'location', 'rm', 'doctor', 'profit_status', 'min_patients']) ? '' : 'hidden'); ?>">

                <form method="GET" action="<?php echo e(route('camp_records.index')); ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-4">
                        
                        <div class="md:col-span-4">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">Search</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                    class="w-full h-10 pl-10 pr-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none"
                                    placeholder="Camp name, location, RM...">
                            </div>
                        </div>

                        
                        <div class="md:col-span-2">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">Date
                                From</label>
                            <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                                class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                        </div>

                        
                        <div class="md:col-span-2">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">Date
                                To</label>
                            <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                                class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none">
                        </div>

                        
                        <div class="md:col-span-4">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">Location</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <input type="text" name="location" value="<?php echo e(request('location')); ?>"
                                    class="w-full h-10 pl-10 pr-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none"
                                    placeholder="Filter by location...">
                            </div>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-5">
                        
                        <div class="md:col-span-3">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">RM
                                Name</label>
                            <input type="text" name="rm" value="<?php echo e(request('rm')); ?>"
                                class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none"
                                placeholder="Relationship Manager...">
                        </div>

                        
                        <div class="md:col-span-3">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">Doctor</label>
                            <input type="text" name="doctor" value="<?php echo e(request('doctor')); ?>"
                                class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none"
                                placeholder="Doctor name...">
                        </div>

                        
                        <div class="md:col-span-3">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">P/L
                                Status</label>
                            <select name="profit_status"
                                class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none appearance-none">
                                <option value="">All</option>
                                <option value="profit" <?php echo e(request('profit_status') === 'profit' ? 'selected' : ''); ?>>Profit
                                    Only</option>
                                <option value="loss" <?php echo e(request('profit_status') === 'loss' ? 'selected' : ''); ?>>Loss Only
                                </option>
                            </select>
                        </div>

                        
                        <div class="md:col-span-3">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1.5">Min
                                Patients</label>
                            <input type="number" name="min_patients" value="<?php echo e(request('min_patients')); ?>" min="0"
                                class="w-full h-10 px-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-300 placeholder-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 transition-all outline-none"
                                placeholder="0">
                        </div>
                    </div>

                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                        <a href="<?php echo e(route('camp_records.index')); ?>"
                            class="text-xs font-bold text-slate-400 hover:text-red-500 transition-colors flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Clear All Filters</span>
                        </a>
                        <div class="flex items-center space-x-3">
                            <button type="button" onclick="exportCSV()"
                                class="inline-flex items-center space-x-2 bg-white dark:bg-slate-800 border border-emerald-300 dark:border-emerald-500/30 text-emerald-600 dark:text-emerald-400 px-5 py-2.5 rounded-xl text-xs font-black hover:bg-emerald-50 dark:hover:bg-emerald-500/10 hover:border-emerald-400 hover:-translate-y-0.5 transition-all active:scale-95 shadow-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Download CSV</span>
                            </button>
                            <button type="submit"
                                class="inline-flex items-center space-x-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-2.5 rounded-xl text-xs font-black shadow-lg shadow-indigo-500/20 hover:shadow-xl hover:shadow-indigo-500/30 hover:-translate-y-0.5 transition-all active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                <span>Apply Filters</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if($records->isEmpty()): ?>
            <div
                class="bg-white/60 dark:bg-slate-900/40 backdrop-blur-xl rounded-3xl border border-slate-200/50 dark:border-white/5 shadow-xl p-20 text-center relative overflow-hidden">
                <!-- Decorative background -->
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0zNiAzNHYtbDItMiAydjRWMzR6IiBmaWxsPSIjOWNhM2FmIiBmaWxsLW9wYWNpdHk9IjAuMSIvPjwvZz48L3N2Zz4=')] opacity-50">
                </div>

                <div
                    class="relative z-10 w-32 h-32 bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-slate-900 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner border border-white dark:border-slate-700/50 rotate-3 transition-transform hover:rotate-6 duration-500">
                    <svg class="w-16 h-16 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h4 class="text-3xl font-black text-slate-800 dark:text-white mb-4 tracking-tight">No Camp Records Found</h4>
                <p class="text-slate-500 dark:text-slate-400 max-w-lg mx-auto mb-10 text-lg leading-relaxed">
                    The directory is currently empty. Start tracking your health camps by adding your first financial record.
                </p>
                <a href="<?php echo e(route('camp_records.create')); ?>"
                    class="inline-flex items-center justify-center space-x-2 bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-8 py-3.5 rounded-xl font-bold text-sm shadow-xl hover:shadow-2xl transition-all active:scale-95">
                    <span>Create First Record</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        <?php else: ?>
            <div
                class="bg-white dark:bg-slate-900/60 backdrop-blur-xl rounded-3xl border border-slate-200/50 dark:border-white/5 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="border-b border-slate-200 dark:border-white/10 bg-slate-50/80 dark:bg-white/5">
                                <th
                                    class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                    Date & Location</th>
                                <th
                                    class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                    Camp Details</th>
                                <th
                                    class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">
                                    Patients</th>
                                <th
                                    class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">
                                    Finances (₹)</th>
                                <th
                                    class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">
                                    Net P/L (₹)</th>
                                <th
                                    class="p-5 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="p-5 align-top">
                                        <div class="flex flex-col space-y-1">
                                            <span class="inline-flex items-center space-x-1.5 w-max">
                                                <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span
                                                    class="text-sm font-bold text-slate-800 dark:text-slate-200"><?php echo e(\Carbon\Carbon::parse($record->date)->format('M d, Y')); ?></span>
                                            </span>
                                            <?php if($record->location): ?>
                                                <span
                                                    class="inline-flex items-center space-x-1 w-max text-slate-500 dark:text-slate-400 mt-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span class="text-xs font-medium truncate max-w-[150px] block"
                                                        title="<?php echo e($record->location); ?>"><?php echo e($record->location); ?></span>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="p-5 align-top">
                                        <div class="font-bold text-slate-800 dark:text-white text-base mb-1">
                                            <?php echo e($record->camp_name); ?>

                                        </div>
                                        <?php if($record->rm): ?>
                                            <div
                                                class="text-[10px] font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-wider mb-2">
                                                RM: <?php echo e($record->rm); ?>

                                            </div>
                                        <?php endif; ?>
                                        <div class="flex flex-wrap gap-1.5 mt-1">
                                            <?php if($record->doctor_name): ?>
                                                <span
                                                    class="inline-flex items-center space-x-1 bg-blue-50/80 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 text-blue-700 dark:text-blue-400 px-2 py-0.5 rounded-lg text-[10px] font-bold">
                                                    <i class="fas fa-user-md opacity-70"></i><span><?php echo e($record->doctor_name); ?></span>
                                                </span>
                                            <?php endif; ?>
                                            <?php if($record->pathologist): ?>
                                                <span
                                                    class="inline-flex items-center space-x-1 bg-purple-50/80 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 text-purple-700 dark:text-purple-400 px-2 py-0.5 rounded-lg text-[10px] font-bold">
                                                    <i class="fas fa-microscope opacity-70"></i><span><?php echo e($record->pathologist); ?></span>
                                                </span>
                                            <?php endif; ?>
                                            <?php if($record->pharmacists_name): ?>
                                                <span
                                                    class="inline-flex items-center space-x-1 bg-teal-50/80 dark:bg-teal-500/10 border border-teal-200 dark:border-teal-500/20 text-teal-700 dark:text-teal-400 px-2 py-0.5 rounded-lg text-[10px] font-bold">
                                                    <i class="fas fa-pills opacity-70"></i><span><?php echo e($record->pharmacists_name); ?></span>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="p-5 align-middle text-center">
                                        <div
                                            class="inline-flex flex-col items-center justify-center w-14 h-14 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 shadow-sm">
                                            <span class="text-xs text-slate-400 font-bold mb-0.5"><i
                                                    class="fas fa-users"></i></span>
                                            <span
                                                class="text-sm font-black text-slate-700 dark:text-slate-200 font-mono"><?php echo e($record->patients_count); ?></span>
                                        </div>
                                    </td>

                                    <td class="p-5 align-middle text-right min-w-[140px]">
                                        <div class="flex flex-col items-end space-y-1">
                                            <div class="flex items-center justify-between w-full space-x-3 text-xs">
                                                <span class="text-slate-500">Buy %</span>
                                                <span
                                                    class="font-bold text-slate-800 dark:text-slate-200 font-mono"><?php echo e(number_format($record->buying_percentage, 1)); ?>%</span>
                                            </div>
                                            <div class="flex items-center justify-between w-full space-x-3 text-[10px]">
                                                <span class="text-slate-400">Profit</span>
                                                <span
                                                    class="font-bold text-emerald-600 dark:text-emerald-400 font-mono"><?php echo e(number_format($record->profit, 2)); ?></span>
                                            </div>
                                            <div class="flex items-center justify-between w-full space-x-3 text-[10px]">
                                                <span class="text-slate-400">Exp.</span>
                                                <span
                                                    class="font-bold text-red-500 dark:text-red-400 font-mono"><?php echo e(number_format($record->expenses, 2)); ?></span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="p-5 align-middle text-right">
                                        <?php $actualNetPL = ($record->profit ?? 0) - ($record->expenses ?? 0); ?>
                                        <?php if($actualNetPL >= 0): ?>
                                            <div class="inline-flex flex-col items-end">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-lg text-sm font-black border border-emerald-500/20 font-mono shadow-sm">
                                                    +<?php echo e(number_format($actualNetPL, 2)); ?>

                                                </span>
                                                <span
                                                    class="text-[9px] font-bold text-emerald-500/70 uppercase tracking-widest mt-1">Profit</span>
                                            </div>
                                        <?php else: ?>
                                            <div class="inline-flex flex-col items-end">
                                                <span
                                                    class="inline-flex items-center px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg text-sm font-black border border-red-500/20 font-mono shadow-sm">
                                                    <?php echo e(number_format($actualNetPL, 2)); ?>

                                                </span>
                                                <span
                                                    class="text-[9px] font-bold text-red-500/70 uppercase tracking-widest mt-1">Loss</span>
                                            </div>
                                        <?php endif; ?>
                                    </td>

                                    <td class="p-5 align-middle text-right">
                                        <div
                                            class="flex items-center justify-end space-x-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300">
                                            <a href="<?php echo e(route('camp_records.pdf', $record->id)); ?>?preview=1"
                                                target="_blank"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-500 hover:bg-emerald-50 dark:hover:border-emerald-500 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:text-slate-300 text-slate-500 shadow-sm transition-all"
                                                title="Preview PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="<?php echo e(route('camp_records.pdf', $record->id)); ?>"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-indigo-500 hover:bg-indigo-50 dark:hover:border-indigo-500 dark:hover:bg-indigo-500/20 hover:text-indigo-600 dark:text-slate-300 text-slate-500 shadow-sm transition-all"
                                                title="Download PDF">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            </a>
                                            <a href="<?php echo e(route('camp_records.edit', $record->id)); ?>"
                                                class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-accent hover:bg-accent/5 dark:hover:border-accent dark:hover:bg-accent/20 hover:text-accent dark:text-slate-300 text-slate-500 shadow-sm transition-all"
                                                title="Edit Record">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </a>
                                            <form action="<?php echo e(route('camp_records.destroy', $record->id)); ?>" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('WARNING: Are you sure you want to permanently delete this camp record? This action cannot be undone.')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-red-500 hover:bg-red-50 dark:hover:border-red-500 dark:hover:bg-red-500/20 hover:text-red-600 dark:text-slate-300 text-slate-500 shadow-sm transition-all"
                                                    title="Delete Record">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Optional Pagination Container (If you add pagination later) -->
                <?php if($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages()): ?>
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-slate-800/20">
                        <?php echo e($records->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function exportCSV() {
            const form = document.querySelector('#filter-panel form');
            const params = new URLSearchParams();
            if (form) {
                const inputs = form.querySelectorAll('input[name], select[name]');
                inputs.forEach(function (el) {
                    if (el.value && el.value.trim() !== '') {
                        params.set(el.name, el.value.trim());
                    }
                });
            }
            params.set('_t', Date.now());
            const url = "<?php echo e(route('camp_records.export')); ?>" + '?' + params.toString();
            window.location.href = url;
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/camp_records/index.blade.php ENDPATH**/ ?>