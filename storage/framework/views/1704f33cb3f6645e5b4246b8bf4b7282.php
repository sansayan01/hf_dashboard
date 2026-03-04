<?php $__env->startSection('title', 'Camp Management'); ?>
<?php $__env->startSection('header_title', 'NGO Pharmacy | Health Camps'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white">Camp Locations</h3>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-tight">Manage active health camp sites</p>
            </div>
            <button onclick="openCreateModal()"
                class="px-6 h-12 bg-accent text-white rounded-2xl flex items-center space-x-2 font-bold text-sm shadow-xl shadow-accent/20 hover:opacity-90 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Add Camp Location</span>
            </button>
        </div>

        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-white/5">
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Camp Name</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Camp Parent (RM)
                            </th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Location/Area
                            </th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Stock Count</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        <?php $__empty_1 = true; $__currentLoopData = $camps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $camp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                <td class="p-4">
                                    <span class="font-bold text-sm"><?php echo e($camp->name); ?></span>
                                </td>
                                <td class="p-4">
                                    <?php if($camp->parent): ?>
                                        <div class="flex flex-col">
                                            <span
                                                class="font-bold text-xs text-slate-700 dark:text-slate-200"><?php echo e($camp->parent->profile->full_name ?? 'N/A'); ?></span>
                                            <span
                                                class="text-[9px] font-black text-slate-400 uppercase tracking-tight"><?php echo e($camp->parent->employee_id); ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400 italic">No Parent Assigned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-xs font-medium text-slate-500"><?php echo e($camp->location ?? 'N/A'); ?></td>
                                <td class="p-4">
                                    <?php if($camp->is_active): ?>
                                        <span
                                            class="px-2 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black rounded-lg uppercase tracking-tight">Active</span>
                                    <?php else: ?>
                                        <span
                                            class="px-2 py-1 bg-slate-100 text-slate-400 text-[10px] font-black rounded-lg uppercase tracking-tight">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <span class="text-xs font-bold"><?php echo e($camp->stocks()->sum('quantity')); ?> Units</span>
                                </td>
                                <td class="p-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <button
                                            onclick="openEditModal(<?php echo e($camp->id); ?>, '<?php echo e(addslashes($camp->name)); ?>', '<?php echo e(addslashes($camp->location ?? '')); ?>', <?php echo e($camp->is_active); ?>, <?php echo e($camp->parent_id ?? 'null'); ?>)"
                                            class="p-2 text-slate-400 hover:text-accent transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <form action="<?php echo e(route('inventory.camps.destroy', $camp->id)); ?>" method="POST"
                                            class="inline" onsubmit="return confirm('Are you sure?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="p-2 text-slate-400 hover:text-danger transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="p-20 text-center text-slate-500 font-bold">No health camps registered.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="camp-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" aria-hidden="true"
                onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="camp-form" method="POST">
                    <?php echo csrf_field(); ?>
                    <div id="method-field"></div>
                    <div class="bg-white dark:bg-slate-800 px-8 pt-8 pb-4">
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6" id="modal-title">Add New Camp
                            Site
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Camp
                                    Name</label>
                                <input type="text" name="name" id="name" required
                                    class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Camp
                                    Parent (RM)</label>
                                <div class="relative">
                                    <?php if(auth()->user()->isSuperAdmin()): ?>
                                        <div class="mb-2">
                                            <input type="text" id="rm-search" placeholder="Search RM by name or ID..."
                                                class="w-full h-10 px-4 rounded-xl border border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition"
                                                onkeyup="filterRMs()">
                                        </div>
                                    <?php endif; ?>
                                    <select name="parent_id" id="parent_id" required
                                        class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                                        <option value="">Select Camp Parent (RM)</option>
                                        <?php $__currentLoopData = $rms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($rm->id); ?>"
                                                data-search="<?php echo e(strtolower($rm->profile->full_name ?? '')); ?> <?php echo e(strtolower($rm->employee_id)); ?>">
                                                <?php echo e($rm->profile->full_name ?? 'N/A'); ?> (<?php echo e($rm->employee_id); ?>)
                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Location/Area</label>
                                <input type="text" name="location" id="location"
                                    class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            </div>
                            <div id="status-container" class="hidden">
                                <label class="flex items-center space-x-2 cursor-pointer mt-2">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                        class="w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent">
                                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Mark as Active</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-6 flex flex-row-reverse space-x-2 space-x-reverse">
                        <button type="submit"
                            class="inline-flex justify-center px-6 py-2.5 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                            Save Site
                        </button>
                        <button type="button" onclick="closeModal()"
                            class="inline-flex justify-center px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        function filterRMs() {
            const input = document.getElementById('rm-search');
            if (!input) return;
            const filter = input.value.toLowerCase();
            const select = document.getElementById('parent_id');
            const options = select.getElementsByTagName('option');

            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                if (option.value === "") continue;

                const searchText = option.getAttribute('data-search') || "";
                if (searchText.indexOf(filter) > -1) {
                    option.style.display = "";
                } else {
                    option.style.display = "none";
                }
            }
        }

        function openCreateModal() {
            const modal = document.getElementById('camp-modal');
            const form = document.getElementById('camp-form');
            const methodField = document.getElementById('method-field');
            const statusContainer = document.getElementById('status-container');
            const title = document.getElementById('modal-title');

            form.action = "<?php echo e(route('inventory.camps.store')); ?>";
            methodField.innerHTML = '';
            document.getElementById('name').value = '';
            document.getElementById('location').value = '';
            document.getElementById('parent_id').value = '';
            if (document.getElementById('rm-search')) document.getElementById('rm-search').value = '';
            filterRMs();

            statusContainer.classList.add('hidden');
            title.innerText = 'Add New Camp Site';

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function openEditModal(id, name, location, isActive, parentId) {
            const modal = document.getElementById('camp-modal');
            const form = document.getElementById('camp-form');
            const methodField = document.getElementById('method-field');
            const statusContainer = document.getElementById('status-container');
            const title = document.getElementById('modal-title');

            form.action = `<?php echo e(url('inventory/camps')); ?>/${id}`;
            methodField.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('name').value = name;
            document.getElementById('location').value = location;
            document.getElementById('is_active').checked = isActive === 1;
            document.getElementById('parent_id').value = parentId || '';
            if (document.getElementById('rm-search')) document.getElementById('rm-search').value = '';
            filterRMs();

            statusContainer.classList.remove('hidden');
            title.innerText = 'Edit Camp Site';

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            const modal = document.getElementById('camp-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\inventory\camps\index.blade.php ENDPATH**/ ?>