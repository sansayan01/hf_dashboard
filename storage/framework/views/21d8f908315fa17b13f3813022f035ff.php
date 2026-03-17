<?php $__env->startSection('title', 'Membership Registry'); ?>
<?php $__env->startSection('header_title', 'Membership Management'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <!-- Dashboard Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
            <div
                class="glass bg-white dark:bg-darkbg/40 p-6 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-amber-500/10 text-amber-500 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 id="stat-total" class="text-3xl font-black text-slate-800 dark:text-white leading-none">
                    <?php echo e($patients->total()); ?></h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-2">Total Members</p>
            </div>

            <div
                class="glass bg-white dark:bg-darkbg/40 p-6 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-indigo-500/10 text-indigo-500 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <h3 id="stat-monthly-growth" class="text-3xl font-black text-slate-800 dark:text-white leading-none">
                    <?php echo e($patients->filter(fn($p) => $p->created_at >= now()->subMonth())->count()); ?>

                </h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-2">Monthly Growth</p>
            </div>

            <div
                class="glass bg-white dark:bg-darkbg/40 p-6 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 id="stat-active-plans" class="text-3xl font-black text-slate-800 dark:text-white leading-none">
                    <?php echo e($patients->filter(fn($p) => $p->appointments->isNotEmpty())->count()); ?>

                </h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-2">Active Plans</p>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Membership Registry</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest mt-1 italic">
                    Verified HF Members</p>
            </div>
            <div class="flex items-center space-x-3">
                <form id="filterForm" action="<?php echo e(route('membership.index')); ?>" method="GET" class="relative no-loader">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search members..."
                        class="w-64 h-12 pl-12 pr-4 rounded-2xl border border-slate-200 dark:border-white/5 bg-white dark:bg-darkbg/40 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="button" id="clearFilters"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black uppercase text-slate-400 hover:text-rose-500 transition-colors">Clear</button>
                </form>
            </div>
        </div>

        <?php if($patients->isEmpty()): ?>
            <div
                class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl p-20 text-center">
                <div class="w-24 h-24 bg-amber-500/10 text-amber-500 rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white mb-3">No Registered Members</h4>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-10 font-medium leading-relaxed">
                    Members will appear here once registered via their patient profile. All surveys and regular patients are
                    managed in the <a href="<?php echo e(route('patients.index')); ?>" class="text-accent hover:underline">Patients</a>
                    section.
                </p>
            </div>
        <?php else: ?>
            <div
                class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Member</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">ID & Gender</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Contact</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Joined Date</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="divide-y divide-slate-100 dark:divide-white/5">
                            <?php echo $__env->make('membership.partials.table', ['patients' => $patients], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="paginationContainer">
                <?php if($patients->hasPages()): ?>
                    <div class="mt-8">
                        <?php echo e($patients->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <script src="<?php echo e(asset('js/live-filter.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window._liveFilter = new LiveFilter({
                formId: 'filterForm',
                tableBodyId: 'tableBody',
                paginationId: 'paginationContainer',
                onAfterUpdate: function (data) {
                    if (data && data.stats) {
                        if (document.getElementById('stat-total')) document.getElementById('stat-total').textContent = data.stats.total;
                        if (document.getElementById('stat-monthly-growth')) document.getElementById('stat-monthly-growth').textContent = data.stats.monthly_growth;
                        if (document.getElementById('stat-active-plans')) document.getElementById('stat-active-plans').textContent = data.stats.active_plans;
                    }
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/membership/index.blade.php ENDPATH**/ ?>