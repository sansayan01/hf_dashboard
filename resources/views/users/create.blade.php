@extends('layouts.app')

@section('title', 'Add New Member')
@section('header_title', 'Create New ' . strtoupper($allowedDesignation))

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-8 border-b border-slate-50">
                <h3 class="font-bold text-xl text-slate-800">Registration Form</h3>
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
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Assign Parent (Manager)</label>
                            <select name="parent_id" id="parent-select" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 focus:border-accent transition-all outline-none">
                                <option value="">Select Role First</option>
                            </select>
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
                                Office In-Charge Configuration: Select the upline this Office In-Charge will represent
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
                            <label class="block text-sm font-bold text-slate-700">Employee ID Generation</label>
                            
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

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Profile Picture</label>
                            <input type="file" name="profile_picture" accept="image/*"
                                class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-accent/10 transition-all outline-none file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-accent file:text-white hover:file:bg-accent/80">
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
                <div>
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

                <!-- Submit -->
                <div class="pt-10 border-t border-slate-100 flex items-center justify-between">
                    <button type="reset"
                        class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-slate-800 transition">Reset
                        Form</button>
                    <button type="submit"
                        class="px-10 py-4 bg-accent text-white font-bold rounded-xl shadow-xl shadow-accent/20 hover:shadow-md hover:-translate-y-0.5 transition-all">
                        Register User to Downline
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/locations.js') }}"></script>
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
                if (designationSelect.value === 'office_in_charge') {
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
                if (designationSelect.value === 'office_in_charge') {
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
                
                // Update Hint
                const hintMap = {
                    'super_admin': 'SA',
                    'office_in_charge': 'OI',
                    'hs': 'HS',
                    'dm': 'DM',
                    'bm': 'BM',
                    'rm': 'RM',
                    'ro': 'RO'
                };
                hintDesignation.innerText = hintMap[designation] || 'XX';

                @if(auth()->user()->isSuperAdmin())
                // Show/hide Office In-Charge upline section
                if (designation === 'office_in_charge') {
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

                // Roles that don't need manual parent selection (Top Level)
                if (designation === 'super_admin' || designation === 'hs') {
                    parentSelect.innerHTML = '<option value="">None (Top Level)</option>';
                    parentSelect.required = false;
                    parentSelect.closest('div').classList.add('opacity-50');
                    parentSelect.disabled = true;
                } else if (designation === 'office_in_charge') {
                    // Office In-Charge gets parent from Upline
                    parentSelect.innerHTML = '<option value="">Auto-assigned from Upline</option>';
                    parentSelect.required = false;
                    parentSelect.closest('div').classList.add('opacity-50');
                    parentSelect.disabled = true;
                    
                    // Trigger sync if we have a value
                    if (document.getElementById('upline-person-select').value) {
                        const ups = document.getElementById('upline-person-select');
                        const opt = ups.options[ups.selectedIndex];
                        parentSelect.innerHTML = '';
                        parentSelect.add(new Option(opt.text, opt.value, true, true));
                    }
                } else {
                    parentSelect.required = true;
                    parentSelect.closest('div').classList.remove('opacity-50');
                    parentSelect.disabled = false;

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

            if (designationSelect.value) {
                triggerSelectChange(designationSelect);
            }

            @if(auth()->user()->isSuperAdmin())
            // Trigger upline designation change if old value exists
            if (uplineDesignationSelect.value) {
                triggerSelectChange(uplineDesignationSelect);
            }
            @endif
            @endif

            // PAN Validation on Submit
            const form = document.querySelector('form');
            form.addEventListener('submit', function(e) {
                const panInput = document.querySelector('input[name="pan_number"]');
                const panPattern = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
                if (panInput && panInput.value && !panPattern.test(panInput.value)) {
                    e.preventDefault();
                    alert('Invalid PAN Card format!\n\nRules:\n1. First 5 must be LETTERS\n2. Next 4 must be DIGITS\n3. Last 1 must be a LETTER\n\nExample: ABCDE1234F');
                    panInput.focus();
                    return false;
                }

                const aadhaarInput = document.querySelector('input[name="aadhaar_number"]');
                if (aadhaarInput && aadhaarInput.value && aadhaarInput.value.length !== 12) {
                    e.preventDefault();
                    alert('Aadhaar Number must be exactly 12 digits.');
                    aadhaarInput.focus();
                    return false;
                }

                const phoneInput = document.querySelector('input[name="phone_number"]');
                if (phoneInput && phoneInput.value && phoneInput.value.length !== 10) {
                    e.preventDefault();
                    alert('Phone Number must be exactly 10 digits.');
                    phoneInput.focus();
                    return false;
                }

                const pinInput = document.querySelector('input[name="pin_code"]');
                if (pinInput && pinInput.value && pinInput.value.length !== 6) {
                    e.preventDefault();
                    alert('Pin Code must be exactly 6 digits.');
                    pinInput.focus();
                    return false;
                }
            });
        });
    </script>
@endsection