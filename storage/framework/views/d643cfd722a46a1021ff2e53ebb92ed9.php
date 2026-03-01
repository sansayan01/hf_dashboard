

<?php $__env->startSection('title', 'Camp Records'); ?>
<?php $__env->startSection('header_title', 'Camp Records Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-white dark:bg-darkbg/40 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden">
    <!-- Header -->
    <div class="p-6 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h3 class="font-bold text-lg text-slate-800 dark:text-white">All Camp Records</h3>
                <span class="px-2 py-0.5 bg-accent/10 text-accent text-[10px] font-black rounded-full border border-accent/20">
                    <?php echo e($records->count()); ?> Total
                </span>
            </div>
            <p class="text-sm text-slate-500 dark:text-slate-400">Manage finances and statistics of all held camps.</p>
        </div>
        
        <div class="flex items-center space-x-3">
            <a href="<?php echo e(route('camp_records.create')); ?>"
                class="px-4 py-2 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                + New Camp Record
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="p-6 pb-0">
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-6 py-4 rounded-2xl font-bold text-sm">
                <?php echo e(session('success')); ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if($records->isEmpty()): ?>
        <div class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl p-20 text-center m-6">
            <div class="w-24 h-24 bg-accent/10 text-accent rounded-3xl flex items-center justify-center mx-auto mb-8">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h4 class="text-2xl font-black text-slate-800 dark:text-white mb-3">No Camp Records Found</h4>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-10 font-medium leading-relaxed">
                You haven't added any camp records yet. Click the button above to create your first record.
            </p>
        </div>
    <?php else: ?>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Date</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Camp Details</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Patients</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Medicine Billing</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Profit</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Net P/L</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        <?php $__currentLoopData = $records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                <td class="p-4 text-xs font-bold text-slate-600 dark:text-slate-300">
                                    <?php echo e(\Carbon\Carbon::parse($record->date)->format('M d, Y')); ?>

                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800 dark:text-white text-sm"><?php echo e($record->camp_name); ?></div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-wider mt-0.5">
                                        <?php echo e($record->location ?? 'N/A'); ?> | RM: <?php echo e($record->rm ?? 'N/A'); ?>

                                    </div>
                                    <div class="mt-1 text-[10px] text-slate-400 flex gap-2 flex-wrap">
                                        <?php if($record->doctor_name): ?><span class="bg-blue-50 dark:bg-blue-500/10 text-blue-600 px-2 py-0.5 rounded">Dr: <?php echo e($record->doctor_name); ?></span><?php endif; ?>
                                        <?php if($record->pathologist): ?><span class="bg-purple-50 dark:bg-purple-500/10 text-purple-600 px-2 py-0.5 rounded">Path: <?php echo e($record->pathologist); ?></span><?php endif; ?>
                                        <?php if($record->pharmacists_name): ?><span class="bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 px-2 py-0.5 rounded">Pharm: <?php echo e($record->pharmacists_name); ?></span><?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-4 text-center text-sm font-bold text-slate-600 dark:text-slate-300">
                                    <?php echo e($record->patients_count); ?>

                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-xs font-bold text-slate-700 dark:text-slate-200">₹<?php echo e(number_format($record->billing_price, 2)); ?></div>
                                    <div class="text-[10px] text-slate-400">MRP: ₹<?php echo e(number_format($record->medicine_mrp, 2)); ?></div>
                                    <div class="text-[10px] text-slate-400">Disc: ₹<?php echo e(number_format($record->medicine_discount, 2)); ?></div>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="text-xs font-black text-emerald-600 dark:text-emerald-400">₹<?php echo e(number_format($record->profit, 2)); ?></div>
                                    <div class="text-[10px] text-red-400">Exp: ₹<?php echo e(number_format($record->expenses, 2)); ?></div>
                                </td>
                                <td class="p-4 text-right">
                                    <?php if($record->net_profit_loss >= 0): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-emerald-50 text-emerald-600 dark:bg-emerald-500/10 dark:text-emerald-400 rounded-lg text-xs font-black border border-emerald-500/20">
                                            +₹<?php echo e(number_format($record->net_profit_loss, 2)); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400 rounded-lg text-xs font-black border border-red-500/20">
                                            -₹<?php echo e(number_format(abs($record->net_profit_loss), 2)); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="<?php echo e(route('camp_records.edit', $record->id)); ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-accent hover:text-white dark:bg-slate-800 dark:hover:bg-accent text-slate-400 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </a>
                                        <form action="<?php echo e(route('camp_records.destroy', $record->id)); ?>" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-red-500 hover:text-white dark:bg-slate-800 dark:hover:bg-red-500 text-slate-400 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/camp_records/index.blade.php ENDPATH**/ ?>