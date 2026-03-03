@extends('layouts.app')

@section('title', 'Membership Registration')
@section('header_title', 'Upgrade to Premium Membership')

@section('content')
    <div class="max-w-4xl mx-auto pb-20">
        <!-- Patient Info Card (Read Only) -->
        <div
            class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden mb-8">
            <div
                class="p-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-xl text-slate-800 dark:text-white">Patient Profile</h3>
                    <p class="text-sm text-slate-500 mt-1 font-medium">Verify details before upgrading to membership.</p>
                </div>
                <div
                    class="px-4 py-2 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 rounded-xl text-xs font-black uppercase tracking-widest">
                    ID: {{ $patient->patient_id }}
                </div>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Full Name</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-white">{{ $patient->full_name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Phone</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-white">{{ $patient->phone_number }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Age / Gender</p>
                    <p class="text-sm font-bold text-slate-700 dark:text-white">{{ $patient->age }} Yrs /
                        {{ ucfirst($patient->gender) }}
                    </p>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-amber-500/5">
                <h3 class="font-black text-xl text-slate-800 dark:text-white flex items-center">
                    <svg class="w-6 h-6 text-amber-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    Membership Registration Form
                </h3>
                <p class="text-sm text-slate-500 mt-1 font-medium italic">Complete the following details to register this
                    patient as a premium member.</p>
            </div>

            <form action="{{ route('patients.membership.register', $patient->id) }}" method="POST"
                enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf

                <!-- Section: Personal Info (Updates existing if changed) -->
                <div class="space-y-6">
                    <div
                        class="flex items-center space-x-4 p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                        <div class="h-6 w-1.5 bg-amber-500 rounded-full shadow-[0_0_12px_rgba(245,158,11,0.5)]"></div>
                        <h4
                            class="font-black text-lg uppercase tracking-[0.2em] text-slate-800 dark:text-white underline underline-offset-[12px] decoration-amber-500/40 decoration-4">
                            verification & updates</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Confirm Full
                                Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name', $patient->full_name) }}" required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Confirm
                                Phone</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number', $patient->phone_number) }}"
                                required maxlength="10"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Age</label>
                            <input type="number" name="age" value="{{ old('age', $patient->age) }}" required min="1"
                                max="120"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Gender</label>
                            <select name="gender" required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="male" {{ old('gender', $patient->gender) == 'male' ? 'selected' : '' }}>Male
                                </option>
                                <option value="female" {{ old('gender', $patient->gender) == 'female' ? 'selected' : '' }}>
                                    Female</option>
                                <option value="other" {{ old('gender', $patient->gender) == 'other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section: Membership Purpose -->
                <div class="space-y-8 pt-16 mt-8 border-t border-slate-100 dark:border-white/5">
                    <div
                        class="flex items-center space-x-4 p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                        <div class="h-6 w-1.5 bg-indigo-500 rounded-full shadow-[0_0_12px_rgba(99,102,241,0.4)]"></div>
                        <h4
                            class="font-black text-lg uppercase tracking-[0.2em] text-slate-800 dark:text-white underline underline-offset-[12px] decoration-indigo-500/40 decoration-4">
                            premium details</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Father/Husband
                                Name <span class="text-danger">*</span></label>
                            <input type="text" name="relative_name"
                                value="{{ old('relative_name', $patient->relative_name) }}" required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="Relative's Name"
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Blood
                                Group</label>
                            <select name="blood_group"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="">Select Blood Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $bg)
                                    <option value="{{ $bg }}" {{ old('blood_group', $patient->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Aadhar
                                Number <span class="text-danger">*</span></label>
                            <input type="text" name="aadhar_number"
                                value="{{ old('aadhar_number', $patient->aadhar_number) }}" maxlength="12" required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="12-digit Aadhar"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">PAN
                                Number</label>
                            <input type="text" name="pan_number" value="{{ old('pan_number', $patient->pan_number) }}"
                                maxlength="10"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white uppercase"
                                placeholder="ABCDE1234F" oninput="validatePAN(this)">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">District <span
                                    class="text-danger">*</span></label>
                            <select id="district-select" name="district" required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="">Select District</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Block <span
                                    class="text-danger">*</span></label>
                            <select id="block-select" name="block" disabled required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none disabled:opacity-50">
                                <option value="">Select Block</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">GP <span
                                    class="text-danger">*</span></label>
                            <select id="gp-select" name="gp" disabled required
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none disabled:opacity-50">
                                <option value="">Select GP</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full
                            Address</label>
                        <textarea name="address" rows="3" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                            oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">{{ old('address', $patient->address) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">PIN
                                Code <span class="text-danger">*</span></label>
                            <input type="text" name="pin" value="{{ old('pin', $patient->pin) }}" required maxlength="6"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Insurance
                                Requirement</label>
                            <select name="insurance_loan_req"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="No" {{ old('insurance_loan_req', $patient->insurance_loan_req) == 'No' ? 'selected' : '' }}>No</option>
                                <option value="Yes" {{ old('insurance_loan_req', $patient->insurance_loan_req) == 'Yes' ? 'selected' : '' }}>Yes</option>
                            </select>
                        </div>
                    </div>

                </div>

                <!-- Section: Payment Details (Matching User Registration Design) -->
                <div class="space-y-8 pt-16 mt-8 border-t border-slate-100 dark:border-white/5">
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full shadow-[0_0_12px_rgba(60,80,224,0.4)]"></div>
                        <h4 class="font-bold text-slate-800 dark:text-white uppercase tracking-wider text-xs">Joining
                            Donation & Payment</h4>
                    </div>

                    <!-- Required Donation Card -->
                    <div
                        class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2rem] p-10 mb-6 text-center">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Required Joining
                            Donation</p>
                        <h3 class="text-6xl font-black text-slate-800 dark:text-white">₹<span
                                id="donation-amount-display">200</span></h3>
                        <p class="text-xs text-slate-400 mt-4 italic">* This is a one-time joining donation for
                            premium membership.</p>

                        <!-- Hidden Logic Inputs -->
                        <input type="hidden" name="membership_fee" value="200">
                        <input type="hidden" name="discount_percentage" id="discount_percentage" value="0">
                        <input type="hidden" name="discount_amount" id="discount_amount_input" value="0">
                        <input type="hidden" name="final_amount" id="final_amount_input" value="200">
                        <input type="hidden" name="amount_paid" id="amount_paid" value="200">
                        <input type="hidden" name="due_amount" id="due_amount_input" value="0">
                    </div>

                    <!-- Payment Mode Selection -->
                    <div
                        class="mb-8 p-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-200 dark:border-white/5">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Payment
                            Mode</label>
                        <div class="relative">
                            <select id="payment-mode-select"
                                class="w-full h-12 pl-4 pr-10 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-bold focus:ring-2 focus:ring-accent/50 outline-none transition appearance-none">
                                <option value="" disabled>Choose Payment Mode...</option>
                                <option value="upi_app">📱 Pay via UPI App</option>
                                <option value="upi_qr" selected>📷 Scan QR Code</option>
                                <option value="coupon">🎟️ Redeem Coupon Code</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- UPI Payment Card -->
                    <div id="upi-payment-section"
                        class="hidden bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2rem] p-8 mb-8">

                        <!-- UPI App Container -->
                        <div id="upi-app-container" class="hidden">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                                <div>
                                    <h5 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-wider">
                                        Pay via UPI App</h5>
                                    <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">Click the
                                        button below to open your UPI app</p>
                                </div>

                                <a id="upi-pay-link" href="upi://pay?pa=9735563157-4@ybl&pn=MembershipPayment&am=200&cu=INR"
                                    class="inline-flex items-center px-10 py-5 bg-accent text-white font-black rounded-2xl shadow-xl shadow-accent/25 hover:shadow-accent/40 hover:-translate-y-1 transition-all group">
                                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z"
                                            fill="currentColor" />
                                        <path d="M11 7H13V13H11V7ZM11 15H13V17H11V15Z" fill="currentColor" />
                                    </svg>
                                    Pay using UPI App
                                </a>
                            </div>
                        </div>

                        <!-- QR Code Container -->
                        <div id="upi-qr-container" class="hidden">
                            <div
                                class="p-6 bg-white dark:bg-slate-800 rounded-xl border-2 border-dashed border-accent/30 max-w-sm mx-auto">
                                <div class="text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Scan QR
                                        Code to Pay</p>
                                    <div id="qr-code" class="inline-block p-4 bg-white rounded-xl shadow-lg"></div>
                                    <div class="mt-4 space-y-1">
                                        <p class="text-xs text-slate-500">UPI ID: <span
                                                class="font-bold text-slate-700 dark:text-slate-300">9735563157-4@ybl</span>
                                        </p>
                                        <p class="text-xs text-slate-500">Amount: <span
                                                class="font-black text-accent text-lg">₹200.00</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Coupon Code Input Box (Hidden) -->
                    <div id="coupon-code-section" class="hidden relative group/coupon mb-8 max-w-2xl mx-auto">
                        <div
                            class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-2xl">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-wider">
                                    Redeem Coupon Code</h4>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="text" id="coupon_code_input" name="coupon_code" placeholder="HF-MEMB-XXXXX"
                                    class="flex-1 px-5 py-4 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-white/5 rounded-2xl focus:border-accent transition-all outline-none font-mono text-lg font-bold tracking-widest uppercase dark:text-white">
                                <button type="button" onclick="validateCoupon()" id="apply-coupon-btn"
                                    class="px-8 py-4 bg-accent hover:bg-accent/90 text-white font-black rounded-2xl shadow-lg transition-all active:scale-95">
                                    Apply Code
                                </button>
                            </div>

                            <div id="coupon-success-message"
                                class="hidden mt-6 p-4 bg-emerald-50 dark:bg-emerald-500/5 border border-emerald-200 dark:border-emerald-500/20 rounded-2xl">
                                <p class="text-emerald-700 dark:text-emerald-400 text-sm font-bold"
                                    id="coupon-success-text"></p>
                            </div>

                            <div id="coupon-error-message"
                                class="hidden mt-6 p-4 bg-rose-50 dark:bg-rose-500/5 border border-rose-200 dark:border-rose-500/20 rounded-2xl">
                                <p class="text-rose-700 dark:text-rose-400 text-sm font-bold" id="coupon-error-text"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Payment Method Input (Controlled by JS) -->
                    <input type="hidden" name="payment_method" id="payment_method_input" value="">

                    <div id="payment-verification-section"
                        class="hidden space-y-4 animate-in fade-in duration-300 max-w-3xl mx-auto pt-8 border-t border-slate-100 dark:border-white/5">
                        <div class="space-y-4">
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest text-center">Upload
                                Payment Evidence <span class="text-danger">*</span></label>

                            <div class="relative group w-full">
                                <input type="file" name="payment_screenshot" id="payment_screenshot" accept="image/*"
                                    class="absolute inset-0 w-full h-32 opacity-0 cursor-pointer z-10">
                                <div
                                    class="w-full h-32 flex flex-col items-center justify-center bg-slate-50 dark:bg-slate-800 border-2 border-dashed border-slate-300 dark:border-white/10 rounded-2xl group-hover:border-accent group-hover:bg-accent/5 transition-all">
                                    <div class="flex flex-col items-center gap-2" id="upload-placeholder">
                                        <div
                                            class="p-3 bg-white dark:bg-white/5 rounded-full shadow-sm text-slate-400 group-hover:text-accent transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M16 8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <span
                                                class="block text-xs font-black uppercase tracking-widest text-slate-600 dark:text-slate-300">Click
                                                to Upload Screenshot</span>
                                            <span class="block text-[10px] text-slate-400 font-medium mt-0.5">Supports JPG,
                                                PNG</span>
                                        </div>
                                    </div>

                                    <!-- Selected File State -->
                                    <div id="file-selected-state" class="hidden flex flex-col items-center gap-2">
                                        <div class="p-2 bg-emerald-500/10 text-emerald-500 rounded-full">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700 dark:text-white"
                                            id="selected-filename">Filename.jpg</span>
                                        <span
                                            class="text-[10px] text-emerald-500 font-black uppercase tracking-widest">Ready
                                            to Upload</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Check -->
                        <div id="final-confirmation-section" class="hidden pt-2">
                            <label
                                class="flex items-center justify-center space-x-3 p-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl cursor-pointer hover:bg-slate-100 transition-all select-none">
                                <input type="checkbox" name="payment_confirmed" id="payment_confirmed"
                                    class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent">
                                <span class="text-xs font-bold text-slate-600 dark:text-slate-300 uppercase tracking-wide">I
                                    confirm the payment is genuine</span>
                            </label>
                        </div>
                    </div>


                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-slate-100 dark:border-white/5">
                    <a href="{{ route('patients.show', $patient->id) }}"
                        class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest text-[10px] rounded-2xl hover:bg-slate-200 transition-all">
                        Discard
                    </a>
                    <button type="submit" id="submit-membership-btn"
                        class="hidden px-8 py-4 bg-amber-500 text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-amber-500/20 hover:scale-110 active:scale-95 transition-all flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        Confirm & Upgrade to Membership
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/locations.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const districtSelect = document.getElementById('district-select');
            const blockSelect = document.getElementById('block-select');
            const gpSelect = document.getElementById('gp-select');

            // Values from model
            const savedDistrict = "{{ $patient->district }}";
            const savedBlock = "{{ $patient->block }}";
            const savedGP = "{{ $patient->gp }}";

            // Populate Districts
            if (window.locationData && window.locationData["West Bengal"]) {
                const wbDistricts = Object.keys(window.locationData["West Bengal"]).sort();
                wbDistricts.forEach(dist => {
                    const option = new Option(dist, dist);
                    if (dist === savedDistrict) option.selected = true;
                    districtSelect.add(option);
                });
                if (savedDistrict) districtSelect.dispatchEvent(new Event('change'));
            }

            districtSelect.addEventListener('change', function () {
                const district = this.value;
                blockSelect.innerHTML = '<option value="">Select Block</option>';
                gpSelect.innerHTML = '<option value="">Select GP</option>';
                gpSelect.disabled = true;

                if (district && window.locationData["West Bengal"][district]) {
                    blockSelect.disabled = false;
                    const blocks = Object.keys(window.locationData["West Bengal"][district]).sort();
                    blocks.forEach(block => {
                        const option = new Option(block, block);
                        if (block === savedBlock) option.selected = true;
                        blockSelect.add(option);
                    });
                    if (savedBlock) blockSelect.dispatchEvent(new Event('change'));
                } else {
                    blockSelect.disabled = true;
                }
            });

            blockSelect.addEventListener('change', function () {
                const district = districtSelect.value;
                const block = this.value;
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (district && block && window.locationData["West Bengal"][district][block]) {
                    gpSelect.disabled = false;
                    const gps = window.locationData["West Bengal"][district][block].sort();
                    gps.forEach(gp => {
                        const option = new Option(gp, gp);
                        if (gp === savedGP) option.selected = true;
                        gpSelect.add(option);
                    });
                } else {
                    gpSelect.disabled = true;
                }
            });

            // Payment Mode Logic
            const modeSelect = document.getElementById('payment-mode-select');
            const upiPaySection = document.getElementById('upi-payment-section');
            const couponSection = document.getElementById('coupon-code-section');
            const verifySection = document.getElementById('payment-verification-section');
            const finalConfirm = document.getElementById('final-confirmation-section');
            const methodInput = document.getElementById('payment_method_input');
            const screenshotInput = document.getElementById('payment_screenshot');
            const confirmationCheck = document.getElementById('payment_confirmed');
            const submitBtn = document.getElementById('submit-membership-btn');

            const upiAppContainer = document.getElementById('upi-app-container');
            const upiQrContainer = document.getElementById('upi-qr-container');

            // Coupon Elements for Reset
            const couponInput = document.getElementById('coupon_code_input');
            const couponBtn = document.getElementById('apply-coupon-btn');
            const couponSuccessMsg = document.getElementById('coupon-success-message');
            const couponErrorMsg = document.getElementById('coupon-error-message');
            const donationDisplay = document.getElementById('donation-amount-display');
            const feeInput = document.querySelector('input[name="membership_fee"]');

            // Check initial state (browser refresh might keep value)
            if (modeSelect.value) {
                toggleSections(modeSelect.value);
            }

            modeSelect.addEventListener('change', function () {
                toggleSections(this.value);
            });

            function toggleSections(mode) {
                // Reset common hidden sections
                upiPaySection.classList.add('hidden');
                couponSection.classList.add('hidden');
                verifySection.classList.add('hidden');
                finalConfirm.classList.add('hidden');
                submitBtn.classList.add('hidden');

                // Hide inner UPI containers
                upiAppContainer.classList.add('hidden');
                upiQrContainer.classList.add('hidden');

                // Clear Requirements
                screenshotInput.required = false;
                confirmationCheck.required = false;

                // Reset Coupon UI
                couponInput.value = '';
                couponInput.readOnly = false;
                couponBtn.innerHTML = 'Apply Code';
                couponBtn.disabled = false;
                couponBtn.classList.remove('bg-emerald-500');
                couponBtn.classList.add('bg-accent');

                couponSuccessMsg.classList.add('hidden');
                couponErrorMsg.classList.add('hidden');

                // Reset Fee to Default
                donationDisplay.textContent = "200";
                feeInput.value = 200;

                if (mode === 'upi_app') {
                    upiPaySection.classList.remove('hidden');
                    upiAppContainer.classList.remove('hidden');
                    // Reset Method input until they actually click Pay
                    methodInput.value = '';
                } else if (mode === 'upi_qr') {
                    upiPaySection.classList.remove('hidden');
                    upiQrContainer.classList.remove('hidden');

                    // Show Verification Immediately for QR
                    methodInput.value = 'UPI (QR)';
                    verifySection.classList.remove('hidden');
                    finalConfirm.classList.remove('hidden');
                    screenshotInput.required = true;
                    confirmationCheck.required = true;

                    // Generate QR
                    setTimeout(() => generateUPIQR(200), 100);
                } else if (mode === 'coupon') {
                    couponSection.classList.remove('hidden');
                    methodInput.value = '';
                }
            }

            // QR Code Generation
            const UPI_ID = '9735563157-4@ybl';
            const PAYEE_NAME = 'Humanity Foundation';

            function generateUPIQR(amount) {
                const qrContainer = document.getElementById('qr-code');
                // Clear previous
                qrContainer.innerHTML = '';

                const upiLink = `upi://pay?pa=${encodeURIComponent(UPI_ID)}&pn=${encodeURIComponent(PAYEE_NAME)}&am=${amount.toFixed(2)}&cu=INR`;

                if (typeof QRCode !== 'undefined') {
                    new QRCode(qrContainer, {
                        text: upiLink,
                        width: 180,
                        height: 180,
                        colorDark: '#1e293b',
                        colorLight: '#ffffff',
                        correctLevel: QRCode.CorrectLevel.H
                    });
                }
            }

            // UPI Timer Logic
            const upiLink = document.getElementById('upi-pay-link');
            upiLink.addEventListener('click', function (e) {
                const button = this;
                const originalText = button.innerHTML;
                button.classList.add('opacity-50', 'pointer-events-none');

                let seconds = 30;
                const timer = setInterval(() => {
                    seconds--;
                    button.innerHTML = `Wait ${seconds}s...`;

                    if (seconds <= 0) {
                        clearInterval(timer);
                        button.innerHTML = originalText;
                        button.classList.remove('opacity-50', 'pointer-events-none');

                        // Set method to UPI and show verification
                        methodInput.value = 'UPI';
                        verifySection.classList.remove('hidden');
                        finalConfirm.classList.remove('hidden');
                        screenshotInput.required = true;
                        confirmationCheck.required = true;
                    }
                }, 1000);
            });

            // Coupon Code Handlers
            // (Old toggleCouponSection function removed as it is replaced by Dropdown)

            // File Upload UI & Submit Button Visibility
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const fileSelectedState = document.getElementById('file-selected-state');
            const selectedFilename = document.getElementById('selected-filename');

            screenshotInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    // Update UI
                    const file = this.files[0];
                    selectedFilename.textContent = file.name;
                    uploadPlaceholder.classList.add('hidden');
                    fileSelectedState.classList.remove('hidden');

                    // Show Submit Btn
                    submitBtn.classList.remove('hidden');
                } else {
                    // Reset UI
                    uploadPlaceholder.classList.remove('hidden');
                    fileSelectedState.classList.add('hidden');

                    // Hide Submit Btn
                    submitBtn.classList.add('hidden');
                }
            });

            window.validateCoupon = async function () {
                const input = document.getElementById('coupon_code_input');
                const code = input.value.trim();
                const btn = document.getElementById('apply-coupon-btn');
                const successMsg = document.getElementById('coupon-success-message');
                const errorMsg = document.getElementById('coupon-error-message');
                const successText = document.getElementById('coupon-success-text');
                const errorText = document.getElementById('coupon-error-text');

                if (!code) {
                    errorText.textContent = "Please enter a code.";
                    errorMsg.classList.remove('hidden');
                    return;
                }

                btn.disabled = true;
                btn.textContent = "Verifying...";
                showGlobalLoader();
                successMsg.classList.add('hidden');
                errorMsg.classList.add('hidden');

                try {
                    const response = await fetch('{{ route("coupons.validate") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ code: code, designation: 'membership' })
                    });

                    const data = await response.json();
                    if (data.valid) {
                        successText.textContent = data.message || "Coupon Applied Successfully!";
                        successMsg.classList.remove('hidden');

                        // Hide UPI section entirely
                        upiPaySection.classList.add('hidden');
                        verifySection.classList.add('hidden');
                        finalConfirm.classList.add('hidden');

                        // Update fee and hidden method
                        const feeInput = document.querySelector('input[name="membership_fee"]');
                        feeInput.value = 0;
                        document.getElementById('donation-amount-display').textContent = "0 (Coupon Applied)";

                        // Reset other fields
                        document.getElementById('discount_percentage').value = 0;
                        document.getElementById('discount_amount_input').value = 0;
                        document.getElementById('final_amount_input').value = 0;
                        document.getElementById('amount_paid').value = 0;
                        document.getElementById('due_amount_input').value = 0;

                        methodInput.value = 'Coupon';

                        screenshotInput.required = false;
                        confirmationCheck.required = false;

                        btn.innerHTML = "Applied ✓";
                        btn.classList.replace('bg-accent', 'bg-emerald-500');
                        input.readOnly = true;
                        submitBtn.classList.remove('hidden');
                    } else {
                        errorText.textContent = data.message || "Invalid or expired coupon.";
                        errorMsg.classList.remove('hidden');
                        btn.disabled = false;
                        btn.textContent = "Apply Code";
                    }
                } catch (err) {
                    console.error(err);
                    errorText.textContent = "Server error. Try again.";
                    errorMsg.classList.remove('hidden');
                    btn.disabled = false;
                    btn.textContent = "Apply Code";
                } finally {
                    hideGlobalLoader();
                }
            };

            // Form Validation extension
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const method = methodInput.value;
                if (!method) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Payment Required',
                        text: 'Please complete the UPI payment or apply a coupon code.',
                        ...getSwalConfig()
                    });
                    return false;
                }
                if (method === 'UPI' && verifySection.classList.contains('hidden')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Verification Pending',
                        text: 'Please click the UPI Payment button and wait for the verification section before submitting.',
                        ...getSwalConfig()
                    });
                    return false;
                }

                // Show a more descriptive loader for AI verification if a file is present
                if (screenshotInput.files && screenshotInput.files.length > 0) {
                    const loader = document.getElementById('global-loader');
                    if (loader) {
                        // Remove existing text if any
                        const oldText = document.getElementById('loader-ai-text');
                        if (oldText) oldText.remove();

                        const loaderText = document.createElement('div');
                        loaderText.id = 'loader-ai-text';
                        loaderText.className = 'absolute bottom-20 left-0 right-0 text-center animate-in slide-in-from-bottom-4 duration-700';
                        loaderText.innerHTML = `
                                                        <p class="text-slate-800 dark:text-white font-black uppercase tracking-[0.3em] text-sm md:text-base px-6">AI Verifying Payment Screenshot...</p>
                                                        <p class="text-slate-400 dark:text-slate-500 text-[10px] md:text-xs font-bold mt-3 uppercase tracking-widest leading-relaxed">This process takes about 5-8 seconds.<br>Please do not close or refresh this window.</p>
                                                    `;
                        loader.appendChild(loaderText);
                    }
                }
            });

            // Payment Calculations
            window.calculateFinalAmount = function () {
                const baseFee = parseFloat(feeInput.value) || 0;
                const discPercInput = document.getElementById('discount_percentage');
                const discPerc = discPercInput ? (parseFloat(discPercInput.value) || 0) : 0;

                const discAmt = (baseFee * discPerc) / 100;
                const finalAmt = baseFee - discAmt;

                const discAmtInput = document.getElementById('discount_amount_input');
                if (discAmtInput) discAmtInput.value = discAmt.toFixed(2);

                const finalAmtInput = document.getElementById('final_amount_input');
                if (finalAmtInput) finalAmtInput.value = finalAmt.toFixed(2);

                const finalAmtDisplay = document.getElementById('final-amount-display');
                if (finalAmtDisplay) finalAmtDisplay.textContent = finalAmt.toFixed(2);

                // Also update QR code if visible
                if (modeSelect.value === 'upi_qr') {
                    generateUPIQR(finalAmt);
                }

                // Update amount paid to final amount (no due allowed for membership)
                const amountPaidInput = document.getElementById('amount_paid');
                if (amountPaidInput) amountPaidInput.value = finalAmt.toFixed(2);

                calculateDueAmount();
            };

            window.calculateDueAmount = function () {
                const finalAmt = parseFloat(document.getElementById('final_amount_input').value) || 0;
                const amtPaid = parseFloat(document.getElementById('amount_paid').value) || 0;

                let dueAmt = finalAmt - amtPaid;
                if (dueAmt < 0) dueAmt = 0;

                // Due display removed from UI, but we still update the hidden input
                document.getElementById('due_amount_input').value = dueAmt.toFixed(2);

                // Update QR code to amount paid if using QR
                if (modeSelect.value === 'upi_qr') {
                    generateUPIQR(amtPaid);
                }
            };
        });



        window.validatePAN = function (input) {
            let val = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
            let result = '';
            for (let i = 0; i < val.length && i < 10; i++) {
                if (i < 5) { if (/[A-Z]/.test(val[i])) result += val[i]; }
                else if (i < 9) { if (/[0-9]/.test(val[i])) result += val[i]; }
                else { if (/[A-Z]/.test(val[i])) result += val[i]; }
            }
            input.value = result;
        };

        // Real-time Uniqueness Check for Aadhaar
        const aadharInput = document.querySelector('input[name="aadhar_number"]');
        if (aadharInput) {
            aadharInput.addEventListener('input', function () {
                const val = this.value;
                if (val.length === 12) {
                    checkPatientUniqueness('aadhar_number', val);
                }
            });
        }

        // Real-time Uniqueness Check for Phone
        const phoneInput = document.querySelector('input[name="phone_number"]');
        if (phoneInput) {
            phoneInput.addEventListener('input', function () {
                const val = this.value;
                if (val.length === 10) {
                    checkPatientUniqueness('phone_number', val);
                }
            });
        }

        async function checkPatientUniqueness(field, value) {
            try {
                const response = await fetch('{{ route("patients.check-uniqueness") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        field: field,
                        value: value,
                        exclude_id: '{{ $patient->id }}'
                    })
                });

                const data = await response.json();
                if (data.exists) {
                    const p = data.patient;
                    Swal.fire({
                        icon: 'info',
                        title: 'Patient Already Exists!',
                        html: `
                                <div class="text-left p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-white/5 mt-4">
                                    <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Existing Patient Details</p>
                                    <div class="space-y-2">
                                        <p class="text-sm font-bold text-slate-700 dark:text-white">Name: <span class="text-accent">${p.full_name}</span></p>
                                        <p class="text-sm font-bold text-slate-700 dark:text-white">ID: <span class="text-accent">${p.patient_id}</span></p>
                                        <p class="text-sm font-bold text-slate-700 dark:text-white">Phone: <span class="text-accent">${p.phone_number}</span></p>
                                        <p class="text-sm font-bold text-slate-700 dark:text-white">Role: <span class="px-2 py-0.5 rounded text-[10px] ${p.is_member ? 'bg-amber-500/10 text-amber-500' : 'bg-blue-500/10 text-blue-500'} font-black uppercase">${p.is_member ? 'Premium Member' : 'Regular Patient'}</span></p>
                                    </div>
                                    <div class="mt-6">
                                        <a href="/patients/${p.id}" class="inline-block w-full text-center px-4 py-3 bg-accent text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all hover:scale-105">View Profile</a>
                                    </div>
                                </div>
                            `,
                        confirmButtonText: 'Got It',
                        background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#1e293b',
                        confirmButtonColor: '#3C50E0',
                        customClass: {
                            popup: 'rounded-3xl border border-white/10 shadow-2xl overflow-hidden'
                        }
                    });
                }
            } catch (err) {
                console.error('Uniqueness check failed:', err);
            }
        }
    </script>
@endsection