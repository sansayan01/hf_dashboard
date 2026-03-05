<?php $__empty_1 = true; $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
        <td class="p-5 align-top">
            <div class="flex flex-col space-y-1">
                <span class="inline-flex items-center space-x-1.5 w-max">
                    <svg class="w-3.5 h-3.5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span
                        class="text-sm font-bold text-slate-800 dark:text-slate-200"><?php echo e(\Carbon\Carbon::parse($record->date)->format('M d, Y')); ?></span>
                </span>
                <?php if($record->location): ?>
                    <span class="inline-flex items-center space-x-1 w-max text-slate-500 dark:text-slate-400 mt-1">
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
                <div class="text-[10px] font-bold text-indigo-500 dark:text-indigo-400 uppercase tracking-wider mb-2">
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
                <span class="text-xs text-slate-400 font-bold mb-0.5"><i class="fas fa-users"></i></span>
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
                    <span class="text-[9px] font-bold text-emerald-500/70 uppercase tracking-widest mt-1">Profit</span>
                </div>
            <?php else: ?>
                <div class="inline-flex flex-col items-end">
                    <span
                        class="inline-flex items-center px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg text-sm font-black border border-red-500/20 font-mono shadow-sm">
                        <?php echo e(number_format($actualNetPL, 2)); ?>

                    </span>
                    <span class="text-[9px] font-bold text-red-500/70 uppercase tracking-widest mt-1">Loss</span>
                </div>
            <?php endif; ?>
        </td>

        <td class="p-5 align-middle text-right">
            <div
                class="flex items-center justify-end space-x-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity duration-300">
                <a href="<?php echo e(route('camp_records.pdf', $record->id)); ?>?preview=1" target="_blank"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-emerald-500 hover:bg-emerald-50 dark:hover:border-emerald-500 dark:hover:bg-emerald-500/20 hover:text-emerald-600 dark:text-slate-300 text-slate-500 shadow-sm transition-all"
                    title="Preview PDF">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                <form action="<?php echo e(route('camp_records.destroy', $record->id)); ?>" method="POST" class="inline-block"
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
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
    <tr>
        <td colspan="6" class="p-20 text-center">
            <div
                class="relative z-10 w-24 h-24 bg-slate-100 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner border border-white dark:border-slate-700/50">
                <svg class="w-12 h-12 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h4 class="text-xl font-black text-slate-800 dark:text-white mb-2 tracking-tight">No Records Found</h4>
            <p class="text-slate-500 dark:text-slate-400 max-w-sm mx-auto text-sm leading-relaxed">
                Adjust your filters or clear them to view all camp records.
            </p>
        </td>
    </tr>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/camp_records/partials/table.blade.php ENDPATH**/ ?>