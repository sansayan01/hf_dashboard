<?php $__env->startSection('title', 'Role Permissions'); ?>
<?php $__env->startSection('header_title', 'Bulk Permission Control'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6 max-w-7xl mx-auto overflow-y-auto h-full pb-20">
    
    <div class="mb-10 animate-in fade-in slide-in-from-top-4 duration-700">
        <div class="flex items-center gap-4 mb-4">
            <a href="<?php echo e(route('admin.control-panel')); ?>"
               class="w-10 h-10 rounded-xl bg-white dark:bg-darkcard border border-slate-200 dark:border-white/10 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/5 transition shadow-sm">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h1 class="text-4xl font-black text-slate-800 dark:text-white tracking-tight">Role Permissions</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Configure default permissions for each designation. Changes apply to all users with that role.</p>
            </div>
        </div>

        
        <div class="bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 rounded-2xl p-4 flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-sm text-blue-700 dark:text-blue-300 font-medium leading-relaxed">
                These are <strong>role-level defaults</strong>. Per-user overrides (set on individual profiles) always take priority over these settings.
            </p>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('admin.permissions.show', $key)); ?>"
               class="group relative block p-[3px] bg-gradient-to-br <?php echo e($designation['gradient']); ?> rounded-[2rem] shadow-xl hover:shadow-2xl transition-all hover:scale-[1.02] hover:-translate-y-1 duration-500">
                <div class="bg-white dark:bg-darkcard rounded-[1.9rem] p-6 h-full relative overflow-hidden">
                    
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-<?php echo e($designation['color']); ?>-500/10 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>

                    <div class="relative z-10 flex flex-col h-full">
                        
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br <?php echo e($designation['gradient']); ?> flex items-center justify-center mb-5 group-hover:rotate-6 transition-transform duration-500 shadow-lg">
                            <span class="text-white font-black text-lg tracking-tight"><?php echo e($designation['short']); ?></span>
                        </div>

                        
                        <h3 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-tight mb-1 leading-tight"><?php echo e($designation['label']); ?></h3>

                        
                        <p class="text-xs text-slate-400 dark:text-slate-500 font-bold mb-5">
                            <?php echo e($designation['user_count']); ?> <?php echo e(Str::plural('user', $designation['user_count'])); ?>

                        </p>

                        
                        <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                            <div class="flex flex-col">
                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Enabled</span>
                                <span class="text-sm font-black text-<?php echo e($designation['color']); ?>-500"><?php echo e($designation['enabled_count']); ?> / <?php echo e($designation['total_count']); ?></span>
                            </div>

                            <div class="flex items-center gap-2">
                                <?php if($designation['has_overrides']): ?>
                                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse" title="Custom overrides active"></span>
                                <?php endif; ?>
                                <div class="w-8 h-8 rounded-full bg-slate-50 dark:bg-white/5 flex items-center justify-center text-slate-400 group-hover:bg-<?php echo e($designation['color']); ?>-500 group-hover:text-white transition-all duration-500">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/admin/permissions/index.blade.php ENDPATH**/ ?>