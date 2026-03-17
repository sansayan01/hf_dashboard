<?php
    $activeFilters = [];
    if (request()->filled('search'))
        $activeFilters[] = ['label' => 'Search: ' . request('search'), 'key' => 'search'];
    if (request()->filled('category'))
        $activeFilters[] = ['label' => request('category'), 'key' => 'category'];
    if (request()->filled('payment_method'))
        $activeFilters[] = ['label' => \App\Models\Income::PAYMENT_METHODS[request('payment_method')] ?? request('payment_method'), 'key' => 'payment_method'];
    if (request()->filled('received_by'))
        $activeFilters[] = ['label' => 'By: ' . request('received_by'), 'key' => 'received_by'];
    if (request()->filled('source'))
        $activeFilters[] = ['label' => 'Source: ' . request('source'), 'key' => 'source'];
    if (request()->filled('date_preset')) {
        $presetLabels = ['today' => 'Today', '7d' => 'Last 7 Days', '30d' => 'Last 30 Days', '90d' => 'Last 90 Days', 'this_month' => 'This Month', 'last_month' => 'Last Month', 'this_year' => 'This Year'];
        $activeFilters[] = ['label' => $presetLabels[request('date_preset')] ?? request('date_preset'), 'key' => 'date_preset'];
    }
    if (request()->filled('date_from'))
        $activeFilters[] = ['label' => 'From: ' . request('date_from'), 'key' => 'date_from'];
    if (request()->filled('date_to'))
        $activeFilters[] = ['label' => 'To: ' . request('date_to'), 'key' => 'date_to'];
    if (request()->filled('amount_min'))
        $activeFilters[] = ['label' => 'Min: ₹' . request('amount_min'), 'key' => 'amount_min'];
    if (request()->filled('amount_max'))
        $activeFilters[] = ['label' => 'Max: ₹' . request('amount_max'), 'key' => 'amount_max'];
?>

<?php if(count($activeFilters) > 0): ?>
    <div class="flex flex-wrap items-center gap-2 px-5 pb-4" id="activeFilterTags">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mr-1">Active:</span>
        <?php $__currentLoopData = $activeFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(request()->fullUrlWithoutQuery($filter['key'])); ?>" class="active-filter-tag"
                data-filter-key="<?php echo e($filter['key']); ?>">
                <?php echo e($filter['label']); ?>

                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <a href="<?php echo e(route('incomes.index')); ?>" class="text-[10px] font-bold text-red-500 hover:text-red-600 ml-1">Clear
            All</a>
    </div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\incomes\partials\active_filters.blade.php ENDPATH**/ ?>