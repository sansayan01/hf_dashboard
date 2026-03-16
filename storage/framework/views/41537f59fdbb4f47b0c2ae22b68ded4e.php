<?php $__env->startSection('title', 'Stock Transfer'); ?>
<?php $__env->startSection('header_title', 'NGO Pharmacy | Move Stock'); ?>

<?php $__env->startSection('css'); ?>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <style>
        .ts-control {
            border-radius: 1rem !important;
            height: 3rem !important;
            padding: 0 1.25rem !important;
            display: flex !important;
            align-items: center !important;
            border: 1px solid #e2e8f0 !important;
            background-color: white !important;
            font-family: 'Outfit', sans-serif !important;
        }

        .dark .ts-control {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: white !important;
        }

        .ts-dropdown {
            border-radius: 1rem !important;
            margin-top: 5px !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
            font-family: 'Outfit', sans-serif !important;
            background-color: white !important;
        }

        .dark .ts-dropdown {
            background-color: #1e293b !important;
            border-color: #334155 !important;
            color: white !important;
        }

        .ts-dropdown .active {
            background-color: #3C50E0 !important;
            color: white !important;
        }

        .ts-dropdown .option.active * {
            color: white !important;
        }

        .ts-dropdown .optgroup-header {
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            font-size: 10px !important;
            color: #94a3b8 !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-2xl mx-auto">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-xl overflow-hidden text-slate-800 dark:text-white">
            <div class="p-8 border-b border-slate-100 dark:border-white/5">
                <h3 class="font-bold text-xl">Transfer Inventory</h3>
                <p class="text-sm text-slate-500">Move medicine stock between warehouse locations.</p>
            </div>

            <form action="<?php echo e(route('inventory.process-transfer')); ?>" method="POST" class="p-8 space-y-6">
                <?php echo csrf_field(); ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">From
                            Warehouse (Source)</label>
                        <select name="from_warehouse_id" id="from_warehouse_id" required onchange="handleSourceChange()"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select Source...</option>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($wh->id); ?>" data-type="<?php echo e($wh->type); ?>" <?php echo e(($preSelectedStock && $preSelectedStock->warehouse_id == $wh->id) ? 'selected' : ''); ?>>
                                    <?php echo e($wh->name); ?> (<?php echo e(ucfirst($wh->type)); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">To
                            Location (Destination)</label>
                        <select name="to_warehouse_id" id="to_warehouse_id" required
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select Destination...</option>
                            <?php $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($wh->id); ?>"><?php echo e($wh->name); ?> (<?php echo e(ucfirst($wh->type)); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <!-- Transfer All Option -->
                    <div class="md:col-span-2 hidden" id="transfer_all_wrapper">
                        <div
                            class="flex items-center space-x-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl">
                            <input type="checkbox" id="transfer_all" name="transfer_all" value="1"
                                onchange="toggleTransferMode()"
                                class="w-5 h-5 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                            <label for="transfer_all"
                                class="text-sm font-bold text-amber-900 dark:text-amber-200 cursor-pointer">
                                Transfer ALL stock from this location (saves time by moving everything at once)
                            </label>
                        </div>
                    </div>


                    <!-- Multi-Item Transfer (for warehouses) -->
                    <div class="md:col-span-2 hidden" id="multi_item_wrapper">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Items to
                            Transfer</label>

                        <div id="transfer_items_container" class="space-y-4 mb-4">
                            <!-- Item rows will be added here dynamically -->
                        </div>

                        <button type="button" id="add_item_btn" onclick="addTransferItem()"
                            class="w-full py-3 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Add More Items</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-4">
                    <a href="<?php echo e(route('inventory.index')); ?>"
                        class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Cancel</a>
                    <button type="submit"
                        class="px-8 h-12 bg-accent text-white rounded-2xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                        Confirm Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        let itemCounter = 0;
        const tomSelectInstances = {};
        const stockOptions = `
                        <option value="">Select matching stock...</option>
                        <?php $__currentLoopData = $medicines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $med): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <optgroup label="<?php echo e($med->name); ?> (<?php echo e($med->unit); ?>)">
                                <?php $__currentLoopData = $med->stocks->groupBy(fn($s) => $s->warehouse_id . '-' . $s->batch_number); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupKey => $batchStocks): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php 
                                                                                                                $first = $batchStocks->sortBy('expiry_date')->first();
                                        $totalQty = $batchStocks->sum('quantity');
                                    ?>
                                    <option value="<?php echo e($first->id); ?>" 
                                            data-warehouse="<?php echo e($first->warehouse_id); ?>" 
                                            data-quantity="<?php echo e($totalQty); ?>"
                                            data-unit="<?php echo e($med->unit); ?>"
                                            data-generic="<?php echo e($med->generic_name); ?>"
                                            data-units-per-box="<?php echo e($med->units_per_box ?? 100); ?>"
                                            class="stock-option">
                                        <?php echo e($med->name); ?> | <?php echo e($med->generic_name); ?> | Batch: #<?php echo e($first->batch_number); ?> | Exp: <?php echo e($first->expiry_date->format('M Y')); ?> | Qty: <?php echo e($totalQty); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </optgroup>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    `;

        document.addEventListener('DOMContentLoaded', function () {
            handleSourceChange();

            <?php if(isset($preSelectedRepresentativeId)): ?>
                // Wait for the first row to be created
                setTimeout(() => {
                    const firstSelect = document.querySelector('.stock-select');
                    if (firstSelect && tomSelectInstances[1]) {
                        tomSelectInstances[1].setValue('<?php echo e($preSelectedRepresentativeId); ?>');
                    }
                }, 300);
            <?php endif; ?>
                                                                                        });

        function handleSourceChange() {
            const fromWhSelect = document.getElementById('from_warehouse_id');
            const selectedOpt = fromWhSelect.options[fromWhSelect.selectedIndex];
            const transferAllWrapper = document.getElementById('transfer_all_wrapper');
            const transferAllCheckbox = document.getElementById('transfer_all');
            const multiItemWrapper = document.getElementById('multi_item_wrapper');

            if (!selectedOpt || !selectedOpt.value) {
                transferAllWrapper.classList.add('hidden');
                multiItemWrapper.classList.add('hidden');
                transferAllCheckbox.checked = false;
                return;
            }

            // Show Transfer All option for any selected source
            transferAllWrapper.classList.remove('hidden');

            // Update multi-item visibility based on transfer_all
            toggleTransferMode();

            // Initialize with one item row if empty
            if (document.getElementById('transfer_items_container').children.length === 0) {
                addTransferItem();
            }

            filterAllStockSelects();
        }

        function toggleTransferMode() {
            const transferAll = document.getElementById('transfer_all').checked;
            const multiItemWrapper = document.getElementById('multi_item_wrapper');

            if (transferAll) {
                multiItemWrapper.classList.add('hidden');
                // Disable all inputs in the wrapper so they aren't submitted and don't trigger validation
                multiItemWrapper.querySelectorAll('input, select').forEach(el => {
                    el.disabled = true;
                });
            } else {
                multiItemWrapper.classList.remove('hidden');
                // Re-enable inputs
                multiItemWrapper.querySelectorAll('input, select').forEach(el => {
                    el.disabled = false;
                });
            }
        }

        function recalculateAllAvailable() {
            const usage = {};
            // First pass: sum up usages across all items
            document.querySelectorAll('.transfer-item').forEach(row => {
                const id = row.id.split('_')[1];
                const ts = tomSelectInstances[id];
                if (!ts) return;
                const stockId = ts.getValue();
                const actualQtyInput = row.querySelector('.actual-quantity');
                const qty = parseInt(actualQtyInput ? actualQtyInput.value : 0) || 0;
                if (stockId) {
                    usage[stockId] = (usage[stockId] || 0) + qty;
                }
            });

            // Second pass: update each row's UI and TomSelect options
            Object.keys(tomSelectInstances).forEach(id => {
                const ts = tomSelectInstances[id];
                const row = document.getElementById(`item_${id}`);
                const selectedStockId = ts.getValue();
                const actualQtyInput = row.querySelector('.actual-quantity');
                const rowQty = parseInt(actualQtyInput.value) || 0;

                // Update the "Available" label for the currently selected item in this row
                if (selectedStockId) {
                    const data = ts.options[selectedStockId];
                    if (!data.originalQuantity) data.originalQuantity = data.quantity;

                    const otherRowsUsage = (usage[selectedStockId] || 0) - rowQty;
                    const availableNow = parseInt(data.originalQuantity) - otherRowsUsage;

                    row.querySelector('.max-qty-label').innerText = availableNow;
                    row.querySelector('.units-input').max = availableNow;
                    // Update the boxes-input max too if it's visible
                    const boxesInput = row.querySelector('.boxes-input');
                    if (boxesInput) {
                        const unitsPerBox = parseInt(data.unitsPerBox) || 100;
                        boxesInput.max = Math.floor(availableNow / unitsPerBox);
                    }
                }

                // Update text of all options in the dropdown to show current availability
                Object.values(ts.options).forEach(opt => {
                    if (!opt.value || opt.value === "") return;

                    const rawOrigQty = opt.originalQuantity !== undefined ? opt.originalQuantity : opt.quantity;
                    const origQty = parseInt(rawOrigQty) || 0;

                    if (opt.originalQuantity === undefined) opt.originalQuantity = origQty;
                    if (opt.originalText === undefined) opt.originalText = opt.text;

                    const storedUsage = parseInt(usage[opt.value]) || 0;
                    const thisRowUsage = (selectedStockId === opt.value ? rowQty : 0);
                    const otherRowsUsage = storedUsage - thisRowUsage;
                    const currentAvailable = Math.max(0, origQty - otherRowsUsage);

                    // Update the display text to show real-time quantity
                    let newText = opt.originalText || "";
                    if (newText.includes(' | Qty:')) {
                        newText = newText.substring(0, newText.lastIndexOf(' | Qty:')) + ` | Qty: ${currentAvailable}`;
                    } else if (newText.split(' | ').length > 1) {
                        const parts = newText.split(' | ');
                        parts[parts.length - 1] = `Qty: ${currentAvailable}`;
                        newText = parts.join(' | ');
                    }

                    if (opt.text !== newText) {
                        ts.updateOption(opt.value, { ...opt, text: newText, quantity: currentAvailable });
                    }
                });
            });
        }

        function addTransferItem() {
            itemCounter++;
            const container = document.getElementById('transfer_items_container');
            const fromWhId = document.getElementById('from_warehouse_id').value;

            const itemRow = document.createElement('div');
            itemRow.className = 'transfer-item relative p-6 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700 transition-all hover:border-accent/30';
            itemRow.id = `item_${itemCounter}`;
            itemRow.innerHTML = `
                                                <button type="button" onclick="removeTransferItem(${itemCounter})" 
                                                    class="absolute -top-3 -right-3 w-8 h-8 flex items-center justify-center bg-white dark:bg-slate-700 text-red-500 hover:bg-red-500 hover:text-white rounded-full shadow-lg border border-slate-100 dark:border-slate-600 transition-all z-10"
                                                    title="Remove Item">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                                                    <div class="md:col-span-7">
                                                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Stock Item</label>
                                                        <select name="items[${itemCounter}][stock_id]" class="stock-select w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition" required onchange="updateItemMaxQty(${itemCounter})">
                                                            ${stockOptions}
                                                        </select>
                                                        <div class="qty-indicator mt-2 text-[10px] font-bold text-accent hidden">
                                                            Available: <span class="max-qty-label">0</span> <span class="max-qty-unit">units</span>
                                                        </div>
                                                    </div>
                                                    <div class="md:col-span-5">
                                                        <div class="flex justify-between items-center mb-2">
                                                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">
                                                                <span class="quantity-label">Quantity</span>
                                                            </label>
                                                            <label class="flex items-center space-x-1 cursor-pointer">
                                                                <input type="checkbox" class="transfer-all-row w-3 h-3 rounded text-accent focus:ring-accent/20" onchange="toggleRowTransferAll(${itemCounter})">
                                                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">All Stock</span>
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center space-x-2">
                                                            <div class="boxes-wrapper flex-1 hidden">
                                                                <input type="number" class="boxes-input w-full h-12 px-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition" 
                                                                    min="0" placeholder="Boxes" oninput="calculateItemQuantity(${itemCounter})">
                                                                <p class="text-[9px] text-slate-400 mt-1 uppercase font-bold text-center">Boxes</p>
                                                            </div>
                                                            <div class="units-wrapper flex-1">
                                                                <input type="number" class="units-input w-full h-12 px-4 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition" 
                                                                    min="0" placeholder="Units" oninput="calculateItemQuantity(${itemCounter})">
                                                                <p class="units-label-small text-[9px] text-slate-400 mt-1 uppercase font-bold text-center">Units</p>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="items[${itemCounter}][quantity]" class="actual-quantity" required>
                                                        <p class="quantity-hint text-[10px] text-slate-500 font-medium mt-2 text-center"></p>
                                                    </div>
                                                </div>
                                            `;

            container.appendChild(itemRow);

            // Initialize Tom Select for the row
            const newSelect = itemRow.querySelector('.stock-select');
            const ts = new TomSelect(newSelect, {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Search medicine name or batch...",
                allowEmptyOption: true,
                optgroupField: 'optgroup',
                // Important: map data attributes to the Tom Select item
                dataAttr: 'data-data',
                valueField: 'value',
                labelField: 'text',
                searchField: ['text', 'generic'],
                maxOptions: 1000,
                render: {
                    optgroup_header: function (data, escape) {
                        return '<div class="optgroup-header">' + escape(data.label) + '</div>';
                    },
                    option: function (data, escape) {
                        const parts = escape(data.text).split(' | ');
                        const name = parts[0];
                        const details = parts.slice(1).join(' | ');
                        return `
                                                                <div class="py-1">
                                                                    <div class="font-bold text-slate-800 dark:text-white text-sm">${name}</div>
                                                                    <div class="text-[10px] text-slate-500 font-medium">${details}</div>
                                                                </div>`;
                    },
                    item: function (data, escape) {
                        return '<div class="text-sm font-medium">' + escape(data.text) + '</div>';
                    }
                }
            });

            // Initial data load into Tom Select instance
            const options = Array.from(newSelect.querySelectorAll('option.stock-option')).map(opt => ({
                value: opt.value,
                text: opt.textContent.trim(),
                optgroup: opt.parentElement.label,
                warehouse: opt.dataset.warehouse,
                quantity: opt.dataset.quantity,
                originalQuantity: opt.dataset.quantity,
                originalText: opt.textContent.trim(),
                unit: opt.dataset.unit,
                generic: opt.dataset.generic,
                unitsPerBox: opt.dataset.unitsPerBox
            }));

            ts.addOptions(options);
            tomSelectInstances[itemCounter] = ts;
            filterStockSelect(newSelect, fromWhId, ts);

            // Recalculate if there's usage already
            recalculateAllAvailable();
        }

        function removeTransferItem(id) {
            const item = document.getElementById(`item_${id}`);
            if (item) {
                if (tomSelectInstances[id]) {
                    tomSelectInstances[id].destroy();
                    delete tomSelectInstances[id];
                }
                item.remove();
                recalculateAllAvailable();
            }
        }

        function filterAllStockSelects() {
            const fromWhId = document.getElementById('from_warehouse_id').value;
            Object.values(tomSelectInstances).forEach(ts => {
                const stockSelect = ts.input;
                filterStockSelect(stockSelect, fromWhId, ts);
            });
            recalculateAllAvailable();
        }

        function filterStockSelect(select, fromWhId, tsInstance = null) {
            if (!tsInstance) return;

            // Save currently selected value
            const currentValue = tsInstance.getValue();

            // Get all original options from the template select
            const allOptions = Array.from(select.querySelectorAll('option.stock-option')).map(opt => ({
                value: opt.value,
                text: opt.textContent.trim(),
                optgroup: opt.parentElement.label,
                warehouse: opt.dataset.warehouse,
                quantity: opt.dataset.quantity,
                originalQuantity: opt.dataset.quantity,
                originalText: opt.textContent.trim(),
                unit: opt.dataset.unit,
                generic: opt.dataset.generic,
                unitsPerBox: opt.dataset.unitsPerBox
            }));

            // Filter options based on warehouse
            const filteredOptions = allOptions.filter(opt =>
                opt.warehouse == fromWhId || fromWhId === ''
            );

            // Rebuild the Tom Select dropdown
            tsInstance.clearOptions();
            tsInstance.addOptions(filteredOptions);
            tsInstance.refreshOptions(false);

            // Check if current selection is still valid
            const isStillValid = filteredOptions.some(opt => opt.value == currentValue);
            if (!isStillValid && currentValue) {
                tsInstance.clear();
            } else if (currentValue) {
                tsInstance.setValue(currentValue);
            }
        }

        function updateItemMaxQty(itemId) {
            const ts = tomSelectInstances[itemId];
            if (!ts) return;

            const selectedValue = ts.getValue();
            const itemRow = document.getElementById(`item_${itemId}`);
            const boxesInput = itemRow.querySelector('.boxes-input');
            const unitsInput = itemRow.querySelector('.units-input');
            const boxesWrapper = itemRow.querySelector('.boxes-wrapper');
            const unitsWrapper = itemRow.querySelector('.units-wrapper');
            const unitsLabelSmall = itemRow.querySelector('.units-label-small');
            const actualQuantity = itemRow.querySelector('.actual-quantity');
            const quantityLabel = itemRow.querySelector('.quantity-label');
            const quantityHint = itemRow.querySelector('.quantity-hint');
            const indicator = itemRow.querySelector('.qty-indicator');
            const label = itemRow.querySelector('.max-qty-label');
            const unitLabel = itemRow.querySelector('.max-qty-unit');

            if (selectedValue) {
                const data = ts.options[selectedValue];
                const maxQty = parseInt(data.originalQuantity || data.quantity);
                const unit = data.unit;
                const unitsPerBox = parseInt(data.unitsPerBox) || 100;

                if (unit === 'Tablet' || unit === 'Capsule') {
                    boxesWrapper.classList.remove('hidden');
                    unitsWrapper.classList.remove('hidden');
                    quantityLabel.textContent = 'Units / Boxes';
                    unitsLabelSmall.textContent = 'Units';
                    quantityHint.textContent = `* 1 Box = ${unitsPerBox} ${unit.toLowerCase()}s`;
                    label.innerText = maxQty;
                    unitLabel.innerText = `${unit.toLowerCase()}s (${(maxQty / unitsPerBox).toFixed(1)} boxes)`;

                    boxesInput.max = Math.floor(maxQty / unitsPerBox);
                    unitsInput.max = maxQty;
                } else {
                    boxesWrapper.classList.add('hidden');
                    unitsWrapper.classList.remove('hidden');
                    quantityLabel.textContent = 'Quantity';
                    unitsLabelSmall.textContent = 'Units';
                    quantityHint.textContent = '';
                    label.innerText = maxQty;
                    unitLabel.innerText = 'units';
                    unitsInput.max = maxQty;
                }

                indicator.classList.remove('hidden');
                boxesInput.value = '';
                unitsInput.value = '';
                actualQuantity.value = '';

                const rowTransferAll = itemRow.querySelector('.transfer-all-row');
                if (rowTransferAll) rowTransferAll.checked = false;
                boxesInput.disabled = false;
                unitsInput.disabled = false;
            } else {
                indicator.classList.add('hidden');
                quantityHint.textContent = '';
            }
            recalculateAllAvailable();
        }

        function toggleRowTransferAll(itemId) {
            const ts = tomSelectInstances[itemId];
            if (!ts) return;

            const selectedValue = ts.getValue();
            const itemRow = document.getElementById(`item_${itemId}`);
            const checkbox = itemRow.querySelector('.transfer-all-row');
            const boxesInput = itemRow.querySelector('.boxes-input');
            const unitsInput = itemRow.querySelector('.units-input');
            const actualQuantity = itemRow.querySelector('.actual-quantity');

            if (checkbox.checked && selectedValue) {
                const data = ts.options[selectedValue];
                // Use original quantity minus other usage
                const usage = {};
                document.querySelectorAll('.transfer-item').forEach(row => {
                    const rid = row.id.split('_')[1];
                    if (rid == itemId) return;
                    const rts = tomSelectInstances[rid];
                    if (!rts) return;
                    const rstockId = rts.getValue();
                    const rqty = parseInt(row.querySelector('.actual-quantity').value) || 0;
                    if (rstockId) usage[rstockId] = (usage[rstockId] || 0) + rqty;
                });

                const maxAvailable = parseInt(data.originalQuantity || data.quantity) - (usage[selectedValue] || 0);
                const unit = data.unit;
                const unitsPerBox = parseInt(data.unitsPerBox) || 100;

                if (unit === 'Tablet' || unit === 'Capsule') {
                    const fullBoxes = Math.floor(maxAvailable / unitsPerBox);
                    const remUnits = maxAvailable % unitsPerBox;
                    boxesInput.value = fullBoxes;
                    unitsInput.value = remUnits;
                    actualQuantity.value = maxAvailable;
                } else {
                    boxesInput.value = '';
                    unitsInput.value = maxAvailable;
                    actualQuantity.value = maxAvailable;
                }
                boxesInput.disabled = true;
                unitsInput.disabled = true;
            } else {
                boxesInput.disabled = false;
                unitsInput.disabled = false;
                boxesInput.value = '';
                unitsInput.value = '';
                actualQuantity.value = '';
            }
            recalculateAllAvailable();
        }

        function calculateItemQuantity(itemId) {
            const ts = tomSelectInstances[itemId];
            if (!ts) return;

            const selectedValue = ts.getValue();
            const itemRow = document.getElementById(`item_${itemId}`);
            const boxesInput = itemRow.querySelector('.boxes-input');
            const unitsInput = itemRow.querySelector('.units-input');
            const actualQuantity = itemRow.querySelector('.actual-quantity');
            const checkbox = itemRow.querySelector('.transfer-all-row');

            const boxesValue = parseInt(boxesInput.value) || 0;
            const unitsValue = parseInt(unitsInput.value) || 0;

            if (selectedValue) {
                const data = ts.options[selectedValue];
                const unit = data.unit;
                const unitsPerBox = parseInt(data.unitsPerBox) || 100;

                // Calculate other rows usage to get current max
                const usage = {};
                document.querySelectorAll('.transfer-item').forEach(row => {
                    const rid = row.id.split('_')[1];
                    if (rid == itemId) return;
                    const rts = tomSelectInstances[rid];
                    if (!rts) return;
                    const rstockId = rts.getValue();
                    const rqty = parseInt(row.querySelector('.actual-quantity').value) || 0;
                    if (rstockId) usage[rstockId] = (usage[rstockId] || 0) + rqty;
                });
                const maxTotal = parseInt(data.originalQuantity || data.quantity) - (usage[selectedValue] || 0);

                if (checkbox && checkbox.checked) {
                    actualQuantity.value = maxTotal;
                } else {
                    let total = 0;
                    if (unit === 'Tablet' || unit === 'Capsule') {
                        total = (boxesValue * unitsPerBox) + unitsValue;
                    } else {
                        total = unitsValue;
                    }

                    // Cap at max available
                    if (total > maxTotal) {
                        total = maxTotal;
                        // Adjust inputs to match cap
                        if (unit === 'Tablet' || unit === 'Capsule') {
                            boxesInput.value = Math.floor(maxTotal / unitsPerBox);
                            unitsInput.value = maxTotal % unitsPerBox;
                        } else {
                            unitsInput.value = maxTotal;
                        }
                    }
                    actualQuantity.value = total;
                }
            }
            recalculateAllAvailable();
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/inventory/transfer.blade.php ENDPATH**/ ?>