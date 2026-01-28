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
                        {{ ucfirst($patient->gender) }}</p>
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

            <form action="{{ route('membership.register', $patient->id) }}" method="POST" class="p-8 space-y-8">
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
                                Name</label>
                            <input type="text" name="relative_name"
                                value="{{ old('relative_name', $patient->relative_name) }}"
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
                                Number</label>
                            <input type="text" name="aadhar_number"
                                value="{{ old('aadhar_number', $patient->aadhar_number) }}" maxlength="12"
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
                            <label
                                class="block text-xs font-black text-slate-500 uppercase tracking-widest">District</label>
                            <select id="district-select" name="district"
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                                <option value="">Select District</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Block</label>
                            <select id="block-select" name="block" disabled
                                class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-amber-500 focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none disabled:opacity-50">
                                <option value="">Select Block</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">GP</label>
                            <select id="gp-select" name="gp" disabled
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
                                Code</label>
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

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-slate-100 dark:border-white/5">
                    <a href="{{ route('patients.show', $patient->id) }}"
                        class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest text-[10px] rounded-2xl hover:bg-slate-200 transition-all">
                        Discard
                    </a>
                    <button type="submit"
                        class="px-8 py-4 bg-amber-500 text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-amber-500/20 hover:scale-110 active:scale-95 transition-all flex items-center">
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