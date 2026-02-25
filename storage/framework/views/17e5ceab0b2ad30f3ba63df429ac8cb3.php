<?php $__env->startSection('title', 'Attendance Dashboard'); ?>
<?php $__env->startSection('header_title', $user->id === auth()->id() ? 'My Attendance' : ($user->profile->full_name ?? $user->employee_id) . "'s Attendance"); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .calendar-container {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 12px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }

        .calendar-day:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .status-present {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }

        .status-absent {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }

        .status-future {
            background: rgba(255, 255, 255, 0.5);
            border: 1px dashed #cbd5e1;
            color: #94a3b8;
        }

        .dark .status-future {
            background: rgba(30, 41, 59, 0.5);
            border: 1px dashed rgba(255, 255, 255, 0.1);
            color: #475569;
        }

        .calendar-day-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 700;
            text-align: center;
            padding-bottom: 8px;
        }

        .indicator {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: white;
            margin-top: 4px;
            opacity: 0.8;
        }

        .summary-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            transition: all 0.3s ease;
        }

        .dark .summary-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="px-6 pt-6 max-w-7xl mx-auto">
        <div id="filter-panel"
            class="<?php echo e(request()->anyFilled(['district', 'block', 'gram_panchayat', 'designation', 'search', 'status']) ? '' : 'hidden'); ?> p-6 bg-white dark:bg-darkcard rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm mb-6 transition-all">
            <form action="<?php echo e(route('attendance.dashboard')); ?>" method="GET" class="no-loader space-y-4">
                <?php if(request('user_id')): ?>
                    <input type="hidden" name="user_id" value="<?php echo e(request('user_id')); ?>">
                <?php endif; ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1.5">Search
                            Member</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name, ID or Phone..."
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                    </div>

                    <!-- Designation -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Designation</label>
                        <select name="designation"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All Roles</option>
                            <?php $__currentLoopData = $allowedFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($val); ?>" <?php echo e(request('designation') == $val ? 'selected' : ''); ?>><?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- District -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">District</label>
                        <select name="district" id="district-filter"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All Districts</option>
                        </select>
                    </div>

                    <!-- Block -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Block</label>
                        <select name="block" id="block-filter"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All Blocks</option>
                        </select>
                    </div>

                    <!-- GP -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Gram
                            Panchayat</label>
                        <select name="gram_panchayat" id="gp-filter"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All GPs</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1.5">Status
                            Filter</label>
                        <select name="status"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Status</option>
                            <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active Members
                            </option>
                            <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending Approvals
                            </option>
                        </select>
                    </div>

                    <div class="lg:col-span-2 flex items-end space-x-2">
                        <button type="submit"
                            class="h-10 px-6 bg-accent text-white rounded-xl text-sm font-bold hover:opacity-90 transition">Apply
                            Filters</button>
                        <a href="<?php echo e(route('attendance.dashboard')); ?>"
                            class="h-10 px-6 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold flex items-center justify-center hover:opacity-90 transition">Reset</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="calendar-wrapper"
        class="p-6 pt-0 space-y-8 max-w-7xl mx-auto overflow-y-auto h-full pb-20 transition-opacity duration-300">
        <?php echo $__env->make('attendance.partials.calendar_content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </div>

    <script src="<?php echo e(asset('js/locations.js')); ?>"></script>
    <script>
        function toggleFilters() {
            const panel = document.getElementById('filter-panel');
            panel.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const districtSelect = document.getElementById('district-filter');
            const blockSelect = document.getElementById('block-filter');
            const gpSelect = document.getElementById('gp-filter');

            if (districtSelect && typeof locationData !== 'undefined') {
                const state = "West Bengal";
                const districts = locationData[state];

                // Setup Districts
                Object.keys(districts).forEach(district => {
                    const option = new Option(district, district);
                    if ("<?php echo e(request('district')); ?>" === district) option.selected = true;
                    districtSelect.add(option);
                });

                function updateBlocks() {
                    const district = districtSelect.value;
                    blockSelect.innerHTML = '<option value="">All Blocks</option>';
                    gpSelect.innerHTML = '<option value="">All GPs</option>';

                    if (district && districts[district]) {
                        Object.keys(districts[district]).forEach(block => {
                            const option = new Option(block, block);
                            if ("<?php echo e(request('block')); ?>" === block) option.selected = true;
                            blockSelect.add(option);
                        });

                        if ("<?php echo e(request('block')); ?>") {
                            updateGPs();
                        }
                    }
                }

                function updateGPs() {
                    const district = districtSelect.value;
                    const block = blockSelect.value;
                    gpSelect.innerHTML = '<option value="">All GPs</option>';

                    if (district && block && districts[district][block]) {
                        districts[district][block].forEach(gp => {
                            const option = new Option(gp, gp);
                            if ("<?php echo e(request('gram_panchayat')); ?>" === gp) option.selected = true;
                            gpSelect.add(option);
                        });
                    }
                }

                districtSelect.addEventListener('change', updateBlocks);
                blockSelect.addEventListener('change', updateGPs);

                // Initial population if values exist
                if (districtSelect.value) {
                    updateBlocks();
                }
            }
        });

        function loadCalendar(event, url) {
            if (event) event.preventDefault();
            const wrapper = document.getElementById('calendar-wrapper');
            wrapper.style.opacity = '0.5';
            wrapper.style.pointerEvents = 'none';

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    wrapper.innerHTML = data.html;
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = 'auto';

                    // Manually execute scripts in the injected HTML
                    const scripts = wrapper.querySelectorAll('script');
                    scripts.forEach(oldScript => {
                        const newScript = document.createElement('script');
                        Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
                        newScript.appendChild(document.createTextNode(oldScript.innerHTML));
                        oldScript.parentNode.replaceChild(newScript, oldScript);
                    });

                    // Update header title if provided
                    if (data.page_title) {
                        const dashboardTitle = document.querySelector('header h2') || document.querySelector('header h1');
                        if (dashboardTitle) dashboardTitle.innerText = data.page_title;
                    }

                    // Update URL in browser for bookmarking/history
                    window.history.pushState({}, '', url);
                })
                .catch(error => {
                    console.error('Error:', error);
                    wrapper.style.opacity = '1';
                    wrapper.style.pointerEvents = 'auto';
                    Swal.fire('Error', 'Failed to load calendar data', 'error');
                });
        }

        function showDetails(dateRaw, dateFormatted, status, incentive, ta, med, path, mem, ots, total, markedBy, time, userId) {
            const isSuperAdmin = <?php echo e(auth()->user()->isSuperAdmin() ? 'true' : 'false'); ?>;
            const isRO = <?php echo e(auth()->user()->designation === 'ro' ? 'true' : 'false'); ?>;
            // ROs can NEVER edit attendance. Managers/admins can edit if viewing someone else's record.
            const isViewingOther = <?php echo e(auth()->id()); ?> !== userId;

            const canUpdate = !isRO && (isSuperAdmin || isViewingOther);
            const isPastOrToday = new Date(dateRaw) <= new Date(new Date().toDateString());

            Swal.fire({
                title: `<span class="text-2xl font-bold">${dateFormatted}</span>`,
                html: `
                                                                                <div class="text-left space-y-4 p-4">
                                                                                    <div class="flex justify-between items-center border-b border-slate-100 pb-2 dark:border-slate-700">
                                                                                        <span class="text-slate-500 font-medium">Status:</span>
                                                                                        ${canUpdate && isPastOrToday ? `
                                                                                            <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
                                                                                                <button onclick="updateAttendance('${dateRaw}', ${userId}, 'present')" 
                                                                                                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all ${status === 'Present' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-500 hover:text-emerald-600'}">
                                                                                                    Present
                                                                                                </button>
                                                                                                <button onclick="updateAttendance('${dateRaw}', ${userId}, 'absent')" 
                                                                                                    class="px-4 py-1.5 rounded-lg text-xs font-bold transition-all ${status !== 'Present' ? 'bg-rose-500 text-white shadow-lg' : 'text-slate-500 hover:text-rose-600'}">
                                                                                                    Absent
                                                                                                </button>
                                                                                            </div>
                                                                                        ` : `
                                                                                            <span class="px-3 py-1 rounded-full text-xs font-bold ${status === 'Present' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'}">
                                                                                                ${status}
                                                                                            </span>
                                                                                        `}
                                                                                    </div>
                                                                                    <div class="grid grid-cols-2 gap-3">
                                                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Basic Inc.</span>
                                                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(incentive).toLocaleString()}</p>
                                                                                        </div>
                                                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Daily TA</span>
                                                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(ta).toLocaleString()}</p>
                                                                                        </div>
                                                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Medicines</span>
                                                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(med).toLocaleString()}</p>
                                                                                        </div>
                                                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Pathology</span>
                                                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(path).toLocaleString()}</p>
                                                                                        </div>
                                                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Membership</span>
                                                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(mem).toLocaleString()}</p>
                                                                                        </div>
                                                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                                                            <span class="text-[9px] text-slate-400 uppercase font-black">OTs</span>
                                                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(ots).toLocaleString()}</p>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="bg-accent/10 p-4 rounded-xl flex justify-between items-center">
                                                                                        <span class="font-bold text-accent">Total Earning</span>
                                                                                        <span class="text-xl font-black text-accent">₹${parseFloat(total).toLocaleString()}</span>
                                                                                    </div>
                                                                                    <div class="pt-2 text-center">
                                                                                        <p class="text-[11px] text-slate-400">Marked by <span class="text-slate-500 font-semibold">${markedBy}</span> at ${time}</p>
                                                                                    </div>
                                                                                </div>
                                                                            `,
                showConfirmButton: false,
                showCloseButton: true,
                background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                borderRadius: '24px'
            }).then((result) => {
                if (result.isConfirmed) {
                    updateAttendance(dateRaw, userId, status === 'Present' ? 'absent' : 'present');
                }
            });
        }

        function updateAttendance(date, userId, status) {
            Swal.fire({
                title: 'Updating...',
                didOpen: () => Swal.showLoading(),
                ...getSwalConfig()
            });

            fetch("<?php echo e(route('attendance.store')); ?>", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: status,
                    date: date
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                        // Reload current month view
                        const currentUrl = window.location.href;
                        loadCalendar(null, currentUrl);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to update attendance', 'error');
                });
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/attendance/calendar.blade.php ENDPATH**/ ?>