

<?php $__env->startSection('title', 'Edit Patient'); ?>
<?php $__env->startSection('header_title', 'Edit Patient Details'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto pb-20">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                <h3 class="font-black text-xl text-slate-800 dark:text-white">Edit Patient Registration</h3>
                <p class="text-sm text-slate-500 mt-1 font-medium">Update the information for this patient record.</p>
            </div>

            <form action="<?php echo e(route('patients.update', $patient->id)); ?>" method="POST" class="p-8 space-y-12">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

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
                            <input type="text" name="full_name" value="<?php echo e(old('full_name', $patient->full_name)); ?>" required
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
                            <input type="tel" name="phone_number" value="<?php echo e(old('phone_number', $patient->phone_number)); ?>"
                                required maxlength="10"
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
                            <input type="number" name="age" value="<?php echo e(old('age', $patient->age)); ?>" required min="1"
                                max="120"
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
                                <option value="male" <?php echo e(old('gender', $patient->gender) == 'male' ? 'selected' : ''); ?>>Male
                                </option>
                                <option value="female" <?php echo e(old('gender', $patient->gender) == 'female' ? 'selected' : ''); ?>>
                                    Female</option>
                                <option value="other" <?php echo e(old('gender', $patient->gender) == 'other' ? 'selected' : ''); ?>>Other
                                </option>
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
                            <input type="text" name="pin" value="<?php echo e(old('pin', $patient->pin)); ?>" required maxlength="6"
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

                        <div class="hidden md:block"></div>
                    </div>

                    <!-- Address -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Address <span
                                class="text-danger">*</span></label>
                        <textarea name="address" rows="3" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                            placeholder="House No, Street, Village/Town etc."
                            oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')"><?php echo e(old('address', $patient->address)); ?></textarea>
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
                    <?php
                        $standardIssues = ['Gas', 'Sugar', 'Pressure', 'Thyroid', 'Uric Acid', 'Skin/Hair', 'Heart', 'Eye', 'ENT', 'Dental'];
                        $dbHealthArr = array_filter(array_map('trim', explode(',', $patient->health_issues ?? '')));

                        // Get selected categories from old input or database
                        $selectedCategories = old('health_issue_category');
                        if (is_null($selectedCategories)) {
                            // If no old input (first load), derive from DB
                            $selectedCategories = array_intersect($dbHealthArr, $standardIssues);
                            $otherFromDb = array_diff($dbHealthArr, $standardIssues);
                            if (!empty($otherFromDb)) {
                                $selectedCategories[] = 'Any other';
                            }
                        }

                        $hasOther = in_array('Any other', $selectedCategories);
                        $otherValuesFromDb = implode(', ', array_diff($dbHealthArr, $standardIssues));
                        $otherText = old('health_issue_other', $otherValuesFromDb);
                    ?>

                    <div class="space-y-4">
                        <div class="space-y-4">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Health Issues
                                (Category)</label>

                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php $__currentLoopData = $standardIssues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <label for="health_issue_<?php echo e($index); ?>"
                                        class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-accent/30 cursor-pointer transition-all has-[:checked]:border-accent/50 has-[:checked]:bg-accent/5">
                                        <input type="checkbox" name="health_issue_category[]" value="<?php echo e($issue); ?>"
                                            id="health_issue_<?php echo e($index); ?>"
                                            class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent"
                                            <?php echo e(in_array($issue, $selectedCategories) ? 'checked' : ''); ?>>
                                        <span class="text-sm font-bold text-slate-700 dark:text-white"><?php echo e($issue); ?></span>
                                    </label>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <label for="health_any_other"
                                    class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-accent/30 cursor-pointer transition-all has-[:checked]:border-accent/50 has-[:checked]:bg-accent/5">
                                    <input type="checkbox" name="health_issue_category[]" value="Any other"
                                        id="health_any_other"
                                        class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent"
                                        onchange="toggleHealthOther(this.checked)" <?php echo e($hasOther ? 'checked' : ''); ?>>
                                    <span class="text-sm font-bold text-slate-700 dark:text-white">Any other</span>
                                </label>
                            </div>
                        </div>

                        <div id="health-other-container" class="space-y-2 <?php echo e($hasOther ? '' : 'hidden'); ?>">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Specify Other
                                Health Issue</label>
                            <textarea id="health-other-input" name="health_issue_other" rows="2"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                                placeholder="Describe the health issue..."><?php echo e($otherText); ?></textarea>
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
                            <input type="text" name="relative_name"
                                value="<?php echo e(old('relative_name', $patient->relative_name)); ?>"
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
                                <?php $__currentLoopData = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($bg); ?>" <?php echo e(old('blood_group', $patient->blood_group) == $bg ? 'selected' : ''); ?>><?php echo e($bg); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Aadhar Number -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Aadhar
                                Number</label>
                            <input type="text" name="aadhar_number"
                                value="<?php echo e(old('aadhar_number', $patient->aadhar_number)); ?>" maxlength="12"
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
                            <input type="text" name="pan_number" value="<?php echo e(old('pan_number', $patient->pan_number)); ?>"
                                maxlength="10"
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
                                data-selected="<?php echo e(old('district', $patient->district)); ?>"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="">Select District</option>
                            </select>
                        </div>

                        <!-- Block -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Block</label>
                            <select id="block-select" name="block" data-selected="<?php echo e(old('block', $patient->block)); ?>"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none disabled:opacity-50">
                                <option value="">Select Block</option>
                            </select>
                        </div>

                        <!-- GP -->
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Gram Panchayat
                                (GP)</label>
                            <select id="gp-select" name="gp" data-selected="<?php echo e(old('gp', $patient->gp)); ?>"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none disabled:opacity-50">
                                <option value="">Select GP</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Landmark -->
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest">Landmark</label>
                            <input type="text" name="landmark" value="<?php echo e(old('landmark', $patient->landmark)); ?>"
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
                                <option value="No" <?php echo e(old('insurance_loan_req', $patient->insurance_loan_req) == 'No' ? 'selected' : ''); ?>>No</option>
                                <option value="Yes" <?php echo e(old('insurance_loan_req', $patient->insurance_loan_req) == 'Yes' ? 'selected' : ''); ?>>Yes</option>
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
                            oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')"><?php echo e(old('past_diseases', $patient->past_diseases)); ?></textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-slate-100 dark:border-white/5">
                    <a href="<?php echo e(route('patients.index')); ?>"
                        class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest text-[10px] rounded-2xl hover:bg-slate-200 transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-4 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition-all">
                        Update Patient
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

            const selectedDistrict = districtSelect.dataset.selected;
            const selectedBlock = blockSelect.dataset.selected;
            const selectedGP = gpSelect.dataset.selected;

            // Function to populate blocks
            function populateBlocks(district, selectedValue = '') {
                blockSelect.innerHTML = '<option value="">Select Block</option>';
                gpSelect.innerHTML = '<option value="">Select GP</option>';
                gpSelect.disabled = true;

                if (district && window.locationData["West Bengal"][district]) {
                    blockSelect.disabled = false;
                    const blocks = Object.keys(window.locationData["West Bengal"][district]).sort();
                    blocks.forEach(block => {
                        const option = new Option(block, block);
                        if (block === selectedValue) option.selected = true;
                        blockSelect.add(option);
                    });
                    if (selectedValue) populateGPs(district, selectedValue, selectedGP);
                } else {
                    blockSelect.disabled = true;
                }
            }

            // Function to populate GPs
            function populateGPs(district, block, selectedValue = '') {
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (district && block && window.locationData["West Bengal"][district][block]) {
                    gpSelect.disabled = false;
                    const gps = window.locationData["West Bengal"][district][block].sort();
                    gps.forEach(gp => {
                        const option = new Option(gp, gp);
                        if (gp === selectedValue) option.selected = true;
                        gpSelect.add(option);
                    });
                } else {
                    gpSelect.disabled = true;
                }
            }

            // Populate Districts
            if (window.locationData && window.locationData["West Bengal"]) {
                const wbDistricts = Object.keys(window.locationData["West Bengal"]).sort();
                wbDistricts.forEach(dist => {
                    const option = new Option(dist, dist);
                    if (dist === selectedDistrict) option.selected = true;
                    districtSelect.add(option);
                });

                if (selectedDistrict) {
                    populateBlocks(selectedDistrict, selectedBlock);
                }
            }

            districtSelect.addEventListener('change', function () {
                populateBlocks(this.value);
            });

            blockSelect.addEventListener('change', function () {
                populateGPs(districtSelect.value, this.value);
            });
        });

        function toggleHealthOther(isChecked) {
            const container = document.getElementById('health-other-container');
            const input = document.getElementById('health-other-input');

            if (isChecked) {
                container.classList.remove('hidden');
                input.required = true;
                if (input.value === '') input.focus();
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

            const aadhaarInput = document.querySelector('input[name="aadhar_number"]');
            if (aadhaarInput && aadhaarInput.value && aadhaarInput.value.length !== 12) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Aadhaar',
                    text: 'Aadhaar Number must be exactly 12 digits.',
                    background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#1e293b'
                });
                aadhaarInput.focus();
                return false;
            }
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\patients\edit.blade.php ENDPATH**/ ?>