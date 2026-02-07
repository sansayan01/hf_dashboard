@extends('layouts.app')

@section('title', 'Edit Distribution')
@section('header_title', 'Edit Medicine Distribution')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Flash Messages --}}
        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 px-6 py-4 rounded-2xl font-bold">
                {{ session('error') }}
            </div>
        @endif

        {{-- Patient Info Card --}}
        <div
            class="glass bg-white dark:bg-darkbg/40 rounded-3xl p-6 border border-slate-200/10 dark:border-white/5 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <svg class="w-32 h-32 text-accent" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M19 3H5a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2V5a2 2 0 00-2-2zm-7 3a1 1 0 011 1v4h4a1 1 0 110 2h-4v4a1 1 0 11-2 0v-4H7a1 1 0 110-2h4V7a1 1 0 011-1z" />
                </svg>
            </div>
            <div class="relative z-10 flex items-start gap-6">
                <div
                    class="w-16 h-16 bg-accent text-white rounded-2xl flex items-center justify-center text-2xl font-black">
                    {{ substr($distribution->patient->full_name ?? 'N', 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Editing Distribution For
                    </p>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-none mb-2">
                        {{ $distribution->patient->full_name ?? 'Unknown Patient' }}
                    </h2>
                    <div class="flex flex-wrap items-center gap-3 text-sm font-bold text-slate-500">
                        <span
                            class="bg-slate-100 dark:bg-white/5 px-2 py-0.5 rounded">{{ $distribution->patient->patient_id ?? 'N/A' }}</span>
                        <span>•</span>
                        <span>Invoice #{{ $distribution->id }}</span>
                        <span>•</span>
                        <span>{{ $distribution->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    @if($distribution->patient->creator ?? null)
                        <div class="flex items-center gap-2 mt-2 text-xs text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-bold uppercase tracking-widest text-[10px]">RO:</span>
                            <span
                                class="font-bold text-accent">{{ $distribution->patient->creator->profile->full_name ?? 'N/A' }}</span>
                                    <span class="bg-slate-100 dark:bg-white/10 px-1.5 py-0.5 rounded text-[10px] font-mono">{{ $distribution->patient->creator->employee_id ?? 'N/A' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Distribution Edit Form --}}
        <div
            class="glass bg-white dark:bg-darkbg/40 rounded-3xl p-8 border border-slate-200/10 dark:border-white/5 shadow-xl">
            <form id="editForm" action="{{ route('medicine.distribution.update', $distribution->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    {{-- Camp (Read Only) --}}
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Camp /
                            Warehouse</label>
                        <div
                            class="w-full h-12 px-4 rounded-xl border border-slate-200/50 dark:border-white/10 bg-slate-100/50 dark:bg-white/5 text-slate-500 flex items-center text-sm font-bold opacity-80 cursor-not-allowed">
                            {{ $distribution->camp->name ?? 'N/A' }} ({{ $distribution->camp->location ?? 'No Loc' }})
                        </div>
                        <input type="hidden" id="camp_id" value="{{ $distribution->camp_id }}">
                    </div>

                    {{-- Pharmacist (Read Only) --}}
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Pharmacist</label>
                        <div
                            class="w-full h-12 px-4 rounded-xl border border-slate-200/50 dark:border-white/10 bg-slate-100/50 dark:bg-white/5 text-slate-500 flex items-center text-sm font-bold">
                            {{ $distribution->pharmacist->profile->full_name ?? $distribution->pharmacist->employee_id ?? 'N/A' }}
                        </div>
                    </div>
                </div>

                {{-- Medicine Search & Add --}}
                <div class="mb-8 p-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/5">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Search & Add
                        Medicine</label>
                    <div class="relative">
                        <input type="text" id="medicine_search"
                            class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-bold focus:ring-2 focus:ring-accent/50 outline-none transition shadow-sm placeholder:font-medium"
                            placeholder="Type medicine name to add more..." autocomplete="off">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        {{-- Dropdown Results --}}
                        <div id="search_results"
                            class="absolute z-50 left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 max-h-60 overflow-y-auto hidden">
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 pl-1">* Only medicines with stock in the camp will appear.</p>
                </div>

                {{-- Items Table --}}
                <div class="mb-8">
                    <table class="w-full text-left border-collapse" id="itemsTable">
                        <thead>
                            <tr
                                class="border-b border-slate-200 dark:border-white/10 text-[10px] uppercase font-black text-slate-400 tracking-widest">
                                <th class="py-4 pl-2">Medicine Name</th>
                                <th class="py-4 w-32 text-center">Unit Price</th>
                                <th class="py-4 w-32 text-center">Stock</th>
                                <th class="py-4 w-32 text-center">Quantity</th>
                                <th class="py-4 w-32 text-right pr-2">Total</th>
                                <th class="py-4 w-12"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsBody" class="text-sm font-bold text-slate-700 dark:text-slate-300">
                            {{-- Items will be rendered by JavaScript --}}
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-slate-200 dark:border-white/10">
                                <td colspan="5"
                                    class="py-4 text-right font-black uppercase tracking-widest text-[10px] text-slate-400">
                                    Subtotal</td>
                                <td colspan="1" class="py-4 text-right font-bold text-slate-700 dark:text-slate-300 pr-2">
                                    ₹<span id="subTotal">0.00</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5"
                                    class="py-2 text-right font-black uppercase tracking-widest text-[10px] text-emerald-500">
                                    Discount (<span id="discountPerc">0</span>%)
                                </td>
                                <td colspan="1" class="py-2 text-right font-bold text-emerald-500 pr-2">
                                    -₹<span id="discountAmt">0.00</span>
                                </td>
                            </tr>
                            <tr class="border-t-2 border-slate-200 dark:border-white/10">
                                <td colspan="5"
                                    class="py-6 text-right font-black uppercase tracking-widest text-xs text-slate-500">
                                    Final Total</td>
                                <td colspan="1" class="py-6 text-right font-black text-2xl text-accent pr-2">
                                    ₹<span id="grandTotal">0.00</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="empty-cart-msg" class="text-center py-10 text-slate-400 text-sm font-medium italic hidden">
                        No medicines added. Please add at least one medicine.
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('inventory.transactions', ['view' => 'dispenses']) }}"
                        class="px-6 py-3 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 font-bold hover:bg-slate-200 transition">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-8 py-3 rounded-xl bg-accent text-white font-black uppercase tracking-widest shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition disabled:opacity-50 disabled:scale-100 disabled:cursor-not-allowed">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize items from existing distribution
        let addedItems = {};
        let existingItemIds = {}; // Track original item IDs for updates
        const campId = document.getElementById('camp_id').value;

        // Load existing items
        @foreach($distribution->items as $item)
            addedItems[{{ $item->medicine_id }}] = {
                id: {{ $item->medicine_id }},
                itemId: {{ $item->id }}, // Distribution item ID for updates
                name: @json($item->medicine->name ?? 'Unknown'),
                genericName: @json($item->medicine->generic_name ?? ''),
                price: {{ $item->unit_price }},
                stock: {{ $item->quantity + ($stocks[$item->medicine_id] ?? 0) }}, // Original qty + current stock
                qty: {{ $item->quantity }},
                isExisting: true
            };
            existingItemIds[{{ $item->medicine_id }}] = {{ $item->id }};
        @endforeach

        // Initial render
        renderCart();

        const searchInput = document.getElementById('medicine_search');
        const resultsBox = document.getElementById('search_results');
        let debounceTimer;

        searchInput.addEventListener('input', function (e) {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            if (query.length < 2) {
                resultsBox.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('medicine.search') }}?q=${encodeURIComponent(query)}&camp_id=${campId}`)
                    .then(res => res.json())
                    .then(data => {
                        resultsBox.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'p-3 hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer border-b border-slate-100 dark:border-white/5 last:border-0 transition-colors';

                                // Check if already in cart
                                const alreadyAdded = addedItems[item.id] ? addedItems[item.id].qty : 0;
                                const available = item.available_stock - alreadyAdded;
                                const disabled = available <= 0;

                                div.innerHTML = `
                                            <div class="flex items-center justify-between ${disabled ? 'opacity-50' : ''}">
                                                <div>
                                                    <div class="font-bold text-slate-700 dark:text-slate-200">${item.text.split(' - ')[0]}</div>
                                                    <div class="text-[10px] text-slate-400 uppercase font-black tracking-wider">Rate: ₹${item.market_price} / unit • Stock: ${item.available_stock}</div>
                                                </div>
                                                <div class="${available > 0 ? 'text-emerald-500' : 'text-rose-500'} font-black text-xs">
                                                    ${available > 0 ? 'Add +' : (addedItems[item.id] ? 'In Cart' : 'Out of Stock')}
                                                </div>
                                            </div>
                                        `;

                                if (!disabled) {
                                    div.addEventListener('click', () => {
                                        addItemToCart(item);
                                        searchInput.value = '';
                                        resultsBox.classList.add('hidden');
                                        searchInput.focus();
                                    });
                                }
                                resultsBox.appendChild(div);
                            });
                            resultsBox.classList.remove('hidden');
                        } else {
                            resultsBox.innerHTML = '<div class="p-4 text-center text-slate-400 font-bold italic text-sm">No medicines found in this camp matching your search.</div>';
                            resultsBox.classList.remove('hidden');
                        }
                    });
            }, 300);
        });

        // Close search when clicking outside
        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
                resultsBox.classList.add('hidden');
            }
        });

        function addItemToCart(item) {
            if (addedItems[item.id]) {
                // Increase quantity if already exists
                if (addedItems[item.id].qty < addedItems[item.id].stock) {
                    addedItems[item.id].qty++;
                } else {
                    alert(`Only ${addedItems[item.id].stock} available in stock.`);
                    return;
                }
            } else {
                addedItems[item.id] = {
                    id: item.id,
                    itemId: null, // New item, no existing ID
                    name: item.text.split(' - ')[0],
                    genericName: '',
                    price: parseFloat(item.market_price),
                    stock: parseInt(item.available_stock),
                    qty: 1,
                    isExisting: false
                };
            }
            renderCart();
        }

        function removeItem(id) {
            delete addedItems[id];
            renderCart();
        }

        function updateQty(id, newQty) {
            const item = addedItems[id];
            if (newQty > item.stock) {
                alert(`Only ${item.stock} available in stock.`);
                return;
            }
            if (newQty < 1) newQty = 1;
            item.qty = parseInt(newQty);
            renderCart();
        }

        function renderCart() {
            const tbody = document.getElementById('itemsBody');
            tbody.innerHTML = '';
            let grandTotal = 0;
            let count = 0;

            Object.values(addedItems).forEach((item, index) => {
                count++;
                const total = item.price * item.qty;
                grandTotal += total;

                const tr = document.createElement('tr');
                tr.className = 'border-b border-slate-100 dark:border-white/5 hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group';

                // Hidden inputs for form submission
                let hiddenInputs = `
                            <input type="hidden" name="items[${index}][medicine_id]" value="${item.id}">
                            <input type="hidden" name="items[${index}][item_id]" value="${item.itemId || ''}">
                            <input type="hidden" name="items[${index}][unit_price]" value="${item.price}">
                        `;

                tr.innerHTML = `
                            <td class="py-4 pl-2">
                                ${hiddenInputs}
                                <div class="font-bold">${item.name}</div>
                                ${item.genericName ? `<div class="text-[10px] text-slate-400">${item.genericName}</div>` : ''}
                                ${item.isExisting ? '<span class="text-[9px] bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded font-bold">EXISTING</span>' : '<span class="text-[9px] bg-emerald-100 text-emerald-600 px-1.5 py-0.5 rounded font-bold">NEW</span>'}
                            </td>
                            <td class="py-4 text-center text-slate-500">₹${item.price.toFixed(2)}</td>
                            <td class="py-4 text-center text-xs text-slate-400 font-mono bg-slate-100 dark:bg-white/5 rounded-lg py-1">${item.stock}</td>
                            <td class="py-4 text-center">
                                <input type="number" name="items[${index}][quantity]" value="${item.qty}" min="1" max="${item.stock}"
                                    onchange="updateQty(${item.id}, this.value)"
                                    class="w-16 h-8 text-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-accent/50 outline-none font-bold text-sm">
                            </td>
                            <td class="py-4 text-right pr-2 font-mono">₹${total.toFixed(2)}</td>
                            <td class="py-4 text-right">
                                <button type="button" onclick="removeItem(${item.id})" class="text-slate-400 hover:text-rose-500 transition px-2" title="Remove">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </td>
                        `;
                tbody.appendChild(tr);
            });

            document.getElementById('subTotal').innerText = grandTotal.toFixed(2);

            const perc = grandTotal > 300 ? 20 : 18;
            const discount = (grandTotal * perc) / 100;
            const finalTotal = grandTotal - discount;

            document.getElementById('discountPerc').innerText = perc;
            document.getElementById('discountAmt').innerText = discount.toFixed(2);
            document.getElementById('grandTotal').innerText = finalTotal.toFixed(2);

            const emptyMsg = document.getElementById('empty-cart-msg');
            const submitBtn = document.getElementById('submitBtn');
            if (count === 0) {
                emptyMsg.classList.remove('hidden');
                submitBtn.disabled = true;
            } else {
                emptyMsg.classList.add('hidden');
                submitBtn.disabled = false;
            }
        }

        // Handle form submission
        document.getElementById('editForm').addEventListener('submit', function (e) {
            const itemCount = Object.keys(addedItems).length;
            if (itemCount === 0) {
                e.preventDefault();
                alert('Please add at least one medicine.');
                return false;
            }
        });
    </script>
@endsection