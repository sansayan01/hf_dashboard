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
                <h3 class="text-3xl font-black text-slate-800 dark:text-white leading-none"><?php echo e($patients->total()); ?></h3>
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
                <h3 class="text-3xl font-black text-slate-800 dark:text-white leading-none">
                    <?php echo e($patients->filter(fn($p) => $p->created_at >= now()->subMonth())->count()); ?></h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-2">Monthly Growth</p>
            </div>

            <div
                class="glass bg-white dark:bg-darkbg/40 p-6 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-emerald-500/10 text-emerald-500 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white leading-none"><?php echo e($patients->filter(fn($p) => $p->appointments->isNotEmpty())->count()); ?></h3>
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
                <form action="<?php echo e(route('membership.index')); ?>" method="GET" class="relative no-loader">
                    <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search members..."
                        class="w-64 h-12 pl-12 pr-4 rounded-2xl border border-slate-200 dark:border-white/5 bg-white dark:bg-darkbg/40 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
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
                    Members will appear here once registered via their patient profile. All surveys and regular patients are managed in the <a href="<?php echo e(route('patients.index')); ?>" class="text-accent hover:underline">Patients</a> section.
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
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="p-6">
                                        <div class="flex items-center space-x-4">
                                            <div
                                                class="w-10 h-10 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center text-sm font-black">
                                                <?php echo e(substr($patient->full_name, 0, 1)); ?>

                                            </div>
                                            <div>
                                                <h4 class="font-black text-slate-800 dark:text-white text-sm">
                                                    <?php echo e($patient->full_name); ?></h4>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">
                                                    <?php echo e($patient->relative_name ?? 'N/A'); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="space-y-1">
                                            <p class="text-xs font-black text-slate-700 dark:text-slate-200">
                                                <?php echo e($patient->patient_id); ?></p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                                <?php echo e(ucfirst($patient->gender)); ?></p>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="space-y-1">
                                            <p class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                                <?php echo e($patient->phone_number); ?></p>
                                            <p class="text-[10px] text-slate-400 font-medium truncate max-w-[150px]">
                                                <?php echo e($patient->address); ?></p>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <p class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                            <?php echo e($patient->created_at->format('d M, Y')); ?></p>
                                        <p class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">
                                            <?php echo e($patient->created_at->diffForHumans()); ?></p>
                                    </td>
                                    <td class="p-6">
                                        <?php $hasAppointment = $patient->appointments->isNotEmpty(); ?>
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest <?php echo e($hasAppointment ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'); ?>">
                                            <?php echo e($hasAppointment ? 'Active' : 'Pending Care'); ?>

                                        </span>
                                    </td>
                                    <td class="p-6 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="<?php echo e(route('patients.show', $patient->id)); ?>"
                                                class="p-2 bg-slate-100 dark:bg-white/5 text-slate-500 hover:text-accent transition rounded-lg"
                                                title="View Profile">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                             <a href="<?php echo e(route('patients.membership', $patient->id)); ?>"
                                                class="p-2 bg-amber-500/10 text-amber-600 transition rounded-lg"
                                                title="Membership Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                </svg>
                                            </a>

                                             <a href="<?php echo e(route('membership.card.download', $patient->id)); ?>"
                                                class="p-2 bg-emerald-500/10 text-emerald-600 transition rounded-lg"
                                                title="Download PVC Card">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>

                                            <a href="<?php echo e(route('membership.card.preview', $patient->id)); ?>" target="_blank"
                                                class="p-2 bg-indigo-500/10 text-indigo-600 transition rounded-lg"
                                                title="Preview Card">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            
                                            <?php if(auth()->user()->isSuperAdmin()): ?>
                                                <form action="<?php echo e(route('patients.membership.cancel', $patient->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to cancel this membership? The patient will be moved to the regular patient section.')">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="p-2 bg-orange-500/10 text-orange-500 hover:bg-orange-500 hover:text-white transition rounded-lg" title="Cancel Membership">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            
                                            <?php if(auth()->user()->id === $patient->created_by || auth()->user()->canAccess($patient->creator)): ?>
                                                <form action="<?php echo e(route('patients.destroy', $patient->id)); ?>" method="POST" onsubmit="return confirm('Move this member record to BIN?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition rounded-lg" title="Delete Member">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-8">
                <?php echo e($patients->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/membership/index.blade.php ENDPATH**/ ?>