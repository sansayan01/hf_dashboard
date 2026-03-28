<?php $__env->startSection('title', 'Role Permissions — ' . $meta['label']); ?>
<?php $__env->startSection('header_title', $meta['label'] . ' Permissions'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6">

    
    <?php if(session('success')): ?>
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-300 flex items-center gap-3 animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold text-sm"><?php echo e(session('success')); ?></span>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-800 dark:text-red-300 flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold text-sm"><?php echo e(session('error')); ?></span>
        </div>
    <?php endif; ?>

    
    <div class="bg-white dark:bg-darkcard rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-white/5 p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('admin.permissions.index')); ?>"
                   class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center hover:bg-slate-200 dark:hover:bg-white/10 transition">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="flex items-center gap-3">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br <?php echo e($meta['gradient']); ?> flex items-center justify-center shadow-lg">
                        <span class="text-white font-black text-xl tracking-tight"><?php echo e($meta['short']); ?></span>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-slate-800 dark:text-white"><?php echo e($meta['label']); ?></h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 font-semibold">
                            Bulk editing for <strong class="text-<?php echo e($meta['color']); ?>-500"><?php echo e($userCount); ?></strong> <?php echo e(Str::plural('user', $userCount)); ?>

                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap ml-auto">
                
                <form method="POST" action="<?php echo e(route('admin.permissions.reset', $designation)); ?>"
                      onsubmit="return confirm('Reset all permissions for <?php echo e($meta['label']); ?> to system defaults? This will remove all role-level overrides.')">
                    <?php echo csrf_field(); ?>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold rounded-xl text-[10px] uppercase hover:bg-red-100 dark:hover:bg-red-500/20 transition border border-red-100 dark:border-red-500/20">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Defaults
                    </button>
                </form>
            </div>
        </div>

        
        <div class="mt-4 flex items-center gap-2 flex-wrap">
            <?php if($hasOverrides): ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-300 text-[10px] font-black uppercase border border-blue-100 dark:border-blue-500/20 italic">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Custom Role Overrides Active
                </span>
            <?php else: ?>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 dark:bg-white/5 text-slate-500 text-[10px] font-black uppercase border border-slate-100 dark:border-white/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Using System Defaults
                </span>
            <?php endif; ?>
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider ml-1">
                <?php echo e(collect($rolePermissions)->filter(fn($v) => $v)->count()); ?> / <?php echo e(count($rolePermissions)); ?> ENABLED
            </span>

            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-300 text-[10px] font-bold uppercase border border-amber-100 dark:border-amber-500/20 ml-auto">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <?php echo e($userCount); ?> <?php echo e(Str::plural('User', $userCount)); ?> Affected
            </span>
        </div>
    </div>

    
    <form method="POST" action="<?php echo e(route('admin.permissions.update', $designation)); ?>" id="permissionsForm">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div class="space-y-4" id="categoriesContainer">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catKey => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white dark:bg-darkcard rounded-2xl shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-100 dark:border-white/5 overflow-hidden permission-category hover:border-blue-200 dark:hover:border-blue-500/20 transition-colors"
                     data-category="<?php echo e($catKey); ?>">

                    
                    <div class="w-full flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-slate-50/50 dark:hover:bg-white/[0.02] transition group"
                         onclick="toggleCategory('<?php echo e($catKey); ?>')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 dark:from-blue-500/20 dark:to-indigo-500/20 flex items-center justify-center group-hover:from-blue-500/20 group-hover:to-indigo-500/20 transition">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($category['icon']); ?>"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <h3 class="font-black text-slate-800 dark:text-white text-sm leading-tight uppercase tracking-tight"><?php echo e($category['label']); ?></h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                    <span id="count-<?php echo e($catKey); ?>"><?php echo e(collect($category['permissions'])->filter(fn($label, $key) => $rolePermissions[$key] ?? false)->count()); ?></span> / <?php echo e(count($category['permissions'])); ?> enabled
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 group/header">
                            
                            <div class="hidden sm:flex items-center gap-1 bg-slate-100 dark:bg-white/5 p-1 rounded-xl border border-slate-200/50 dark:border-white/10" onclick="event.stopPropagation()">
                                <button type="button"
                                        onclick="applyCategoryPreset('<?php echo e($catKey); ?>', 'full')"
                                        title="Full Access"
                                        class="px-2.5 py-1 text-[8px] font-black uppercase rounded-lg hover:bg-emerald-500 hover:text-white hover:shadow-md transition-all text-slate-500 dark:text-slate-400">
                                    Full
                                </button>
                                <button type="button"
                                        onclick="applyCategoryPreset('<?php echo e($catKey); ?>', 'default')"
                                        title="System Default"
                                        class="px-2.5 py-1 text-[8px] font-black uppercase rounded-lg hover:bg-slate-800 hover:text-white hover:shadow-md transition-all text-slate-500 dark:text-slate-400">
                                    Default
                                </button>
                                <button type="button"
                                        onclick="applyCategoryPreset('<?php echo e($catKey); ?>', 'read')"
                                        title="Read Only"
                                        class="px-2.5 py-1 text-[8px] font-black uppercase rounded-lg hover:bg-blue-500 hover:text-white hover:shadow-md transition-all text-slate-500 dark:text-slate-400">
                                    Read
                                </button>
                                <button type="button"
                                        onclick="applyCategoryPreset('<?php echo e($catKey); ?>', 'off')"
                                        title="Turn Off"
                                        class="px-2.5 py-1 text-[8px] font-black uppercase rounded-lg hover:bg-red-500 hover:text-white hover:shadow-md transition-all text-slate-500 dark:text-slate-400">
                                    Off
                                </button>
                            </div>

                            <svg class="w-5 h-5 text-slate-300 dark:text-slate-600 transition-transform duration-300 ml-1 group-hover/header:text-blue-400" id="chevron-<?php echo e($catKey); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    
                    <div id="body-<?php echo e($catKey); ?>" class="hidden border-t border-slate-100 dark:border-white/5">
                        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <?php $__currentLoopData = $category['permissions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $isEnabled = $rolePermissions[$key] ?? false; ?>
                                <label class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/[0.02] transition cursor-pointer group/perm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full <?php echo e($isEnabled ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600'); ?> transition perm-dot"
                                             data-key="<?php echo e($key); ?>"></div>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover/perm:text-slate-900 dark:group-hover/perm:text-white"><?php echo e($label); ?></span>
                                    </div>
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               name="permissions[<?php echo e($key); ?>]"
                                               value="1"
                                               class="sr-only peer perm-toggle"
                                               data-key="<?php echo e($key); ?>"
                                               data-category="<?php echo e($catKey); ?>"
                                               <?php echo e($isEnabled ? 'checked' : ''); ?>

                                               onchange="updateCounts('<?php echo e($catKey); ?>')">
                                        <div class="w-9 h-5 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 dark:after:border-slate-600 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </div>
                                </label>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <div class="sticky bottom-0 bg-white/80 dark:bg-darkcard/80 backdrop-blur-xl border-t border-slate-200 dark:border-white/5 -mx-4 sm:-mx-6 px-4 sm:px-6 py-4 mt-6 rounded-b-2xl z-40">
            <div class="flex items-center justify-between max-w-5xl mx-auto">
                <p class="text-xs text-slate-400 font-medium hidden sm:block">
                    Changes affect <strong>all <?php echo e($meta['label']); ?>s</strong>. Per-user overrides always take priority.
                </p>
                <div class="flex items-center gap-3 ml-auto">
                    <a href="<?php echo e(route('admin.permissions.index')); ?>"
                       class="px-6 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-8 py-2.5 text-sm font-bold text-white bg-gradient-to-r <?php echo e($meta['gradient']); ?> rounded-xl shadow-lg shadow-<?php echo e($meta['color']); ?>-600/30 hover:opacity-90 transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save for All <?php echo e($meta['label']); ?>s
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>

<script>
    function toggleCategory(catKey) {
        const body = document.getElementById('body-' + catKey);
        const chevron = document.getElementById('chevron-' + catKey);

        if (body.classList.contains('hidden')) {
            body.classList.remove('hidden');
            body.style.maxHeight = '0px';
            body.style.overflow = 'hidden';
            body.style.transition = 'max-height 0.3s ease-out';
            requestAnimationFrame(() => {
                body.style.maxHeight = body.scrollHeight + 'px';
            });
            chevron.style.transform = 'rotate(180deg)';
        } else {
            body.style.maxHeight = '0px';
            setTimeout(() => {
                body.classList.add('hidden');
                body.style.maxHeight = '';
                body.style.overflow = '';
                body.style.transition = '';
            }, 300);
            chevron.style.transform = 'rotate(0deg)';
        }
    }

    const permissionDefaults = <?php echo json_encode($defaults, 15, 512) ?>;

    function applyCategoryPreset(catKey, type) {
        const toggles = document.querySelectorAll('.perm-toggle[data-category="' + catKey + '"]');
        toggles.forEach(toggle => {
            const key = toggle.dataset.key;
            if (type === 'full') {
                toggle.checked = true;
            } else if (type === 'default') {
                toggle.checked = !!permissionDefaults[key];
            } else if (type === 'read') {
                toggle.checked = key.includes('.view') || key.includes('.report') || key.includes('.view_profile') || key.includes('.view_stats');
            } else if (type === 'off') {
                toggle.checked = false;
            }
        });
        updateCounts(catKey);
    }

    function updateCounts(catKey) {
        const toggles = document.querySelectorAll('.perm-toggle[data-category="' + catKey + '"]');
        const enabledCount = Array.from(toggles).filter(t => t.checked).length;
        const countEl = document.getElementById('count-' + catKey);
        if (countEl) countEl.textContent = enabledCount;

        // Update dots
        toggles.forEach(toggle => {
            const dot = document.querySelector('.perm-dot[data-key="' + toggle.dataset.key + '"]');
            if (dot) {
                if (toggle.checked) {
                    dot.classList.remove('bg-slate-300', 'dark:bg-slate-600');
                    dot.classList.add('bg-emerald-500');
                } else {
                    dot.classList.remove('bg-emerald-500');
                    dot.classList.add('bg-slate-300');
                }
            }
        });
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/admin/permissions/role.blade.php ENDPATH**/ ?>