

<?php $__env->startSection('header_title', 'Add Income'); ?>

<?php $__env->startSection('css'); ?>
<style>
    .form-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 6px 16px rgba(0,0,0,0.04);
        transition: box-shadow 0.3s ease;
    }
    .dark .form-card {
        background: #1a1f2e;
        border-color: rgba(255,255,255,0.06);
        box-shadow: 0 1px 3px rgba(0,0,0,0.2), 0 8px 24px rgba(0,0,0,0.2);
    }

    .form-section {
        padding: 28px 32px;
        border-bottom: 1px solid #f1f5f9;
    }
    .dark .form-section {
        border-bottom-color: rgba(255,255,255,0.04);
    }
    .form-section:last-of-type {
        border-bottom: none;
    }

    .section-title {
        font-size: 13px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .dark .section-title {
        color: #e2e8f0;
    }
    .section-title .icon-wrap {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .field-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 6px;
        letter-spacing: 0.01em;
    }
    .dark .field-label {
        color: #94a3b8;
    }
    .field-label .req { color: #e11d48; }

    .field-input {
        width: 100%;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 500;
        color: #1e293b;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        outline: none;
        transition: all 0.2s ease;
    }
    .dark .field-input {
        background: rgba(15,23,42,0.5);
        border-color: rgba(255,255,255,0.08);
        color: #e2e8f0;
    }
    .field-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }
    .field-input:focus {
        border-color: #059669;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(5,150,105,0.08);
    }
    .dark .field-input:focus {
        border-color: #34d399;
        background: rgba(15,23,42,0.8);
        box-shadow: 0 0 0 3px rgba(52,211,153,0.1);
    }
    .field-input.has-error {
        border-color: #e11d48;
        box-shadow: 0 0 0 3px rgba(225,29,72,0.06);
    }

    .dropzone {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 10px;
        padding: 32px 24px;
        background: #fafbfc;
        border: 1.5px dashed #d1d5db;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.25s ease;
    }
    .dark .dropzone {
        background: rgba(15,23,42,0.3);
        border-color: rgba(255,255,255,0.08);
    }
    .dropzone:hover {
        border-color: #059669;
        background: #ecfdf5;
    }
    .dark .dropzone:hover {
        border-color: #34d399;
        background: rgba(5,150,105,0.05);
    }
    .dropzone .dz-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #ecfdf5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #059669;
        transition: transform 0.3s ease;
    }
    .dark .dropzone .dz-icon {
        background: rgba(5,150,105,0.15);
    }
    .dropzone:hover .dz-icon {
        transform: scale(1.1);
    }

    .form-footer {
        padding: 20px 32px;
        background: #fafbfc;
        border-top: 1px solid #f1f5f9;
        border-radius: 0 0 16px 16px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
    }
    .dark .form-footer {
        background: rgba(15,23,42,0.4);
        border-top-color: rgba(255,255,255,0.04);
    }

    .btn-cancel {
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .dark .btn-cancel {
        background: rgba(30,41,59,0.6);
        border-color: rgba(255,255,255,0.08);
        color: #94a3b8;
    }
    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    .dark .btn-cancel:hover {
        background: rgba(30,41,59,0.8);
    }

    .btn-save {
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        color: #fff;
        background: #059669;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(5,150,105,0.3);
    }
    .btn-save:hover {
        background: #047857;
        box-shadow: 0 4px 12px rgba(5,150,105,0.35);
        transform: translateY(-1px);
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        color: #64748b;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .dark .back-btn {
        background: #1a1f2e;
        border-color: rgba(255,255,255,0.06);
        color: #94a3b8;
    }
    .back-btn:hover {
        border-color: #059669;
        color: #059669;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-2xl mx-auto pb-12">

    
    <div class="flex items-center gap-3 mb-6">
        <a href="<?php echo e(route('incomes.index')); ?>" class="back-btn">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">New Income</h2>
            <p class="text-xs text-slate-400 font-medium">Record an incoming fund or revenue</p>
        </div>
    </div>

    
    <div class="form-card">
        <form action="<?php echo e(route('incomes.store')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>

            
            <div class="form-section">
                <div class="section-title">
                    <span class="icon-wrap bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </span>
                    Basic Information
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="field-label">Income Title <span class="req">*</span></label>
                        <input type="text" name="title" value="<?php echo e(old('title')); ?>" required placeholder="e.g. Monthly Donation"
                            class="field-input <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-500 font-semibold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="field-label">Amount (₹) <span class="req">*</span></label>
                        <input type="number" name="amount" value="<?php echo e(old('amount')); ?>" required step="0.01" min="0.01" placeholder="0.00"
                            class="field-input <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="font-variant-numeric:tabular-nums">
                        <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-500 font-semibold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="form-section">
                <div class="section-title">
                    <span class="icon-wrap bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </span>
                    Classification
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="field-label">Category <span class="req">*</span></label>
                        <select name="category" required class="field-input <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">Select Category</option>
                            <?php $__currentLoopData = \App\Models\Income::CATEGORIES; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat); ?>" <?php echo e(old('category') == $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-500 font-semibold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="field-label">Date <span class="req">*</span></label>
                        <input type="date" name="income_date" value="<?php echo e(old('income_date', date('Y-m-d'))); ?>" required max="<?php echo e(date('Y-m-d')); ?>"
                            class="field-input <?php $__errorArgs = ['income_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" style="font-variant-numeric:tabular-nums">
                        <?php $__errorArgs = ['income_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-500 font-semibold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="form-section">
                <div class="section-title">
                    <span class="icon-wrap bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </span>
                    Payment Details
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="field-label">Payment Method <span class="req">*</span></label>
                        <select name="payment_method" required class="field-input <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php $__currentLoopData = \App\Models\Income::PAYMENT_METHODS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(old('payment_method', 'cash') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-500 font-semibold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="field-label">Reference Number</label>
                        <input type="text" name="reference_number" value="<?php echo e(old('reference_number')); ?>" placeholder="UPI / Cheque / Transaction ref"
                            class="field-input">
                    </div>
                    <div>
                        <label class="field-label">Received By <span class="req">*</span></label>
                        <input type="text" name="received_by" value="<?php echo e(old('received_by')); ?>" required list="received-by-list"
                            placeholder="Type or select a name..."
                            class="field-input <?php $__errorArgs = ['received_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <datalist id="received-by-list">
                            <?php $__currentLoopData = \App\Models\Income::RECEIVED_BY_OPTIONS; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($option); ?>">
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </datalist>
                        <?php $__errorArgs = ['received_by'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-500 font-semibold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div>
                        <label class="field-label">Source</label>
                        <input type="text" name="source" value="<?php echo e(old('source')); ?>" placeholder="Who or where did the funds come from?"
                            class="field-input <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> has-error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php $__errorArgs = ['source'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-500 font-semibold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>

            
            <div class="form-section">
                <div class="section-title">
                    <span class="icon-wrap bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </span>
                    Details & Attachments
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="field-label">Description</label>
                        <textarea name="description" rows="2" placeholder="Brief description of the income..."
                            class="field-input resize-none"><?php echo e(old('description')); ?></textarea>
                    </div>

                    <div>
                        <label class="field-label">Receipt</label>
                        <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" id="receipt-input" class="hidden">
                        <label for="receipt-input" class="dropzone">
                            <span class="dz-icon">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                            </span>
                            <span id="receipt-label" class="text-sm font-medium text-slate-500 dark:text-slate-400 text-center">
                                Click to upload receipt
                            </span>
                            <span class="text-[11px] text-slate-400">JPG, PNG, PDF — Max 5MB</span>
                        </label>
                        <?php $__errorArgs = ['receipt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-xs text-rose-500 font-semibold mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div>
                        <label class="field-label">Additional Notes</label>
                        <textarea name="notes" rows="2" placeholder="Any additional notes or remarks..."
                            class="field-input resize-none"><?php echo e(old('notes')); ?></textarea>
                    </div>
                </div>
            </div>

            
            <div class="form-footer">
                <a href="<?php echo e(route('incomes.index')); ?>" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-save">Save Income</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
    document.getElementById('receipt-input').addEventListener('change', function() {
        const label = document.getElementById('receipt-label');
        if (this.files && this.files[0]) {
            label.textContent = this.files[0].name;
            label.style.color = '#059669';
            label.style.fontWeight = '600';
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\HF\resources\views\incomes\create.blade.php ENDPATH**/ ?>