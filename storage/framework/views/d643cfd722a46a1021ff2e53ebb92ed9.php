

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
                                <div id="stat-camp-count" class="text-xl font-black text-white leading-none mt-0.5">
                                    <?php echo e($records->count()); ?></div>
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
                                    <span
                                        id="stat-net-profit">₹<?php echo e(number_format($records->sum('profit') - $records->sum('expenses'), 0)); ?></span>
                                </div>
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

                <form id="filterForm" method="GET" action="<?php echo e(route('camp_records.index')); ?>" class="no-loader">
                    
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
                        <button type="button" id="clearFilters"
                            class="text-xs font-bold text-slate-400 hover:text-red-500 transition-colors flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Clear All Filters</span>
                        </button>
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

        <div id="table-container"
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
                    <tbody id="tableBody" class="divide-y divide-slate-100 dark:divide-white/5">
                        <?php echo $__env->make('camp_records.partials.table', ['records' => $records], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function exportCSV() {
            const form = document.getElementById('filterForm');
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
    <script src="<?php echo e(asset('js/live-filter.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new LiveFilter({
                formId: 'filterForm',
                tableBodyId: 'tableBody',
                paginationId: 'paginationContainer',
                onAfterUpdate: function (data) {
                    if (data && data.stats) {
                        const countEl = document.getElementById('stat-camp-count');
                        const profitEl = document.getElementById('stat-net-profit');
                        if (countEl) countEl.textContent = data.stats.count;
                        if (profitEl) profitEl.textContent = '₹' + Number(data.stats.net_profit).toLocaleString('en-IN');
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/camp_records/index.blade.php ENDPATH**/ ?>