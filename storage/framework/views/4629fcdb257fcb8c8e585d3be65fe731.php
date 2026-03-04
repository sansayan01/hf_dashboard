<?php if($incomes->count() > 0): ?>
    <?php $__currentLoopData = $incomes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $income): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td class="whitespace-nowrap">
                <span class="text-[11px] font-semibold text-slate-500 dark:text-slate-400"
                    style="font-variant-numeric:tabular-nums">
                    <?php echo e($income->income_date->format('d M Y')); ?>

                </span>
            </td>
            <td>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-800 dark:text-white truncate max-w-[200px]"><?php echo e($income->title); ?></p>
                    <?php if($income->source): ?>
                        <p class="text-[11px] text-slate-400 truncate max-w-[200px]">From: <?php echo e($income->source); ?></p>
                    <?php endif; ?>
                </div>
            </td>
            <td>
                <?php
                    $catColors = [
                        'Donations' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                        'Grants' => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                        'Membership Fees' => 'bg-violet-50 text-violet-700 border-violet-200 dark:bg-violet-500/10 dark:text-violet-400 dark:border-violet-500/20',
                        'Service Revenue' => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                        'Camp Revenue' => 'bg-cyan-50 text-cyan-700 border-cyan-200 dark:bg-cyan-500/10 dark:text-cyan-400 dark:border-cyan-500/20',
                        'Pathology Revenue' => 'bg-teal-50 text-teal-700 border-teal-200 dark:bg-teal-500/10 dark:text-teal-400 dark:border-teal-500/20',
                        'Medicine Sales' => 'bg-green-50 text-green-700 border-green-200 dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/20',
                        'Sponsorship' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                        'Interest' => 'bg-sky-50 text-sky-700 border-sky-200 dark:bg-sky-500/10 dark:text-sky-400 dark:border-sky-500/20',
                        'Miscellaneous' => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:border-slate-500/20',
                    ];
                ?>
                <span class="cat-badge <?php echo e($catColors[$income->category] ?? 'bg-slate-50 text-slate-600 border-slate-200'); ?>">
                    <?php echo e($income->category); ?>

                </span>
            </td>
            <td>
                <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400"
                    style="font-variant-numeric:tabular-nums">
                    +₹<?php echo e(number_format($income->amount, 2)); ?>

                </span>
            </td>
            <td>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-300">
                    <?php echo e(\App\Models\Income::PAYMENT_METHODS[$income->payment_method] ?? $income->payment_method); ?>

                </span>
            </td>
            <td>
                <span class="text-xs font-medium text-slate-600 dark:text-slate-300">
                    <?php echo e($income->received_by ?? '—'); ?>

                </span>
            </td>
            <td>
                <div class="flex items-center gap-1">
                    <?php if($income->receipt_path): ?>
                        <a href="<?php echo e(url('/storage-render/' . $income->receipt_path)); ?>" target="_blank"
                            class="icon-btn bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-500/10"
                            title="View Receipt">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('incomes.edit', $income)); ?>"
                        class="icon-btn bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-500/10"
                        title="Edit">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <form action="<?php echo e(route('incomes.destroy', $income)); ?>" method="POST"
                        onsubmit="return confirm('Delete this income entry?')" style="display:inline">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button type="submit"
                            class="icon-btn bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10"
                            title="Delete">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php else: ?>
    <tr>
        <td colspan="7" class="text-center py-12">
            <div class="flex flex-col items-center gap-2">
                <svg class="w-10 h-10 text-slate-200 dark:text-slate-600" fill="none" stroke="currentColor" stroke-width="1"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="text-sm font-semibold text-slate-400">No income entries found</p>
                <p class="text-xs text-slate-400">Try adjusting your filters or add a new income</p>
            </div>
        </td>
    </tr>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/incomes/partials/table.blade.php ENDPATH**/ ?>