<?php $__env->startSection('title', 'All Appointments'); ?>
<?php $__env->startSection('header_title', 'Appointment Central'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2 relative z-50">
            <div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">
                    <?php if(request('view') === 'successful'): ?>
                        Successful Appointments
                    <?php elseif(request('view') === 'not_attended'): ?>
                        Not Attended Registry
                    <?php else: ?>
                        Scheduled Appointments
                    <?php endif; ?>
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest mt-1 italic">
                    <?php if(request('view') === 'successful'): ?>
                        History of completed clinical visits
                    <?php elseif(request('view') === 'not_attended'): ?>
                        Records of missed or unfulfilled appointments
                    <?php else: ?>
                        Managing upcoming clinic visits across all registry
                    <?php endif; ?>
                </p>
            </div>
            <div class="flex items-center gap-3">
                <!-- Registry Filter Dropdown -->
                <div class="relative z-[60]">
                    <button type="button" onclick="toggleDropdown('appointment-filter-dropdown')"
                        class="px-5 py-3 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-black uppercase tracking-widest rounded-xl border border-slate-200/10 dark:border-white/5 shadow-sm flex items-center gap-2 hover:border-accent transition-all active:scale-95">
                        <span
                            class="w-2 h-2 rounded-full <?php echo e(request('view') === 'successful' ? 'bg-emerald-500' : (request('view') === 'not_attended' ? 'bg-rose-500' : 'bg-accent')); ?>"></span>
                        <?php if(request('view') === 'successful'): ?>
                            View: Successful
                        <?php elseif(request('view') === 'not_attended'): ?>
                            View: Not Attended
                        <?php else: ?>
                            View: Scheduled
                        <?php endif; ?>
                        <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" id="dropdown-arrow"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="appointment-filter-dropdown"
                        class="hidden absolute right-0 mt-2 w-72 bg-white dark:bg-slate-900 rounded-[2rem] shadow-2xl border border-slate-100 dark:border-white/5 p-4 z-[100] transition-all transform origin-top-right opacity-0 invisible scale-95 translate-y-2">
                        <div class="grid grid-cols-1 gap-3">
                            <button type="button" onclick="setAppointmentView('scheduled')"
                                class="w-full text-left group/tile flex items-center p-3 rounded-2xl bg-accent/5 hover:bg-accent border border-accent/10 hover:border-accent transition-all duration-300">
                                <div
                                    class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm group-hover/tile:scale-110 transition-transform">
                                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-xs font-black uppercase tracking-widest text-accent group-hover/tile:text-white transition-colors">
                                        Scheduled</p>
                                    <p
                                        class="text-[10px] font-bold text-slate-400 group-hover/tile:text-white/70 transition-colors">
                                        Upcoming clinical visits</p>
                                </div>
                                </a>

                                <button type="button" onclick="setAppointmentView('successful')"
                                    class="w-full text-left group/tile flex items-center p-3 rounded-2xl bg-emerald-500/5 hover:bg-emerald-500 border border-emerald-500/10 hover:border-emerald-500 transition-all duration-300">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm group-hover/tile:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p
                                            class="text-xs font-black uppercase tracking-widest text-emerald-600 group-hover/tile:text-white transition-colors">
                                            Successful</p>
                                        <p
                                            class="text-[10px] font-bold text-slate-400 group-hover/tile:text-white/70 transition-colors">
                                            Completed health checkups</p>
                                    </div>
                                </button>

                                <button type="button" onclick="setAppointmentView('not_attended')"
                                    class="w-full text-left group/tile flex items-center p-3 rounded-2xl bg-rose-500/5 hover:bg-rose-500 border border-rose-500/10 hover:border-rose-500 transition-all duration-300">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 flex items-center justify-center shadow-sm group-hover/tile:scale-110 transition-transform">
                                        <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <p
                                            class="text-xs font-black uppercase tracking-widest text-rose-600 group-hover/tile:text-white transition-colors">
                                            Not Attended</p>
                                        <p
                                            class="text-[10px] font-bold text-slate-400 group-hover/tile:text-white/70 transition-colors">
                                            Missed or skipped visits</p>
                                    </div>
                                </button>
                        </div>
                    </div>
                </div>

                <!-- View All / Paginate Toggle -->
                <?php if(request('view_all')): ?>
                    <a href="<?php echo e(route('appointments.all', request()->except('view_all'))); ?>"
                        class="w-10 h-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl border border-slate-200/10 dark:border-white/5 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center shadow-sm"
                        title="Paginate">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('appointments.all', array_merge(request()->all(), ['view_all' => 1]))); ?>"
                        class="w-10 h-10 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl border border-slate-200/10 dark:border-white/5 hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center shadow-sm"
                        title="View All">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                    </a>
                <?php endif; ?>

                <a href="<?php echo e(route('appointments.export', request()->all())); ?>"
                    class="w-10 h-10 bg-emerald-500/10 text-emerald-600 rounded-xl border border-emerald-500/10 hover:bg-emerald-500 hover:text-white transition-all flex items-center justify-center shadow-sm"
                    title="Download Filtered CSV">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </a>

                <span
                    class="px-4 py-3 bg-slate-100 dark:bg-slate-800 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-xl border border-slate-200/10 dark:border-white/5">
                    <span id="stat-total"><?php echo e($appointments->total()); ?></span> Records
                </span>
            </div>
        </div>

        <!-- Search & Filter Bar -->
        <div
            class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm">
            <form id="filterForm" action="<?php echo e(route('appointments.all')); ?>" method="GET"
                class="no-loader flex flex-col gap-4">
                <div class="flex flex-col lg:flex-row items-center gap-4 w-full">
                    <div class="flex-1 w-full relative">
                        <input type="text" name="search" id="search-input" value="<?php echo e(request('search')); ?>"
                            placeholder="Search Patient Name, ID, or Clinic Type..."
                            class="w-full pl-12 pr-4 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <div class="w-full lg:w-48 relative">
                        <input type="date" name="date" value="<?php echo e(request('date')); ?>"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white">
                    </div>

                    <div class="flex items-center space-x-3 w-full lg:w-auto">
                        <input type="hidden" name="view" value="<?php echo e(request('view', 'scheduled')); ?>">
                        <button type="button"
                            onclick="document.getElementById('advanced-filters').classList.toggle('hidden')"
                            class="px-4 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-2xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </button>
                        <button type="submit"
                            class="flex-1 lg:flex-none px-8 py-4 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition-all">
                            Filter
                        </button>
                        <?php if(request()->anyFilled(['search', 'date', 'district', 'block', 'gp'])): ?>
                            <a href="<?php echo e(route('appointments.all', ['view' => request('view', 'scheduled')])); ?>"
                                class="px-6 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-danger rounded-2xl transition-all text-[10px] font-black uppercase tracking-widest border border-transparent hover:border-danger/20">
                                Reset
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Advanced Filters -->
                <div id="advanced-filters"
                    class="<?php echo e(request()->anyFilled(['district', 'block', 'gp']) ? '' : 'hidden'); ?> grid grid-cols-1 md:grid-cols-3 gap-4 border-t border-slate-100 dark:border-white/5 pt-4">
                    <!-- District -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">District</label>
                        <select name="district" id="district-select"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Districts</option>
                            <!-- Populated by JS -->
                        </select>
                    </div>

                    <!-- Block -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Block</label>
                        <select name="block" id="block-select"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Blocks</option>
                            <!-- Populated by JS -->
                        </select>
                    </div>

                    <!-- Gram Panchayat -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Gram
                            Panchayat</label>
                        <select name="gp" id="gp-select"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All GPs</option>
                            <!-- Populated by JS -->
                        </select>
                    </div>
                </div>

                <script src="<?php echo e(asset('js/locations.js')); ?>"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const districtSelect = document.getElementById('district-select');
                        const blockSelect = document.getElementById('block-select');
                        const gpSelect = document.getElementById('gp-select');

                        const currentDistrict = "<?php echo e(request('district')); ?>";
                        const currentBlock = "<?php echo e(request('block')); ?>";
                        const currentGp = "<?php echo e(request('gp')); ?>";

                        // 1. Populate Districts from all States
                        if (window.locationData) {
                            let allDistricts = [];
                            for (const state in window.locationData) {
                                allDistricts = allDistricts.concat(Object.keys(window.locationData[state]));
                            }
                            allDistricts = [...new Set(allDistricts)].sort();

                            allDistricts.forEach(district => {
                                const option = new Option(district, district);
                                if (district === currentDistrict) option.selected = true;
                                districtSelect.add(option);
                            });
                        }

                        function updateBlocks() {
                            const selectedDistrict = districtSelect.value;
                            blockSelect.innerHTML = '<option value="">All Blocks</option>';
                            gpSelect.innerHTML = '<option value="">All GPs</option>';

                            if (selectedDistrict && window.locationData) {
                                let selectedState = null;
                                for (const state in window.locationData) {
                                    if (window.locationData[state][selectedDistrict]) {
                                        selectedState = state;
                                        break;
                                    }
                                }

                                if (selectedState) {
                                    const blocks = Object.keys(window.locationData[selectedState][selectedDistrict]).sort();
                                    blocks.forEach(block => {
                                        const option = new Option(block, block);
                                        if (block === currentBlock) option.selected = true;
                                        blockSelect.add(option);
                                    });
                                }
                            }
                            updateGps();
                        }

                        function updateGps() {
                            const selectedDistrict = districtSelect.value;
                            const selectedBlock = blockSelect.value;
                            gpSelect.innerHTML = '<option value="">All GPs</option>';

                            if (selectedDistrict && selectedBlock && window.locationData) {
                                let selectedState = null;
                                for (const state in window.locationData) {
                                    if (window.locationData[state][selectedDistrict]) {
                                        selectedState = state;
                                        break;
                                    }
                                }

                                if (selectedState && window.locationData[selectedState][selectedDistrict][selectedBlock]) {
                                    const gps = window.locationData[selectedState][selectedDistrict][selectedBlock].sort();
                                    gps.forEach(gp => {
                                        const option = new Option(gp, gp);
                                        if (gp === currentGp) option.selected = true;
                                        gpSelect.add(option);
                                    });
                                }
                            }
                        }

                        districtSelect.addEventListener('change', updateBlocks);
                        blockSelect.addEventListener('change', updateGps);

                        // Initial Call
                        updateBlocks();
                    });
                </script>
            </form>
        </div>

        <div id="appointments-container">
            <?php if($appointments->isEmpty()): ?>
                <div
                    class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl p-20 text-center">
                    <div class="w-24 h-24 bg-accent/10 text-accent rounded-3xl flex items-center justify-center mx-auto mb-8">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-2xl font-black text-slate-800 dark:text-white mb-3">No Appointments Found</h4>
                    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto font-medium leading-relaxed">
                        There are no clinic visits scheduled matching your criteria. You can schedule new ones from individual
                        patient profiles.
                    </p>
                </div>
            <?php else: ?>
                <div
                    class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                                    
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Patient</th>
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Schedule
                                    </th>
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Clinic Type
                                    </th>
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Location
                                    </th>
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Recorded By
                                    </th>
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="divide-y divide-slate-100 dark:divide-white/5">
                                <?php echo $__env->make('appointments.partials.table', ['appointments' => $appointments], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div id="paginationContainer">
                        <?php if($appointments->hasPages()): ?>
                            <div class="p-8 border-t border-slate-100 dark:border-white/5">
                                <?php echo e($appointments->links()); ?>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            const arrow = document.getElementById('dropdown-arrow');

            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
                setTimeout(() => {
                    dropdown.classList.remove('opacity-0', 'invisible', 'translate-y-2', 'scale-95');
                    arrow.classList.add('rotate-180');
                }, 10);
            } else {
                dropdown.classList.add('opacity-0', 'invisible', 'translate-y-2', 'scale-95');
                arrow.classList.remove('rotate-180');
                setTimeout(() => {
                    dropdown.classList.add('hidden');
                }, 200);
            }
        }

        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('appointment-filter-dropdown');
            const button = dropdown.previousElementSibling;
            if (!dropdown.contains(event.target) && !button.contains(event.target)) {
                if (!dropdown.classList.contains('hidden')) {
                    toggleDropdown('appointment-filter-dropdown');
                }
            }
        });
    </script>
    <script src="<?php echo e(asset('js/live-filter.js')); ?>"></script>
    <script>
        function setAppointmentView(view) {
            const form = document.getElementById('filterForm');
            const viewInput = form.querySelector('input[name="view"]');
            if (viewInput) {
                viewInput.value = view;
                toggleDropdown('appointment-filter-dropdown');
                if (window._liveFilter) window._liveFilter.applyFilters();
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            window._liveFilter = new LiveFilter({
                formId: 'filterForm',
                tableBodyId: 'tableBody',
                paginationId: 'paginationContainer',
                onAfterUpdate: function (data) {
                    if (data && data.total !== undefined) {
                        const totalEl = document.getElementById('stat-total');
                        if (totalEl) totalEl.textContent = data.total;
                    }
                }
            });
        });
    </script>
    </div>
<?php $__env->stopSection(); ?>
```
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/appointments/all.blade.php ENDPATH**/ ?>