<?php $__env->startSection('title', 'Stock In'); ?>
<?php $__env->startSection('header_title', 'Receive New Stock'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-xl overflow-hidden text-slate-800 dark:text-white">
            <div class="p-8 border-b border-slate-100 dark:border-white/5">
                <h3 class="font-bold text-xl">Receive Inventory</h3>
                <p class="text-sm text-slate-500">Log new stock arrivals into the system.</p>
            </div>

            <form action="<?php echo e(route('inventory.store')); ?>" method="POST" class="p-8 space-y-6">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Select
                            Medicine</label>
                        <select name="medicine_id" id="medicine_select" required
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select a registered medicine...</option>
                            <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medicine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($medicine->id); ?>" data-unit="<?php echo e($medicine->unit); ?>"
                                    data-units-per-box="<?php echo e($medicine->units_per_box ?? 100); ?>">
                                    <?php echo e($medicine->name); ?> (<?php echo e($medicine->unit); ?>) - <?php echo e($medicine->dosage ?? 'N/A'); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <p class="mt-2 flex items-center text-[10px] font-bold text-slate-400 px-1">
                            <span class="mr-1">Not in list?</span>
                            <a href="<?php echo e(route('inventory.medicines.create')); ?>" class="text-accent underline">Register new
                                medicine first</a>
                        </p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Target
                            Warehouse</label>
                        <select name="warehouse_id" required
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select Warehouse...</option>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($wh->id); ?>"><?php echo e($wh->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Sponsor
                            (Optional)</label>
                        <select name="sponsor_id"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">No Sponsor / Purchased</option>
                            <?php $__currentLoopData = $sponsors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sponsor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($sponsor->id); ?>"><?php echo e($sponsor->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Batch
                            Number</label>
                        <input type="text" name="batch_number" required placeholder="BN-12345"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Expiry
                            Date</label>
                        <input type="date" name="expiry_date" required min="<?php echo e(date('Y-m-d', strtotime('+1 day'))); ?>"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2"
                            id="quantity_label">Quantity
                            (Units)</label>
                        <input type="number" id="quantity_input" required min="1" placeholder="0"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                        <input type="hidden" name="quantity" id="actual_quantity">
                        <p class="mt-1 text-[10px] text-slate-500 font-medium" id="quantity_hint"></p>
                    </div>

                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="<?php echo e(route('inventory.index')); ?>"
                        class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Cancel</a>
                    <button type="submit"
                        class="px-8 h-12 bg-accent text-white rounded-2xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                        Complete Stock In
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const medicineSelect = document.getElementById('medicine_select');
            const quantityLabel = document.getElementById('quantity_label');
            const quantityInput = document.getElementById('quantity_input');
            const actualQuantity = document.getElementById('actual_quantity');
            const quantityHint = document.getElementById('quantity_hint');

            function updateQuantityField() {
                const selectedOption = medicineSelect.options[medicineSelect.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit');
                const unitsPerBox = parseInt(selectedOption.getAttribute('data-units-per-box')) || 100;

                if (unit === 'Tablet' || unit === 'Capsule') {
                    // For tablets/capsules, input is in boxes
                    quantityLabel.textContent = 'Quantity (Boxes)';
                    quantityInput.placeholder = 'Number of boxes';
                    quantityHint.textContent = '* Each box contains ' + unitsPerBox + ' ' + unit.toLowerCase() + 's';
                } else {
                    // For other units, input is direct
                    quantityLabel.textContent = 'Quantity (Units)';
                    quantityInput.placeholder = '0';
                    quantityHint.textContent = '';
                }

                // Reset quantity when medicine changes
                quantityInput.value = '';
                actualQuantity.value = '';
            }

            function calculateActualQuantity() {
                const selectedOption = medicineSelect.options[medicineSelect.selectedIndex];
                const unit = selectedOption.getAttribute('data-unit');
                const unitsPerBox = parseInt(selectedOption.getAttribute('data-units-per-box')) || 100;
                const inputValue = parseInt(quantityInput.value) || 0;

                if (unit === 'Tablet' || unit === 'Capsule') {
                    // Convert boxes to individual units using the medicine's units_per_box
                    actualQuantity.value = inputValue * unitsPerBox;
                } else {
                    // Direct value for other units
                    actualQuantity.value = inputValue;
                }
            }

            // Update field when medicine is selected
            medicineSelect.addEventListener('change', updateQuantityField);

            // Calculate actual quantity when user enters boxes/units
            quantityInput.addEventListener('input', calculateActualQuantity);

            // Initial setup
            updateQuantityField();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\inventory\create.blade.php ENDPATH**/ ?>