<?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
        <td class="p-6">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-accent/10 text-accent dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black">
                    <?php echo e(substr($patient->full_name, 0, 1)); ?>

                </div>
                <div>
                    <h4 class="font-black text-slate-800 dark:text-white text-sm"><?php echo e($patient->full_name); ?></h4>
                    <div class="flex items-center space-x-2 text-[10px] text-slate-500 font-bold uppercase tracking-tight mt-0.5">
                        <span><?php echo e($patient->patient_id); ?></span>
                        <span class="text-slate-300 dark:text-slate-700 font-black">•</span>
                        <span><?php echo e(ucfirst($patient->gender)); ?></span>
                    </div>
                </div>
            </div>
        </td>
        <?php if(auth()->user()->designation !== 'staff'): ?>
            <td class="p-6">
                <?php if($patient->is_member): ?>
                    <span class="inline-flex items-center px-4 py-2 bg-emerald-500/10 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/10">
                        Member
                    </span>
                <?php elseif(auth()->user()->designation !== 'staff'): ?>
                    <a href="<?php echo e(route('patients.membership', $patient->id)); ?>" class="inline-flex items-center space-x-2 px-4 py-2 bg-amber-500/10 text-amber-500 rounded-xl hover:bg-amber-500 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-amber-500/10 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Become Member</span>
                    </a>
                <?php else: ?>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Non-Member</span>
                <?php endif; ?>
            </td>
        <?php endif; ?>
        <?php if(auth()->user()->designation === 'staff'): ?>
        <td class="p-6">
            <a href="<?php echo e(route('medicine.distribute', $patient->id)); ?>" class="inline-flex items-center space-x-2 px-4 py-2 bg-emerald-600/10 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-emerald-600/10">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
                <span>Dispense</span>
            </a>
        </td>
        <?php endif; ?>
        <td class="p-6">
            <a href="<?php echo e(route('patients.show', $patient->id)); ?>" class="inline-flex items-center space-x-2 px-4 py-2 bg-indigo-600/10 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-indigo-600/10">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <span>Overview</span>
            </a>
        </td>
        <td class="p-6">
            <?php if($patient->health_issues): ?>
                <div class="inline-flex items-center space-x-2 px-3 py-1.5 bg-amber-500/10 text-amber-500 rounded-lg border border-amber-500/10">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span class="text-[10px] font-black uppercase tracking-wider"><?php echo e(Str::limit($patient->health_issues, 20)); ?></span>
                </div>
            <?php else: ?>
                <span class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-lg text-[10px] font-black uppercase tracking-wider">
                    Normal
                </span>
            <?php endif; ?>
        </td>
        <td class="p-6">
            <div class="space-y-1">
                <div class="flex items-center space-x-2 text-slate-600 dark:text-slate-300">
                    <i class="fas fa-phone text-[10px] w-4 text-center text-slate-400"></i>
                    <span class="text-xs font-bold"><?php echo e($patient->phone_number); ?></span>
                </div>
            </div>
        </td>
        <td class="p-6">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg overflow-hidden ring-2 ring-slate-100 dark:ring-slate-800">
                    <?php if($patient->creator && $patient->creator->profile && $patient->creator->profile->profile_picture): ?>
                        <img src="<?php echo e($patient->creator->profile->getProfilePictureUrl()); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-[10px] font-black">
                            <?php echo e(substr($patient->creator->profile->full_name ?? ($patient->creator->employee_id ?? 'U'), 0, 1)); ?>

                        </div>
                    <?php endif; ?>
                </div>
                <div>
                    <?php if(auth()->user()->isSuperAdmin() && $patient->creator): ?>
                        <a href="<?php echo e(route('users.show', $patient->creator->id)); ?>" class="text-xs font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                            <span><?php echo e($patient->creator->profile->full_name ?? ($patient->creator->employee_id ?? 'Unknown User')); ?></span>
                            <svg class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    <?php else: ?>
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-200"><?php echo e($patient->creator->profile->full_name ?? ($patient->creator->employee_id ?? 'Unknown User')); ?></p>
                    <?php endif; ?>
                    <p class="text-[10px] font-medium text-slate-400 mt-0.5"><?php echo e($patient->created_at->format('M d, Y')); ?></p>
                </div>
            </div>
        </td>
        <td class="p-6 text-right flex justify-end space-x-2">
            <?php if(!in_array(auth()->user()->designation, ['ro', 'rm', 'bm', 'dm'])): ?>
            <a href="<?php echo e(route('pathology.create', $patient->id)); ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 transition-all border border-rose-500/10 shadow-sm" title="Record Pathology">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.675.337a4 4 0 01-2.5.467l-3.21.321a2 2 0 00-1.554 1.554l-.321 3.21a2 2 0 001.554 1.554l3.21-.321a2 2 0 001.554-1.554l.321-3.21a2 2 0 00-1.554-1.554z" />
                </svg>
            </a>
            <?php endif; ?>
             <?php if(Auth::user()->isSuperAdmin() || Auth::user()->designation == 'office_in_charge'): ?>
                <a href="<?php echo e(route('medicine.distribute', $patient->id)); ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500 hover:text-white dark:bg-blue-500/10 dark:hover:bg-blue-500 text-blue-500 transition-all border border-blue-500/10 shadow-sm" title="Dispense Medicine">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </a>
             <?php endif; ?>
             <?php if(auth()->user()->id === $patient->created_by || auth()->user()->canAccess($patient->creator)): ?>
                <form action="<?php echo e(route('patients.destroy', $patient->id)); ?>" method="POST" onsubmit="return confirm('Move this patient record to BIN?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 transition-all border border-rose-500/10 shadow-sm" title="Delete Patient">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
             <?php endif; ?>
         </td>
    </tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\xampp\htdocs\HF\resources\views/patients/partials/table.blade.php ENDPATH**/ ?>