@extends('layouts.app')

@section('title', 'Stock In')
@section('header_title', 'Receive New Stock')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-xl overflow-hidden text-slate-800 dark:text-white">
            <div class="p-8 border-b border-slate-100 dark:border-white/5">
                <h3 class="font-bold text-xl">Receive Inventory</h3>
                <p class="text-sm text-slate-500">Log new stock arrivals into the system.</p>
            </div>

            <form action="{{ route('inventory.store') }}" method="POST" class="p-8 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Select
                            Medicine</label>
                        <select name="medicine_id" id="medicine_select" required
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select a registered medicine...</option>
                            @foreach($medicines as $medicine)
                                <option value="{{ $medicine->id }}" data-unit="{{ $medicine->unit }}"
                                    data-units-per-box="{{ $medicine->units_per_box ?? 100 }}">
                                    {{ $medicine->name }} ({{ $medicine->unit }}) - {{ $medicine->dosage ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-2 flex items-center text-[10px] font-bold text-slate-400 px-1">
                            <span class="mr-1">Not in list?</span>
                            <a href="{{ route('inventory.medicines.create') }}" class="text-accent underline">Register new
                                medicine first</a>
                        </p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Target
                            Warehouse</label>
                        <select name="warehouse_id" required
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">Select Warehouse...</option>
                            @foreach($warehouses as $wh)
                                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Sponsor
                            (Optional)</label>
                        <select name="sponsor_id"
                            class="w-full h-12 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">No Sponsor / Purchased</option>
                            @foreach($sponsors as $sponsor)
                                <option value="{{ $sponsor->id }}">{{ $sponsor->name }}</option>
                            @endforeach
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
                        <input type="date" name="expiry_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
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
                    <a href="{{ route('inventory.index') }}"
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
@endsection

@section('js')
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
@endsection