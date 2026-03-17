<?php
    $activeCount = collect(['search', 'category', 'payment_method', 'date_from', 'date_to', 'date_preset', 'amount_min', 'amount_max'])
        ->filter(fn($k) => request()->filled($k))->count();
    $presets = [
        '' => 'All Time',
        'today' => 'Today',
        '7d' => 'Last 7 Days',
        '30d' => 'Last 30 Days',
        '90d' => 'Last 90 Days',
        'this_month' => 'This Month',
        'last_month' => 'Last Month',
        'this_year' => 'This Year',
    ];
?>

<?php if($activeCount > 0): ?>
    <div class="px-5 py-3 border-t border-slate-100 dark:border-white/5 flex flex-wrap items-center gap-2"
        id="activeFiltersInner">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mr-1">Active:</span>
        <?php if(request()->filled('search')): ?>
            <a href="javascript:void(0)" onclick="removeFilter('search')" class="active-filter-tag">
                Search: "<?php echo e(Str::limit(request('search'), 15)); ?>" ×
            </a>
        <?php endif; ?>
        <?php if(request()->filled('category')): ?>
            <a href="javascript:void(0)" onclick="removeFilter('category')" class="active-filter-tag">
                <?php echo e(request('category')); ?> ×
            </a>
        <?php endif; ?>
        <?php if(request()->filled('payment_method')): ?>
            <a href="javascript:void(0)" onclick="removeFilter('payment_method')" class="active-filter-tag">
                <?php echo e(\App\Models\Expense::PAYMENT_METHODS[request('payment_method')] ?? request('payment_method')); ?> ×
            </a>
        <?php endif; ?>
        <?php if(request()->filled('expense_by')): ?>
            <a href="javascript:void(0)" onclick="removeFilter('expense_by')" class="active-filter-tag">
                By: <?php echo e(request('expense_by')); ?> ×
            </a>
        <?php endif; ?>
        <?php if(request()->filled('date_preset')): ?>
            <a href="javascript:void(0)" onclick="removeFilter('date_preset')" class="active-filter-tag">
                <?php echo e($presets[request('date_preset')] ?? request('date_preset')); ?> ×
            </a>
        <?php endif; ?>
        <?php if(request()->filled('amount_min')): ?>
            <a href="javascript:void(0)" onclick="removeFilter('amount_min')" class="active-filter-tag">
                Min: ₹<?php echo e(request('amount_min')); ?> ×
            </a>
        <?php endif; ?>
        <?php if(request()->filled('amount_max')): ?>
            <a href="javascript:void(0)" onclick="removeFilter('amount_max')" class="active-filter-tag">
                Max: ₹<?php echo e(request('amount_max')); ?> ×
            </a>
        <?php endif; ?>
        <span class="ml-auto text-[10px] font-bold text-slate-400" id="resultCountText"><?php echo e($expenses->total()); ?>

            results</span>
    </div>
<?php endif; ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\expenses\partials\active_filters.blade.php ENDPATH**/ ?>