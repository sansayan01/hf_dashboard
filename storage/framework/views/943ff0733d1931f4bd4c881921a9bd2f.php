<?php $__env->startSection('title', 'Edit Survey'); ?>
<?php $__env->startSection('header_title', 'Edit Field Survey'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto pb-20">
        <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                <h3 class="font-black text-xl text-slate-800 dark:text-white">Edit Survey Participant Details</h3>
                <p class="text-sm text-slate-500 mt-1 font-medium">Update the information for the survey record</p>
            </div>

            <form action="<?php echo e(route('surveys.update', $survey->id)); ?>" method="POST" class="p-8 space-y-8">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" value="<?php echo e(old('full_name', $survey->full_name)); ?>" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="Full Name"
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

                    <!-- Age -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Age <span class="text-danger">*</span></label>
                        <input type="number" name="age" value="<?php echo e(old('age', $survey->age)); ?>" required min="1" max="120"
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
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Gender <span class="text-danger">*</span></label>
                        <select name="gender" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                            <option value="">Select Gender</option>
                            <option value="male" <?php echo e(old('gender', $survey->gender) == 'male' ? 'selected' : ''); ?>>Male</option>
                            <option value="female" <?php echo e(old('gender', $survey->gender) == 'female' ? 'selected' : ''); ?>>Female</option>
                            <option value="other" <?php echo e(old('gender', $survey->gender) == 'other' ? 'selected' : ''); ?>>Other</option>
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

                    <!-- Phone Number -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone_number" value="<?php echo e(old('phone_number', $survey->phone_number)); ?>" required maxlength="10"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="10-digit Phone Number"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        <?php $__errorArgs = ['phone_number'];
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
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">PIN Code <span class="text-danger">*</span></label>
                        <input type="text" name="pin" value="<?php echo e(old('pin', $survey->pin)); ?>" required maxlength="6"
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
                </div>

                <!-- Address -->
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Address <span class="text-danger">*</span></label>
                    <textarea name="address" rows="3" required
                        class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                        placeholder="House No, Street, Village/Town etc."
                        oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')"><?php echo e(old('address', $survey->address)); ?></textarea>
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
                    $dbHealthArr = array_filter(array_map('trim', explode(',', $survey->health_issues ?? '')));
                    
                    // Get selected categories from old input or database
                    $selectedCategories = old('health_issue_category');
                    if (is_null($selectedCategories)) {
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
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Health Issues (Category)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <?php $__currentLoopData = $standardIssues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-accent/30 cursor-pointer transition-all has-[:checked]:border-accent/50 has-[:checked]:bg-accent/5">
                                <input type="checkbox" name="health_issue_category[]" value="<?php echo e($issue); ?>"
                                    class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent"
                                    <?php echo e(in_array($issue, $selectedCategories) ? 'checked' : ''); ?>>
                                <span class="text-sm font-bold text-slate-700 dark:text-white"><?php echo e($issue); ?></span>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                        <label class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-accent/30 cursor-pointer transition-all has-[:checked]:border-accent/50 has-[:checked]:bg-accent/5">
                            <input type="checkbox" name="health_issue_category[]" value="Any other" id="health_any_other"
                                class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent"
                                onchange="toggleHealthOther(this.checked)"
                                <?php echo e($hasOther ? 'checked' : ''); ?>>
                            <span class="text-sm font-bold text-slate-700 dark:text-white">Any other</span>
                        </label>
                    </div>

                    <div id="health-other-container" class="space-y-2 <?php echo e($hasOther ? '' : 'hidden'); ?>">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Specify Other Health Issue</label>
                        <textarea id="health-other-input" name="health_issue_other" rows="3"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                            placeholder="Enter the specific health issue here..."><?php echo e($otherText); ?></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-slate-100 dark:border-white/5">
                    <a href="<?php echo e(route('surveys.index')); ?>"
                        class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest text-[10px] rounded-2xl hover:bg-slate-200 transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-4 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition-all">
                        Update Survey
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
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
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\surveys\edit.blade.php ENDPATH**/ ?>