<?php
    $effectiveUser = \App\Models\User::getEffectiveUser();
    $canBulkApprove = $effectiveUser->isSuperAdmin() || \App\Models\RolePermission::check($effectiveUser->designation, 'can_approve_users');
?>
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
                        <img src="<?php echo e($u->profile->getProfilePictureUrl()); ?>" alt="Avatar" class="w-full h-full object-cover">
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
                <?php echo e($u->created_at->format('d M, Y')); ?>

            </p>
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
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-300 dark:text-slate-600">—</span>
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
                        <option value="present" <?php echo e(!is_null($todayAtt) && $todayAtt->status === 'present' ? 'selected' : ''); ?>>PRESENT
                        </option>
                        <option value="absent" <?php echo e(!is_null($todayAtt) && $todayAtt->status === 'absent' ? 'selected' : ''); ?>>ABSENT
                        </option>
                    </select>
                <?php else: ?>
                    <?php if($todayAtt): ?>
                        <span
                            class="inline-flex items-center space-x-1 px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest <?php echo e($todayAtt->status === 'present' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600'); ?>">
                            <span
                                class="w-1.5 h-1.5 rounded-full <?php echo e($todayAtt->status === 'present' ? 'bg-emerald-500' : 'bg-rose-500'); ?>"></span>
                            <span><?php echo e(strtoupper($todayAtt->status)); ?></span>
                        </span>
                    <?php else: ?>
                        <span
                            class="inline-flex items-center space-x-1 px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-slate-500/10 text-slate-600">
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Approve</span>
                        </button>
                    </form>
                <?php endif; ?>
                <a href="<?php echo e(route('users.show', $u->id)); ?>" class="p-2 text-slate-400 hover:text-accent transition">
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
                        class="p-2 text-amber-500 hover:bg-amber-500/10 rounded-lg transition" title="View Offer Letter">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </a>
                <?php endif; ?>
                <?php if($effectiveUser->isSuperAdmin()): ?>
                    <a href="<?php echo e(route('users.id-card', $u->id)); ?>" target="_blank"
                        class="p-2 text-violet-500 hover:bg-violet-500/10 rounded-lg transition" title="Generate ID Card">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                    </a>
                    <?php if($u->employee_id !== 'HFSA000001'): ?>
                    <form action="<?php echo e(route('users.destroy', $u->id)); ?>" method="POST" onsubmit="return confirm('Move to BIN?')">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="p-2 text-danger hover:bg-danger/10 rounded-lg transition"
                            title="Delete User">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <?php
        $colCount = 6;
        if ($canBulkApprove)
            $colCount++;
        if ($effectiveUser->isSuperAdmin())
            $colCount++;
    ?>
    <tr>
        <td colspan="<?php echo e($colCount); ?>" class="px-6 py-20 text-center">
            <div class="max-w-xs mx-auto text-slate-400 dark:text-slate-500">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\users\partials\table.blade.php ENDPATH**/ ?>