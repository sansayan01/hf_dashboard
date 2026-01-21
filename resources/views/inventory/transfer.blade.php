@extends('layouts.app')

@section('title', 'Stock Transfer')
@section('header_title', 'NGO Pharmacy | Move Stock')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-xl overflow-hidden text-slate-800 dark:text-white">
            <div class="p-8 border-b border-slate-100 dark:border-white/5">
                <h3 class="font-bold text-xl">Transfer Inventory</h3>
                <p class="text-sm text-slate-500">Move medicine stock between warehouse locations.</p>
            </div>

            <form action="{{ route('inventory.process-transfer') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">From
                            Warehouse (Source)</label>
                        <select name="from_warehouse_id" id="from_warehouse_id" required onchange="handleSourceChange()"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select Source...</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}" data-type="{{ $wh->type }}" {{ ($preSelectedStock && $preSelectedStock->warehouse_id == $wh->id) ? 'selected' : '' }}>
                                    {{ $wh->name }} ({{ ucfirst($wh->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">To
                            Location (Destination)</label>
                        <select name="to_warehouse_id" id="to_warehouse_id" required
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select Destination...</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }} ({{ ucfirst($wh->type) }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Transfer All Option (for camps) -->
                    <div class="md:col-span-2 hidden" id="transfer_all_wrapper">
                        <div
                            class="flex items-center space-x-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl">
                            <input type="checkbox" id="transfer_all" name="transfer_all" value="1"
                                onchange="toggleTransferMode()"
                                class="w-5 h-5 rounded border-amber-300 text-amber-600 focus:ring-amber-500">
                            <label for="transfer_all"
                                class="text-sm font-bold text-amber-900 dark:text-amber-200 cursor-pointer">
                                Transfer ALL stock from this camp (saves time by moving everything at once)
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
                    <a href="{{ route('inventory.index') }}"
                        class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Cancel</a>
                    <button type="submit"
                        class="px-8 h-12 bg-accent text-white rounded-2xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                        Confirm Transfer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let itemCounter = 0;
        const stockOptions = `
                            <option value="">Select matching stock...</option>
                            @foreach($medicines as $med)
                                <optgroup label="{{ $med->name }} ({{ $med->unit }})">
                                    @foreach($med->stocks as $stock)
                                        <option value="{{ $stock->id }}" 
                                                data-warehouse="{{ $stock->warehouse_id }}" 
                                                data-quantity="{{ $stock->quantity }}"
                                                data-unit="{{ $med->unit }}"
                                                data-units-per-box="{{ $med->units_per_box ?? 100 }}"
                                                class="stock-option">
                                            Batch: #{{ $stock->batch_number }} | Exp: {{ $stock->expiry_date->format('M Y') }} | Qty: {{ $stock->quantity }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        `;

        document.addEventListener('DOMContentLoaded', function () {
            handleSourceChange();
        });

        function handleSourceChange() {
            const fromWhSelect = document.getElementById('from_warehouse_id');
            const selectedOpt = fromWhSelect.options[fromWhSelect.selectedIndex];
            const transferAllWrapper = document.getElementById('transfer_all_wrapper');
            const transferAllCheckbox = document.getElementById('transfer_all');
            const multiItemWrapper = document.getElementById('multi_item_wrapper');

            // Show Transfer All option only for camps
            if (selectedOpt && selectedOpt.dataset.type === 'camp') {
                transferAllWrapper.classList.remove('hidden');
                multiItemWrapper.classList.add('hidden');
            } else if (selectedOpt && selectedOpt.value) {
                // Show multi-item transfer for warehouses
                transferAllWrapper.classList.add('hidden');
                transferAllCheckbox.checked = false;
                multiItemWrapper.classList.remove('hidden');

                // Initialize with one item row
                if (document.getElementById('transfer_items_container').children.length === 0) {
                    addTransferItem();
                }
            } else {
                transferAllWrapper.classList.add('hidden');
                multiItemWrapper.classList.add('hidden');
                transferAllCheckbox.checked = false;
            }

            filterAllStockSelects();
        }

        function toggleTransferMode() {
            const transferAll = document.getElementById('transfer_all').checked;
            const multiItemWrapper = document.getElementById('multi_item_wrapper');

            if (transferAll) {
                multiItemWrapper.classList.add('hidden');
            } else {
                multiItemWrapper.classList.remove('hidden');
            }
        }

        function addTransferItem() {
            itemCounter++;
            const container = document.getElementById('transfer_items_container');
            const fromWhId = document.getElementById('from_warehouse_id').value;

            const itemRow = document.createElement('div');
            itemRow.className = 'transfer-item p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-200 dark:border-slate-700';
            itemRow.id = `item_${itemCounter}`;
            itemRow.innerHTML = `
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Stock Item</label>
                                        <select name="items[${itemCounter}][stock_id]" class="stock-select" required onchange="updateItemMaxQty(${itemCounter})"
                                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                                            ${stockOptions}
                                        </select>
                                        <div class="qty-indicator mt-2 text-[10px] font-bold text-accent hidden">
                                            Available: <span class="max-qty-label">0</span> <span class="max-qty-unit">units</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">
                                            <span class="quantity-label">Quantity</span>
                                        </label>
                                        <div class="flex space-x-2">
                                            <input type="number" class="quantity-input flex-1 h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition" 
                                                min="1" placeholder="0" required onchange="calculateItemQuantity(${itemCounter})">
                                            <input type="hidden" name="items[${itemCounter}][quantity]" class="actual-quantity">
                                            ${itemCounter > 1 ? '<button type="button" onclick="removeTransferItem(' + itemCounter + ')" class="px-3 h-12 bg-red-500 text-white rounded-xl text-xs font-bold hover:bg-red-600 transition">Remove</button>' : ''}
                                        </div>
                                        <p class="quantity-hint mt-1 text-[10px] text-slate-500 font-medium"></p>
                                    </div>
                                </div>
                            `;

            container.appendChild(itemRow);
            filterStockSelect(itemRow.querySelector('.stock-select'), fromWhId);
        }

        function removeTransferItem(id) {
            const item = document.getElementById(`item_${id}`);
            if (item) {
                item.remove();
            }
        }

        function filterAllStockSelects() {
            const fromWhId = document.getElementById('from_warehouse_id').value;
            const selects = document.querySelectorAll('.stock-select');
            selects.forEach(select => filterStockSelect(select, fromWhId));
        }

        function filterStockSelect(select, fromWhId) {
            const options = select.querySelectorAll('option.stock-option');
            const optgroups = select.querySelectorAll('optgroup');

            options.forEach(opt => {
                if (opt.dataset.warehouse == fromWhId || fromWhId === '') {
                    opt.style.display = '';
                } else {
                    opt.style.display = 'none';
                }
            });

            // Hide empty optgroups
            optgroups.forEach(group => {
                const visibleOptions = Array.from(group.options).filter(opt => opt.style.display !== 'none');
                group.style.display = visibleOptions.length > 0 ? '' : 'none';
            });
        }

        function updateItemMaxQty(itemId) {
            const itemRow = document.getElementById(`item_${itemId}`);
            const stockSelect = itemRow.querySelector('.stock-select');
            const selectedOpt = stockSelect.options[stockSelect.selectedIndex];
            const quantityInput = itemRow.querySelector('.quantity-input');
            const actualQuantity = itemRow.querySelector('.actual-quantity');
            const quantityLabel = itemRow.querySelector('.quantity-label');
            const quantityHint = itemRow.querySelector('.quantity-hint');
            const indicator = itemRow.querySelector('.qty-indicator');
            const label = itemRow.querySelector('.max-qty-label');
            const unitLabel = itemRow.querySelector('.max-qty-unit');

            if (selectedOpt && selectedOpt.value) {
                const maxQty = parseInt(selectedOpt.dataset.quantity);
                const unit = selectedOpt.dataset.unit;
                const unitsPerBox = parseInt(selectedOpt.dataset.unitsPerBox) || 100;

                if (unit === 'Tablet' || unit === 'Capsule') {
                    const maxBoxes = Math.floor(maxQty / unitsPerBox);
                    quantityLabel.textContent = 'Boxes';
                    quantityInput.placeholder = 'Number of boxes';
                    quantityInput.max = maxBoxes;
                    quantityHint.textContent = '* Each box = ' + unitsPerBox + ' ' + unit.toLowerCase() + 's';
                    label.innerText = maxBoxes;
                    unitLabel.innerText = 'boxes (' + maxQty + ' ' + unit.toLowerCase() + 's)';
                } else {
                    quantityLabel.textContent = 'Quantity';
                    quantityInput.placeholder = '0';
                    quantityInput.max = maxQty;
                    quantityHint.textContent = '';
                    label.innerText = maxQty;
                    unitLabel.innerText = 'units';
                }

                indicator.classList.remove('hidden');
                quantityInput.value = '';
                actualQuantity.value = '';
            } else {
                indicator.classList.add('hidden');
                quantityHint.textContent = '';
            }
        }

        function calculateItemQuantity(itemId) {
            const itemRow = document.getElementById(`item_${itemId}`);
            const stockSelect = itemRow.querySelector('.stock-select');
            const selectedOpt = stockSelect.options[stockSelect.selectedIndex];
            const quantityInput = itemRow.querySelector('.quantity-input');
            const actualQuantity = itemRow.querySelector('.actual-quantity');
            const inputValue = parseInt(quantityInput.value) || 0;

            if (selectedOpt && selectedOpt.value) {
                const unit = selectedOpt.dataset.unit;
                const unitsPerBox = parseInt(selectedOpt.dataset.unitsPerBox) || 100;

                if (unit === 'Tablet' || unit === 'Capsule') {
                    actualQuantity.value = inputValue * unitsPerBox;
                } else {
                    actualQuantity.value = inputValue;
                }
            }
        }
    </script>
@endsection