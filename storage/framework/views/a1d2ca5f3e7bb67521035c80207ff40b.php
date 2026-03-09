<?php $__env->startSection('title', 'Warehouses'); ?>
<?php $__env->startSection('header_title', 'Warehouse Management'); ?>

<?php $__env->startSection('content'); ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Warehouse List -->
        <div class="md:col-span-2">
            <div
                class="bg-white dark:bg-darkbg/40 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white">
                <div class="p-6 border-b border-slate-100 dark:border-white/5">
                    <h3 class="font-bold text-lg">Warehouses</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Manage physical storage locations for medicine stock.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-white/5">
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Name</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Location</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            <?php $__empty_1 = true; $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                    <td class="p-4">
                                        <span class="font-bold text-sm"><?php echo e($warehouse->name); ?></span>
                                    </td>
                                    <td class="p-4">
                                        <span
                                            class="text-xs text-slate-500"><?php echo e($warehouse->location ?? 'N/A'); ?></span>
                                    </td>
                                    <td class="p-4">
                                        <?php if($warehouse->is_active): ?>
                                            <span class="px-2 py-0.5 bg-success/10 text-success text-[10px] font-black rounded-full border border-success/20">Active</span>
                                        <?php else: ?>
                                            <span class="px-2 py-0.5 bg-slate-100 text-slate-500 text-[10px] font-black rounded-full border border-slate-200">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <button
                                                onclick="editWarehouse(<?php echo e($warehouse->id); ?>, '<?php echo e($warehouse->name); ?>', '<?php echo e($warehouse->location); ?>', <?php echo e($warehouse->is_active ? 1 : 0); ?>)"
                                                class="p-2 text-slate-400 hover:text-accent transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                </svg>
                                            </button>
                                            <?php if($warehouse->name !== 'Main Warehouse'): ?>
                                            <form action="<?php echo e(route('inventory.warehouses.destroy', $warehouse->id)); ?>"
                                                method="POST" class="inline" onsubmit="return confirm('Delete this warehouse?')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="p-2 text-slate-400 hover:text-danger transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="p-10 text-center text-slate-500">No warehouses found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Create/Edit Form -->
        <div class="md:col-span-1">
            <div
                class="bg-white dark:bg-darkbg/40 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm p-6 text-slate-800 dark:text-white sticky top-24">
                <h3 id="form-title" class="font-bold text-lg mb-4">Add Warehouse</h3>
                <form id="warehouse-form" action="<?php echo e(route('inventory.warehouses.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div id="method-container"></div>

                    <div class="space-y-4">
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Warehouse Name</label>
                            <input type="text" name="name" id="wh-name" required
                                class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                        </div>

                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Location</label>
                            <input type="text" name="location" id="wh-location"
                                class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                        </div>

                        <div id="status-container" class="hidden text-slate-800 dark:text-white">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="is_active" id="wh-active" value="1" class="w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent/20 transition">
                                <span class="text-sm font-medium">Active Status</span>
                            </label>
                        </div>

                        <div class="flex items-center space-x-2 pt-2">
                            <button type="submit"
                                class="flex-1 h-11 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                                Save Warehouse
                            </button>
                            <button type="button" onclick="resetForm()" id="cancel-btn"
                                class="hidden px-4 h-11 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-200 transition">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        function editWarehouse(id, name, location, isActive) {
            const form = document.getElementById('warehouse-form');
            const title = document.getElementById('form-title');
            const cancelBtn = document.getElementById('cancel-btn');
            const methodContainer = document.getElementById('method-container');
            const statusContainer = document.getElementById('status-container');

            form.action = `/inventory/warehouses/${id}`;
            title.innerText = 'Edit Warehouse';
            document.getElementById('wh-name').value = name;
            document.getElementById('wh-location').value = location === 'N/A' ? '' : location;
            document.getElementById('wh-active').checked = isActive === 1;
            cancelBtn.classList.remove('hidden');
            statusContainer.classList.remove('hidden');

            methodContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
        }

        function resetForm() {
            const form = document.getElementById('warehouse-form');
            const title = document.getElementById('form-title');
            const cancelBtn = document.getElementById('cancel-btn');
            const methodContainer = document.getElementById('method-container');
            const statusContainer = document.getElementById('status-container');

            form.action = "<?php echo e(route('inventory.warehouses.store')); ?>";
            title.innerText = 'Add Warehouse';
            form.reset();
            cancelBtn.classList.add('hidden');
            statusContainer.classList.add('hidden');
            methodContainer.innerHTML = '';
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/inventory/warehouses/index.blade.php ENDPATH**/ ?>