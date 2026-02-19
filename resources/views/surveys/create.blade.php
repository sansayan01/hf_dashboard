@extends('layouts.app')

@section('title', 'Create Survey')
@section('header_title', 'New Field Survey')

@section('content')
    <div class="max-w-4xl mx-auto pb-20">
        <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                <h3 class="font-black text-xl text-slate-800 dark:text-white">Survey Participant Details</h3>
                <p class="text-sm text-slate-500 mt-1 font-medium">Please enter accurate information for the survey record</p>
            </div>

            <form action="{{ route('surveys.store') }}" method="POST" class="p-8 space-y-8">
                @csrf

                @if(count($users) > 0)
                    <div class="p-6 bg-indigo-50/50 dark:bg-indigo-500/5 rounded-2xl border-2 border-indigo-100 dark:border-indigo-500/20 space-y-4">
                        <div class="flex items-center space-x-3 text-indigo-600 dark:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <h4 class="text-sm font-black uppercase tracking-widest">{{ auth()->user()->isSuperAdmin() ? 'Create Behalf Of' : 'Register for Team Member' }}</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest">Select Team Member (Search by Name or ID)</label>
                            <input list="team_members" name="created_by_user_search" id="created_by_user_search"
                                class="w-full px-5 py-4 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl outline-none text-sm font-bold text-slate-700 dark:text-white transition-all focus:border-indigo-500"
                                placeholder="Start typing name or volunteer ID..."
                                oninput="updateUserId(this.value)">
                            <input type="hidden" name="created_by_user" id="created_by_user" value="{{ old('created_by_user') }}">
                            <datalist id="team_members">
                                @foreach($users as $u)
                                    <option value="{{ $u->employee_id }} - {{ $u->profile->full_name }}" data-id="{{ $u->id }}">
                                @endforeach
                            </datalist>
                            <p class="text-[10px] text-slate-400 font-medium italic">If left empty, the survey will be registered under your name.</p>
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="Full Name"
                            oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">
                        @error('full_name') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Age -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Age <span class="text-danger">*</span></label>
                        <input type="number" name="age" value="{{ old('age') }}" required min="1" max="120"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="Age">
                        @error('age') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Gender -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Gender <span class="text-danger">*</span></label>
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
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Phone Number <span class="text-danger">*</span></label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" required maxlength="10"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="10-digit Phone Number"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)">
                        @error('phone_number') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- PIN Code -->
                    <div class="space-y-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">PIN Code <span class="text-danger">*</span></label>
                        <input type="text" name="pin" value="{{ old('pin') }}" required maxlength="6"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white"
                            placeholder="6-digit PIN"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)">
                        @error('pin') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Address -->
                <div class="space-y-2">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Full Address <span class="text-danger">*</span></label>
                    <textarea name="address" rows="3" required
                        class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                        placeholder="House No, Street, Village/Town etc."
                        oninput="this.value = this.value.split(' ').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')">{{ old('address') }}</textarea>
                    @error('address') <p class="text-xs text-danger font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Health Issues -->
                <div class="space-y-4">
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Health Issues (Category)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @php
                            $standardIssues = ['Gas', 'Sugar', 'Pressure', 'Thyroid', 'Uric Acid', 'Skin/Hair', 'Heart', 'Eye', 'ENT', 'Dental'];
                        @endphp
                        @foreach($standardIssues as $index => $issue)
                            <label class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-accent/30 cursor-pointer transition-all has-[:checked]:border-accent/50 has-[:checked]:bg-accent/5">
                                <input type="checkbox" name="health_issue_category[]" value="{{ $issue }}"
                                    class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent"
                                    {{ is_array(old('health_issue_category')) && in_array($issue, old('health_issue_category')) ? 'checked' : '' }}>
                                <span class="text-sm font-bold text-slate-700 dark:text-white">{{ $issue }}</span>
                            </label>
                        @endforeach
                        
                        <label class="flex items-center space-x-3 p-4 bg-slate-100/50 dark:bg-slate-800 rounded-2xl border-2 border-transparent hover:border-accent/30 cursor-pointer transition-all has-[:checked]:border-accent/50 has-[:checked]:bg-accent/5">
                            <input type="checkbox" name="health_issue_category[]" value="Any other" id="health_any_other"
                                class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent"
                                onchange="toggleHealthOther(this.checked)"
                                {{ is_array(old('health_issue_category')) && in_array('Any other', old('health_issue_category')) ? 'checked' : '' }}>
                            <span class="text-sm font-bold text-slate-700 dark:text-white">Any other</span>
                        </label>
                    </div>

                    <div id="health-other-container" class="space-y-2 {{ is_array(old('health_issue_category')) && in_array('Any other', old('health_issue_category')) ? '' : 'hidden' }}">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest">Specify Other Health Issue</label>
                        <textarea id="health-other-input" name="health_issue_other" rows="3"
                            class="w-full px-5 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white resize-none"
                            placeholder="Enter the specific health issue here...">{{ old('health_issue_other') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-8 border-t border-slate-100 dark:border-white/5">
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

@section('js')
    <script>
        function updateUserId(value) {
            const options = document.querySelectorAll('#team_members option');
            let foundId = '';
            options.forEach(option => {
                if (option.value === value) {
                    foundId = option.getAttribute('data-id');
                }
            });
            document.getElementById('created_by_user').value = foundId;
        }

        function toggleHealthOther(isChecked) {
            const container = document.getElementById('health-other-container');
            const input = document.getElementById('health-other-input');

            if (isChecked) {
                container.classList.remove('hidden');
                input.required = true;
                input.focus();
            } else {
                container.classList.add('hidden');
                input.required = false;
            }
        }
    </script>
@endsection