<?php $__env->startSection('title', 'Inventory Logs'); ?>
<?php $__env->startSection('header_title', 'Transaction History'); ?>

<?php $__env->startSection('content'); ?>
    <div class="space-y-6">
        <!-- Tab Navigation -->
        <div class="flex items-center space-x-2 bg-slate-100 dark:bg-white/5 p-1 rounded-2xl w-fit">
            <?php if (! (auth()->user()->designation === 'staff')): ?>
                <a href="<?php echo e(route('inventory.transactions', ['view' => 'movements'])); ?>"
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all <?php echo e($view === 'movements' ? 'bg-white dark:bg-slate-800 text-accent shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'); ?>">
                    Inventory Movements
                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('inventory.transactions', ['view' => 'dispenses'])); ?>"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all <?php echo e($view === 'dispenses' ? 'bg-white dark:bg-slate-800 text-accent shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'); ?>">
                Patient Dispenses
            </a>
            <a href="<?php echo e(route('inventory.transactions', ['view' => 'sponsors'])); ?>"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all <?php echo e($view === 'sponsors' ? 'bg-white dark:bg-slate-800 text-accent shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'); ?>">
                Sponsor Estimation
            </a>
        </div>

        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white">
            <div class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg">
                        <?php if($view === 'dispenses'): ?>
                            Medicine Dispense History
                        <?php elseif($view === 'sponsors'): ?>
                            Sponsor-wise Dispense Analysis
                        <?php else: ?>
                            Internal Stock Movements
                        <?php endif; ?>
                    </h3>
                    <p class="text-sm text-slate-500">
                        <?php if($view === 'dispenses'): ?>
                            Records of all medicines given to patients.
                        <?php elseif($view === 'sponsors'): ?>
                            Analysis of medicines dispensed under various sponsors.
                        <?php else: ?>
                            Log of stock arrivals, transfers, and adjustments.
                        <?php endif; ?>
                    </p>
                    <?php if($view === 'dispenses' || $view === 'sponsors'): ?>
                        <div
                            class="mt-2 inline-flex items-center space-x-2 px-3 py-1 bg-accent/5 border border-accent/10 rounded-full">
                            <span class="text-[10px] font-black uppercase tracking-widest text-accent/60">Collection
                                Total:</span>
                            <span class="text-xs font-black text-accent">₹<?php echo e(number_format($totalGrandSum, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="flex items-center space-x-3">
                    <?php if(request('view_all')): ?>
                        <a href="<?php echo e(route('inventory.transactions', request()->except('view_all'))); ?>"
                            class="p-2.5 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 transition flex items-center justify-center"
                            title="Paginate Results">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </a>
                    <?php else: ?>
                        <a href="<?php echo e(route('inventory.transactions', array_merge(request()->all(), ['view_all' => 1]))); ?>"
                            class="p-2.5 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 transition flex items-center justify-center"
                            title="View All Records">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </a>
                    <?php endif; ?>

                    <a href="<?php echo e(route('inventory.transactions.export', request()->all())); ?>"
                        class="p-2.5 bg-emerald-500 text-white rounded-xl hover:opacity-90 transition flex items-center justify-center shadow-lg shadow-emerald-500/20"
                        title="Export CSV">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </a>
                    <button onclick="toggleFilters()"
                        class="p-2.5 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 transition flex items-center justify-center"
                        title="Filter Transactions">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                    <a href="<?php echo e(route('inventory.index')); ?>"
                        class="p-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 transition flex items-center justify-center"
                        title="Back to Overview">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Filter Bar -->
            <div id="filterSection"
                class="<?php echo e(request()->anyFilled(['search', 'date_from', 'date_to', 'payment_method']) ? '' : 'hidden'); ?> bg-slate-50/50 dark:bg-white/5 p-6 border-b border-slate-100 dark:border-white/5">
                <form action="<?php echo e(route('inventory.transactions')); ?>" method="GET"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="view" value="<?php echo e($view); ?>">

                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                            placeholder="Patient, Medicine or Camp..."
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <?php if($view === 'sponsors'): ?>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Sponsor</label>
                            <select name="sponsor_id"
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                                <option value="">All Sponsors</option>
                                <?php $__currentLoopData = $sponsors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sponsor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($sponsor->id); ?>" <?php echo e(request('sponsor_id') == $sponsor->id ? 'selected' : ''); ?>>
                                        <?php echo e($sponsor->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    <?php elseif($view === 'dispenses'): ?>
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Payment Method</label>
                            <select name="payment_method"
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                                <option value="">All Methods</option>
                                <option value="upi" <?php echo e(request('payment_method') == 'upi' ? 'selected' : ''); ?>>UPI</option>
                                <option value="cash" <?php echo e(request('payment_method') == 'cash' ? 'selected' : ''); ?>>Cash</option>
                                <option value="due" <?php echo e(request('payment_method') == 'due' ? 'selected' : ''); ?>>Due / Unpaid</option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">From
                                Date</label>
                            <input type="date" name="date_from" value="<?php echo e(request('date_from')); ?>"
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">To
                                Date</label>
                            <input type="date" name="date_to" value="<?php echo e(request('date_to')); ?>"
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                        </div>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="flex-1 h-10 bg-accent text-white rounded-xl text-xs font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                            Apply Filters
                        </button>
                        <a href="<?php echo e(route('inventory.transactions', ['view' => $view])); ?>"
                            class="h-10 px-4 flex items-center justify-center bg-white dark:bg-slate-800 text-slate-500 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-white/5">
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Date & Time</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                <?php if($view === 'sponsors'): ?>
                                    <span class="text-accent">Sponsor</span>
                                <?php else: ?>
                                    Entity Link
                                <?php endif; ?>
                            </th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine &
                                Location</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Qty</th>
                            <?php if($view === 'dispenses'): ?>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Grand Total</th>
                            <?php elseif($view === 'sponsors'): ?>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-accent">
                                    Medicine Value</th>
                            <?php else: ?>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Type</th>
                            <?php endif; ?>
                            <?php if (! ($view === 'sponsors')): ?>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Performed By
                                </th>
                            <?php endif; ?>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Location</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                <td class="p-4">
                                    <span
                                        class="text-xs font-medium text-slate-500"><?php echo e($transaction->created_at->format('M d, Y')); ?></span><br>
                                    <span
                                        class="text-[10px] font-bold text-slate-400 uppercase"><?php echo e($transaction->created_at->format('h:i A')); ?></span>
                                </td>
                                <td class="p-4">
                                    <?php if($view === 'sponsors'): ?>
                                        <?php
                                            /** @var \App\Models\InventoryTransaction $transaction */
                                            $sponsor = $transaction->sponsor;
                                            if (!$sponsor && $transaction->stock) {
                                                // Fallback to the 'in' transaction of this stock
                                                $inTransaction = $transaction->stock->transactions->where('type', 'in')->first();
                                                $sponsor = $inTransaction?->sponsor;
                                            }
                                        ?>
                                        <span
                                            class="text-xs font-black uppercase text-accent bg-accent/5 px-3 py-1.5 rounded-xl border border-accent/10 inline-block">
                                            <?php echo e($sponsor->name ?? 'N/A'); ?>

                                        </span>
                                    <?php elseif($transaction->patient): ?>
                                        <a href="<?php echo e(route('patients.show', $transaction->patient_id)); ?>"
                                            class="text-[10px] font-black uppercase text-accent hover:underline">
                                            Patient: <?php echo e(Str::limit($transaction->patient->full_name, 15)); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-[10px] font-bold text-slate-400 italic">System Log</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <?php if($view === 'dispenses'): ?>
                                            <?php /** @var \App\Models\MedicineDistribution $transaction */ ?>
                                            <div class="flex flex-wrap gap-2">
                                                <?php $__currentLoopData = $transaction->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="group relative flex items-center gap-1.5 bg-slate-50 dark:bg-white/5 pr-2 pl-1 py-1 rounded-lg border border-slate-100 dark:border-white/10">
                                                        <span class="font-black text-[11px] text-slate-700 dark:text-slate-200"><?php echo e($item->medicine->name); ?></span>
                                                        <span class="text-[9px] font-black text-accent bg-accent/10 px-1 rounded">x<?php echo e($item->quantity); ?></span>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php else: ?>
                                            <?php /** @var \App\Models\InventoryTransaction $transaction */ ?>
                                            <span
                                                class="font-black text-sm <?php echo e($view === 'sponsors' ? 'text-accent' : ''); ?>"><?php echo e($transaction->stock?->medicine?->name ?? 'Deleted Medicine'); ?></span>
                                            <div class="flex items-center flex-wrap gap-x-2 gap-y-1 text-slate-400 font-medium">
                                                <span class="text-[10px]">Batch:
                                                    #<?php echo e($transaction->stock?->batch_number ?? 'N/A'); ?></span>
                                                <span class="text-slate-200 dark:text-white/10 text-[10px]">•</span>
                                                <span
                                                    class="text-[10px] text-accent font-black uppercase tracking-widest"><?php echo e($transaction->warehouse?->name ?? 'Main Warehouse'); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <?php if($view === 'dispenses'): ?>
                                        <span class="text-xs font-bold text-slate-500"><?php echo e($transaction->items->count()); ?> Medicine(s)</span>
                                    <?php else: ?>
                                        <span
                                            class="text-sm font-black <?php echo e(in_array($transaction->type, ['in', 'adjustment', 'in']) ? 'text-emerald-500' : 'text-red-500'); ?>">
                                            <?php echo e(in_array($transaction->type, ['in', 'adjustment', 'in']) ? '+' : '-'); ?><?php echo e($transaction->quantity); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <?php if($view === 'dispenses'): ?>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-800 dark:text-white">
                                                ₹<?php echo e(number_format($transaction->final_amount, 2)); ?>

                                            </span>
                                            <a href="<?php echo e(route('medicine.invoice', $transaction->id)); ?>" target="_blank" class="text-[9px] font-black text-accent uppercase hover:underline">Invoice #<?php echo e($transaction->id); ?></a>
                                        </div>
                                    <?php elseif($view === 'sponsors'): ?>
                                        <?php
                                            /** @var \App\Models\InventoryTransaction $transaction */
                                            $distId = filter_var($transaction->notes, FILTER_SANITIZE_NUMBER_INT);
                                            $lineValue = 0;
                                            if ($distId) {
                                                $distItem = \App\Models\MedicineDistributionItem::where('distribution_id', $distId)
                                                    ->where('medicine_id', $transaction->stock?->medicine_id)
                                                    ->first();
                                                if ($distItem) {
                                                    $lineValue = $distItem->unit_price * $transaction->quantity;
                                                }
                                            }
                                        ?>
                                        <span class="text-sm font-black text-accent">
                                            ₹<?php echo e(number_format($lineValue, 2)); ?>

                                        </span>
                                    <?php else: ?>
                                        <?php
                                            /** @var \App\Models\InventoryTransaction $transaction */
                                            $colors = [
                                                'in' => 'bg-emerald-100 text-emerald-600',
                                                'out' => 'bg-red-100 text-red-600',
                                                'dispense' => 'bg-blue-100 text-blue-600',
                                                'adjustment' => 'bg-slate-100 text-slate-600',
                                                'expired' => 'bg-amber-100 text-amber-600',
                                                'damaged' => 'bg-red-100 text-red-600',
                                            ];
                                            $color = $colors[$transaction->type] ?? 'bg-slate-100 text-slate-600';
                                        ?>
                                        <span class="px-2 py-1 <?php echo e($color); ?> text-[10px] font-black rounded-lg uppercase tracking-tight">
                                            <?php echo e(ucfirst($transaction->type)); ?>

                                        </span>
                                    <?php endif; ?>
                                </td>
                                <?php if (! ($view === 'sponsors')): ?>
                                    <td class="p-4">
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">
                                            <?php if($view === 'dispenses'): ?>
                                                <?php echo e($transaction->pharmacist->profile->full_name ?? $transaction->pharmacist->employee_id); ?>

                                            <?php else: ?>
                                                <?php echo e($transaction->user->profile->full_name ?? $transaction->user->employee_id); ?>

                                            <?php endif; ?>
                                        </span>
                                    </td>
                                <?php endif; ?>
                                <td class="p-4">
                                    <span class="text-xs font-medium text-slate-500">
                                        <?php if($view === 'dispenses'): ?>
                                            <?php echo e($transaction->camp->name ?? 'N/A'); ?>

                                        <?php else: ?>
                                            <?php echo e($transaction->warehouse->name ?? 'Main Warehouse'); ?>

                                        <?php endif; ?>
                                    </span>
                                </td>
                                <td class="p-4 text-right">
                                        <div class="flex justify-end items-center space-x-1">
                                            <?php if($view === 'dispenses'): ?>
                                                <?php if($transaction->due_amount > 0): ?>
                                                    <button type="button" onclick="openPayDueModal('<?php echo e($transaction->id); ?>', '<?php echo e($transaction->final_amount); ?>', '<?php echo e($transaction->amount_paid); ?>', '<?php echo e($transaction->due_amount); ?>')"
                                                        class="p-2 text-slate-400 hover:text-emerald-500 transition" title="Pay Due">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                        </svg>
                                                    </button>
                                                <?php endif; ?>
                                                
                                                <a href="<?php echo e(route('medicine.invoice', $transaction->id)); ?>" target="_blank"
                                                    class="p-2 text-slate-400 hover:text-accent transition" title="Download Invoice">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                    </svg>
                                                </a>
                                                
                                                <?php if(!$isStaff): ?>
                                                    <a href="<?php echo e(route('medicine.distribution.edit', $transaction->id)); ?>"
                                                        class="p-2 text-slate-400 hover:text-accent transition" title="Edit Distribution">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <form action="<?php echo e(route('medicine.distribution.destroy', $transaction->id)); ?>"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this distribution? Stock will be reverted.')">
                                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition" title="Delete Distribution">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            <?php else: ?>
                                            <?php if(!$isStaff): ?>
                                                <button type="button" onclick="openEditModal('<?php echo e($transaction->id); ?>', '<?php echo e($transaction->quantity); ?>', '<?php echo e(addslashes($transaction->notes)); ?>')"
                                                    class="p-2 text-slate-400 hover:text-accent transition" title="Edit Transaction">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                            <?php endif; ?>
                                            <form action="<?php echo e(route('inventory.transactions.destroy', $transaction->id)); ?>"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this transaction? Stock will be reverted.')">
                                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="p-2 text-slate-400 hover:text-red-500 transition" title="Delete Transaction">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="8" class="p-10 text-center text-slate-500 font-medium">No transactions recorded yet.</td>
                                        </tr>
                                    <?php endif; ?>
            </tbody>
            </table>
        </div>

        <?php if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages()): ?>
            <div class="p-6 border-t border-slate-100 dark:border-white/5 italic">
                <?php echo e($transactions->links()); ?>

            </div>
        <?php endif; ?>
    </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" aria-hidden="true"
                onclick="closeEditModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="edit-form" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                    <div class="bg-white dark:bg-slate-800 px-8 pt-8 pb-4">
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6" id="modal-title">Edit
                            Transaction
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Action
                                    Quantity</label>
                                <input type="number" name="quantity" id="edit-quantity" required min="1"
                                    class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Notes
                                    / Reference</label>
                                <textarea name="notes" id="edit-notes" rows="3"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-6 flex flex-row-reverse space-x-2 space-x-reverse">
                        <button type="submit"
                            class="inline-flex justify-center px-6 py-2.5 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="inline-flex justify-center px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pay Due Modal -->
    <div id="pay-due-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" aria-hidden="true"
                onclick="closePayDueModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="pay-due-form" method="POST">
                    <?php echo csrf_field(); ?> 
                    <div class="bg-white dark:bg-slate-800 px-8 pt-8 pb-4">
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6" id="modal-title">Clear Due Payment</h3>

                        <div class="space-y-4">
                            <div class="grid grid-cols-3 gap-4 text-center mb-4">
                                <div class="bg-slate-50 dark:bg-white/5 p-3 rounded-xl">
                                    <span class="block text-[10px] uppercase text-slate-400 font-bold">Total</span>
                                    <span class="text-sm font-black text-slate-700 dark:text-slate-200" id="pd-total">₹0</span>
                                </div>
                                <div class="bg-emerald-50 dark:bg-emerald-500/10 p-3 rounded-xl">
                                    <span class="block text-[10px] uppercase text-emerald-600/70 font-bold">Paid</span>
                                    <span class="text-sm font-black text-emerald-600" id="pd-paid">₹0</span>
                                </div>
                                <div class="bg-red-50 dark:bg-red-500/10 p-3 rounded-xl">
                                    <span class="block text-[10px] uppercase text-red-600/70 font-bold">Due</span>
                                    <span class="text-sm font-black text-red-600" id="pd-due">₹0</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Amount to Pay</label>
                                <input type="number" name="amount" id="pd-amount" required step="0.01" min="0.01"
                                    class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition uppercase font-bold text-slate-700 dark:text-slate-200 placeholder-slate-400 dark:placeholder-slate-600">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Payment Method</label>
                                <select name="payment_method" required
                                    class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                                    <option value="cash">Cash</option>
                                    <option value="upi">UPI</option>
                                    <option value="card">Card</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Notes</label>
                                <textarea name="notes" rows="2"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-6 flex flex-row-reverse space-x-2 space-x-reverse">
                        <button type="submit"
                            class="inline-flex justify-center px-6 py-2.5 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                            Confirm Payment
                        </button>
                        <button type="button" onclick="closePayDueModal()"
                            class="inline-flex justify-center px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const section = document.getElementById('filterSection');
            section.classList.toggle('hidden');
        }

        function openEditModal(id, quantity, notes) {
            const modal = document.getElementById('edit-modal');
            const form = document.getElementById('edit-form');
            const quantityInput = document.getElementById('edit-quantity');
            const notesInput = document.getElementById('edit-notes');

            form.action = `/inventory/transactions/${id}`;
            quantityInput.value = quantity;
            notesInput.value = notes;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openPayDueModal(id, total, paid, due) {
            const modal = document.getElementById('pay-due-modal');
            const form = document.getElementById('pay-due-form');
            
            document.getElementById('pd-total').innerText = '₹' + parseFloat(total).toFixed(2);
            document.getElementById('pd-paid').innerText = '₹' + parseFloat(paid).toFixed(2);
            document.getElementById('pd-due').innerText = '₹' + parseFloat(due).toFixed(2);
            
            const amountInput = document.getElementById('pd-amount');
            amountInput.max = due;
            amountInput.value = due; // Default to full due amount

            // Ensure the route matches the one defined in web.php
            // Route: Route::post('/transactions/pay/{id}', ...) -> 'dispense.pay' inside 'inventory' prefix
            // URL structure: /inventory/transactions/pay/{id}
            form.action = `/inventory/transactions/pay/${id}`;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePayDueModal() {
            const modal = document.getElementById('pay-due-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\inventory\transactions.blade.php ENDPATH**/ ?>