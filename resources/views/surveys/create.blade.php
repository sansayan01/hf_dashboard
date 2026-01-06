@extends('layouts.app')

@section('title', 'Create Survey')
@section('header_title', 'New Field Survey')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                <h3 class="font-black text-xl text-slate-800 dark:text-white">Survey Participant Details</h3>
                <p class="text-sm text-slate-500 mt-1 font-medium">Please enter accurate information for the survey record
                </p>
            </div>

            <form action="{{ route('surveys.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Name</label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="John Doe"
                            oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                        @error('full_name') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Age -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Age</label>
                        <input type="number" name="age" value="{{ old('age') }}" required min="1" max="120"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="25">
                        @error('age') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Gender -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Gender</label>
                        <select name="gender" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone Number -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Phone
                            Number</label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required maxlength="10"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="9876543210" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        @error('phone_number') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- PIN Code -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">PIN Code</label>
                        <input type="text" name="pin" value="{{ old('pin') }}" required maxlength="6"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="700001" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                        @error('pin') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Address -->
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Address</label>
                    <textarea name="address" rows="3" required
                        class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                        placeholder="House No, Street, Village/Town, District, State"
                        oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">{{ old('address') }}</textarea>
                    @error('address') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Health Issues -->
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Health Issues (Category)</label>
                        <select id="health-select" name="health_issue_category" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white appearance-none"
                            onchange="handleHealthSelection(this.value)">
                            <option value="">Select Health Category</option>
                            <option value="Gas">Gas</option>
                            <option value="Sugar">Sugar</option>
                            <option value="Pressure">Pressure</option>
                            <option value="Thyroid">Thyroid</option>
                            <option value="Uric Acid">Uric Acid</option>
                            <option value="Skin/Hair">Skin/Hair</option>
                            <option value="Heart">Heart</option>
                            <option value="Eye">Eye</option>
                            <option value="ENT">ENT</option>
                            <option value="Dental">Dental</option>
                            <option value="Any other">Any other</option>
                        </select>
                    </div>

                    <div id="health-other-container" class="space-y-2 hidden">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Specify Other Health Issue</label>
                        <textarea id="health-other-input" name="health_issue_other" rows="3"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                            placeholder="Enter the specific health issue here..."></textarea>
                    </div>
                </div>

                <script>
                    function handleHealthSelection(value) {
                        const container = document.getElementById('health-other-container');
                        const input = document.getElementById('health-other-input');
                        
                        if (value === 'Any other') {
                            container.classList.remove('hidden');
                            input.required = true;
                            input.focus();
                        } else {
                            container.classList.add('hidden');
                            input.required = false;
                        }
                    }
                </script>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-100 dark:border-white/5">
                    <a href="{{ route('surveys.index') }}"
                        class="px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-black uppercase tracking-widest text-[10px] rounded-2xl hover:bg-slate-200 transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-4 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition-all">
                        Submit Survey
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection