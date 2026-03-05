<?php $__env->startSection('title', 'Pharmacist & Office In-Charge'); ?>
<?php $__env->startSection('header_title', 'Pharmacist Management'); ?>

<?php $__env->startSection('content'); ?>
    <?php
        $canBulkApprove = auth()->user()->isSuperAdmin();
    ?>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h3 class="font-bold text-lg text-slate-800">Pharmacist & Office In-Charge</h3>
                    <span
                        class="px-2 py-0.5 bg-accent/10 text-accent text-[10px] font-black rounded-full border border-accent/20">
                        <?php echo e($users->total()); ?> Total
                    </span>
                </div>
                <p class="text-sm text-slate-500">Manage your pharmacists and in-charges.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="<?php echo e(route('users.export', array_merge(request()->all(), ['type' => 'staff']))); ?>"
                    class="px-2 sm:px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-all flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="hidden lg:inline uppercase tracking-widest">Download CSV</span>
                </a>
                <button type="button" onclick="toggleFilters()"
                    class="px-2 sm:px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    <span class="hidden lg:inline uppercase tracking-widest">Filter</span>
                </button>

                <?php if(auth()->user()->isSuperAdmin()): ?>
                    <button type="submit" form="bulk-actions-form" id="bulk-approve-header-btn"
                        class="bulk-approve-btn hidden px-2 sm:px-4 py-2 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20 hover:bg-emerald-600 transition-all flex items-center space-x-2 border border-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="hidden lg:inline">APPROVE SELECTED</span>
                    </button>

                    <button type="submit" form="bulk-actions-form" formaction="<?php echo e(route('users.bulk-print-selection')); ?>"
                        formtarget="_blank" style="background-color: #e11d48; color: white; border-color: #be185d;"
                        class="px-2 sm:px-4 py-2 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 hover:opacity-90 transition-all flex items-center space-x-2 border">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM12 17h.01M9 16h6" />
                        </svg>
                        <span class="hidden lg:inline">PRINT SELECTED</span>
                    </button>
                <?php endif; ?>

                <?php if(auth()->user()->canCreateUsers()): ?>
                    <a href="<?php echo e(route('users.create', ['type' => 'staff'])); ?>"
                        class="px-4 py-2 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                        + Add Member
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div id="filter-panel"
            class="<?php echo e(request()->anyFilled(['search']) ? '' : 'hidden'); ?> p-6 border-b border-slate-100 bg-slate-50/50 dark:bg-darkbg/20 transition-all">
            <form id="filterForm" action="<?php echo e(route('users.staffIndex')); ?>" method="GET" class="no-loader space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search
                            Member</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name, ID or Phone..."
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <div class="lg:col-span-3 flex items-end space-x-2">
                        <button type="submit"
                            class="h-10 px-6 bg-accent text-white rounded-xl text-sm font-bold hover:opacity-90 transition">Apply
                            Filters</button>
                        <a href="<?php echo e(route('users.staffIndex')); ?>"
                            class="h-10 px-6 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold flex items-center justify-center hover:opacity-90 transition">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <form id="bulk-actions-form" action="<?php echo e(route('users.bulk-approve')); ?>" method="POST">
            <?php echo csrf_field(); ?>
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <?php if($canBulkApprove): ?>
                            <th class="px-6 py-4 w-10 text-center">
                                <input type="checkbox" id="user-select-all" form="bulk-actions-form"
                                    class="w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent" title="Select All">
                            </th>
                        <?php endif; ?>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Member Detail
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Designation
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Joined On
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-slate-50">
                    <?php echo $__env->make('users.partials.staff_table', ['users' => $users], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </tbody>
            </table>
        </div>

        <!-- Bulk Action Bar -->
        <div id="bulk-action-bar"
            class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-50 animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-4 flex items-center space-x-6">
                <div class="px-4 border-r border-slate-700">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Selected</p>
                    <p class="text-white font-black text-lg" id="selected-count">0</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="submit" form="bulk-actions-form"
                        class="bulk-approve-btn px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Approve Selected</span>
                    </button>
                    <?php if(auth()->user()->isSuperAdmin()): ?>
                        <button type="submit" form="bulk-actions-form" formaction="<?php echo e(route('users.bulk-print-selection')); ?>"
                            formtarget="_blank" style="background-color: #e11d48; color: white; border-color: #be185d;"
                            class="px-2 sm:px-6 py-2.5 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-rose-600/20 hover:opacity-90 flex items-center space-x-2 border">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM12 17h.01M9 16h6" />
                            </svg>
                            <span class="hidden lg:inline">PRINT SELECTED</span>
                        </button>
                    <?php endif; ?>
                    <button type="button" onclick="cancelSelection()"
                        class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-black uppercase tracking-widest rounded-xl transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <div id="paginationContainer">
            <?php if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages()): ?>
                <div class="p-6 border-t border-slate-100 italic">
                    <?php echo e($users->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        function toggleFilters() {
            const panel = document.getElementById('filter-panel');
            panel.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Bulk Selection Logic
            const selectAll = document.getElementById('user-select-all');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const bulkBar = document.getElementById('bulk-action-bar');
            const selectedCount = document.getElementById('selected-count');

            function updateBulkBar() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                const checkedCount = checkedBoxes.length;
                if (selectedCount) selectedCount.textContent = checkedCount;

                if (checkedCount > 0) {
                    if (bulkBar) bulkBar.classList.remove('hidden');
                    let hasActive = false;
                    checkedBoxes.forEach(cb => {
                        if (cb.getAttribute('data-status') === 'active') hasActive = true;
                    });
                    const approveButtons = document.querySelectorAll('.bulk-approve-btn');
                    approveButtons.forEach(btn => {
                        if (hasActive) btn.classList.add('hidden');
                        else btn.classList.remove('hidden');
                    });
                } else {
                    if (bulkBar) bulkBar.classList.add('hidden');
                    const headerBtn = document.getElementById('bulk-approve-header-btn');
                    if (headerBtn) headerBtn.classList.add('hidden');
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateBulkBar();
                });
            }
            checkboxes.forEach(cb => cb.addEventListener('change', updateBulkBar));
            window.cancelSelection = function () {
                checkboxes.forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
                updateBulkBar();
            }
        });
    </script>
    <script src="<?php echo e(asset('js/live-filter.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new LiveFilter({
                formId: 'filterForm',
                tableBodyId: 'tableBody',
                paginationId: 'paginationContainer',
                onAfterUpdate: function () {
                    // Re-bind bulk selection after AJAX update
                    const checkboxes = document.querySelectorAll('.user-checkbox');
                    checkboxes.forEach(cb => cb.addEventListener('change', function () {
                        const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                        const selectedCount = document.getElementById('selected-count');
                        const bulkBar = document.getElementById('bulk-action-bar');
                        if (selectedCount) selectedCount.textContent = checkedBoxes.length;
                        if (checkedBoxes.length > 0) {
                            if (bulkBar) bulkBar.classList.remove('hidden');
                        } else {
                            if (bulkBar) bulkBar.classList.add('hidden');
                        }
                    }));
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/users/staff_index.blade.php ENDPATH**/ ?>