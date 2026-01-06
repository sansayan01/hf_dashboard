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
                    <div class="space-y-2">
                        <label for="doctor_type"
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-500">Doctor Type /
                            Specialist</label>
                        <div class="relative">
                            <select name="doctor_type" id="doctor_type"
                                class="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-xl focus:border-accent focus:ring-0 transition-colors font-bold text-slate-700 appearance-none">
                                <option value="">Select Specialist</option>
                                @foreach(['General', 'Orthopedic', 'Eye Specialist', 'Oncologist', 'Gynecologist', 'Pediatric', 'Dermatologist', 'Gastroenterologist', 'ENT', 'Urologist'] as $type)
                                    <option value="{{ $type }}" {{ old('doctor_type') == $type ? 'selected' : '' }}>{{ $type }}
                                    </option>
                                @endforeach
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

                    <!-- React Calendar Integration -->
                    <div id="calendar-booking-root"
                        class="col-span-1 md:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 p-4">
                        <!-- The React component will mount here and inject hidden inputs for appointment_date and appointment_time -->
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 flex items-center justify-end space-x-4">
                    <a href="{{ route('patients.index') }}"
                        class="px-6 py-3 bg-slate-100 text-slate-500 font-black uppercase tracking-widest text-[10px] rounded-xl hover:bg-slate-200 transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition-all">
                        Schedule Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection