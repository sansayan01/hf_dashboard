

<?php $__env->startSection('title', 'Record Pathology Test'); ?>
<?php $__env->startSection('header_title', 'Pathology Services'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Patient Info Card -->
        <div
            class="glass bg-white dark:bg-darkbg/40 rounded-3xl p-6 border border-slate-200/10 dark:border-white/5 shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-10">
                <svg class="w-32 h-32 text-rose-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2L4.5 20.29l.71.71L12 18l6.79 3 .71-.71L12 2z" />
                </svg>
            </div>
            <div class="relative z-10 flex items-start gap-6">
                <div
                    class="w-16 h-16 bg-rose-500 text-white rounded-2xl flex items-center justify-center text-2xl font-black">
                    <?php echo e(substr($patient->full_name, 0, 1)); ?>

                </div>
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Recording Test For</p>
                    <h2 class="text-3xl font-black text-slate-800 dark:text-white leading-none mb-2">
                        <?php echo e($patient->full_name); ?>

                    </h2>
                    <div class="flex items-center gap-3 text-sm font-bold text-slate-500">
                        <span class="bg-slate-100 dark:bg-white/5 px-2 py-0.5 rounded"><?php echo e($patient->patient_id); ?></span>
                        <span>•</span>
                        <span><?php echo e(ucfirst($patient->gender)); ?></span>
                        <span>•</span>
                        <span><?php echo e($patient->age); ?> Years</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pathology Form -->
        <div
            class="glass bg-white dark:bg-darkbg/40 rounded-3xl p-8 border border-slate-200/10 dark:border-white/5 shadow-xl">
            <form id="pathologyForm" action="<?php echo e(route('pathology.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="patient_id" value="<?php echo e($patient->id); ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Select Camp -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Recording
                            From Camp / Warehouse</label>
                        <div class="relative">
                            <?php if($camps->count() === 1): ?>
                                <input type="hidden" name="camp_id" value="<?php echo e($camps->first()->id); ?>" id="camp_id">
                                <div
                                    class="w-full h-12 px-4 rounded-xl border border-slate-200/50 dark:border-white/10 bg-slate-100/50 dark:bg-white/5 text-slate-500 flex items-center text-sm font-bold opacity-80 cursor-not-allowed">
                                    <?php echo e($camps->first()->name); ?> (<?php echo e($camps->first()->location ?? 'No Loc'); ?>)
                                </div>
                            <?php else: ?>
                                <select name="camp_id" id="camp_id" required
                                    class="w-full h-12 pl-4 pr-10 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/5 text-sm font-bold focus:ring-2 focus:ring-rose-500/50 outline-none transition appearance-none">
                                    <option value="" disabled selected>Select Camp...</option>
                                    <?php $__currentLoopData = $camps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $camp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($camp->id); ?>">
                                            <?php echo e($camp->name); ?> (<?php echo e($camp->location ?? 'No Loc'); ?>)
                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            <?php endif; ?>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Test Date -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Test
                            Date</label>
                        <input type="date" name="date" required
                            class="w-full h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/5 text-sm font-bold focus:ring-2 focus:ring-rose-500/50 outline-none transition"
                            value="<?php echo e(date('Y-m-d')); ?>">
                    </div>

                    <!-- Test Name -->
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Pathology
                            Test Name</label>
                        <input type="text" name="test_name" required
                            class="w-full h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/5 text-sm font-bold focus:ring-2 focus:ring-rose-500/50 outline-none transition"
                            placeholder="e.g. Complete Blood Count (CBC), Lipid Profile...">
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Amount
                            (₹)</label>
                        <input type="number" step="0.01" name="amount" id="amount" required
                            class="w-full h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/5 text-sm font-bold focus:ring-2 focus:ring-rose-500/50 outline-none transition"
                            placeholder="0.00" oninput="calculateTotal()">
                    </div>

                    <!-- Discount -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Discount
                            (%)</label>
                        <input type="number" step="0.1" name="discount_percentage" id="discount"
                            class="w-full h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/5 text-sm font-bold focus:ring-2 focus:ring-rose-500/50 outline-none transition"
                            value="0" oninput="calculateTotal()">
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Payment
                            List</label>
                        <select name="payment_method" id="payment_method"
                            class="w-full h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/5 text-sm font-bold focus:ring-2 focus:ring-rose-500/50 outline-none transition appearance-none">
                            <option value="cash">💵 Cash Payment</option>
                            <option value="upi">📱 UPI / Online Transfer</option>
                        </select>
                    </div>

                    <!-- Amount Paid -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Amount
                            Paid (₹)</label>
                        <input type="number" step="0.01" name="amount_paid" id="amount_paid"
                            class="w-full h-12 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-white/5 text-sm font-bold focus:ring-2 focus:ring-rose-500/50 outline-none transition"
                            placeholder="0.00" oninput="calculateTotal()">
                    </div>

                </div>

                <!-- Summary -->
                <div class="mb-8 p-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/5">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Subtotal</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">₹<span
                                    id="subtotalDisplay">0.00</span></span>
                        </div>
                        <div class="flex justify-between items-center mb-2 text-right">
                            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-500">Discount
                                Applied</span>
                            <span class="font-bold text-emerald-500">-₹<span id="discountDisplay">0.00</span></span>
                        </div>
                    </div>

                    <div
                        class="pt-4 border-t border-slate-200 dark:border-white/10 flex justify-between items-center bg-white/40 dark:bg-black/20 p-4 rounded-xl">
                        <div>
                            <span class="text-xs font-black uppercase tracking-widest text-slate-500 block">Total
                                Payable</span>
                            <span class="text-2xl font-black text-rose-500">₹<span id="totalDisplay">0.00</span></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-black uppercase tracking-widest text-rose-500 block">Due
                                Amount</span>
                            <span class="text-xl font-bold text-rose-500">₹<span id="dueDisplay">0.00</span></span>
                        </div>
                    </div>
                </div>

                <!-- UPI QR Code Container -->
                <div id="upi-qr-container"
                    class="hidden mt-6 p-6 bg-white dark:bg-slate-800 rounded-xl border-2 border-dashed border-rose-500/30">
                    <div class="text-center">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Scan to Pay via
                            UPI</p>
                        <div id="qr-code" class="inline-block p-4 bg-white rounded-xl shadow-lg"></div>
                        <div class="mt-4 space-y-1">
                            <p class="text-xs text-slate-500">UPI ID: <span
                                    class="font-bold text-slate-700 dark:text-slate-300">9735563157-4@ybl</span></p>
                            <p class="text-xs text-slate-500">Amount: <span class="font-black text-rose-500 text-lg">₹<span
                                        id="qr-amount">0.00</span></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="<?php echo e(route('patients.show', $patient->id)); ?>"
                        class="px-6 py-3 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-500 font-bold hover:bg-slate-200 transition">Cancel</a>
                    <button type="submit" id="submitBtn"
                        class="px-8 py-3 rounded-xl bg-rose-500 text-white font-black uppercase tracking-widest shadow-lg shadow-rose-500/20 hover:scale-105 active:scale-95 transition">
                        Save Test Record
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- QRCode.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        const UPI_ID = '9735563157-4@ybl';
        const PAYEE_NAME = 'Humanity Foundation';
        let qrCodeInstance = null;
        let isManualPayment = false;

        function calculateTotal() {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const discountPerc = parseFloat(document.getElementById('discount').value) || 0;
            const amountPaidInput = document.getElementById('amount_paid');

            const discountAmt = (amount * discountPerc) / 100;
            const finalTotal = amount - discountAmt;

            if (!isManualPayment) {
                amountPaidInput.value = finalTotal.toFixed(2);
            }

            const amountPaid = parseFloat(amountPaidInput.value) || 0;
            const dueAmount = Math.max(0, finalTotal - amountPaid);

            document.getElementById('subtotalDisplay').innerText = amount.toFixed(2);
            document.getElementById('discountDisplay').innerText = discountAmt.toFixed(2);
            document.getElementById('totalDisplay').innerText = finalTotal.toFixed(2);
            document.getElementById('dueDisplay').innerText = dueAmount.toFixed(2);

            // Update QR if UPI is selected
            if (document.getElementById('payment_method').value === 'upi' && amountPaid > 0) {
                generateUPIQR(amountPaid);
            }
        }

        document.getElementById('amount_paid').addEventListener('input', () => {
            isManualPayment = true;
            calculateTotal();
        });

        function generateUPIQR(amount) {
            const qrContainer = document.getElementById('qr-code');
            const qrAmountEl = document.getElementById('qr-amount');
            qrAmountEl.innerText = amount.toFixed(2);

            const upiLink = `upi://pay?pa=${encodeURIComponent(UPI_ID)}&pn=${encodeURIComponent(PAYEE_NAME)}&am=${amount.toFixed(2)}&cu=INR`;
            qrContainer.innerHTML = '';

            if (typeof QRCode !== 'undefined' && amount > 0) {
                qrCodeInstance = new QRCode(qrContainer, {
                    text: upiLink,
                    width: 180,
                    height: 180,
                    colorDark: '#F43F5E',
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
                calculateTotal();
            } else {
                qrContainer.classList.add('hidden');
            }
        }

        document.getElementById('payment_method').addEventListener('change', toggleUPIQR);

        document.getElementById('pathologyForm').addEventListener('submit', function (e) {
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
                        Swal.fire({
                            title: 'Success!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#F43F5E'
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                        btn.disabled = false;
                        btn.innerText = 'Save Test Record';
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('System Error. Please try again.');
                    btn.disabled = false;
                    btn.innerText = 'Save Test Record';
                });
        });

        // Initialize display
        calculateTotal();
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views/pathology/create.blade.php ENDPATH**/ ?>