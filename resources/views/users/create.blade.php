@extends('layouts.app')

@section('title', 'Add New Member')
@section('header_title', 'Create New ' . strtoupper($allowedDesignation))

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h3 class="font-bold text-xl text-slate-800">Donation Form</h3>
                <p class="text-sm text-slate-500 mt-1">Fill in the details below to add a new
                    {{ strtoupper($allowedDesignation) }} to your downline.</p>
            </div>

            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-10">
                @csrf

                <!-- Section: Designation & Parent (Super Admin & Office In-Charge Only) -->
                @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge())
                <div class="mb-10">
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Designation & Hierarchy</h4>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Role/Designation</label>
                            <select name="designation" id="designation-select" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Role</option>
                                @foreach($allDesignations as $val => $label)
                                    <option value="{{ $val }}" {{ old('designation') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="post-selection-wrapper" class="hidden">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Assign Post (Manual Entry)</label>
                            <input type="text" name="post" id="post-input" value="{{ old('post') }}" placeholder="e.g. Secretary, Vice President"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                        </div>
                        <div id="parent-selection-wrapper" class="{{ request('type') === 'staff' ? 'hidden' : '' }}">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Assign Parent (Manager)</label>
                            <select name="parent_id" id="parent-select" {{ request('type') === 'staff' ? '' : 'required' }}
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Role First</option>
                            </select>
                        </div>
                        
                        <!-- Camp Selection (Pharmacist Only) -->
                        <div id="camp-selection-wrapper" class="{{ request('type') === 'staff' ? '' : 'hidden' }}">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Assign Camp Location</label>
                            <select name="camp_id" id="camp-select" {{ request('type') === 'staff' ? 'required' : '' }}
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Camp</option>
                                @if(isset($camps))
                                    @foreach($camps as $camp)
                                        <option value="{{ $camp->id }}" {{ old('camp_id') == $camp->id ? 'selected' : '' }}>
                                            {{ $camp->name }} {{ $camp->location ? '('.$camp->location.')' : '' }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Pharmacist will be assigned to this camp location.</p>
                        </div>
                    </div>

                    <!-- Office In-Charge Upline Selection (Only for Super Admin) -->
                    @if(auth()->user()->isSuperAdmin())
                    <div id="office-in-charge-upline-section" class="hidden mt-6">
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-4">
                            <p class="text-sm text-blue-800 font-semibold">
                                <svg class="inline w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                Office In-Charge / Camp Organizer Configuration: Select the upline this user will represent
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Upline's Designation</label>
                                <select name="upline_designation" id="upline-designation-select"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                    <option value="">Select Upline Designation</option>
                                    <option value="super_admin" {{ old('upline_designation') == 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                                    <option value="hs" {{ old('upline_designation') == 'hs' ? 'selected' : '' }}>Head of State (HS)</option>
                                    <option value="dm" {{ old('upline_designation') == 'dm' ? 'selected' : '' }}>District Manager (DM)</option>
                                    <option value="bm" {{ old('upline_designation') == 'bm' ? 'selected' : '' }}>Block Manager (BM)</option>
                                    <option value="rm" {{ old('upline_designation') == 'rm' ? 'selected' : '' }}>Relationship Manager (RM)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Select Upline Person</label>
                                <select name="upline_id" id="upline-person-select"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                    <option value="">Select Upline Designation First</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Section: Primary Account Info -->
                <div>
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Account Credentials</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <label id="id-generation-label" class="block text-sm font-bold text-slate-700">Volunteer ID Generation</label>
                            
                            @if(auth()->user()->isSuperAdmin())
                                <div class="flex space-x-4">
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="employee_id_option" value="auto" {{ old('employee_id_option', 'auto') === 'auto' ? 'checked' : '' }}
                                            onchange="toggleEmployeeId(false)" class="w-4 h-4 text-accent focus:ring-accent">
                                        <span class="text-sm font-medium text-slate-600">Auto-generate</span>
                                    </label>
                                    <label class="flex items-center space-x-2 cursor-pointer">
                                        <input type="radio" name="employee_id_option" value="manual" {{ old('employee_id_option') === 'manual' ? 'checked' : '' }}
                                            onchange="toggleEmployeeId(true)" class="w-4 h-4 text-accent focus:ring-accent">
                                        <span class="text-sm font-medium text-slate-600">Manual Entry</span>
                                    </label>
                                </div>
                                <div id="manual_id_container" class="{{ old('employee_id_option') === 'manual' ? '' : 'hidden' }}">
                                    <input type="text" name="employee_id" value="{{ old('employee_id') }}" placeholder="Enter custom ID"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                </div>
                            @else
                                <input type="hidden" name="employee_id_option" value="auto">
                                <div class="bg-blue-50/50 text-blue-700 px-4 py-3 rounded-xl border border-blue-100/50 flex items-center space-x-3">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                                    <span class="text-xs font-bold uppercase tracking-wide">Automatic ID Generation Enabled</span>
                                </div>
                            @endif

                            <p id="auto_id_hint" class="text-[10px] text-bodydark font-bold italic uppercase">System will
                                generate: HF<span id="hint-designation">{{ $allowedDesignation ? strtoupper($allowedDesignation) : 'XX' }}</span>000001</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@foundation.org"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                            <input type="password" name="password" id="password" required placeholder="Minimum 8 characters"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none pr-12">
                            <button type="button" onclick="togglePasswordVisibility('password', 'password-eye-icon')" class="absolute right-4 top-[42px] text-slate-400 hover:text-accent transition-colors">
                                <svg id="password-eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>

                        <div class="relative">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Confirm Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="Repeat password"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none pr-12">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'confirm-password-eye-icon')" class="absolute right-4 top-[42px] text-slate-400 hover:text-accent transition-colors">
                                <svg id="confirm-password-eye-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Section: Personal Profile -->
                <div>
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Personal Profile</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="As per Aadhaar"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                            <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required maxlength="10" placeholder="10 Digit Number"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Blood Group</label>
                            <select name="blood_group"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Group</option>
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $group)
                                    <option value="{{ $group }}" {{ old('blood_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-4">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-200 mb-2">Profile Picture</label>
                    <div class="flex flex-col sm:flex-row items-center gap-6 p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-dashed border-slate-300 dark:border-white/10">
                        <div class="relative w-24 h-24 flex-shrink-0 group cursor-pointer" onclick="handlePreviewClick('profile_picture_input', 'profile-preview')">
                            <div class="initials-placeholder w-full h-full rounded-2xl bg-accent/10 border-2 border-accent/20 flex items-center justify-center text-accent text-2xl font-bold transition-all group-hover:scale-105">
                                <svg class="w-10 h-10 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <img id="profile-preview" src="#" alt="Preview" class="hidden w-full h-full rounded-2xl object-cover border-2 border-accent/20 shadow-lg transition-all group-hover:scale-105">
                            <div class="absolute -bottom-2 -right-2 bg-accent text-white p-1.5 rounded-lg shadow-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 w-full">
                            <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*"
                                class="w-full px-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl focus:ring-4 focus:ring-accent/10 transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-accent file:text-white hover:file:bg-accent/80 cursor-pointer"
                                onchange="initCropper(this, document.getElementById('profile-preview'))">
                            <p class="mt-2 text-[11px] text-slate-500 dark:text-slate-400 font-medium">JPG, PNG or GIF. Max size 10MB. 1:1 aspect ratio recommended.</p>
                        </div>
                    </div>
                </div>
        </div>

                        <div class="col-span-full">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Detailed Address</label>
                            <textarea name="address" required rows="3" placeholder="Village, House No, Landmark"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">{{ old('address') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">State</label>
                            <select name="state" id="state-select" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select State</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">District</label>
                            <select name="district" id="district-select" required disabled
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none disabled:opacity-50">
                                <option value="">Select District</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Block</label>
                            <select name="block" id="block-select" required disabled
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none disabled:opacity-50">
                                <option value="">Select Block</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Gram Panchayat</label>
                            <select name="gram_panchayat" id="gp-select" required disabled
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none disabled:opacity-50">
                                <option value="">Select GP</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Pin Code</label>
                            <input type="text" name="pin_code" value="{{ old('pin_code') }}" required maxlength="6" placeholder="XXXXXX"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Aadhaar Number</label>
                            <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number') }}" required maxlength="12" placeholder="12 digit number"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">PAN Number</label>
                            <input type="text" name="pan_number" value="{{ old('pan_number') }}" maxlength="10" placeholder="ABCDE1234F"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="validatePAN(this)">
                        </div>
                    </div>
                </div>

                <!-- Section: Bank Account -->
                <div class="mb-10">
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Banking Information</h4>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Bank Name</label>
                            <select name="bank_name" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Bank</option>
                                @foreach([
                                    'State Bank of India', 'HDFC Bank', 'ICICI Bank', 'Axis Bank', 'Punjab National Bank',
                                    'Bank of Baroda', 'Canara Bank', 'Union Bank of India', 'Bank of India', 'Indian Bank',
                                    'Central Bank of India', 'Indian Overseas Bank', 'UCO Bank', 'Bank of Maharashtra',
                                    'Punjab & Sind Bank', 'IDBI Bank', 'Yes Bank', 'Kotak Mahindra Bank', 'Federal Bank',
                                    'IndusInd Bank', 'South Indian Bank', 'Karnataka Bank', 'City Union Bank', 'Karur Vysya Bank',
                                    'Tamilnad Mercantile Bank', 'RBL Bank', 'Bandhan Bank', 'IDFC First Bank', 'AU Small Finance Bank',
                                    'Equitas Small Finance Bank', 'India Post Payments Bank', 'Paytm Payments Bank',
                                    'Airtel Payments Bank', 'Jio Payments Bank', 'Rajnagar Samabaya Krishi Unnayan Samity Ltd', 'Bangiya Gramin Vikash Bank'
                                ] as $bank)
                                    <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Account Number</label>
                            <input type="text" name="account_number" value="{{ old('account_number') }}" placeholder="Bank Account No." required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-sm font-bold text-slate-700 mb-2">IFSC Code</label>
                            <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}" placeholder="Enter IFSC code" required maxlength="11"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none"
                                oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '')">
                        </div>
                    </div>
                </div>

                <!-- Section: Donation & Payment -->
                <div id="donation-section" class="hidden">
                    <div class="flex items-center space-x-2 mb-6">
                        <div class="w-1.5 h-6 bg-accent rounded-full"></div>
                        <h4 class="font-bold text-slate-800 uppercase tracking-wider text-xs">Joining Donation & Payment</h4>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 mb-6">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div>
                                <p class="text-sm font-medium text-slate-500">Required Joining Donation</p>
                                <h3 class="text-3xl font-black text-slate-800">₹<span id="donation-amount-display">0</span></h3>
                                <p class="text-xs text-slate-400 mt-1">* This is a one-time joining donation based on the selected role.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Mode Selection -->
                    <div id="payment-mode-section" class="mb-8 p-6 bg-slate-50 border border-slate-200 rounded-2xl">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Payment Mode</label>
                        <div class="relative">
                            <select id="payment-mode-select"
                                class="w-full h-12 pl-4 pr-10 rounded-xl border border-slate-200 bg-white text-sm font-bold focus:ring-2 focus:ring-accent/50 outline-none transition appearance-none">
                                <option value="" disabled>Choose Payment Mode...</option>
                                <option value="upi_app">📱 Pay via UPI App</option>
                                <option value="upi_qr" selected>📷 Scan QR Code</option>
                                <option value="coupon">🎟️ Redeem Coupon Code</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- UPI Payment Wrapper -->
                    <div id="upi-payment-wrapper" class="hidden">
                        
                        <!-- UPI App Container -->
                        <div id="upi-app-container" class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-8 mb-8">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                <div>
                                    <h5 class="font-black text-slate-800 text-sm uppercase tracking-wider">Pay via UPI App</h5>
                                    <p class="text-xs text-slate-400 mt-1 uppercase tracking-widest font-bold">Click the button below to open your UPI app</p>
                                </div>

                                <a id="upi-pay-link" href="#"
                                    class="inline-flex items-center px-10 py-5 bg-accent text-white font-black rounded-2xl shadow-xl shadow-accent/25 hover:shadow-accent/40 hover:-translate-y-1 transition-all group">
                                    <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" viewBox="0 0 24 24"
                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" fill="currentColor" />
                                        <path d="M11 7H13V13H11V7ZM11 15H13V17H11V15Z" fill="currentColor" />
                                    </svg>
                                    Pay using UPI App
                                </a>
                            </div>
                        </div>
                        
                        <!-- QR Code Container -->
                        <div id="upi-qr-container" class="hidden bg-slate-50 border border-slate-200 rounded-2xl p-8 mb-8">
                            <div class="p-6 bg-white rounded-xl border-2 border-dashed border-accent/30 max-w-sm mx-auto">
                                <div class="text-center">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Scan QR Code to Pay</p>
                                    <div id="qr-code" class="inline-block p-4 bg-white rounded-xl shadow-lg"></div>
                                    <div class="mt-4 space-y-1">
                                        <p class="text-xs text-slate-500">UPI ID: <span class="font-bold text-slate-700">9735563157-4@ybl</span></p>
                                        <p class="text-xs text-slate-500">Amount: <span class="font-black text-accent text-lg">₹<span id="qr-amount-display">199.00</span></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Coupon Code Section -->
                    <div id="coupon-code-section" class="hidden relative group/coupon mb-8 max-w-2xl mx-auto">
                        <div class="relative bg-white border border-slate-200 rounded-3xl p-8 shadow-2xl">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-black text-slate-800 text-sm uppercase tracking-wider">Redeem Coupon Code</h4>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="text" id="coupon_code_input" name="coupon_code" placeholder="HF-MEMB-XXXXX"
                                    class="flex-1 px-5 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:border-accent transition-all outline-none font-mono text-lg font-bold tracking-widest uppercase">
                                <button type="button" onclick="validateCoupon()" id="apply-coupon-btn"
                                    class="px-8 py-4 bg-accent hover:bg-accent/90 text-white font-black rounded-2xl shadow-lg transition-all active:scale-95">
                                    Apply Code
                                </button>
                            </div>

                            <div id="coupon-success-message" class="hidden mt-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                                <p class="text-emerald-700 text-sm font-bold" id="coupon-success-text"></p>
                            </div>

                            <div id="coupon-error-message" class="hidden mt-6 p-4 bg-rose-50 border border-rose-200 rounded-2xl">
                                <p class="text-rose-700 text-sm font-bold" id="coupon-error-text"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Payment Method Input -->
                    <input type="hidden" name="payment_method" id="payment_method_input" value="">

                    <!-- Verification Section -->
                    <div id="payment-verification-section"
                        class="hidden space-y-4 animate-in fade-in duration-300 max-w-3xl mx-auto pt-8 border-t border-slate-100">
                        <div class="space-y-4">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest text-center">Upload Payment Evidence <span class="text-danger">*</span></label>
                            
                            <div class="relative group w-full">
                                <input type="file" name="payment_screenshot" id="payment_screenshot_input" accept="image/*"
                                    class="absolute inset-0 w-full h-32 opacity-0 cursor-pointer z-10">
                                <div
                                    class="w-full h-32 flex flex-col items-center justify-center bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl group-hover:border-accent group-hover:bg-accent/5 transition-all">
                                    <div class="flex flex-col items-center gap-2" id="upload-placeholder">
                                        <div class="p-3 bg-white rounded-full shadow-sm text-slate-400 group-hover:text-accent transition-colors">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M16 8l-4-4m0 0L8 8m4-4v12" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <span class="block text-xs font-black uppercase tracking-widest text-slate-600">Click to Upload Screenshot</span>
                                            <span class="block text-[10px] text-slate-400 font-medium mt-0.5">Supports JPG, PNG</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Selected File State -->
                                    <div id="file-selected-state" class="hidden flex flex-col items-center gap-2">
                                        <div class="p-2 bg-emerald-500/10 text-emerald-500 rounded-full">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-bold text-slate-700" id="selected-filename">Filename.jpg</span>
                                        <span class="text-[10px] text-emerald-500 font-black uppercase tracking-widest">Ready to Upload</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Check -->
                        <div id="final-confirmation-section" class="hidden pt-2">
                            <label
                                class="flex items-center justify-center space-x-3 p-4 bg-slate-50 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-100 transition-all select-none">
                                <input type="checkbox" name="payment_confirmed" id="payment_confirmed"
                                    class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent">
                                <span class="text-xs font-bold text-slate-600 uppercase tracking-wide">I confirm the payment is genuine</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-10 border-t border-slate-100 flex items-center justify-between">
                    <button type="reset"
                        class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition">Reset
                        Form</button>
                    <button type="submit" id="register-button"
                        class="px-10 py-4 bg-accent text-white font-bold rounded-xl shadow-xl shadow-accent/20 hover:shadow-md hover:-translate-y-0.5 transition-all hidden">
                        Register User to Downline
                    </button>
                </div>
            </form>
        </div>
    </div>
    @include('layouts.partials.image_cropper')
@endsection

@section('js')
    <script src="{{ asset('js/locations.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        function toggleEmployeeId(manual) {
            const manualContainer = document.getElementById('manual_id_container');
            const autoHint = document.getElementById('auto_id_hint');

            if (manual) {
                manualContainer.classList.remove('hidden');
                autoHint.classList.add('hidden');
            } else {
                manualContainer.classList.add('hidden');
                autoHint.classList.remove('hidden');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const stateSelect = document.getElementById('state-select');
            const districtSelect = document.getElementById('district-select');
            const blockSelect = document.getElementById('block-select');
            const gpSelect = document.getElementById('gp-select');

            // Populate States
            if (window.locationData) {
                for (const state in window.locationData) {
                    const option = new Option(state, state);
                    if (state === "{{ old('state') }}") {
                        option.selected = true;
                    }
                    stateSelect.add(option);
                }
            }

            function triggerSelectChange(element) {
                const event = new Event('change', { bubbles: true });
                element.dispatchEvent(event);
            }

            stateSelect.addEventListener('change', function() {
                const selectedState = this.value;
                districtSelect.innerHTML = '<option value="">Select District</option>';
                blockSelect.innerHTML = '<option value="">Select Block</option>';
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (selectedState && window.locationData[selectedState]) {
                    districtSelect.disabled = false;
                    for (const district in window.locationData[selectedState]) {
                        const option = new Option(district, district);
                        if (district === "{{ old('district') }}") {
                            option.selected = true;
                        }
                        districtSelect.add(option);
                    }
                } else {
                    districtSelect.disabled = true;
                    blockSelect.disabled = true;
                    gpSelect.disabled = true;
                }
            });

            districtSelect.addEventListener('change', function() {
                const selectedState = stateSelect.value;
                const selectedDistrict = this.value;
                blockSelect.innerHTML = '<option value="">Select Block</option>';
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (selectedDistrict && window.locationData[selectedState][selectedDistrict]) {
                    blockSelect.disabled = false;
                    for (const block in window.locationData[selectedState][selectedDistrict]) {
                        const option = new Option(block, block);
                        if (block === "{{ old('block') }}") {
                            option.selected = true;
                        }
                        blockSelect.add(option);
                    }
                } else {
                    blockSelect.disabled = true;
                    gpSelect.disabled = true;
                }
            });

            blockSelect.addEventListener('change', function() {
                const selectedState = stateSelect.value;
                const selectedDistrict = districtSelect.value;
                const selectedBlock = this.value;
                gpSelect.innerHTML = '<option value="">Select GP</option>';

                if (selectedBlock && window.locationData[selectedState][selectedDistrict][selectedBlock]) {
                    gpSelect.disabled = false;
                    const gps = window.locationData[selectedState][selectedDistrict][selectedBlock];
                    gps.forEach(gp => {
                        const option = new Option(gp, gp);
                        if (gp === "{{ old('gram_panchayat') }}") {
                            option.selected = true;
                        }
                        gpSelect.add(option);
                    });
                } else {
                    gpSelect.disabled = true;
                }
            });

            // Re-trigger location changes if old values exist
            if (stateSelect.value) {
                triggerSelectChange(stateSelect);
                if ("{{ old('district') }}") {
                    triggerSelectChange(districtSelect);
                    if ("{{ old('block') }}") {
                        triggerSelectChange(blockSelect);
                    }
                }
            }

            // PAN Formatting helper
            window.validatePAN = function(input) {
                let val = input.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
                let result = '';
                
                for (let i = 0; i < val.length && i < 10; i++) {
                    if (i < 5) {
                        if (/[A-Z]/.test(val[i])) result += val[i];
                    } else if (i < 9) {
                        if (/[0-9]/.test(val[i])) result += val[i];
                    } else {
                        if (/[A-Z]/.test(val[i])) result += val[i];
                    }
                }
                input.value = result;
            };

            // Auto-capitalization Title Case helper
            const titleCase = (str) => {
                return str.toLowerCase().split(' ').map(word => {
                    return word.charAt(0).toUpperCase() + word.slice(1);
                }).join(' ');
            };

            const fullNameInput = document.querySelector('input[name="full_name"]');
            const addressInput = document.querySelector('textarea[name="address"]');

            if (fullNameInput) {
                fullNameInput.addEventListener('blur', function() {
                    this.value = titleCase(this.value);
                });
            }

            if (addressInput) {
                addressInput.addEventListener('blur', function() {
                    this.value = titleCase(this.value);
                });
            }

            // Super Admin & Office In-Charge: Dynamic Parent Selection & Hint Update
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge())
            const designationSelect = document.getElementById('designation-select');
            const parentSelect = document.getElementById('parent-select');
            const hintDesignation = document.getElementById('hint-designation');
            const potentialParents = @json($potentialParents ?? []);

            @if(auth()->user()->isSuperAdmin())
            const officeInChargeUplineSection = document.getElementById('office-in-charge-upline-section');
            const uplineDesignationSelect = document.getElementById('upline-designation-select');
            const uplinePersonSelect = document.getElementById('upline-person-select');
            const potentialUplines = @json($potentialUplines ?? []);

            // Handle upline designation change
            uplineDesignationSelect.addEventListener('change', function() {
                const uplineDesignation = this.value;
                uplinePersonSelect.innerHTML = '<option value="">Select Person</option>';

                if (uplineDesignation && potentialUplines[uplineDesignation]) {
                    potentialUplines[uplineDesignation].forEach(upline => {
                        const name = upline.profile ? upline.profile.full_name : upline.email;
                        const option = new Option(`${name} (${upline.employee_id})`, upline.id);
                        if (upline.id == "{{ old('upline_id') }}") {
                            option.selected = true;
                        }
                        uplinePersonSelect.add(option);
                    });
                }
                
                // Sync parent if Office In-Charge is selected
                if (designationSelect.value === 'office_in_charge' || designationSelect.value === 'camp_organizer') {
                     const selectedOption = uplinePersonSelect.options[uplinePersonSelect.selectedIndex];
                     parentSelect.innerHTML = '';
                     if (uplinePersonSelect.value) {
                         parentSelect.add(new Option(selectedOption.text, selectedOption.value, true, true));
                     } else {
                         parentSelect.add(new Option("Auto-assigned from Upline", ""));
                     }
                }
            });
            
            // Allow manual upline change to update parent
            uplinePersonSelect.addEventListener('change', function() {
                if (designationSelect.value === 'office_in_charge' || designationSelect.value === 'camp_organizer') {
                     const selectedOption = this.options[this.selectedIndex];
                     parentSelect.innerHTML = '';
                     if (this.value) {
                         parentSelect.add(new Option(selectedOption.text, selectedOption.value, true, true));
                     } else {
                         parentSelect.add(new Option("Auto-assigned from Upline", ""));
                     }
                }
            });
            @endif

            designationSelect.addEventListener('change', function() {
                const designation = this.value;
                parentSelect.innerHTML = '<option value="">Select Parent</option>';
                
                // Update ID label based on designation
                const idLabel = document.getElementById('id-generation-label');
                if (idLabel) {
                    const empDesignations = ['office_in_charge', 'staff', 'camp_organizer'];
                    idLabel.innerText = empDesignations.includes(designation) ? 'Employee ID Generation' : 'Volunteer ID Generation';
                }
                
                // Update Hint
                const hintMap = {
                    'super_admin': 'SA',
                    'office_in_charge': 'OI',
                    'camp_organizer': 'CO',
                    'hs': 'HS',
                    'dm': 'DM',
                    'bm': 'BM',
                    'rm': 'RM',
                    'ro': 'RO',
                    'staff': 'PH'
                };
                hintDesignation.innerText = hintMap[designation] || 'XX';

                @if(auth()->user()->isSuperAdmin())
                // Show/hide Office In-Charge upline section
                if (designation === 'office_in_charge' || designation === 'camp_organizer') {
                    officeInChargeUplineSection.classList.remove('hidden');
                    uplineDesignationSelect.required = true;
                    uplinePersonSelect.required = true;
                    uplineDesignationSelect.disabled = false;
                    uplinePersonSelect.disabled = false;
                } else {
                    officeInChargeUplineSection.classList.add('hidden');
                    uplineDesignationSelect.required = false;
                    uplinePersonSelect.required = false;
                    uplineDesignationSelect.disabled = true;
                    uplinePersonSelect.disabled = true;
                    uplineDesignationSelect.value = '';
                    uplinePersonSelect.innerHTML = '<option value="">Select Upline Designation First</option>';
                }
                @endif

                // Default state for Parent & Camp selection
                const parentWrapper = document.getElementById('parent-selection-wrapper');
                const campWrapper = document.getElementById('camp-selection-wrapper');
                const postWrapper = document.getElementById('post-selection-wrapper');
                const campSelect = document.getElementById('camp-select');

                if (parentWrapper) parentWrapper.classList.remove('hidden');
                if (campWrapper) campWrapper.classList.add('hidden');
                if (postWrapper) {
                    if (designation === 'super_admin') {
                        postWrapper.classList.remove('hidden');
                    } else {
                        postWrapper.classList.add('hidden');
                    }
                }
                if (campSelect) campSelect.required = false;
                
                if (parentSelect) {
                    parentSelect.disabled = false;
                    const parentDiv = parentSelect.closest('div');
                    if (parentDiv) parentDiv.classList.remove('opacity-50');
                }

                // Roles that don't need manual parent selection (Top Level)
                if (designation === 'super_admin' || designation === 'hs' || designation === 'staff') {
                    if (parentSelect) {
                        parentSelect.innerHTML = '<option value="">None (Top Level)</option>';
                        parentSelect.required = false;
                    }
                    
                    if (designation === 'staff') {
                        if (parentWrapper) parentWrapper.classList.add('hidden');
                        if (campWrapper) campWrapper.classList.remove('hidden');
                        if (campSelect) campSelect.required = true;
                    } else {
                        if (parentSelect) {
                            parentSelect.disabled = true;
                            const parentDiv = parentSelect.closest('div');
                            if (parentDiv) parentDiv.classList.add('opacity-50');
                        }
                    }
                } else if (designation === 'office_in_charge') {
                    // Office In-Charge gets parent from Upline
                    if (parentSelect) {
                        parentSelect.innerHTML = '<option value="">Auto-assigned from Upline</option>';
                        parentSelect.required = false;
                        parentSelect.disabled = true;
                        const parentDiv = parentSelect.closest('div');
                        if (parentDiv) parentDiv.classList.add('opacity-50');
                    }
                    
                    // Trigger sync if we have a value
                    const ups = document.getElementById('upline-person-select');
                    if (ups && ups.value && parentSelect) {
                        const opt = ups.options[ups.selectedIndex];
                        parentSelect.innerHTML = '';
                        parentSelect.add(new Option(opt.text, opt.value, true, true));
                    }
                } else {
                    if (parentSelect) parentSelect.required = true;

                    let targetParentDesignation = '';
                    if (designation === 'dm') targetParentDesignation = 'hs';
                    else if (designation === 'bm') targetParentDesignation = 'dm';
                    else if (designation === 'rm') targetParentDesignation = 'bm';
                    else if (designation === 'ro') targetParentDesignation = 'rm';

                    if (targetParentDesignation && potentialParents[targetParentDesignation]) {
                        potentialParents[targetParentDesignation].forEach(parent => {
                            const name = parent.profile ? parent.profile.full_name : parent.email;
                            const option = new Option(`${name} (${parent.employee_id})`, parent.id);
                            if (parent.id == "{{ old('parent_id') }}") {
                                option.selected = true;
                            }
                            parentSelect.add(option);
                        });
                        
                        // Auto-select if only one option (e.g. Super Admin for DM)
                        if (potentialParents[targetParentDesignation].length === 1 && !parentSelect.value) {
                            parentSelect.selectedIndex = 1;
                        }
                    }
                }

            });
            @endif
            let currentAmount = 0;

            // Define payment UI elements
            const paymentModeSection = document.getElementById('payment-mode-section');
            const modeSelect = document.getElementById('payment-mode-select');
            const upiWrapper = document.getElementById('upi-payment-wrapper');
            const upiAppContainer = document.getElementById('upi-app-container');
            const upiQrContainer = document.getElementById('upi-qr-container');
            const couponSection = document.getElementById('coupon-code-section');
            const verifySection = document.getElementById('payment-verification-section');
            const finalConfirm = document.getElementById('final-confirmation-section');
            const methodInput = document.getElementById('payment_method_input');
            const orDivider = document.getElementById('payment-or-divider');
            const registerButton = document.getElementById('register-button');
            const donationSection = document.getElementById('donation-section');
            const donationAmountDisplay = document.getElementById('donation-amount-display');
            
            // Payment Inputs
            const screenshotInput = document.getElementById('payment_screenshot_input');
            const confirmationCheck = document.getElementById('payment_confirmation_checkbox'); // Likely renamed or removed in HTML, checking...
            const paymentConfirmedCheck = document.getElementById('payment_confirmed'); // This is the new one

            // File Upload UI
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const fileSelectedState = document.getElementById('file-selected-state');
            const selectedFilename = document.getElementById('selected-filename');

            // Handle Designation Change (Super Admin / Office In Charge)
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge())
            
            // ... (Previous logic for Parent/Upline remains same) ...

            designationSelect.addEventListener('change', function() {
                const designation = this.value;
                parentSelect.innerHTML = '<option value="">Select Parent</option>';

                // Update ID label based on designation
                const idLabel2 = document.getElementById('id-generation-label');
                if (idLabel2) {
                    const empDesignations2 = ['office_in_charge', 'staff', 'camp_organizer'];
                    idLabel2.innerText = empDesignations2.includes(designation) ? 'Employee ID Generation' : 'Volunteer ID Generation';
                }

                // ... (Location / Parent Logic - keep existing) ...
                // Update Hint
                const hintMap = {
                    'super_admin': 'SA',
                    'office_in_charge': 'OI',
                    'camp_organizer': 'CO',
                    'hs': 'HS',
                    'dm': 'DM',
                    'bm': 'BM',
                    'rm': 'RM',
                    'ro': 'RO',
                    'staff': 'PH'
                };
                hintDesignation.innerText = hintMap[designation] || 'XX';

                @if(auth()->user()->isSuperAdmin())
                if (designation === 'office_in_charge' || designation === 'camp_organizer') {
                    officeInChargeUplineSection.classList.remove('hidden');
                    uplineDesignationSelect.required = true;
                    uplinePersonSelect.required = true;
                    uplineDesignationSelect.disabled = false;
                    uplinePersonSelect.disabled = false;
                } else {
                    officeInChargeUplineSection.classList.add('hidden');
                    uplineDesignationSelect.required = false;
                    uplinePersonSelect.required = false;
                    uplineDesignationSelect.disabled = true;
                    uplinePersonSelect.disabled = true;
                    uplineDesignationSelect.value = '';
                    uplinePersonSelect.innerHTML = '<option value="">Select Upline Designation First</option>';
                }
                @endif

                // Default state for Parent & Camp
                const parentWrapper = document.getElementById('parent-selection-wrapper');
                const campWrapper = document.getElementById('camp-selection-wrapper');
                const campSelect = document.getElementById('camp-select');

                if (parentWrapper) parentWrapper.classList.remove('hidden');
                if (campWrapper) campWrapper.classList.add('hidden');
                if (campSelect) campSelect.required = false;
                
                if (parentSelect) {
                    parentSelect.disabled = false;
                    const parentDiv = parentSelect.closest('div');
                    if (parentDiv) parentDiv.classList.remove('opacity-50');
                }

                if (designation === 'super_admin' || designation === 'hs' || designation === 'staff') {
                   if (parentSelect) {
                        parentSelect.innerHTML = '<option value="">None (Top Level)</option>';
                        parentSelect.required = false;
                    }
                    if (designation === 'staff') {
                        if (parentWrapper) parentWrapper.classList.add('hidden');
                        if (campWrapper) campWrapper.classList.remove('hidden');
                        if (campSelect) campSelect.required = true;
                    } else {
                        if (parentSelect) {
                            parentSelect.disabled = true;
                            const parentDiv = parentSelect.closest('div');
                            if (parentDiv) parentDiv.classList.add('opacity-50');
                        }
                    }
                } else if (designation === 'office_in_charge' || designation === 'camp_organizer') {
                    if (parentSelect) {
                        parentSelect.innerHTML = '<option value="">Auto-assigned from Upline</option>';
                        parentSelect.required = false;
                        parentSelect.disabled = true;
                        const parentDiv = parentSelect.closest('div');
                        if (parentDiv) parentDiv.classList.add('opacity-50');
                    }
                     const ups = document.getElementById('upline-person-select');
                    if (ups && ups.value && parentSelect) {
                        const opt = ups.options[ups.selectedIndex];
                        parentSelect.innerHTML = '';
                        parentSelect.add(new Option(opt.text, opt.value, true, true));
                    }
                } else {
                    if (parentSelect) parentSelect.required = true;
                     let targetParentDesignation = '';
                    if (designation === 'dm') targetParentDesignation = 'hs';
                    else if (designation === 'bm') targetParentDesignation = 'dm';
                    else if (designation === 'rm') targetParentDesignation = 'bm';
                    else if (designation === 'ro') targetParentDesignation = 'rm';

                    if (targetParentDesignation && potentialParents[targetParentDesignation]) {
                        potentialParents[targetParentDesignation].forEach(parent => {
                           const name = parent.profile ? parent.profile.full_name : parent.email;
                            const option = new Option(`${name} (${parent.employee_id})`, parent.id);
                            if (parent.id == "{{ old('parent_id') }}") {
                                option.selected = true;
                            }
                            parentSelect.add(option);
                        });
                         if (potentialParents[targetParentDesignation].length === 1 && !parentSelect.value) {
                            parentSelect.selectedIndex = 1;
                        }
                    }
                }

                // --- PAYMENTS LOGIC ---
                const amounts = {
                    'dm': 999,
                    'bm': 999,
                    'rm': 499,
                    'ro': 199
                };

                // Remove hidden from register button initially, will be handled by sections
                registerButton.classList.add('hidden'); 
                 // Reset Coupon
                resetCouponUI();

                if (amounts[designation]) {
                    currentAmount = amounts[designation];
                    donationSection.classList.remove('hidden');
                    donationAmountDisplay.innerText = currentAmount;
                    
                    // Show Payment Mode Section
                    paymentModeSection.classList.remove('hidden');
                    
                    // Trigger section toggle to refresh view (e.g. valid QR code amount)
                    if (modeSelect.value) {
                        toggleSections(modeSelect.value);
                    } else {
                        // Default to QR
                        modeSelect.value = 'upi_qr';
                        toggleSections('upi_qr');
                    }
                } else {
                    currentAmount = 0;
                    donationSection.classList.add('hidden');
                    // Hide all payment stuff
                    paymentModeSection.classList.add('hidden');
                    upiWrapper.classList.add('hidden');
                    couponSection.classList.add('hidden');
                    verifySection.classList.add('hidden');
                    
                    registerButton.classList.remove('hidden'); // Free registration
                }
            });
            @endif

            // Regular User Logic
            @if(!auth()->user()->isSuperAdmin() && !auth()->user()->isOfficeInCharge())
            const allowedDesignation = '{{ $allowedDesignation }}';
            const amounts = {
                'dm': 999,
                'bm': 999,
                'rm': 499,
                'ro': 199
            };
            
             if (amounts[allowedDesignation]) {
                currentAmount = amounts[allowedDesignation];
                donationSection.classList.remove('hidden');
                donationAmountDisplay.innerText = currentAmount;
                paymentModeSection.classList.remove('hidden');
                
                // Default to QR
                if (!modeSelect.value) {
                     modeSelect.value = 'upi_qr';
                }
                toggleSections(modeSelect.value);

            } else {
                currentAmount = 0;
                donationSection.classList.add('hidden');
                paymentModeSection.classList.add('hidden');
                upiWrapper.classList.add('hidden');
                couponSection.classList.add('hidden');
                verifySection.classList.add('hidden');
                registerButton.classList.remove('hidden');
            }
            @endif


            // --- TOGGLE SECTIONS LOGIC ---
            modeSelect.addEventListener('change', function() {
                toggleSections(this.value);
            });

            function toggleSections(mode) {
                // Hide All First
                upiWrapper.classList.add('hidden');
                upiAppContainer.classList.add('hidden');
                upiQrContainer.classList.add('hidden');
                couponSection.classList.add('hidden');
                verifySection.classList.add('hidden');
                finalConfirm.classList.add('hidden');
                
                // Reset Requirements
                if(screenshotInput) screenshotInput.required = false;
                if(paymentConfirmedCheck) paymentConfirmedCheck.required = false;

                // Reset Coupon if switching away (optional, but good for cleanliness)
                if (mode !== 'coupon') {
                     // resetCouponUI(); // Maybe too aggressive? Let's keep input but hide logic
                }
                
                if (currentAmount <= 0) {
                     registerButton.classList.remove('hidden');
                     return; 
                }
                
                // Hide register button by default in payment flows until complete
                registerButton.classList.add('hidden');

                if (mode === 'upi_app') {
                    upiWrapper.classList.remove('hidden');
                    upiAppContainer.classList.remove('hidden');
                    methodInput.value = 'upi_app';
                    
                    // Update Link
                    const upiPayLink = document.getElementById('upi-pay-link');
                    // Calculate LINK
                    const pa = "9735563157-4@ybl";
                    const pn = "Humanity Foundation";
                    const cu = "INR";
                    upiPayLink.href = `upi://pay?pa=${pa}&pn=${pn}&am=${currentAmount}&cu=${cu}`;

                    // Click listener for App flow
                    upiPayLink.onclick = function() {
                        const btn = this;
                         const original = btn.innerHTML;
                         btn.classList.add('opacity-50', 'pointer-events-none');
                         let sec = 30;
                         const t = setInterval(() => {
                             sec--;
                             btn.innerHTML = `Wait ${sec}s...`;
                             if(sec <= 0){
                                 clearInterval(t);
                                 btn.innerHTML = original;
                                 btn.classList.remove('opacity-50', 'pointer-events-none');
                                 
                                 // Show verify
                                 verifySection.classList.remove('hidden');
                                 finalConfirm.classList.remove('hidden');
                                 screenshotInput.required = true;
                                 // Checkboxes are usually required manually or ignored
                                 if(paymentConfirmedCheck) paymentConfirmedCheck.required = true; // Use new one
                             }
                         }, 1000);
                    };

                } else if (mode === 'upi_qr') {
                    upiWrapper.classList.remove('hidden');
                    upiQrContainer.classList.remove('hidden');
                    methodInput.value = 'upi_qr';
                    
                    // Show verify immediately
                    verifySection.classList.remove('hidden');
                    finalConfirm.classList.remove('hidden');
                    screenshotInput.required = true;
                    if(paymentConfirmedCheck) paymentConfirmedCheck.required = true;

                    setTimeout(() => generateUPIQR(currentAmount), 100);

                } else if (mode === 'coupon') {
                    couponSection.classList.remove('hidden');
                    methodInput.value = 'coupon';
                    // Register button only shows if valdiation success
                }
            }

            function generateUPIQR(amount) {
                const qrContainer = document.getElementById('qr-code');
                const qrAmountDisplay = document.getElementById('qr-amount-display');
                if(qrAmountDisplay) qrAmountDisplay.innerText = amount.toFixed(2);
                
                qrContainer.innerHTML = '';
                const pa = "9735563157-4@ybl";
                const pn = "Humanity Foundation";
                const upiUrl = `upi://pay?pa=${pa}&pn=${pn}&am=${amount.toFixed(2)}&cu=INR`;
                
                if (typeof QRCode !== 'undefined') {
                    new QRCode(qrContainer, {
                        text: upiUrl,
                        width: 180,
                        height: 180,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                }
            }

            // File Input Change
            if (screenshotInput) {
                screenshotInput.addEventListener('change', function() {
                    if (this.files && this.files.length > 0) {
                        const file = this.files[0];
                        if (selectedFilename) selectedFilename.textContent = file.name;
                        if (uploadPlaceholder) uploadPlaceholder.classList.add('hidden');
                        if (fileSelectedState) fileSelectedState.classList.remove('hidden');
                        
                        // Show Submit Button
                        registerButton.classList.remove('hidden');
                    } else {
                        if (uploadPlaceholder) uploadPlaceholder.classList.remove('hidden');
                        if (fileSelectedState) fileSelectedState.classList.add('hidden');
                        registerButton.classList.add('hidden');
                    }
                });
            }
            
            function resetCouponUI() {
                const ci = document.getElementById('coupon_code_input');
                const btn = document.getElementById('apply-coupon-btn');
                const sMsg = document.getElementById('coupon-success-message');
                const eMsg = document.getElementById('coupon-error-message');
                if(ci) { ci.value = ''; ci.readOnly = false; }
                if(btn) { btn.disabled = false; btn.innerHTML = 'Apply Code'; btn.classList.remove('bg-emerald-500'); btn.classList.add('bg-accent'); }
                if(sMsg) sMsg.classList.add('hidden');
                if(eMsg) eMsg.classList.add('hidden');
            }


            // Initialize for Super Admin if designation already selected
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge())
             if (designationSelect && designationSelect.value) {
                triggerSelectChange(designationSelect);
                if (uplineDesignationSelect && uplineDesignationSelect.value) {
                    triggerSelectChange(uplineDesignationSelect);
                }
             }
            @endif


            // PAN Validation on Submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                
                // ... (Existing PAN checks etc) ...
                const panInput = document.querySelector('input[name="pan_number"]');
                const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                if (panInput && panInput.value && !panPattern.test(panInput.value)) {
                    e.preventDefault();
                    alert('Invalid PAN Card format!');
                    panInput.focus();
                    return false;
                }
                // ... (Aadhaar, Phone, Pin Code checks) ...
                const aadhaarInput = document.querySelector('input[name="aadhaar_number"]');
                if (aadhaarInput && aadhaarInput.value && aadhaarInput.value.length !== 12) {
                     e.preventDefault(); alert('Aadhaar must be 12 digits'); aadhaarInput.focus(); return false;
                }
                 const phoneInput = document.querySelector('input[name="phone_number"]');
                if (phoneInput && phoneInput.value && phoneInput.value.length !== 10) {
                    e.preventDefault(); alert('Phone must be 10 digits'); phoneInput.focus(); return false;
                }
                const pinInput = document.querySelector('input[name="pin_code"]');
                if (pinInput && pinInput.value && pinInput.value.length !== 6) {
                    e.preventDefault(); alert('Pin Code must be 6 digits'); pinInput.focus(); return false;
                }
            });


        });

        // Coupon Code Validation
        window.validateCoupon = async function() {
            const couponInput = document.getElementById('coupon_code_input');
            const couponCode = couponInput.value.trim();
            const applyBtn = document.getElementById('apply-coupon-btn');
            const successMsg = document.getElementById('coupon-success-message');
            const errorMsg = document.getElementById('coupon-error-message');
            const errorText = document.getElementById('coupon-error-text');
            const registerButton = document.getElementById('register-button');
            const paymentModeSection = document.getElementById('payment-mode-section');
            const upiWrapper = document.getElementById('upi-payment-wrapper');
            const verifySection = document.getElementById('payment-verification-section');

            // Designation logic for determining validity
            let designation;
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge())
                designation = document.getElementById('designation-select').value;
            @else
                designation = '{{ $allowedDesignation }}';
            @endif

            if (!couponCode) {
                if(errorText) errorText.textContent = 'Please enter a coupon code.';
                if(errorMsg) errorMsg.classList.remove('hidden');
                return;
            }
             if (!designation) {
                if(errorText) errorText.textContent = 'Please select a designation first.';
                 if(errorMsg) errorMsg.classList.remove('hidden');
                return;
            }

            // Loading UI
            const originalContent = applyBtn.innerHTML;
            applyBtn.disabled = true;
            applyBtn.innerHTML = 'Verifying...';
            if(successMsg) successMsg.classList.add('hidden');
            if(errorMsg) errorMsg.classList.add('hidden');

            try {
                const response = await fetch('{{ route("coupons.validate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        code: couponCode,
                        designation: designation
                    })
                });
                
                const data = await response.json();
                
                if (response.ok && data.valid) {
                    // Success
                    if(successMsg) successMsg.classList.remove('hidden');
                    document.getElementById('coupon-success-text').textContent = data.message || 'Coupon Applied!';
                    
                    // Hide Payment Sections
                    if(paymentModeSection) paymentModeSection.classList.add('hidden');
                    if(upiWrapper) upiWrapper.classList.add('hidden');
                    if(verifySection) verifySection.classList.add('hidden');
                    
                    // Show Submit
                    registerButton.classList.remove('hidden');
                    
                    // Disable requirements
                    const screenInput = document.getElementById('payment_screenshot_input');
                    const confirmCheck = document.getElementById('payment_confirmed');
                    if(screenInput) screenInput.required = false;
                    if(confirmCheck) confirmCheck.required = false;

                    // Lock Coupon UI
                    couponInput.readOnly = true;
                    applyBtn.disabled = true;
                    applyBtn.classList.remove('bg-accent');
                    applyBtn.classList.add('bg-emerald-500');
                    applyBtn.innerHTML = 'Applied ✓';

                } else {
                     if(errorText) errorText.textContent = data.message || 'Invalid Coupon';
                     if(errorMsg) errorMsg.classList.remove('hidden');
                     applyBtn.disabled = false;
                     applyBtn.innerHTML = originalContent;
                }

            } catch (error) {
                console.error(error);
                if(errorText) errorText.textContent = 'Network Error';
                if(errorMsg) errorMsg.classList.remove('hidden');
                applyBtn.disabled = false;
                applyBtn.innerHTML = originalContent;
            }
        };

        // Allow Enter key to validate coupon
        if(document.getElementById('coupon_code_input')){
            document.getElementById('coupon_code_input').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    validateCoupon();
                }
            });
        }
        // Real-time Uniqueness Check
        const uniqueFields = ['phone_number', 'aadhaar_number', 'pan_number'];
        uniqueFields.forEach(field => {
            const input = document.querySelector(`input[name="${field}"]`);
            if (input) {
                input.addEventListener('blur', function() {
                    const value = this.value;
                    if (value) {
                        fetch('{{ route("users.check-uniqueness") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ field: field, value: value })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Duplicate Entry',
                                    text: data.message,
                                    confirmButtonColor: '#3C50E0',
                                });
                                this.value = ''; // Clear input
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            }
        });

    </script>
@endsection