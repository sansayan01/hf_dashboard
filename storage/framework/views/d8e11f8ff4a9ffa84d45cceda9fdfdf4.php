<?php $__env->startSection('title', 'Dispense Medicine'); ?>
<?php $__env->startSection('header_title', 'NGO Pharmacy | Dispensing'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-xl overflow-hidden text-slate-800 dark:text-white">
            <div
                class="p-8 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="font-bold text-xl">Dispense Medicine</h3>
                    <p class="text-sm text-slate-500">Link medicine distribution to patient records.</p>
                </div>
                <?php if($patient): ?>
                    <div
                        class="px-4 py-2 bg-emerald-500/10 text-emerald-500 rounded-xl border border-emerald-500/10 text-xs font-black uppercase tracking-widest">
                        Selected Patient: <?php echo e($patient->full_name); ?>

                    </div>
                <?php endif; ?>
            </div>

            <form action="<?php echo e(route('inventory.process-dispense')); ?>" method="POST" class="p-8 space-y-8">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Patient Selection -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Patient Selection -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Target
                                Patient (Survey Record)</label>
                            <div class="relative group">
                                <select name="patient_id" required
                                    class="w-full h-14 px-6 rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 text-sm font-bold focus:border-accent focus:ring-0 outline-none transition appearance-none">
                                    <option value="">Search & Select Patient...</option>
                                    <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($p->id); ?>" <?php echo e(($patient && $patient->id == $p->id) ? 'selected' : ''); ?>>
                                            <?php echo e($p->full_name); ?> (#<?php echo e($p->id); ?>) - <?php echo e($p->phone_number); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Warehouse Selection -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">From
                                Warehouse</label>
                            <div class="relative group">
                                <select name="warehouse_id" required
                                    class="w-full h-14 px-6 rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50 text-sm font-bold focus:border-accent focus:ring-0 outline-none transition appearance-none">
                                    <option value="">Select Warehouse...</option>
                                    <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($wh->id); ?>"><?php echo e($wh->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <div class="absolute right-6 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Medicine Selection area -->
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between mb-4">
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400">Medicines
                                    to Dispense</label>
                                <button type="button" onclick="addItem()"
                                    class="text-[10px] font-black uppercase text-accent hover:underline">+ Add More
                                    Items</button>
                            </div>

                            <div id="items-container" class="space-y-4">
                                <div
                                    class="dispense-item grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50/50 dark:bg-white/5 p-6 rounded-2xl border border-slate-100 dark:border-white/5">
                                    <div class="md:col-span-7">
                                        <label
                                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Select
                                            Medicine</label>
                                        <select name="items[0][medicine_id]" required onchange="setDefaultQty(this)"
                                            class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                                            <option value="">Choose medicine...</option>
                                            <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($med->id); ?>" data-unit="<?php echo e(strtolower($med->unit)); ?>">
                                                    <?php echo e($med->name); ?> (<?php echo e($med->totalStock); ?> <?php echo e($med->unit); ?>s available)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="md:col-span-3">
                                        <label
                                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Qty</label>
                                        <input type="number" name="items[0][quantity]" required min="1" placeholder="0"
                                            class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                                    </div>
                                    <div class="md:col-span-2 text-right">
                                        <button type="button" onclick="removeItem(this)"
                                            class="p-3 text-slate-300 hover:text-red-500 transition opacity-0 pointer-events-none">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Dispensing
                                Notes / Prescription Ref</label>
                            <textarea name="notes" rows="3" placeholder="Administered following doctor's advice..."
                                class="w-full px-5 py-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition"></textarea>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-4 pt-4 border-t border-slate-100 dark:border-white/5">
                        <a href="<?php echo e(route('inventory.index')); ?>"
                            class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Cancel</a>
                        <button type="submit"
                            class="px-10 h-14 bg-emerald-500 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-emerald-500/20 hover:opacity-90 transition">
                            Confirm & Dispense
                        </button>
                    </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        let itemIndex = 1;

        function addItem() {
            const container = document.getElementById('items-container');
            const newItem = document.createElement('div');
            newItem.className = 'dispense-item grid grid-cols-1 md:grid-cols-12 gap-4 items-end bg-slate-50/50 dark:bg-white/5 p-6 rounded-2xl border border-slate-100 dark:border-white/5 animate-fadeIn';
            newItem.innerHTML = `
                        <div class="md:col-span-7">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Select Medicine</label>
                            <select name="items[${itemIndex}][medicine_id]" required onchange="setDefaultQty(this)"
                                class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition focus:border-accent">
                                <option value="">Choose medicine...</option>
                                <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($med->id); ?>" data-unit="<?php echo e(strtolower($med->unit)); ?>">
                                        <?php echo e($med->name); ?> (<?php echo e($med->totalStock); ?> <?php echo e($med->unit); ?>s available)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Qty</label>
                            <input type="number" name="items[${itemIndex}][quantity]" required min="1" placeholder="0"
                                class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition focus:border-accent">
                        </div>
                        <div class="md:col-span-2 text-right">
                            <button type="button" onclick="removeItem(this)" class="p-3 text-slate-400 hover:text-red-500 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    `;
            container.appendChild(newItem);
            itemIndex++;
        }

        function removeItem(button) {
            button.closest('.dispense-item').remove();
        }

        function setDefaultQty(selectEl) {
            const selected = selectEl.options[selectEl.selectedIndex];
            const unit = (selected.getAttribute('data-unit') || '').toLowerCase();
            const qtyInput = selectEl.closest('.dispense-item').querySelector('input[type="number"]');
            if (qtyInput && (unit === 'tablet' || unit === 'capsule')) {
                qtyInput.value = 10;
            } else if (qtyInput) {
                qtyInput.value = '';
            }
        }
    </script>
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/inventory/dispense.blade.php ENDPATH**/ ?>