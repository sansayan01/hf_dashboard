<?php $__env->startSection('title', 'Add Medicine'); ?>
<?php $__env->startSection('header_title', 'Register New Medicine'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-xl overflow-hidden text-slate-800 dark:text-white">
            <div class="p-8 border-b border-slate-100 dark:border-white/5">
                <h3 class="font-bold text-xl">Medicine Details</h3>
                <p class="text-sm text-slate-500">Enter the basic definition of the medicine.</p>
            </div>

            <form action="<?php echo e(route('inventory.medicines.store')); ?>" method="POST" class="p-8 space-y-6">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Medicine
                            Brand Name</label>
                        <input type="text" name="name" required placeholder="e.g., Paracetamol"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Generic
                            Name (Composition)</label>
                        <input type="text" name="generic_name" placeholder="e.g., Acetaminophen"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Unit</label>
                        <select name="unit" required
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="Tablet">Tablet</option>
                            <option value="Strip">Strip (10 Tablets)</option>
                            <option value="Capsule">Capsule</option>
                            <option value="Bottle">Bottle</option>
                            <option value="Tube">Tube</option>
                            <option value="Injection">Injection</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Dosage
                            Strength</label>
                        <input type="text" name="dosage" placeholder="e.g., 500mg, 5ml"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <div class="md:col-span-2">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Category</label>
                        <select name="category_id" required
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select Category</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Estimated
                            Cost (Market Price)</label>
                        <div class="flex items-center space-x-3">
                            <input type="number" step="0.01" name="market_price" placeholder="0.00"
                                class="w-32 h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">

                            <span class="text-sm font-bold text-slate-500">Rupees <span id="per_text"
                                    class="hidden">per</span></span>

                            <div id="unit_count_wrapper" class="hidden flex items-center space-x-2">
                                <input type="number" name="market_price_unit_count" id="market_price_unit_count"
                                    placeholder="10"
                                    class="w-24 h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">

                                <span class="text-sm font-bold text-slate-500">Tablets</span>
                            </div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5 font-medium">* Helps in calculating per-tablet cost.</p>
                    </div>

                    <div class="md:col-span-2 hidden" id="box_config_wrapper">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                            <span id="box_label">Tablets</span> per Box
                        </label>
                        <div class="flex items-center space-x-3">
                            <input type="number" name="units_per_box" id="units_per_box" placeholder="100" value="100"
                                class="w-32 h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <span class="text-sm font-bold text-slate-500">
                                <span id="box_unit_label">Tablets</span> per Box
                            </span>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5 font-medium">* Used for box-based stock entry and
                            transfers.</p>
                    </div>


                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="<?php echo e(route('inventory.medicines.index')); ?>"
                        class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Cancel</a>
                    <button type="submit"
                        class="px-8 h-12 bg-accent text-white rounded-2xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                        Save Medicine Register
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const unitSelect = document.querySelector('select[name="unit"]');
            const unitCountWrapper = document.getElementById('unit_count_wrapper');
            const perText = document.getElementById('per_text');
            const unitCountInput = document.getElementById('market_price_unit_count');
            const boxConfigWrapper = document.getElementById('box_config_wrapper');
            const boxLabel = document.getElementById('box_label');
            const boxUnitLabel = document.getElementById('box_unit_label');
            const unitsPerBoxInput = document.getElementById('units_per_box');

            function toggleUnitCount() {
                if (unitSelect.value === 'Tablet' || unitSelect.value === 'Capsule' || unitSelect.value === 'Injection') {
                    // For single units, we might just want to show "Rupees" (cost per unit)
                    // But user request says "if tablet is selected then only show the rupees per ______ section"
                    // Wait, re-reading request: "if tablet is selected then only show the rupees per ______ section otherwise show only the rupees"
                    // So if Tablet -> Show "Rupees per [ ] Tablets"
                    // Else -> Show "Rupees"

                    if (unitSelect.value === 'Tablet') {
                        unitCountWrapper.classList.remove('hidden');
                        perText.classList.remove('hidden');
                        unitCountInput.setAttribute('required', 'required'); // Maybe optional? Sticking to visible/hidden for now
                    } else {
                        unitCountWrapper.classList.add('hidden');
                        perText.classList.add('hidden');
                        unitCountInput.removeAttribute('required');
                        unitCountInput.value = '';
                    }
                } else {
                    // Default behavior for other units (Bottle, Tube, etc) - usually cost is per Unit, so just "Rupees"
                    unitCountWrapper.classList.add('hidden');
                    perText.classList.add('hidden');
                    unitCountInput.removeAttribute('required');
                    unitCountInput.value = '';
                }

                // Show box configuration for Tablet and Capsule
                const selectedUnit = unitSelect.value;
                if (selectedUnit === 'Tablet' || selectedUnit === 'Capsule') {
                    boxConfigWrapper.classList.remove('hidden');
                    boxLabel.textContent = selectedUnit + 's';
                    boxUnitLabel.textContent = selectedUnit + 's';
                    unitsPerBoxInput.setAttribute('required', 'required');
                } else {
                    boxConfigWrapper.classList.add('hidden');
                    unitsPerBoxInput.removeAttribute('required');
                    unitsPerBoxInput.value = '';
                }
            }

            // Initial check
            toggleUnitCount();

            // On change
            unitSelect.addEventListener('change', toggleUnitCount);
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\inventory\medicines\create.blade.php ENDPATH**/ ?>