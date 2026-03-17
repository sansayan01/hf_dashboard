<?php $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr class="group/row hover:bg-accent/5 dark:hover:bg-accent/5 transition-colors">
        <td class="px-8 py-6">
            <div class="flex flex-col">
                <code class="text-sm font-black text-accent tracking-tighter"><?php echo e($coupon->code); ?></code>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-1 items-center flex gap-1">
                    <i class="far fa-calendar-alt text-[8px]"></i>
                    <?php echo e($coupon->created_at->format('M d, Y • h:i A')); ?>

                </span>
            </div>
        </td>
        <td class="px-8 py-6">
            <?php if($coupon->designation): ?>
                <div
                    class="inline-flex items-center px-3 py-1 bg-slate-100 dark:bg-white/10 rounded-full text-[10px] font-black text-slate-500 dark:text-slate-300 uppercase tracking-widest">
                    <?php echo e($coupon->designation); ?>

                </div>
            <?php else: ?>
                <div
                    class="inline-flex items-center px-3 py-1 bg-blue-500/10 rounded-full text-[10px] font-black text-blue-500 uppercase tracking-widest">
                    Universal
                </div>
            <?php endif; ?>
        </td>
        <td class="px-8 py-6 text-right">
            <span
                class="text-lg font-black text-slate-800 dark:text-white tracking-tighter">₹<?php echo e(number_format($coupon->original_amount)); ?></span>
        </td>
        <td class="px-8 py-6 text-center">
            <?php if($coupon->is_used): ?>
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-500/10 rounded-full text-[9px] font-black text-blue-500 uppercase tracking-widest border border-blue-500/10 shadow-sm shadow-blue-500/10">
                    <i class="fas fa-check-double text-[8px]"></i>
                    Redeemed
                </span>
            <?php elseif($coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast()): ?>
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-rose-500/10 rounded-full text-[9px] font-black text-rose-500 uppercase tracking-widest border border-rose-500/10">
                    <i class="fas fa-history text-[8px]"></i>
                    Expired
                </span>
            <?php else: ?>
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-500/10 rounded-full text-[9px] font-black text-emerald-500 uppercase tracking-widest border border-emerald-500/10">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Available
                </span>
            <?php endif; ?>

            <?php if($coupon->expires_at && !$coupon->is_used && !\Carbon\Carbon::parse($coupon->expires_at)->isPast()): ?>
                <div class="text-[8px] font-black text-amber-500 uppercase mt-2">
                    Exp: <?php echo e(\Carbon\Carbon::parse($coupon->expires_at)->format('M d, Y')); ?>

                </div>
            <?php endif; ?>
        </td>
        <td class="px-8 py-6">
            <?php if($coupon->usedBy): ?>
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/5 flex items-center justify-center text-accent">
                        <i class="fas fa-user text-[10px]"></i>
                    </div>
                    <div class="flex flex-col">
                        <a href="<?php echo e(route('users.show', $coupon->usedBy->id)); ?>"
                            class="text-xs font-black text-slate-700 dark:text-slate-200 hover:text-accent transition-colors">
                            <?php echo e($coupon->usedBy->profile->full_name); ?>

                        </a>
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                            <?php echo e(\Carbon\Carbon::parse($coupon->used_at)->format('M d, h:i A')); ?>

                        </span>
                    </div>
                </div>
            <?php else: ?>
                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">— Available —</span>
            <?php endif; ?>
        </td>
        <td class="px-8 py-6 text-right">
            <div class="flex justify-end gap-2">
                <?php if(!$coupon->is_used): ?>
                    <form action="<?php echo e(route('coupons.destroy', $coupon->id)); ?>" method="POST" class="inline"
                        onsubmit="return confirm('Archive this unused coupon?');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit"
                            class="w-10 h-10 flex items-center justify-center bg-rose-500/5 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl transition-all border border-rose-500/10">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>
                <?php else: ?>
                    <div
                        class="w-10 h-10 flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-400 rounded-xl cursor-not-allowed border border-transparent">
                        <i class="fas fa-lock text-xs opacity-30"></i>
                    </div>
                <?php endif; ?>
            </div>
        </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\admin\coupons\partials\table.blade.php ENDPATH**/ ?>