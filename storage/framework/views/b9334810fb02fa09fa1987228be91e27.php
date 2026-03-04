<?php
    $effectiveUser = \App\Models\User::getEffectiveUser();
    $canBulkApprove = $effectiveUser->isSuperAdmin() || \App\Models\RolePermission::check($effectiveUser->designation, 'can_approve_users');
    $canMarkAttendanceHeader = ($effectiveUser->isSuperAdmin() || in_array($effectiveUser->designation, ['hs', 'dm', 'bm', 'rm']));

    $stats = $stats ?? [
        'total_downline' => 0,
        'active_downline' => 0,
        'pending_approvals' => 0,
        'direct_children' => 0
    ];
?>



<?php $__env->startSection('title', 'My Team - Healthcare Foundation'); ?>
<?php $__env->startSection('header_title', 'My Team'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Stats Bar -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white dark:bg-darkcard p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-accent/5 rounded-2xl">
                        <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Team</p>
                        <h4 class="text-2xl font-black text-slate-800 dark:text-white">
                            <?php echo e(number_format($stats['total_downline'])); ?>

                        </h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-darkcard p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-success/5 rounded-2xl">
                        <svg class="w-6 h-6 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Active Members</p>
                        <h4 class="text-2xl font-black text-slate-800 dark:text-white">
                            <?php echo e(number_format($stats['active_downline'])); ?>

                        </h4>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-darkcard p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:shadow-md">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-amber-500/5 rounded-2xl">
                        <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Direct Reports</p>
                        <h4 class="text-2xl font-black text-slate-800 dark:text-white">
                            <?php echo e(number_format($stats['direct_children'])); ?>

                        </h4>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-darkcard p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 transition-all hover:shadow-md cursor-pointer"
                onclick="window.location.href='<?php echo e(route('users.index', ['status' => 'pending'])); ?>'">
                <div class="flex items-center space-x-4">
                    <div class="p-3 bg-rose-500/5 rounded-2xl">
                        <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pending Verification</p>
                        <h4 class="text-2xl font-black text-slate-800 dark:text-white">
                            <?php echo e(number_format($stats['pending_approvals'])); ?>

                        </h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Table Section -->
        <div
            class="bg-white dark:bg-darkcard rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 overflow-hidden">
            <div class="p-4 sm:p-6 border-b border-slate-100 dark:border-white/5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div class="flex items-center space-x-3">
                    <h3 class="text-lg font-black text-slate-800 dark:text-white">Team Members</h3>
                    <span
                        class="px-2 py-0.5 bg-accent/10 text-accent text-[10px] font-black rounded-full uppercase tracking-widest"><?php echo e($users->total()); ?>

                        Total</span>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <!-- View All / Paginate Toggle -->
                    <?php if(request('view_all')): ?>
                        <a href="<?php echo e(route('users.index', request()->except('view_all'))); ?>" title="Paginate"
                            class="px-3 py-2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <span class="hidden">Paginate</span>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('users.index', array_merge(request()->all(), ['view_all' => 1]))); ?>" title="View All"
                            class="px-3 py-2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            <span class="hidden">View All</span>
                        </a>
                    <?php endif; ?>

                    <!-- Export -->
                    <a href="<?php echo e(route('users.export', request()->all())); ?>" title="Download CSV"
                        class="px-3 py-2 bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20 rounded-xl text-xs font-bold hover:bg-emerald-100 dark:hover:bg-emerald-500/20 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="hidden">Download CSV</span>
                    </a>

                    <!-- Filter -->
                    <button type="button" onclick="toggleFilters()" title="Filter"
                        class="px-3 py-2 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-all flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                            </path>
                        </svg>
                        <span class="hidden">Filter</span>
                    </button>

                    <?php if($canBulkApprove): ?>
                        <button type="submit" form="bulk-actions-form" id="bulk-approve-header-btn"
                            class="bulk-approve-btn hidden px-2 sm:px-4 py-2 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20 hover:bg-emerald-600 transition-all flex items-center space-x-2 border border-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="hidden lg:inline">APPROVE SELECTED</span>
                        </button>

                        <button type="submit" form="bulk-actions-form" formaction="<?php echo e(route('users.bulk-print-selection')); ?>"
                            formtarget="_blank" style="background-color: #e11d48; color: white; border-color: #be185d;"
                            title="Print Selected"
                            class="px-3 py-2 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 hover:opacity-90 transition-all flex items-center justify-center border">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM12 17h.01M9 16h6" />
                            </svg>
                            <span class="hidden">PRINT SELECTED</span>
                        </button>
                    <?php endif; ?>

                    <?php if($effectiveUser->canCreateUsers()): ?>
                        <a href="<?php echo e(route('users.create', ['type' => 'team'])); ?>"
                            class="px-4 py-2 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                            + Add Member
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Filter Panel -->
            <div id="filter-panel"
                class="<?php echo e(request()->anyFilled(['district', 'block', 'gram_panchayat', 'designation', 'search']) ? '' : 'hidden'); ?> p-6 border-b border-slate-100 bg-slate-50/50 dark:bg-darkbg/20 transition-all">
                <form action="<?php echo e(route('users.index')); ?>" method="GET" class="no-loader space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-1.5">Search
                                Member</label>
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                placeholder="Name, ID or Phone..."
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
                                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Pending
                                    Approvals</option>
                            </select>
                        </div>

                        <div class="lg:col-span-2 flex items-end space-x-2">
                            <button type="submit"
                                class="h-10 px-6 bg-accent text-white rounded-xl text-sm font-bold hover:opacity-90 transition">Apply
                                Filters</button>
                            <a href="<?php echo e(route('users.index')); ?>"
                                class="h-10 px-6 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold flex items-center justify-center hover:opacity-90 transition">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <form id="bulk-actions-form" action="<?php echo e(route('users.bulk-approve')); ?>" method="POST">
                <?php echo csrf_field(); ?>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-white/5">
                            <?php if($canBulkApprove): ?>
                                <th class="px-6 py-4 w-10 text-center">
                                    <input type="checkbox" id="user-select-all" form="bulk-actions-form"
                                        class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-accent focus:ring-accent"
                                        title="Select All">
                                </th>
                            <?php endif; ?>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                Member Detail</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                Designation</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                Joined On</th>
                            <?php if($effectiveUser->isSuperAdmin()): ?>
                                <th
                                    class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                    Salary Mode</th>
                            <?php endif; ?>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                Today's Attendance</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                Status</th>
                            <th
                                class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 text-right whitespace-nowrap">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 dark:divide-white/5">
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                                <?php if($canBulkApprove): ?>
                                    <td class="px-6 py-4 text-center">
                                        <input type="checkbox" name="selected_users[]" value="<?php echo e($u->id); ?>" form="bulk-actions-form"
                                            data-status="<?php echo e($u->status); ?>"
                                            class="user-checkbox w-4 h-4 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-accent focus:ring-accent">
                                    </td>
                                <?php endif; ?>
                                <td class="px-6 py-4">
                                    <a href="<?php echo e(route('users.show', $u->id)); ?>" class="flex items-center space-x-3 group">
                                        <div
                                            class="w-10 h-10 rounded-full bg-accent/5 text-accent flex items-center justify-center font-bold overflow-hidden border border-slate-100 dark:border-white/5 group-hover:border-accent/30 transition-colors">
                                            <?php if($u->profile?->profile_picture): ?>
                                                <img src="<?php echo e($u->profile->getProfilePictureUrl()); ?>" alt="Avatar"
                                                    class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <?php echo e(substr($u->profile?->full_name ?? $u->employee_id ?? 'U', 0, 1)); ?>

                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <p
                                                class="text-sm font-bold text-slate-800 dark:text-slate-200 group-hover:text-accent transition-colors">
                                                <?php echo e($u->profile?->full_name ?? 'Incomplete Profile'); ?>

                                            </p>
                                            <p class="text-[10px] text-bodydark font-bold uppercase"><?php echo e($u->employee_id); ?></p>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-3 py-1 bg-primary/5 dark:bg-white/5 text-primary dark:text-slate-300 rounded-full text-[10px] font-black uppercase tracking-widest border border-primary/10 dark:border-white/10">
                                        <?php echo e($u->getDesignationLabel()); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                                        <?php echo e($u->created_at->format('d M, Y')); ?></p>
                                </td>
                                <?php if($effectiveUser->isSuperAdmin()): ?>
                                    <td class="px-6 py-4">
                                        <?php if($u->isRO() || $u->isRM() || $u->isBM() || $u->isDM()): ?>
                                            <button onclick="toggleSalaryMode(<?php echo e($u->id); ?>, this)"
                                                class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all cursor-pointer border
                                                                    <?php echo e(($u->salary_mode ?? 'tab') === 'dab' ? 'bg-violet-500/10 text-violet-600 dark:text-violet-400 border-violet-500/20 hover:bg-violet-500/20' : 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-500/20 hover:bg-blue-500/20'); ?>"
                                                data-mode="<?php echo e($u->salary_mode ?? 'tab'); ?>">
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full <?php echo e(($u->salary_mode ?? 'tab') === 'dab' ? 'bg-violet-500' : 'bg-blue-500'); ?>"></span>
                                                <span class="mode-label"><?php echo e(strtoupper($u->salary_mode ?? 'tab')); ?></span>
                                            </button>
                                        <?php else: ?>
                                            <span
                                                class="text-[10px] font-black uppercase tracking-widest text-slate-300 dark:text-slate-600">—</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <td class="px-6 py-4">
                                    <?php
                                        $isMarkableRole = $u->isRO() || $u->isRM() || $u->isBM() || $u->isDM();
                                        $isTabMode = ($u->salary_mode ?? 'tab') === 'tab';
                                    ?>
                                    <?php if($isMarkableRole && $isTabMode): ?>
                                        <?php
                                            $canMark = $effectiveUser->isSuperAdmin() || $effectiveUser->id === $u->parent_id;
                                            $todayAtt = $u->todayAttendance;
                                        ?>
                                        <?php if($canMark): ?>
                                            <select onchange="markAttendance(<?php echo e($u->id); ?>, this.value, this)"
                                                class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 outline-none focus:ring-2 focus:ring-accent/20 transition-all">
                                                <option value="" <?php echo e(is_null($todayAtt) ? 'selected' : ''); ?>>MARK</option>
                                                <option value="present" <?php echo e(!is_null($todayAtt) && $todayAtt->status === 'present' ? 'selected' : ''); ?>>PRESENT</option>
                                                <option value="absent" <?php echo e(!is_null($todayAtt) && $todayAtt->status === 'absent' ? 'selected' : ''); ?>>ABSENT</option>
                                            </select>
                                        <?php else: ?>
                                            <?php if($todayAtt): ?>
                                                <span class="inline-flex items-center space-x-1 px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest <?php echo e($todayAtt->status === 'present' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600'); ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full <?php echo e($todayAtt->status === 'present' ? 'bg-emerald-500' : 'bg-rose-500'); ?>"></span>
                                                    <span><?php echo e(strtoupper($todayAtt->status)); ?></span>
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center space-x-1 px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-500/10 text-slate-600">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                    <span>PENDING</span>
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-300 dark:text-slate-600">—</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if($u->status === 'active'): ?>
                                        <span class="inline-flex items-center space-x-1.5 text-success">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Active</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center space-x-1.5 text-warning">
                                            <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Pending</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <?php if($u->status === 'pending' && $effectiveUser->canApprove($u)): ?>
                                            <form action="<?php echo e(route('users.approve', $u->id)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg transition-all text-[10px] font-black uppercase tracking-widest flex items-center space-x-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                            d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    <span>Approve</span>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('users.show', $u->id)); ?>"
                                            class="p-2 text-slate-400 hover:text-accent transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <?php if($effectiveUser->canAccess($u)): ?>
                                        <a href="<?php echo e(route('users.joining-letter', $u->id)); ?>" target="_blank"
                                            class="p-2 text-amber-500 hover:bg-amber-500/10 rounded-lg transition"
                                            title="View Offer Letter">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </a>
                                        <?php endif; ?>
                                        <?php if($effectiveUser->isSuperAdmin()): ?>
                                            <a href="<?php echo e(route('users.id-card', $u->id)); ?>" target="_blank"
                                                class="p-2 text-violet-500 hover:bg-violet-500/10 rounded-lg transition"
                                                title="Generate ID Card">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                </svg>
                                            </a>
                                            <form action="<?php echo e(route('users.destroy', $u->id)); ?>" method="POST"
                                                onsubmit="return confirm('Move to BIN?')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit"
                                                    class="p-2 text-danger hover:bg-danger/10 rounded-lg transition"
                                                    title="Delete User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php
                                $colCount = 6; // Member Detail, Designation, Joined On, Today's Attendance, Status, Actions
                                if($canBulkApprove) $colCount++;
                                if($effectiveUser->isSuperAdmin()) $colCount++;
                            ?>
                            <tr>
                                <td colspan="<?php echo e($colCount); ?>" class="px-6 py-20 text-center">
                                    <div class="max-w-xs mx-auto text-slate-400 dark:text-slate-500">
                                        <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                        <p class="font-bold text-slate-600 dark:text-slate-400">No downline members found yet.
                                        </p>
                                        <p class="text-xs mt-1">Start growing the foundation by adding new members.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($users instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                <div class="p-6 border-t border-slate-100 italic">
                    <?php echo e($users->links()); ?>

                </div>
            <?php endif; ?>
        </div>

        <!-- Bulk Action Bar -->
        <div id="bulk-action-bar"
            class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-50 animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-4 flex items-center space-x-6">
                <div class="px-4 border-r border-slate-700">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Selected</p>
                    <p class="text-white font-black text-lg" id="selected-count">0</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="submit" form="bulk-actions-form"
                        class="bulk-approve-btn px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Approve Selected</span>
                    </button>
                    <?php if($effectiveUser->isSuperAdmin()): ?>
                        <button type="submit" form="bulk-actions-form" formaction="<?php echo e(route('users.bulk-print-selection')); ?>"
                            formtarget="_blank" style="background-color: #e11d48; color: white; border-color: #be185d;"
                            class="px-4 py-2.5 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-rose-600/20 hover:opacity-90 flex items-center justify-center border">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM12 17h.01M9 16h6" />
                            </svg>
                            <span class="hidden">PRINT SELECTED</span>
                        </button>
                    <?php endif; ?>
                    <button type="button" onclick="cancelSelection()"
                        class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-black uppercase tracking-widest rounded-xl transition-all">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(asset('js/locations.js')); ?>"></script>
    <script>
        function toggleFilters() {
            const panel = document.getElementById('filter-panel');
            panel.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const selectAll = document.getElementById('user-select-all');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const bulkBar = document.getElementById('bulk-action-bar');
            const selectedCount = document.getElementById('selected-count');

            function updateBulkBar() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                const checkedCount = checkedBoxes.length;
                selectedCount.textContent = checkedCount;

                if (checkedCount > 0) {
                    bulkBar.classList.remove('hidden');
                    let hasActive = false;
                    checkedBoxes.forEach(cb => {
                        if (cb.getAttribute('data-status') === 'active') hasActive = true;
                    });
                    const approveButtons = document.querySelectorAll('.bulk-approve-btn');
                    approveButtons.forEach(btn => hasActive ? btn.classList.add('hidden') : btn.classList.remove('hidden'));
                } else {
                    bulkBar.classList.add('hidden');
                    const headerBtn = document.getElementById('bulk-approve-header-btn');
                    if (headerBtn) headerBtn.classList.add('hidden');
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateBulkBar();
                });
            }

            checkboxes.forEach(cb => cb.addEventListener('change', updateBulkBar));

            window.cancelSelection = function () {
                checkboxes.forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
                updateBulkBar();
            }

            // Location Filters
            const districtSelect = document.getElementById('district-filter');
            const blockSelect = document.getElementById('block-filter');
            const gpSelect = document.getElementById('gp-filter');
            const state = "West Bengal";
            const districts = locationData[state];

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
                    if ("<?php echo e(request('block')); ?>") updateGPs();
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
            if (districtSelect.value) updateBlocks();
        });

        function toggleSalaryMode(userId, element) {
            const oldMode = element.dataset.mode || 'tab';
            const newMode = oldMode === 'dab' ? 'tab' : 'dab';
            const originalContent = element.innerHTML;
            const originalClasses = element.className;

            element.dataset.mode = newMode;
            element.innerHTML = `<span class="w-1.5 h-1.5 rounded-full ${newMode === 'dab' ? 'bg-violet-500' : 'bg-blue-500'}"></span><span class="mode-label">${newMode.toUpperCase()}</span>`;
            element.classList.remove('bg-violet-500/10', 'text-violet-600', 'dark:text-violet-400', 'border-violet-500/20', 'hover:bg-violet-500/20', 'bg-blue-500/10', 'text-blue-600', 'dark:text-blue-400', 'border-blue-500/20', 'hover:bg-blue-500/20');
            element.classList.add(newMode === 'dab' ? 'bg-violet-500/10' : 'bg-blue-500/10', newMode === 'dab' ? 'text-violet-600' : 'text-blue-600', 'border-' + (newMode === 'dab' ? 'violet' : 'blue') + '-500/20');

            let url = "<?php echo e(route('users.toggle-salary-mode', ':id')); ?>".replace(':id', userId);
            fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' } })
                .then(response => response.json())
                .then(data => {
                    if (data.success) Toast.fire({ icon: 'success', title: data.message, timer: 1500, showConfirmButton: false });
                    else throw new Error(data.message || 'Server Error');
                })
                .catch(error => {
                    element.dataset.mode = oldMode;
                    element.innerHTML = originalContent;
                    element.className = originalClasses;
                    Toast.fire({ icon: 'error', title: 'Action Failed: ' + error.message });
                });
        }

        function markAttendance(userId, status, element) {
            if (!status) return;
            fetch('<?php echo e(route("attendance.store")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: status,
                    date: '<?php echo e(date("Y-m-d")); ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.attendance) {
                    Toast.fire({ icon: 'success', title: data.message, timer: 1500, showConfirmButton: false });
                } else {
                    Toast.fire({ icon: 'error', title: data.message || 'Something went wrong' });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Toast.fire({ icon: 'error', title: 'System Error' });
            });
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\users\index.blade.php ENDPATH**/ ?>