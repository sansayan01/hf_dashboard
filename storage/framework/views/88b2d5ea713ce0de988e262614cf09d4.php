<?php
$effectiveUser = \App\Models\User::getEffectiveUser();
?>
<ul class="space-y-1">
    <?php if($effectiveUser->designation !== 'staff'): ?>
        <li>
            <a href="<?php echo e(route('dashboard')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('dashboard') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                <span>Dashboard</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if($effectiveUser->isSuperAdmin() || \App\Models\RolePermission::check($effectiveUser->designation, 'can_create_surveys')): ?>
        <li>
            <a href="<?php echo e(route('surveys.index')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('surveys.*') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                <span>Survey</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if($effectiveUser->isSuperAdmin() || \App\Models\RolePermission::check($effectiveUser->designation, 'can_create_surveys') || $effectiveUser->designation === 'staff'): ?>
        <li>
            <a href="<?php echo e(route('patients.index')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('patients.*') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span>Patients</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if($effectiveUser->isSuperAdmin() || \App\Models\RolePermission::check($effectiveUser->designation, 'can_manage_appointments')): ?>
        <li>
            <a href="<?php echo e(route('appointments.all')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('appointments.all') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <span>Appointments</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if($effectiveUser->isRO()): ?>
        <li>
            <a href="<?php echo e(route('attendance.dashboard')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->is('attendance*') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                    </path>
                </svg>
                <span>Attendance</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if($effectiveUser->designation !== 'staff'): ?>
        <li>
            <a href="<?php echo e(route('membership.index')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('membership.*') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                    </path>
                </svg>
                <span>Membership</span>
            </a>
        </li>
    <?php endif; ?>
    <?php if($effectiveUser->isSuperAdmin() || $effectiveUser->designation === 'staff' || $effectiveUser->isOfficeInCharge()): ?>
        <li>
            <a href="<?php echo e(route('inventory.index')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('inventory.*') && !request()->routeIs('inventory.camps.*') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.183.244l-.28.14a2 2 0 00-.774 2.58l.14.28a2 2 0 002.58.774l.28-.14a2 2 0 001.183-.244l2.143-.357a6 6 0 013.86-.517l.318-.158a6 6 0 003.86-.517l2.143.428a2 2 0 001.183-.244l.28-.14a2 2 0 00.774-2.58l-.14-.28z">
                    </path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 9.75l.136-.068a2 2 0 012.728.894l.272.544a2 2 0 01-.894 2.728L17.106 14M12 7.5l.136-.068a2 2 0 012.728.894l.272.544a2 2 0 01-.894 2.728L14.106 11.75M9 5.25l.136-.068a2 2 0 012.728.894l.272.544a2 2 0 01-.894 2.728L11.106 9.5">
                    </path>
                </svg>
                <span>Inventory</span>
            </a>
        </li>
    <?php endif; ?>

    <?php if($effectiveUser->canViewDownline()): ?>
        <li>
            <a href="<?php echo e(route('users.index')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('users.*') && !request()->routeIs('users.bin') && !request()->routeIs('users.staffIndex') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span>My Team</span>
            </a>
        </li>

        <?php if($effectiveUser->isSuperAdmin()): ?>
            <li>
                <a href="<?php echo e(route('users.staffIndex')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('users.staffIndex') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span>Staffs</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if($effectiveUser->isSuperAdmin()): ?>
            <li>
                <a href="<?php echo e(route('users.bin')); ?>"
                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e(request()->routeIs('users.bin') ? 'bg-accent text-white shadow-lg' : ''); ?>">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    <span>BIN Recovery</span>
                </a>
            </li>
        <?php endif; ?>
    <?php endif; ?>

    <?php if($effectiveUser->isSuperAdmin()): ?>
        <li>
            <a href="<?php echo e(route('finances.index')); ?>"
                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e((request()->routeIs('finances.*') || request()->routeIs('camp_records.*') || request()->routeIs('expenses.*')) ? 'bg-accent text-white shadow-lg' : ''); ?>">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <span>Finances</span>
            </a>
        </li>
    <?php endif; ?>

    <li>
        <a href="<?php echo e(auth()->user()->isSuperAdmin() ? route('admin.control-panel') : route('profile.edit')); ?>"
            class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium <?php echo e((request()->routeIs('profile.*') || request()->routeIs('admin.control-panel')) ? 'bg-accent text-white shadow-lg' : ''); ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                </path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <span><?php echo e(auth()->user()->isSuperAdmin() ? 'Admin Controls' : 'Account Settings'); ?></span>
        </a>
    </li>
</ul><?php /**PATH C:\xampp\htdocs\HF\resources\views/layouts/partials/sidebar_nav.blade.php ENDPATH**/ ?>