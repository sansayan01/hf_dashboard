<?php $__env->startSection('title', 'Membership Registration'); ?>
<?php $__env->startSection('header_title', 'New Member Registration'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto pb-20">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                <h3 class="font-black text-xl text-slate-800 dark:text-white">New Member Registration</h3>
                <p class="text-sm text-slate-500 mt-1 font-medium">Please provide the member/patient details below.</p>
            </div>

            <form action="<?php echo e(route('patients.store')); ?>" method="POST" class="p-8 space-y-8">
                <?php echo csrf_field(); ?>

                <?php if(count($users) > 0): ?>
                    <div
                        class="p-6 bg-indigo-50/50 dark:bg-indigo-500/5 rounded-2xl border-2 border-indigo-100 dark:border-indigo-500/20 space-y-4">
                        <div class="flex items-center space-x-3 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <h4 class="text-sm font-black uppercase tracking-widest">
                                <?php echo e(auth()->user()->isSuperAdmin() ? 'Register Behalf Of' : 'Register for Team Member'); ?>

                            </h4>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Select Team
                                Member (Search by Name or ID)</label>
                            <input list="team_members" name="created_by_user_search" id="created_by_user_search"
                                class="w-full px-5 py-4 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl outline-none text-sm font-bold text-slate-700 dark:text-white transition-all focus:border-indigo-500"
                                placeholder="Start typing name or volunteer ID..." oninput="updateUserId(this.value)">
                            <input type="hidden" name="created_by_user" id="created_by_user"
                                value="<?php echo e(old('created_by_user')); ?>">
                            <datalist id="team_members">
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($u->employee_id); ?> - <?php echo e($u->profile->full_name); ?>" data-id="<?php echo e($u->id); ?>">
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </datalist>
                            <script>
                                function updateUserId(value) {
                                    const options = document.querySelectorAll('#team_members option');
                                    let foundId = '';
                                    options.forEach(option => {
                                        if (option.value === value) {
                                            foundId = option.getAttribute('data-id');
                                        }
                                    });
                                    document.getElementById('created_by_user').value = foundId;
                                }
                            </script>
                            <p class="text-[10px] text-slate-400 font-medium italic">If left empty, the patient will be
                                registered under your name.</p>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Section 1: Mandatory Information -->
                <div class="space-y-6">
                    <div
                        class="flex items-center space-x-4 p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                        <div class="h-6 w-1.5 bg-accent rounded-full shadow-[0_0_12px_rgba(var(--accent-rgb),0.5)]"></div>
                        <h4
                            class="font-black text-lg md:text-xl uppercase tracking-[0.2em] text-slate-800 dark:text-white underline underline-offset-[12px] decoration-accent/40 decoration-4">
                            for appointment booking</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="full_name" value="<?php echo e(old('full_name')); ?>" required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="Patient's Full Name"
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                            <?php $__errorArgs = ['full_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Phone Number -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Phone Number
                                <span class="text-danger">*</span></label>
                            <input type="tel" name="phone_number" value="<?php echo e(old('phone_number')); ?>" required maxlength="10"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="10-digit mobile number"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                            <?php $__errorArgs = ['phone_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Age -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Age <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="age" value="<?php echo e(old('age')); ?>" required min="1" max="120"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="Age">
                            <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Gender -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Gender <span
                                    class="text-danger">*</span></label>
                            <select name="gender" required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="">Select Gender</option>
                                <option value="male" <?php echo e(old('gender') == 'male' ? 'selected' : ''); ?>>Male</option>
                                <option value="female" <?php echo e(old('gender') == 'female' ? 'selected' : ''); ?>>Female</option>
                                <option value="other" <?php echo e(old('gender') == 'other' ? 'selected' : ''); ?>>Other</option>
                            </select>
                            <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- PIN Code -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">PIN Code <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="pin" value="<?php echo e(old('pin')); ?>" required maxlength="6"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="6-digit PIN"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                            <?php $__errorArgs = ['pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Empty for grid alignment -->
                        <div class="hidden md:block"></div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Address <span
                                class="text-danger">*</span></label>
                        <textarea name="address" rows="3" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                            placeholder="House No, Street, Village/Town etc."
                            oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')"><?php echo e(old('address')); ?></textarea>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Health Issues -->
                    <div class="space-y-4">
                        <div class="space-y-4">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Health Issues
                                (Category)</label>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php
                                    $standardIssues = ['Gas', 'Sugar', 'Pressure', 'Thyroid', 'Uric Acid', 'Skin/Hair', 'Heart', 'Eye', 'ENT', 'Dental'];
                                ?>
                                <?php $__currentLoopData = $standardIssues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label for="health_issue_<?php echo e($index); ?>"
                                        class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-accent/30 cursor-pointer transition-all has-[:checked]:border-accent/50 has-[:checked]:bg-accent/5">
                                        <input type="checkbox" name="health_issue_category[]" value="<?php echo e($issue); ?>"
                                            id="health_issue_<?php echo e($index); ?>"
                                            class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent"
                                            <?php echo e(is_array(old('health_issue_category')) && in_array($issue, old('health_issue_category')) ? 'checked' : ''); ?>>
                                        <span class="text-sm font-bold text-slate-700 dark:text-white"><?php echo e($issue); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <label for="health_any_other"
                                    class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-accent/30 cursor-pointer transition-all has-[:checked]:border-accent/50 has-[:checked]:bg-accent/5">
                                    <input type="checkbox" name="health_issue_category[]" value="Any other"
                                        id="health_any_other"
                                        class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent"
                                        onchange="toggleHealthOther(this.checked)" <?php echo e(is_array(old('health_issue_category')) && in_array('Any other', old('health_issue_category')) ? 'checked' : ''); ?>>
                                    <span class="text-sm font-bold text-slate-700 dark:text-white">Any other</span>
                                </label>
                            </div>
                        </div>

                        <div id="health-other-container"
                            class="space-y-2 <?php echo e(is_array(old('health_issue_category')) && in_array('Any other', old('health_issue_category')) ? '' : 'hidden'); ?>">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Specify Other
                                Health Issue</label>
                            <textarea id="health-other-input" name="health_issue_other" rows="2"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                                placeholder="Describe the health issue..."><?php echo e(old('health_issue_other')); ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Optional Information -->
                <div class="space-y-8 pt-16 mt-8 border-t border-slate-100 dark:border-white/5">
                    <div
                        class="flex items-center space-x-4 p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                        <div class="h-6 w-1.5 bg-indigo-500 rounded-full shadow-[0_0_12px_rgba(99,102,241,0.4)]"></div>
                        <h4
                            class="font-black text-lg md:text-xl uppercase tracking-[0.2em] text-slate-800 dark:text-white underline underline-offset-[12px] decoration-indigo-500/40 decoration-4">
                            for membership purpose</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Father/Husband Name -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Father/Husband
                                Name</label>
                            <input type="text" name="relative_name" value="<?php echo e(old('relative_name')); ?>"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="Relative's Name"
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                            <?php $__errorArgs = ['relative_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Blood Group -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Blood
                                Group</label>
                            <select name="blood_group"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="">Select Blood Group</option>
                                <option value="A+" <?php echo e(old('blood_group') == 'A+' ? 'selected' : ''); ?>>A+</option>
                                <option value="A-" <?php echo e(old('blood_group') == 'A-' ? 'selected' : ''); ?>>A-</option>
                                <option value="B+" <?php echo e(old('blood_group') == 'B+' ? 'selected' : ''); ?>>B+</option>
                                <option value="B-" <?php echo e(old('blood_group') == 'B-' ? 'selected' : ''); ?>>B-</option>
                                <option value="O+" <?php echo e(old('blood_group') == 'O+' ? 'selected' : ''); ?>>O+</option>
                                <option value="O-" <?php echo e(old('blood_group') == 'O-' ? 'selected' : ''); ?>>O-</option>
                                <option value="AB+" <?php echo e(old('blood_group') == 'AB+' ? 'selected' : ''); ?>>AB+</option>
                                <option value="AB-" <?php echo e(old('blood_group') == 'AB-' ? 'selected' : ''); ?>>AB-</option>
                            </select>
                            <?php $__errorArgs = ['blood_group'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Aadhar Number -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Aadhar
                                Number</label>
                            <input type="text" name="aadhar_number" value="<?php echo e(old('aadhar_number')); ?>" maxlength="12"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="12-digit Aadhar"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                            <?php $__errorArgs = ['aadhar_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- PAN Number -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">PAN
                                Number</label>
                            <input type="text" name="pan_number" value="<?php echo e(old('pan_number')); ?>" maxlength="10"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white uppercase"
                                placeholder="ABCDE1234F" oninput="validatePAN(this)">
                            <?php $__errorArgs = ['pan_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Location Dropdowns -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- District -->
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest">District</label>
                            <select id="district-select" name="district"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="">Select District</option>
                                <!-- Populated by JS -->
                            </select>
                            <?php $__errorArgs = ['district'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Block -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Block</label>
                            <select id="block-select" name="block" disabled
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none disabled:opacity-50">
                                <option value="">Select Block</option>
                            </select>
                            <?php $__errorArgs = ['block'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- GP -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Gram Panchayat
                                (GP)</label>
                            <select id="gp-select" name="gp" disabled
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none disabled:opacity-50">
                                <option value="">Select GP</option>
                            </select>
                            <?php $__errorArgs = ['gp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-danger font-bold mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Landmark -->
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest">Landmark</label>
                            <input type="text" name="landmark" value="<?php echo e(old('landmark')); ?>"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="Near Mandir, School, etc."
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                        </div>

                        <!-- Health Insurance/Loan Req -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Insurance/Loan
                                Requirement</label>
                            <select name="insurance_loan_req"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="No" <?php echo e(old('insurance_loan_req') == 'No' ? 'selected' : ''); ?>>No</option>
                                <option value="Yes" <?php echo e(old('insurance_loan_req') == 'Yes' ? 'selected' : ''); ?>>Yes</option>
                            </select>
                        </div>
                    </div>

                    <!-- Past Diseases -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Any diseases till
                            now</label>
                        <textarea name="past_diseases" rows="3"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                            placeholder="Previous medical history..."
                            oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')"><?php echo e(old('past_diseases')); ?></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-slate-100 dark:border-white/5">
                    <a href="<?php echo e(route('patients.index')); ?>"
                        class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest text-[10px] rounded-2xl hover:bg-slate-200 transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-4 bg-amber-500 text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-amber-500/20 hover:scale-105 active:scale-95 transition-all">
                        Register Member
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(asset('js/locations.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const districtSelect = document.getElementById('district-select');
            const blockSelect = document.getElementById('block-select');
            const gpSelect = document.getElementById('gp-select');

            // Populate Districts for West Bengal (default)
            if (window.locationData && window.locationData["West Bengal"]) {
                const wbDistricts = Object.keys(window.locationData["West Bengal"]).sort();
                wbDistricts.forEach(dist => {
                    const option = new Option(dist, dist);
                    districtSelect.add(option);
                });
            }

            districtSelect.addEventListener('change', function () {
                const district = this.value;
                blockSelect.innerHTML = '<option value="">Select Block</option>';
                gpSelect.innerHTML = '<option value="">Select GP</option>';
                gpSelect.disabled = true;

                if (district && window.locationData["West Bengal"][district]) {
                    blockSelect.disabled = false;
                    const blocks = Object.keys(window.locationData["West Bengal"][district]).sort();
                    blocks.forEach(block => {
                        const option = new Option(block, block);
                        blockSelect.add(option);
                    });
                } else {
                    blockSelect.disabled = true;
                }
            });

            blockSelect.addEventListener('change', function () {
                const district = districtSelect.value;
                const block = this.value;
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (district && block && window.locationData["West Bengal"][district][block]) {
                    gpSelect.disabled = false;
                    const gps = window.locationData["West Bengal"][district][block].sort();
                    gps.forEach(gp => {
                        const option = new Option(gp, gp);
                        gpSelect.add(option);
                    });
                } else {
                    gpSelect.disabled = true;
                }
            });
        });

        function toggleHealthOther(isChecked) {
            const container = document.getElementById('health-other-container');
            const input = document.getElementById('health-other-input');

            if (isChecked) {
                container.classList.remove('hidden');
                input.required = true;
                input.focus();
            } else {
                container.classList.add('hidden');
                input.required = false;
            }
        }

        // PAN Formatting helper
        window.validatePAN = function (input) {
            let val = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            let result = '';

            for (let i = 0; i < val.length && i < 10; i++) {
                if (i < 5) {
                    if (/[A-Z]/.test(val[i])) result += val[i];
                } else if (i < 9) {
                    if (/[0-9]/.test(val[i])) result += val[i];
                } else {
                    if (/[A-Z]/.test(val[i])) result += val[i];
                }
            }
            input.value = result;
        };

        // Form Validation on Submit
        const form = document.querySelector('form');
        form.addEventListener('submit', function (e) {
            const panInput = document.querySelector('input[name="pan_number"]');
            const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
            if (panInput && panInput.value && !panPattern.test(panInput.value)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid PAN Format',
                    text: 'Rules: 5 Letters, 4 Digits, 1 Letter (Example: ABCDE1234F)',
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#1e293b'
                });
                panInput.focus();
                return false;
            }

        }
                });

        // Real-time Uniqueness Check for Aadhaar
        const aadharInput = document.querySelector('input[name="aadhar_number"]');
        if (aadharInput) {
            aadharInput.addEventListener('input', function () {
                const val = this.value;
                if (val.length === 12) {
                    checkPatientUniqueness('aadhar_number', val);
                }
            });
        }

        // Real-time Uniqueness Check for Phone
        const phoneInput = document.querySelector('input[name="phone_number"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function () {
                const val = this.value;
                if (val.length === 10) {
                    checkPatientUniqueness('phone_number', val);
                }
            });
        }

        async function checkPatientUniqueness(field, value) {
            try {
                const response = await fetch('<?php echo e(route("patients.check-uniqueness")); ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                    },
                    body: JSON.stringify({ field: field, value: value })
                });

                const data = await response.json();
                if (data.exists) {
                    const p = data.patient;
                    Swal.fire({
                        icon: 'info',
                        title: 'Patient Already Exists!',
                        html: `
                                    <div class="text-left p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-white/5 mt-4">
                                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Patient Details</p>
                                        <div class="space-y-2">
                                            <p class="text-sm font-bold text-slate-700 dark:text-white">Name: <span class="text-accent">${p.full_name}</span></p>
                                            <p class="text-sm font-bold text-slate-700 dark:text-white">ID: <span class="text-accent">${p.patient_id}</span></p>
                                            <p class="text-sm font-bold text-slate-700 dark:text-white">Phone: <span class="text-accent">${p.phone_number}</span></p>
                                            <p class="text-sm font-bold text-slate-700 dark:text-white">Role: <span class="px-2 py-0.5 rounded text-[10px] ${p.is_member ? 'bg-amber-500/10 text-amber-500' : 'bg-blue-500/10 text-blue-500'} font-black uppercase">${p.is_member ? 'Premium Member' : 'Regular Patient'}</span></p>
                                        </div>
                                        <div class="mt-6">
                                            <a href="/patients/${p.id}" class="inline-block w-full text-center px-4 py-3 bg-accent text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all hover:scale-105">View Profile</a>
                                        </div>
                                    </div>
                                `,
                        confirmButtonText: 'Got It',
                        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#1e293b',
                        confirmButtonColor: '#3C50E0',
                        customClass: {
                            popup: 'rounded-3xl border border-white/10 shadow-2xl overflow-hidden'
                        }
                    });
                }
            } catch (err) {
                console.error('Uniqueness check failed:', err);
            }
        }
    </script>
<?php $__env->stopSection(); ?>
```
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\patients\create.blade.php ENDPATH**/ ?>