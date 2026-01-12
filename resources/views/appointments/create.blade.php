@extends('layouts.app')

@section('title', 'Schedule Appointment')
@section('header_title', 'Schedule Appointment')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('patients.index') }}"
                class="flex items-center text-slate-500 hover:text-accent transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Surveys
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-slate-100">
            <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-black text-xl text-slate-800">New Appointment</h3>
                    <p class="text-sm text-slate-500 font-bold mt-1">Schedule a doctor visit for this patient</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>

            <form action="{{ route('patients.appointments.store', $patient->id) }}" method="POST" class="p-8 space-y-8">
                @csrf

                <!-- Patient Info (Read Only) -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Patient Full
                        Name</label>
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-accent/10 text-accent flex items-center justify-center font-black text-sm">
                            {{ substr($patient->full_name, 0, 1) }}
                        </div>
                        <span class="font-bold text-slate-800 text-lg">{{ $patient->full_name }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Doctor Type -->
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label for="doctor_type"
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Doctor Type /
                                Specialist</label>
                            <div class="relative">
                                <select name="doctor_type" id="doctor_type" onchange="handleSpecialistSelection(this.value)"
                                    class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-accent focus:ring-0 transition-colors font-bold text-slate-700 appearance-none">
                                    <option value="">Select Specialist</option>
                                    @foreach(['General', 'Orthopedic', 'Eye Specialist', 'Oncologist', 'Gynecologist', 'Pediatric', 'Dermatologist', 'Gastroenterologist', 'ENT', 'Urologist'] as $type)
                                        <option value="{{ $type }}" {{ old('doctor_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                    <option value="Any other" {{ old('doctor_type') == 'Any other' ? 'selected' : '' }}>Any
                                        other</option>
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </div>
                            @error('doctor_type')
                                <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="specialist-other-container"
                            class="space-y-2 {{ old('doctor_type') == 'Any other' ? '' : 'hidden' }}">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Specify
                                Other Specialist</label>
                            <input type="text" id="specialist-other-input" name="doctor_type_other"
                                value="{{ old('doctor_type_other') }}"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-accent focus:ring-0 transition-colors font-bold text-slate-700 placeholder:font-normal placeholder:text-slate-400"
                                placeholder="Describe the specialist...">
                            @error('doctor_type_other')
                                <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="space-y-2">
                        <label for="location"
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Camp
                            Location</label>
                        <input type="text" name="location" id="location" value="{{ old('location') }}"
                            placeholder="e.g. City Community Hall, School Ground"
                            oninput="this.value = this.value.replace(/\b\w/g, l => l.toUpperCase())"
                            class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-accent focus:ring-0 transition-colors font-bold text-slate-700 placeholder:font-normal placeholder:text-slate-400">
                        @error('location')
                            <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Standard Date & Time Inputs -->
                    <div class="space-y-2">
                        <label for="appointment_date"
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Appointment
                            Date</label>
                        <input type="date" name="appointment_date" id="appointment_date"
                            value="{{ old('appointment_date') }}"
                            class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-accent focus:ring-0 transition-colors font-bold text-slate-700">
                        @error('appointment_date')
                            <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="appointment_time"
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Appointment
                            Time</label>
                        <input type="time" name="appointment_time" id="appointment_time"
                            value="{{ old('appointment_time') }}"
                            class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-accent focus:ring-0 transition-colors font-bold text-slate-700">
                        @error('appointment_time')
                            <p class="text-xs text-rose-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <a href="{{ route('patients.index') }}"
                        class="px-6 py-3 bg-slate-100 text-slate-500 font-black uppercase tracking-widest text-[10px] rounded-xl hover:bg-slate-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" id="submit-btn"
                        class="px-8 py-3 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition-all flex items-center">
                        <span id="btn-text">Schedule Appointment</span>
                        <div id="btn-loader"
                            class="hidden ml-2 w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin">
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.querySelector('form').addEventListener('submit', function (e) {
            const btn = document.getElementById('submit-btn');
            const text = document.getElementById('btn-text');
            const loader = document.getElementById('btn-loader');

            if (btn.disabled) return;

            // Get values for validation
            const doctorType = document.getElementById('doctor_type').value;
            const doctorTypeOther = document.getElementById('specialist-other-input').value;
            const location = document.getElementById('location').value;
            const apptDateInput = document.querySelector('input[name="appointment_date"]');
            const apptTimeInput = document.querySelector('input[name="appointment_time"]');
            const apptDate = apptDateInput ? apptDateInput.value : '';
            const apptTime = apptTimeInput ? apptTimeInput.value : '';

            // Validation check
            let errors = [];
            if (!doctorType) errors.push('Select a Specialist Type');
            if (doctorType === 'Any other' && !doctorTypeOther.trim()) errors.push('Specify the other Specialist');
            if (!location.trim()) errors.push('Enter the Camp Location');
            if (!apptDate) errors.push('Select an Appointment Date');
            if (!apptTime) errors.push('Select an Appointment Time');

            if (errors.length > 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
                    html: `<div class="text-left font-bold text-sm"><ul class="list-disc list-inside">${errors.map(err => `<li>${err}</li>`).join('')}</ul></div>`,
                    confirmButtonColor: '#F2994A',
                    background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1C2434',
                });
                return;
            }

            e.preventDefault();

            Swal.fire({
                title: 'Schedule Appointment?',
                text: "Are you sure you want to book this appointment with the selected details?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3C50E0',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Schedule it!',
                background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1C2434',
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.disabled = true;
                    btn.classList.add('opacity-70', 'cursor-not-allowed');
                    text.innerText = 'Scheduling...';
                    loader.classList.remove('hidden');
                    this.submit();
                }
            });
        });

        function handleSpecialistSelection(value) {
            const container = document.getElementById('specialist-other-container');
            const input = document.getElementById('specialist-other-input');

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
@endsection