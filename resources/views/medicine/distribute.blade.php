@extends('layouts.app')

@section('title', 'Dispense Medicine')
@section('header_title', 'Farmasi & Stock')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Patient Info Card -->
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
                    {{ substr($patient->full_name, 0, 1) }}
                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Dispensing For</p>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-none mb-2">
                        {{ $patient->full_name }}
                    </h2>
                    <div class="flex items-center gap-3 text-sm font-bold text-slate-500">
                        <span class="bg-slate-100 dark:bg-white/5 px-2 py-0.5 rounded">{{ $patient->patient_id }}</span>
                        <span>•</span>
                        <span>{{ ucfirst($patient->gender) }}</span>
                        <span>•</span>
                        <span>{{ $patient->age }} Years</span>
                    </div>
                    @if($patient->creator ?? null)
                        <div class="flex items-center gap-2 mt-2 text-xs text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <span class="font-bold uppercase tracking-widest text-[10px]">RO:</span>
                            <span class="font-bold text-accent">{{ $patient->creator->profile->full_name ?? 'N/A' }}</span>
                            <span
                                class="bg-slate-100 dark:bg-white/10 px-1.5 py-0.5 rounded text-[10px] font-mono">{{ $patient->creator->employee_id ?? 'N/A' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dispense Form -->
        <div
            class="glass bg-white dark:bg-darkbg/40 rounded-3xl p-8 border border-slate-200/10 dark:border-white/5 shadow-xl">
            <form id="dispenseForm" action="{{ route('medicine.distribute.store') }}" method="POST">
                @csrf
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Select Camp -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Dispensing
                            From Camp / Warehouse</label>
                        <div class="relative">
                            @if($camps->count() === 1)
                                <input type="hidden" name="camp_id" value="{{ $camps->first()->id }}" id="camp_id">
                                <div
                                    class="w-full h-12 px-4 rounded-xl border border-slate-200/50 dark:border-white/10 bg-slate-100/50 dark:bg-white/5 text-slate-500 flex items-center text-sm font-bold opacity-80 cursor-not-allowed">
                                    {{ $camps->first()->name }} ({{ $camps->first()->location ?? 'No Loc' }})
                                </div>
                            @else
                                <select name="camp_id" id="camp_id" required
                                    class="w-full h-12 pl-4 pr-10 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/5 text-sm font-bold focus:ring-2 focus:ring-accent/50 outline-none transition appearance-none">
                                    <option value="" disabled selected>Select Camp...</option>
                                    @foreach($camps as $camp)
                                        <option value="{{ $camp->id }}">
                                            {{ $camp->name }} ({{ $camp->location ?? 'No Loc' }})
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Pharmacist (Read Only) -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Dispensing
                            Pharmacist</label>
                        <div
                            class="w-full h-12 px-4 rounded-xl border border-slate-200/50 dark:border-white/10 bg-slate-100/50 dark:bg-white/5 text-slate-500 flex items-center text-sm font-bold">
                            {{ Auth::user()->profile->full_name ?? Auth::user()->employee_id }}
                        </div>
                    </div>
                </div>

                <!-- Medicine Search & Add -->
                <div class="mb-8 p-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/5"
                    id="search-container" style="display:none;">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Search & Add
                        Medicine</label>
                    <div class="relative">
                        <input type="text" id="medicine_search"
                            class="w-full h-12 pl-12 pr-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-bold focus:ring-2 focus:ring-accent/50 outline-none transition shadow-sm placeholder:font-medium"
                            placeholder="Type medicine name (e.g. Paracetamol)..." autocomplete="off">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>

                        <!-- Dropdown Results -->
                        <div id="search_results"
                            class="absolute z-50 left-0 right-0 mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-slate-200 dark:border-slate-700 max-h-60 overflow-y-auto hidden">
                            <!-- Items injected by JS -->
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 pl-1">* Only medicines with stock in selected camp will
                        appear.</p>
                </div>

                <div id="select-camp-msg"
                    class="text-center p-8 bg-amber-500/10 rounded-2xl border border-amber-500/20 text-amber-600 dark:text-amber-400 font-bold mb-8">
                    Please select a camp to start dispensing.
                </div>

                <!-- Items Table -->
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
                            <!-- Rows -->
                        </tbody>
                        </tfoot>
                        <tfoot>
                            <tr class="border-t border-slate-200 dark:border-white/10">
                                <td colspan="4"
                                    class="py-4 text-right font-black uppercase tracking-widest text-[10px] text-slate-400">
                                    Subtotal</td>
                                <td colspan="1" class="py-4 text-right font-bold text-slate-700 dark:text-slate-300 pr-2">
                                    ₹<span id="subTotal">0.00</span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4"
                                    class="py-2 text-right font-black uppercase tracking-widest text-[10px] text-emerald-500">
                                    Discount (<input type="number" step="0.01" name="discount_percentage"
                                        id="discountPercInput"
                                        class="w-14 h-6 text-center rounded-md border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-[10px] font-bold outline-none focus:ring-1 focus:ring-accent"
                                        value="0" oninput="isManualDiscount = true; renderCart();">%)</td>
                                <td colspan="1" class="py-2 text-right font-bold text-emerald-500 pr-2">-₹<span
                                        id="discountAmt">0.00</span></td>
                                <td></td>
                            </tr>
                            <tr class="border-t-2 border-slate-200 dark:border-white/10">
                                <td colspan="4"
                                    class="py-4 text-right font-black uppercase tracking-widest text-xs text-slate-500">
                                    Final Total</td>
                                <td colspan="1" class="py-4 text-right font-black text-2xl text-accent pr-2">₹<span
                                        id="grandTotal">0.00</span></td>
                                <td></td>
                            </tr>
                            <!-- Amount Paid & Due -->
                            <tr>
                                <td colspan="4"
                                    class="py-2 text-right font-black uppercase tracking-widest text-[10px] text-slate-400">
                                    Amount Paid</td>
                                <td colspan="1" class="py-2 text-right pr-2">
                                    <input type="number" step="0.01" name="amount_paid" id="amountPaidInput"
                                        class="w-24 h-8 text-right rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-bold outline-none focus:ring-2 focus:ring-accent/50"
                                        placeholder="0.00" oninput="isManualPayment = true; updateDueAmount();">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="4"
                                    class="py-2 text-right font-black uppercase tracking-widest text-[10px] text-rose-500">
                                    Due Amount</td>
                                <td colspan="1" class="py-2 text-right font-black text-lg text-rose-500 pr-2">
                                    ₹<span id="dueAmount">0.00</span>
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                    <div id="empty-cart-msg" class="text-center py-10 text-slate-400 text-sm font-medium italic">
                        No medicines added yet.
                    </div>
                </div>

                <!-- Payment Method Selection -->
                <div class="mb-8 p-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/5">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Payment
                        Method</label>
                    <div class="relative">
                        <select name="payment_method" id="payment_method"
                            class="w-full h-12 pl-4 pr-10 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-bold focus:ring-2 focus:ring-accent/50 outline-none transition appearance-none">
                            <option value="cash" selected>💵 Cash</option>
                            <option value="upi">📱 UPI</option>
                        </select>
                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <!-- UPI QR Code Container -->
                    <div id="upi-qr-container"
                        class="hidden mt-6 p-6 bg-white dark:bg-slate-800 rounded-xl border-2 border-dashed border-accent/30">
                        <div class="text-center">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Scan to Pay via
                                UPI</p>
                            <div id="qr-code" class="inline-block p-4 bg-white rounded-xl shadow-lg"></div>
                            <div class="mt-4 space-y-1">
                                <p class="text-xs text-slate-500">UPI ID: <span
                                        class="font-bold text-slate-700 dark:text-slate-300">9735563157-4@ybl</span></p>
                                <p class="text-xs text-slate-500">Amount: <span
                                        class="font-black text-accent text-lg">₹<span id="qr-amount">0.00</span></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('patients.index') }}"
                        class="px-6 py-3 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 font-bold hover:bg-slate-200 transition">Cancel</a>
                    <button type="submit" id="submitBtn" disabled
                        class="px-8 py-3 rounded-xl bg-accent text-white font-black uppercase tracking-widest shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition disabled:opacity-50 disabled:scale-100 disabled:cursor-not-allowed">
                        Complete Dispense
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- QRCode.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        let addedItems = {}; // { medId: {price: x, qty: y, name: 'z'} }
        let isManualDiscount = false;
        let isManualPayment = false;
        let selectedCamp = null;

        const campSelect = document.getElementById('camp_id');

        campSelect.addEventListener('change', function () {
            selectedCamp = this.value;
            if (selectedCamp) {
                document.getElementById('search-container').style.display = 'block';
                document.getElementById('select-camp-msg').style.display = 'none';
                // Clear cart when camp changes to avoid stock mismatch
                addedItems = {};
                renderCart();
            } else {
                document.getElementById('search-container').style.display = 'none';
            }
        });

        // Auto-trigger if camp is already selected (for pharmacists with only one camp)
        if (campSelect.value) {
            campSelect.dispatchEvent(new Event('change'));
        }

        const searchInput = document.getElementById('medicine_search');
        const resultsBox = document.getElementById('search_results');
        let debounceTimer;

        searchInput.addEventListener('input', function (e) {
            clearTimeout(debounceTimer);
            const query = e.target.value.trim();

            if (query.length < 2 || !selectedCamp) {
                resultsBox.classList.add('hidden');
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('medicine.search') }}?q=${encodeURIComponent(query)}&camp_id=${selectedCamp}`)
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
                                                                                    ${available > 0 ? 'Add +' : 'Out of Stock'}
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
            const unit = (item.unit || '').toLowerCase();
            const increment = (unit === 'tablet' || unit === 'capsule') ? 10 : 1;

            if (addedItems[item.id]) {
                addedItems[item.id].qty += increment;
            } else {
                addedItems[item.id] = {
                    id: item.id,
                    name: item.text.split(' - ')[0],
                    price: parseFloat(item.market_price),
                    stock: parseInt(item.available_stock),
                    qty: increment,
                    unit: unit
                };
            }

            // Cap at stock
            if (addedItems[item.id].qty > addedItems[item.id].stock) {
                addedItems[item.id].qty = addedItems[item.id].stock;
            }

            renderCart();
        }

        function removeItem(id) {
            delete addedItems[id];
            renderCart();
        }

        function updateQty(id, newQty) {
            const item = addedItems[id];

            // Allow empty or partial input (user might be typing)
            if (newQty === "") {
                item.qty = 0;
            } else {
                let qty = parseInt(newQty);
                if (qty > item.stock) {
                    alert(`Only ${item.stock} available in stock.`);
                    qty = item.stock;
                    // Update input value back to stock max
                    document.getElementById(`qty-input-${id}`).value = qty;
                }
                item.qty = qty;
            }

            // Update the row total instantly
            const rowTotal = item.price * (item.qty || 0);
            const rowTotalEl = document.getElementById(`row-total-${id}`);
            if (rowTotalEl) rowTotalEl.innerText = rowTotal.toFixed(2);

            // Update summary totals
            updateTotals();
        }

        function updateTotals() {
            let grandTotal = 0;
            Object.values(addedItems).forEach(item => {
                grandTotal += item.price * (item.qty || 0);
            });

            document.getElementById('subTotal').innerText = grandTotal.toFixed(2);

            let perc;
            const percInput = document.getElementById('discountPercInput');
            if (isManualDiscount) {
                perc = parseFloat(percInput.value) || 0;
            } else {
                perc = grandTotal > 300 ? 20 : 18;
                percInput.value = perc;
            }

            const discount = (grandTotal * perc) / 100;
            const finalTotal = grandTotal - discount;

            document.getElementById('discountAmt').innerText = discount.toFixed(2);
            document.getElementById('grandTotal').innerText = finalTotal.toFixed(2);

            // Update Payment Fields
            const amountPaidInput = document.getElementById('amountPaidInput');
            if (!isManualPayment) {
                amountPaidInput.value = finalTotal.toFixed(2);
            }
            updateDueAmount();
        }

        function updateDueAmount() {
            const finalTotal = parseFloat(document.getElementById('grandTotal').innerText) || 0;
            const amountPaidInput = document.getElementById('amountPaidInput');
            let amountPaid = parseFloat(amountPaidInput.value);

            if (isNaN(amountPaid)) {
                amountPaid = 0;
            }

            // Optional: Warn if paid > total? For now, we allow it (change/tip) but due shouldn't be negative generally unless we want to show change.
            // Let's assume due amount is 0 if paid > total.
            let due = finalTotal - amountPaid;
            if (due < 0) due = 0;

            document.getElementById('dueAmount').innerText = due.toFixed(2);
        }

        function renderCart() {
            const tbody = document.getElementById('itemsBody');
            tbody.innerHTML = '';
            let count = 0;

            Object.values(addedItems).forEach(item => {
                count++;
                const total = item.price * item.qty;

                const tr = document.createElement('tr');
                tr.className = 'border-b border-slate-100 dark:border-white/5 hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group';
                tr.innerHTML = `
                                                                    <td class="py-4 pl-2">
                                                                        <input type="hidden" name="medicines[${count}][id]" value="${item.id}">
                                                                        <div class="font-bold">${item.name}</div>
                                                                    </td>
                                                                    <td class="py-4 text-center text-slate-500">${item.price.toFixed(2)}</td>
                                                                    <td class="py-4 text-center text-xs text-slate-400 font-mono bg-slate-100 dark:bg-white/5 rounded-lg py-1">${item.stock}</td>
                                                                    <td class="py-4 text-center">
                                                                        <input type="number" id="qty-input-${item.id}" name="medicines[${count}][quantity]" value="${item.qty}" min="1" max="${item.stock}"
                                                                            oninput="updateQty(${item.id}, this.value)"
                                                                            class="w-16 h-8 text-center rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 focus:ring-2 focus:ring-accent/50 outline-none font-bold text-sm">
                                                                    </td>
                                                                    <td class="py-4 text-right pr-2 font-mono">₹<span id="row-total-${item.id}">${total.toFixed(2)}</span></td>
                                                                    <td class="py-4 text-right">
                                                                        <button type="button" onclick="removeItem(${item.id})" class="text-slate-400 hover:text-rose-500 transition px-2">
                                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                                        </button>
                                                                    </td>
                                                                `;
                tbody.appendChild(tr);
            });

            updateTotals();

            const emptyMsg = document.getElementById('empty-cart-msg');
            if (count === 0) {
                emptyMsg.style.display = 'block';
                document.getElementById('submitBtn').disabled = true;
            } else {
                emptyMsg.style.display = 'none';
                document.getElementById('submitBtn').disabled = false;
            }
        }

        // Handle form submission via AJAX to manage potential errors better
        document.getElementById('dispenseForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const form = this;

            btn.disabled = true;
            btn.innerText = 'Processing...';

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                        btn.disabled = false;
                        btn.innerText = 'Complete Dispense';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('System Error. Please try again.');
                    btn.disabled = false;
                    btn.innerText = 'Complete Dispense';
                });
        });

        // UPI QR Code Generation
        const UPI_ID = '9735563157-4@ybl';
        const PAYEE_NAME = 'Humanity Foundation';
        let qrCodeInstance = null;

        function generateUPIQR(amount) {
            const qrContainer = document.getElementById('qr-code');
            const qrAmountEl = document.getElementById('qr-amount');

            // Update displayed amount
            qrAmountEl.innerText = amount.toFixed(2);

            // Generate UPI deep link
            const upiLink = `upi://pay?pa=${encodeURIComponent(UPI_ID)}&pn=${encodeURIComponent(PAYEE_NAME)}&am=${amount.toFixed(2)}&cu=INR`;

            // Clear previous QR code
            qrContainer.innerHTML = '';

            // Generate new QR code
            if (typeof QRCode !== 'undefined') {
                qrCodeInstance = new QRCode(qrContainer, {
                    text: upiLink,
                    width: 180,
                    height: 180,
                    colorDark: '#1e293b',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }
        }

        function toggleUPIQR() {
            const paymentMethod = document.getElementById('payment_method').value;
            const qrContainer = document.getElementById('upi-qr-container');

            if (paymentMethod === 'upi') {
                qrContainer.classList.remove('hidden');
                const finalTotal = parseFloat(document.getElementById('grandTotal').innerText) || 0;
                generateUPIQR(finalTotal);
            } else {
                qrContainer.classList.add('hidden');
            }
        }

        // Listen for payment method change
        document.getElementById('payment_method').addEventListener('change', toggleUPIQR);

        // Override updateTotals to also update QR code
        const originalUpdateTotals = updateTotals;
        updateTotals = function () {
            originalUpdateTotals();
            // Update QR if UPI is selected
            if (document.getElementById('payment_method').value === 'upi') {
                const finalTotal = parseFloat(document.getElementById('grandTotal').innerText) || 0;
                generateUPIQR(finalTotal);
            }
        };
    </script>
@endsection