<?php $__env->startSection('content'); ?>
<div class="container-fluid py-6 animate-in fade-in duration-700">
    <!-- Header Section -->
    <div class="relative mb-10">
        <!-- Background Glow -->
        <div class="absolute -top-10 -left-10 w-64 h-64 bg-accent/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white uppercase tracking-tighter flex items-center gap-3">
                    <span class="p-3 bg-accent/10 rounded-2xl">
                        <svg class="w-8 h-8 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                        </svg>
                    </span>
                    Coupon Code Management
                </h1>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px] mt-2 ml-14">
                    Control and monitor registration vouchers for cash transactions
                </p>
            </div>
            
            <div class="flex items-center gap-3 ml-14 md:ml-0">
                <a href="<?php echo e(route('coupons.export', request()->query())); ?>" class="group px-5 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-300 font-black text-xs uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all flex items-center gap-2">
                    <i class="fas fa-file-excel text-emerald-500 group-hover:scale-110 transition-transform"></i>
                    Export CSV
                </a>
                <a href="<?php echo e(route('coupons.create')); ?>" class="group px-6 py-3 bg-accent text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-xl shadow-accent/20 hover:shadow-accent/40 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                    <i class="fas fa-plus group-hover:rotate-90 transition-transform"></i>
                    Generate New
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Coupons -->
        <div class="group relative">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-accent to-blue-600 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
            <div class="relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6">
                <div class="flex items-center justify-between pointer-events-none">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Issued</p>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter"><?php echo e($coupons->total()); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-accent/10 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-accent text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 w-full bg-slate-100 dark:bg-white/5 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-accent h-full w-full opacity-60"></div>
                </div>
            </div>
        </div>

        <!-- Unused -->
        <div class="group relative">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
            <div class="relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Available</p>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter"><?php echo e($stats['unused']); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 w-full bg-slate-100 dark:bg-white/5 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full transition-all duration-1000" style="width: <?php echo e($coupons->total() > 0 ? ($stats['unused'] / $coupons->total()) * 100 : 0); ?>%"></div>
                </div>
            </div>
        </div>

        <!-- Used -->
        <div class="group relative">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
            <div class="relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Redeemed</p>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter"><?php echo e($stats['used']); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-user-check text-blue-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 w-full bg-slate-100 dark:bg-white/5 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full transition-all duration-1000" style="width: <?php echo e($coupons->total() > 0 ? ($stats['used'] / $coupons->total()) * 100 : 0); ?>%"></div>
                </div>
            </div>
        </div>

        <!-- Expired -->
        <div class="group relative">
            <div class="absolute -inset-0.5 bg-gradient-to-r from-amber-500 to-orange-500 rounded-3xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
            <div class="relative bg-white dark:bg-slate-900/80 backdrop-blur-xl border border-white/20 rounded-3xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Expired</p>
                        <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tighter"><?php echo e($stats['expired']); ?></h3>
                    </div>
                    <div class="w-12 h-12 bg-amber-500/10 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-clock text-amber-500 text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 w-full bg-slate-100 dark:bg-white/5 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full transition-all duration-1000" style="width: <?php echo e($coupons->total() > 0 ? ($stats['expired'] / $coupons->total()) * 100 : 0); ?>%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl border border-white/10 rounded-[2.5rem] p-8 mb-10 shadow-2xl shadow-slate-200/50 dark:shadow-none">
        <form method="GET" action="<?php echo e(route('coupons.index')); ?>" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Redemption Status</label>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-white/5 rounded-2xl px-5 py-3 text-sm font-bold text-slate-600 dark:text-slate-300 focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                    <option value="">All Coupons</option>
                    <option value="unused" <?php echo e(request('status') == 'unused' ? 'selected' : ''); ?>>Available (Unused)</option>
                    <option value="used" <?php echo e(request('status') == 'used' ? 'selected' : ''); ?>>Redeemed (Used)</option>
                    <option value="expired" <?php echo e(request('status') == 'expired' ? 'selected' : ''); ?>>Expired Only</option>
                </select>
            </div>
            
            <div class="flex flex-col gap-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Designation</label>
                <select name="designation" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-white/5 rounded-2xl px-5 py-3 text-sm font-bold text-slate-600 dark:text-slate-300 focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                    <option value="">All Tiers</option>
                    <option value="ro" <?php echo e(request('designation') == 'ro' ? 'selected' : ''); ?>>RO Tier (₹199)</option>
                    <option value="rm" <?php echo e(request('designation') == 'rm' ? 'selected' : ''); ?>>RM Tier (₹499)</option>
                    <option value="bm" <?php echo e(request('designation') == 'bm' ? 'selected' : ''); ?>>BM Tier (₹999)</option>
                    <option value="dm" <?php echo e(request('designation') == 'dm' ? 'selected' : ''); ?>>DM Tier (₹999)</option>
                    <option value="membership" <?php echo e(request('designation') == 'membership' ? 'selected' : ''); ?>>Membership (₹199)</option>
                    <option value="any" <?php echo e(request('designation') == 'any' ? 'selected' : ''); ?>>Universal / Any</option>
                </select>
            </div>
            
            <div class="lg:col-span-2 flex flex-col gap-2">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4">Search Voucher Code</label>
                <div class="relative group">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-accent transition-colors"></i>
                    <input type="text" name="search" placeholder="Enter HF-CASH-XXXXX..." value="<?php echo e(request('search')); ?>" 
                        class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-white/5 rounded-2xl pl-12 pr-5 py-3 text-sm font-bold text-slate-600 dark:text-slate-300 focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none placeholder:text-slate-300">
                </div>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full bg-slate-800 dark:bg-accent text-white font-black text-xs uppercase tracking-widest py-4 rounded-2xl hover:bg-slate-700 dark:hover:bg-accent/80 transition-all shadow-lg shadow-slate-200 dark:shadow-none">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="relative group">
        <!-- Decoration -->
        <div class="absolute -inset-1 bg-gradient-to-r from-accent/20 to-purple-500/20 rounded-[2.5rem] blur opacity-25 group-hover:opacity-50 transition duration-700"></div>
        
        <div class="relative bg-white/80 dark:bg-slate-900/85 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-200/50 dark:shadow-none">
            <?php if($coupons->count() > 0): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-white/5 border-b border-slate-100 dark:border-white/5">
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Coupon Identity</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Target Tier</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Value</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Lifecycle</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Redeemed By</th>
                                <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-white/5">
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
                                            <div class="inline-flex items-center px-3 py-1 bg-slate-100 dark:bg-white/10 rounded-full text-[10px] font-black text-slate-500 dark:text-slate-300 uppercase tracking-widest">
                                                <?php echo e($coupon->designation); ?>

                                            </div>
                                        <?php else: ?>
                                            <div class="inline-flex items-center px-3 py-1 bg-blue-500/10 rounded-full text-[10px] font-black text-blue-500 uppercase tracking-widest">
                                                Universal
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <span class="text-lg font-black text-slate-800 dark:text-white tracking-tighter">₹<?php echo e(number_format($coupon->original_amount)); ?></span>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        <?php if($coupon->is_used): ?>
                                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-500/10 rounded-full text-[9px] font-black text-blue-500 uppercase tracking-widest border border-blue-500/10 shadow-sm shadow-blue-500/10">
                                                <i class="fas fa-check-double text-[8px]"></i>
                                                Redeemed
                                            </span>
                                        <?php elseif($coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast()): ?>
                                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-rose-500/10 rounded-full text-[9px] font-black text-rose-500 uppercase tracking-widest border border-rose-500/10">
                                                <i class="fas fa-history text-[8px]"></i>
                                                Expired
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-500/10 rounded-full text-[9px] font-black text-emerald-500 uppercase tracking-widest border border-emerald-500/10">
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
                                                    <a href="<?php echo e(route('users.show', $coupon->usedBy->id)); ?>" class="text-xs font-black text-slate-700 dark:text-slate-200 hover:text-accent transition-colors">
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
                                                <form action="<?php echo e(route('coupons.destroy', $coupon->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Archive this unused coupon?');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="w-10 h-10 flex items-center justify-center bg-rose-500/5 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl transition-all border border-rose-500/10">
                                                        <i class="fas fa-trash-alt text-xs"></i>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                                <div class="w-10 h-10 flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-400 rounded-xl cursor-not-allowed border border-transparent">
                                                    <i class="fas fa-lock text-xs opacity-30"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <!-- Footer / Pagination -->
                <div class="px-8 py-6 border-t border-slate-50 dark:border-white/5 bg-slate-50/30 dark:bg-white/5">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <div>Showing <?php echo e($coupons->firstItem()); ?> - <?php echo e($coupons->lastItem()); ?> of <?php echo e($coupons->total()); ?> vouchers</div>
                        <div class="flex items-center gap-1 themed-pagination">
                            <?php echo e($coupons->links()); ?>

                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center py-20 px-8 text-center">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-white/5 rounded-[2rem] flex items-center justify-center mb-6 border border-slate-100 dark:border-white/5 shadow-inner">
                        <i class="fas fa-ticket-alt text-4xl text-slate-300"></i>
                    </div>
                    <h5 class="text-xl font-black text-slate-800 dark:text-white tracking-tighter uppercase mb-2">No Records Detected</h5>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest max-w-[280px] leading-relaxed">Your search criteria didn't match any existing vouchers.</p>
                    <a href="<?php echo e(route('coupons.create')); ?>" class="mt-8 px-8 py-4 bg-accent text-white font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl shadow-accent/20 hover:shadow-accent/40 hover:-translate-y-1 transition-all">
                        Generate First Batch
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Custom styled tooltips / UI micro-fixes */
.themed-pagination .pagination { @apply flex gap-1; }
.themed-pagination .page-item .page-link { 
    @apply border-none bg-slate-100/50 dark:bg-white/10 rounded-lg w-8 h-8 flex items-center justify-center text-[10px] font-black text-slate-500 dark:text-slate-400 hover:bg-accent hover:text-white transition-all;
}
.themed-pagination .page-item.active .page-link { @apply bg-accent text-white shadow-lg shadow-accent/20; }
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/admin/coupons/index.blade.php ENDPATH**/ ?>