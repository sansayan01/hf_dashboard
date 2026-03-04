@extends('layouts.app')

@section('header_title', 'Edit Expense')

@section('css')
    <style>
        .form-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04), 0 6px 16px rgba(0, 0, 0, 0.04);
        }

        .dark .form-card {
            background: #1a1f2e;
            border-color: rgba(255, 255, 255, 0.06);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2), 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        .form-section {
            padding: 28px 32px;
            border-bottom: 1px solid #f1f5f9;
        }

        .dark .form-section {
            border-bottom-color: rgba(255, 255, 255, 0.04);
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

        .field-label .req {
            color: #e11d48;
        }

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
            background: rgba(15, 23, 42, 0.5);
            border-color: rgba(255, 255, 255, 0.08);
            color: #e2e8f0;
        }

        .field-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .field-input:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
        }

        .dark .field-input:focus {
            border-color: #818cf8;
            background: rgba(15, 23, 42, 0.8);
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.1);
        }

        .field-input.has-error {
            border-color: #e11d48;
            box-shadow: 0 0 0 3px rgba(225, 29, 72, 0.06);
        }

        .receipt-badge {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .dark .receipt-badge {
            background: rgba(34, 197, 94, 0.06);
            border-color: rgba(34, 197, 94, 0.15);
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
            background: rgba(15, 23, 42, 0.3);
            border-color: rgba(255, 255, 255, 0.08);
        }

        .dropzone:hover {
            border-color: #6366f1;
            background: #f5f3ff;
        }

        .dark .dropzone:hover {
            border-color: #818cf8;
            background: rgba(99, 102, 241, 0.05);
        }

        .dropzone .dz-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #eef2ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6366f1;
            transition: transform 0.3s ease;
        }

        .dark .dropzone .dz-icon {
            background: rgba(99, 102, 241, 0.15);
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
            background: rgba(15, 23, 42, 0.4);
            border-top-color: rgba(255, 255, 255, 0.04);
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
            background: rgba(30, 41, 59, 0.6);
            border-color: rgba(255, 255, 255, 0.08);
            color: #94a3b8;
        }

        .btn-cancel:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        .btn-save {
            padding: 10px 24px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            background: #4f46e5;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(79, 70, 229, 0.3);
        }

        .btn-save:hover {
            background: #4338ca;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
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
            border-color: rgba(255, 255, 255, 0.06);
            color: #94a3b8;
        }

        .back-btn:hover {
            border-color: #6366f1;
            color: #6366f1;
        }
    </style>
@endsection

@section('content')
    <div class="max-w-2xl mx-auto pb-12">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('expenses.index') }}" class="back-btn">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Edit Expense</h2>
                <p class="text-xs text-slate-400 font-medium">{{ $expense->title }}</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="form-card">
            <form action="{{ route('expenses.update', $expense) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Section 1: Basic Info --}}
                <div class="form-section">
                    <div class="section-title">
                        <span class="icon-wrap bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </span>
                        Basic Information
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="field-label">Expense Title <span class="req">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $expense->title) }}" required
                                class="field-input @error('title') has-error @enderror">
                            @error('title')<p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="field-label">Amount (₹) <span class="req">*</span></label>
                            <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" required
                                step="0.01" min="0.01" class="field-input @error('amount') has-error @enderror"
                                style="font-variant-numeric:tabular-nums">
                            @error('amount')<p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- Section 2: Classification --}}
                <div class="form-section">
                    <div class="section-title">
                        <span class="icon-wrap bg-violet-50 dark:bg-violet-500/10 text-violet-600 dark:text-violet-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </span>
                        Classification
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="field-label">Category <span class="req">*</span></label>
                            <select name="category" required class="field-input @error('category') has-error @enderror">
                                <option value="">Select Category</option>
                                @foreach(\App\Models\Expense::CATEGORIES as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $expense->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                            @error('category')<p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="field-label">Date <span class="req">*</span></label>
                            <input type="date" name="expense_date"
                                value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required
                                max="{{ date('Y-m-d') }}" class="field-input @error('expense_date') has-error @enderror"
                                style="font-variant-numeric:tabular-nums">
                            @error('expense_date')<p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Section 3: Payment --}}
                <div class="form-section">
                    <div class="section-title">
                        <span class="icon-wrap bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </span>
                        Payment Details
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="field-label">Payment Method <span class="req">*</span></label>
                            <select name="payment_method" required
                                class="field-input @error('payment_method') has-error @enderror">
                                @foreach(\App\Models\Expense::PAYMENT_METHODS as $key => $label)
                                    <option value="{{ $key }}" {{ old('payment_method', $expense->payment_method) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('payment_method')<p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="field-label">Reference Number</label>
                            <input type="text" name="reference_number"
                                value="{{ old('reference_number', $expense->reference_number) }}"
                                placeholder="UPI / Cheque / Transaction ref" class="field-input">
                        </div>
                    </div>
                </div>

                {{-- Section 4: Details & Attachments --}}
                <div class="form-section">
                    <div class="section-title">
                        <span class="icon-wrap bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </span>
                        Details & Attachments
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="field-label">Description</label>
                            <textarea name="description" rows="2" placeholder="Brief description..."
                                class="field-input resize-none">{{ old('description', $expense->description) }}</textarea>
                        </div>

                        <div>
                            <label class="field-label">Receipt</label>

                            @if($expense->receipt_path)
                                <div class="receipt-badge">
                                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor"
                                        stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">Receipt
                                        attached</span>
                                    <a href="{{ route('storage.bridge', ['path' => $expense->receipt_path]) }}" target="_blank"
                                        class="ml-auto text-xs font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">View
                                        →</a>
                                </div>
                            @endif

                            <input type="file" name="receipt" accept=".jpg,.jpeg,.png,.pdf" id="receipt-input"
                                class="hidden">
                            <label for="receipt-input" class="dropzone">
                                <span class="dz-icon">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                </span>
                                <span id="receipt-label" class="text-sm font-medium text-slate-500 dark:text-slate-400">
                                    {{ $expense->receipt_path ? 'Replace receipt' : 'Upload receipt' }}
                                </span>
                                <span class="text-[11px] text-slate-400">JPG, PNG, PDF — Max 5MB</span>
                            </label>
                            @error('receipt')<p class="text-xs text-rose-500 font-semibold mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="field-label">Additional Notes</label>
                            <textarea name="notes" rows="2" placeholder="Any additional notes..."
                                class="field-input resize-none">{{ old('notes', $expense->notes) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="form-footer">
                    <a href="{{ route('expenses.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">Update Expense</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('receipt-input').addEventListener('change', function () {
            const label = document.getElementById('receipt-label');
            if (this.files && this.files[0]) {
                label.textContent = this.files[0].name;
                label.style.color = '#4f46e5';
                label.style.fontWeight = '600';
            }
        });
    </script>
@endsection