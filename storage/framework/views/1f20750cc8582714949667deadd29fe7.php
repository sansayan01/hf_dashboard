<?php
    $children = $item->getDirectChildren();
    $hasChildren = $children->count() > 0;
    $statusColor = $item->status === 'active' ? 'text-emerald-500' : 'text-amber-500';
?>

<div class="tree-item" data-id="<?php echo e($item->id); ?>">
    <div
        class="flex items-center group py-2 px-4 rounded-xl bg-gradient-to-b from-white to-slate-50 dark:from-slate-800 dark:to-slate-900 border border-slate-200 dark:border-slate-700 border-b-4 border-b-slate-300 dark:border-b-slate-950 shadow-md mb-2 relative w-full max-w-[600px] h-16">

        <!-- Toggle Icon (Click only here to expand/collapse) -->
        <div class="w-6 h-6 flex items-center justify-center mr-2 cursor-pointer bg-white dark:bg-slate-700/50 rounded-lg border border-slate-200/50 dark:border-white/5 hover:bg-slate-100 transition-colors"
            onclick="toggleTreeItem(event, <?php echo e($item->id); ?>)">
            <?php if($hasChildren): ?>
                <svg id="toggle-icon-<?php echo e($item->id); ?>"
                    class="w-3 h-3 text-slate-400 group-hover:text-accent transform transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
            <?php else: ?>
                <div class="w-1 h-1 bg-slate-300 dark:bg-slate-600 rounded-full"></div>
            <?php endif; ?>
        </div>

        <!-- Main Content Area (Clicking here goes to profile) -->
        <a href="<?php echo e(route('users.show', $item->id)); ?>" class="flex items-center flex-1 min-w-0 group/link">
            <!-- Small Profile Picture -->
            <div
                class="w-9 h-9 rounded-lg overflow-hidden mr-3 bg-white dark:bg-slate-800 flex-shrink-0 border border-slate-200/50 dark:border-white/5 ring-1 ring-slate-200/50 dark:ring-white/10">
                <?php if($item->profile && $item->profile->profile_picture): ?>
                    <img src="<?php echo e($item->profile->getProfilePictureUrl()); ?>" alt="Profile"
                        class="profile-img w-full h-full object-cover">
                <?php else: ?>
                    <div
                        class="w-full h-full flex items-center justify-center bg-accent/5 text-accent text-[10px] font-black">
                        <?php echo e(substr($item->profile->full_name ?? 'U', 0, 1)); ?>

                    </div>
                <?php endif; ?>
            </div>

            <!-- Status Dot (After profile picture) -->
            <div
                class="w-2.5 h-2.5 rounded-full <?php echo e($item->status === 'active' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]'); ?> mr-3 flex-shrink-0">
            </div>

            <!-- Member Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <span
                        class="text-xs font-black text-slate-700 dark:text-slate-200 truncate group-hover/link:text-accent transition-colors">
                        <?php echo e($item->profile->full_name ?? $item->employee_id); ?>

                    </span>
                    <span
                        class="text-[9px] font-black px-1.5 py-0.5 rounded bg-white dark:bg-slate-700 text-slate-500 tracking-tighter border border-slate-100 dark:border-slate-600">
                        <?php echo e($item->employee_id); ?>

                    </span>
                </div>
                <p
                    class="text-[9px] font-bold uppercase tracking-widest text-slate-400 group-hover/link:text-accent/70 mt-0.5 transition-colors">
                    <?php echo e($item->getDesignationLabel()); ?>

                </p>
            </div>
        </a>
    </div>

    <?php if($hasChildren): ?>
        <div id="children-<?php echo e($item->id); ?>"
            class="tree-children hidden ml-6 pl-4 border-l border-slate-200 dark:border-slate-800 mt-1 space-y-1"
            data-loaded="false">
            <!-- Shimmer/Loading state -->
            <div class="tree-loading-shimmer hidden py-2 italic text-[10px] text-slate-400">Loading team...</div>
        </div>
    <?php endif; ?>

</div><?php /**PATH C:\xampp\htdocs\HF\resources\views/dashboard/partials/tree_item.blade.php ENDPATH**/ ?>