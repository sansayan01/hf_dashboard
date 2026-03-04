<?php $__env->startSection('title', 'Invoice #' . $distribution->id); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto py-16 pb-32 px-6 md:px-20">
        <!-- Action Bar -->
        <div class="flex items-center justify-between mb-8 no-print">
            <a href="<?php echo e(route('patients.index')); ?>"
                class="flex items-center space-x-2 text-slate-500 hover:text-slate-700 dark:hover:text-white transition font-bold">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Registry</span>
            </a>
            <button id="download-pdf"
                class="px-6 py-2.5 bg-accent text-white font-black uppercase tracking-widest rounded-xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition flex items-center space-x-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                <span>Download PDF</span>
            </button>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

        <!-- Invoice Card -->
        <div id="invoice-card" class="bg-white py-12 px-10 md:px-16 rounded-3xl shadow-xl border border-slate-100 print:shadow-none print:border-0 print:p-0">
            <!-- Header -->
            <div class="flex justify-between items-start mb-12 border-b border-slate-100 pb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">INVOICE</h1>
                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs mt-1">Humanity Foundation</p>
                </div>
                <div class="text-right">
                    <div class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Invoice ID</div>
                    <div class="text-xl font-bold text-slate-800">
                        #INV-<?php echo e(str_pad($distribution->id, 6, '0', STR_PAD_LEFT)); ?></div>
                    <div class="text-xs font-medium text-slate-500 mt-1">
                        <?php echo e($distribution->created_at->format('M d, Y h:i A')); ?></div>
                </div>
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-2 gap-12 mb-12">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Bill To (Patient)</p>
                    <h3 class="text-lg font-bold text-slate-800"><?php echo e($distribution->patient->full_name); ?></h3>
                    <p class="text-sm text-slate-500 mt-1"><?php echo e($distribution->patient->patient_id); ?></p>
                    <p class="text-sm text-slate-500 mt-1"><?php echo e($distribution->patient->address); ?></p>
                    <?php if($distribution->patient->phone_number): ?>
                        <p class="text-sm text-slate-500 mt-1"><?php echo e($distribution->patient->phone_number); ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Dispensed From</p>
                    <h3 class="text-lg font-bold text-slate-800"><?php echo e($distribution->camp->name); ?></h3>
                    <p class="text-sm text-slate-500 mt-1"><?php echo e($distribution->camp->location); ?></p>
                    <div class="mt-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Pharmacist</p>
                        <p class="text-sm font-bold text-slate-700">
                            <?php echo e($distribution->pharmacist->profile->full_name ?? $distribution->pharmacist->employee_id); ?>

                        </p>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <table class="w-full text-left mb-8">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine</th>
                        <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 text-center">Qty
                        </th>
                        <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Unit
                            Price</th>
                        <th class="py-3 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Total
                        </th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-50">
                    <?php $__currentLoopData = $distribution->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="py-4 font-bold text-slate-700">
                                <?php echo e($item->medicine->name); ?>

                                <?php if($item->medicine->dosage): ?>
                                    <span class="text-xs font-normal text-slate-400 ml-1">(<?php echo e($item->medicine->dosage); ?>)</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 text-center text-slate-600"><?php echo e($item->quantity); ?></td>
                            <td class="py-4 text-right text-slate-600"><?php echo e(number_format($item->unit_price, 2)); ?></td>
                            <td class="py-4 text-right font-bold text-slate-800"><?php echo e(number_format($item->total_price, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

            <!-- Total -->
            <div class="border-t border-slate-100 pt-6">
                <div class="flex justify-between items-start">
                    <div class="text-xs text-slate-400 font-medium max-w-xs mt-2">
                        This is a computer generated receipt. Signature is not required.
                        <div class="mt-4 p-3 bg-slate-50 rounded-xl text-[10px] text-slate-500 leading-relaxed">
                            <span class="font-bold text-slate-700">Discount Policy:</span><br>
                            • Total > ₹300: 20% Discount<br>
                            • Total ≤ ₹300: 18% Discount
                        </div>
                    </div>
                    <div class="text-right space-y-2">
                        <div class="flex justify-end items-center space-x-8">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Subtotal</p>
                            <p class="text-sm font-bold text-slate-700">₹<?php echo e(number_format($distribution->total_amount, 2)); ?></p>
                        </div>
                        <div class="flex justify-end items-center space-x-8">
                            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-500">Discount (<?php echo e($distribution->discount_percentage); ?>%)</p>
                            <p class="text-sm font-bold text-emerald-500">-₹<?php echo e(number_format($distribution->discount_amount, 2)); ?></p>
                        </div>
                        <div class="pt-2">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Grand Total</p>
                            <p class="text-4xl font-black text-accent">₹<?php echo e(number_format($distribution->final_amount, 2)); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            
            <?php if($distribution->due_amount > 0): ?>
                <div class="mt-6 border-2 border-dashed border-red-200 rounded-2xl p-6 bg-red-50/50">
                    <div class="flex items-center space-x-2 mb-4">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h4 class="text-sm font-black text-red-600 uppercase tracking-widest">Pending Dues</h4>
                    </div>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Grand Total</span>
                            <span class="text-lg font-black text-slate-800">₹<?php echo e(number_format($distribution->final_amount, 2)); ?></span>
                        </div>
                        <div class="bg-emerald-50 rounded-xl p-4 shadow-sm border border-emerald-100">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-emerald-500 mb-1">Amount Paid</span>
                            <span class="text-lg font-black text-emerald-600">₹<?php echo e(number_format($distribution->amount_paid, 2)); ?></span>
                        </div>
                        <div class="bg-red-50 rounded-xl p-4 shadow-sm border border-red-200">
                            <span class="block text-[10px] font-black uppercase tracking-widest text-red-500 mb-1">Balance Due</span>
                            <span class="text-xl font-black text-red-600">₹<?php echo e(number_format($distribution->due_amount, 2)); ?></span>
                        </div>
                    </div>
                    <p class="text-[10px] text-red-400 font-bold mt-3 text-center uppercase tracking-wider">
                        This invoice has an outstanding balance. Please clear the dues at the pharmacy counter.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            .max-w-4xl,
            .max-w-4xl * {
                visibility: visible;
            }

            .max-w-4xl {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .shadow-xl, .shadow-lg {
                box-shadow: none !important;
            }

            .bg-white {
                border: 0 !important;
            }

            /* Ensure text colors are black for printing */
            .text-slate-800, .text-slate-700, .text-slate-600 {
                color: #000 !important;
            }
        }
    </style>

    <script>
        document.getElementById('download-pdf').addEventListener('click', function() {
            const element = document.getElementById('invoice-card');
            const opt = {
                margin: 0.5,
                filename: 'Invoice_<?php echo e(str_pad($distribution->id, 6, "0", STR_PAD_LEFT)); ?>.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true },
                jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
            };

            // New Promise-based usage:
            html2pdf().set(opt).from(element).save();
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\medicine\invoice.blade.php ENDPATH**/ ?>