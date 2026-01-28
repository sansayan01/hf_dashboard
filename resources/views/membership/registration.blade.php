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
                                Group <span class="text-danger">*</span></label>
                            <select name="blood_group" required
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

                    <!-- Health Issues -->
                    <div class="space-y-4">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Update Health
                            Issues</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php
                                $standardIssues = ['Gas', 'Sugar', 'Pressure', 'Thyroid', 'Uric Acid', 'Skin/Hair', 'Heart', 'Eye', 'ENT', 'Dental'];
                                $currentIssues = explode(', ', $patient->health_issues);
                            @endphp
                            @foreach($standardIssues as $index => $issue)
                                <label
                                    class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-amber-500/30 cursor-pointer transition-all has-[:checked]:border-amber-500/50 has-[:checked]:bg-amber-500/5">
                                    <input type="checkbox" name="health_issue_category[]" value="{{ $issue }}"
                                        class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500 accent-amber-500"
                                        {{ in_array($issue, $currentIssues) ? 'checked' : '' }}>
                                    <span class="text-xs font-bold text-slate-700 dark:text-white">{{ $issue }}</span>
                                </label>
                            @endforeach
                            <label
                                class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-amber-500/30 cursor-pointer transition-all has-[:checked]:border-amber-500/50 has-[:checked]:bg-amber-500/5">
                                <input type="checkbox" name="health_issue_category[]" value="Any other"
                                    id="health_any_other"
                                    class="w-5 h-5 rounded border-slate-300 text-amber-500 focus:ring-amber-500 accent-amber-500"
                                    onchange="toggleHealthOther(this.checked)">
                                <span class="text-xs font-bold text-slate-700 dark:text-white">Any other</span>
                            </label>
                        </div>
                        <div id="health-other-container" class="space-y-2 hidden">
                            <textarea name="health_issue_other" rows="2"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                                placeholder="Describe other health issues...">{{ old('health_issue_other') }}</textarea>
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
                        class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2rem] p-8 mb-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <p class="text-sm font-medium text-slate-500 uppercase tracking-widest">Required Joining
                                    Donation</p>
                                <h3 class="text-4xl font-black text-slate-800 dark:text-white mt-1">₹<span
                                        id="donation-amount-display">199</span></h3>
                                <p class="text-xs text-slate-400 mt-2 italic">* This is a one-time joining donation for
                                    premium membership.</p>
                                <input type="hidden" name="membership_fee" value="199">
                            </div>
                        </div>
                    </div>

                    <!-- UPI Payment Card -->
                    <div id="upi-payment-section"
                        class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-[2rem] p-8 mb-8">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <h5 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-wider">Pay
                                    via UPI</h5>
                                <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">Click the button
                                    below to open your UPI app</p>
                            </div>

                            <a id="upi-pay-link" href="upi://pay?pa=9735563157-4@ybl&pn=MembershipPayment&am=199&cu=INR"
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

                    <!-- Coupon Code Section -->
                    <div class="text-center mb-8">
                        <button type="button" onclick="toggleCouponSection()" id="coupon-toggle-btn"
                            class="group text-sm text-accent hover:text-accent/80 font-bold inline-flex items-center gap-3 px-6 py-3 rounded-full bg-accent/5 dark:bg-accent/5 border border-accent/20 dark:border-accent/20 transition-all duration-300">
                            <span
                                class="p-1.5 bg-accent/10 dark:bg-accent/10 rounded-full group-hover:bg-accent/20 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                                    </path>
                                </svg>
                            </span>
                            <span>Redeem Coupon Code</span>
                        </button>
                    </div>

                    <!-- Coupon Code Input Box (Hidden) -->
                    <div id="coupon-code-section" class="hidden relative group/coupon mb-8 max-w-2xl mx-auto">
                        <div
                            class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-3xl p-8 shadow-2xl">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-black text-slate-800 dark:text-white text-sm uppercase tracking-wider">
                                    Redeem Coupon Code</h4>
                                <button type="button" onclick="toggleCouponSection()"
                                    class="text-slate-400 hover:text-red-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
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
                        class="hidden space-y-4 animate-in fade-in duration-300 max-w-2xl mx-auto">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
                            <div class="space-y-4">
                                <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Upload
                                    Screenshot <span class="text-danger">*</span></label>
                                <div class="relative group">
                                    <input type="file" name="payment_screenshot" id="payment_screenshot" accept="image/*"
                                        class="w-full px-5 py-4 bg-slate-100 dark:bg-slate-800 border-2 border-dashed border-slate-300 dark:border-white/10 rounded-2xl focus:border-accent outline-none transition-all cursor-pointer file:hidden">
                                    <div
                                        class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none text-slate-400 group-hover:text-accent transition-colors">
                                        <div class="flex items-center gap-2 mb-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M16 8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                            <span class="text-xs font-black uppercase tracking-widest">Upload
                                                Evidence</span>
                                        </div>
                                        <div
                                            class="flex items-center gap-1.5 px-3 py-1 bg-accent/5 rounded-full border border-accent/10">
                                            <div class="w-1.5 h-1.5 bg-accent rounded-full animate-pulse"></div>
                                            <span class="text-[9px] font-bold text-accent uppercase tracking-tighter">Smart
                                                AI Verification Active</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmation Check -->
                            <div id="final-confirmation-section" class="hidden">
                                <label
                                    class="flex items-center space-x-4 p-5 bg-blue-500/5 border-2 border-dashed border-blue-500/20 rounded-2xl cursor-pointer w-full hover:bg-blue-500/10 transition-all">
                                    <input type="checkbox" name="payment_confirmed" id="payment_confirmed"
                                        class="w-6 h-6 rounded border-slate-300 text-accent focus:ring-accent accent-accent">
                                    <span class="text-sm font-bold text-slate-700 dark:text-slate-300">I confirm the payment
                                        is genuine.</span>
                                </label>
                            </div>
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

            // Payment Logic - Refactored for Digital-Only (UPI/Coupon)
            const methodInput = document.getElementById('payment_method_input');
            const upiPaySection = document.getElementById('upi-payment-section');
            const verifySection = document.getElementById('payment-verification-section');
            const finalConfirm = document.getElementById('final-confirmation-section');
            const upiLink = document.getElementById('upi-pay-link');
            const screenshotInput = document.getElementById('payment_screenshot');
            const confirmationCheck = document.getElementById('payment_confirmed');
            const submitBtn = document.getElementById('submit-membership-btn');

            // Show submit button when screenshot is uploaded
            screenshotInput.addEventListener('change', function () {
                if (this.files && this.files.length > 0) {
                    submitBtn.classList.remove('hidden');
                } else {
                    if (methodInput.value !== 'Coupon') {
                        submitBtn.classList.add('hidden');
                    }
                }
            });

            // UPI Timer Logic
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
            window.toggleCouponSection = function () {
                const section = document.getElementById('coupon-code-section');
                const btn = document.getElementById('coupon-toggle-btn');
                section.classList.toggle('hidden');
                btn.classList.toggle('hidden');
                if (!section.classList.contains('hidden')) {
                    document.getElementById('coupon_code_input').focus();
                }
            };

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
                        document.querySelector('input[name="membership_fee"]').value = 0;
                        document.getElementById('donation-amount-display').textContent = "0 (Coupon Applied)";
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
                }
            };

            // Form Validation extension
            const form = document.querySelector('form');
            form.addEventListener('submit', function (e) {
                const method = methodInput.value;
                if (!method) {
                    e.preventDefault();
                    alert('Please complete the UPI payment or apply a coupon code.');
                    return false;
                }
                if (method === 'UPI' && verifySection.classList.contains('hidden')) {
                    e.preventDefault();
                    alert('Please click the UPI Payment button and wait for the verification section before submitting.');
                    return false;
                }
            });
        });

        function toggleHealthOther(isChecked) {
            const container = document.getElementById('health-other-container');
            container.classList.toggle('hidden', !isChecked);
        }

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
    </script>
@endsection