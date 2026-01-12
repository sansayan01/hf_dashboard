@extends('layouts.app')

@section('title', 'Patient Appointments')
@section('header_title', 'Appointment History')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="mb-6">
            <a href="{{ route('patients.index') }}"
                class="flex items-center text-slate-500 hover:text-accent transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Surveys
            </a>
        </div>

        <!-- Patient Profile Card -->
        <div
            class="bg-white dark:bg-darkcard rounded-3xl shadow-xl overflow-hidden border border-slate-100 dark:border-white/5 p-6 md:p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center space-y-4 md:space-y-0 md:space-x-6">
                <div
                    class="w-20 h-20 rounded-2xl bg-gradient-to-br from-accent to-purple-600 text-white flex items-center justify-center text-3xl font-black shadow-lg shadow-accent/30">
                    {{ substr($patient->full_name, 0, 1) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-black text-slate-800 dark:text-white">{{ $patient->full_name }}</h2>
                    <div
                        class="flex flex-wrap items-center gap-3 mt-2 text-sm font-medium text-slate-500 dark:text-slate-400">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            {{ $patient->phone_number }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $patient->address }}
                        </span>
                        <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-slate-700"></span>
                        <span class="{{ $patient->gender === 'Male' ? 'text-blue-500' : 'text-pink-500' }} font-bold">
                            {{ $patient->gender }}
                        </span>
                    </div>
                </div>
                <a href="{{ route('patients.appointments.create', $patient->id) }}"
                    class="px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold rounded-xl text-xs hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    New Appointment
                </a>
            </div>
        </div>

        <!-- Appointments Timeline -->
        <div class="space-y-4">
            <h3 class="text-lg font-black text-slate-800 dark:text-white px-2">Scheduled Visits</h3>

            @forelse($appointments as $appointment)
                <div
                    class="bg-white dark:bg-darkcard p-6 md:p-8 rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 relative overflow-hidden group hover:shadow-md transition-all">
                    <div
                        class="absolute top-0 left-0 w-2 h-full bg-{{ $appointment->appointment_date->setTimeFromTimeString($appointment->appointment_time)->isPast() ? 'slate-300' : 'accent' }}">
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pl-4">
                        <div class="flex items-start space-x-4">
                            <div
                                class="flex-shrink-0 flex flex-col items-center justify-center w-16 h-16 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-800/30">
                                <span
                                    class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M') }}</span>
                                <span
                                    class="text-2xl font-black">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d') }}</span>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-1">
                                    <h4 class="text-lg font-black text-slate-800 dark:text-white">
                                        {{ $appointment->doctor_type }}
                                    </h4>
                                    <span
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-100/50 dark:bg-white/5 px-2 py-0.5 rounded">{{ $appointment->appointment_id }}</span>
                                    @if($appointment->status === 'successful')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-600 uppercase">Successful</span>
                                    @elseif($appointment->status === 'not_attended')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-red-100 text-red-600 uppercase">Not Attended</span>
                                    @elseif($appointment->status === 'missed_reported')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-600 uppercase">Pending Confirmation</span>
                                    @else
                                        @if($appointment->appointment_date->isPast())
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-500 uppercase">Overdue</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-600 uppercase">Upcoming</span>
                                        @endif
                                    @endif
                                </div>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                </p>
                                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium flex items-center mt-1">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $appointment->location }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col items-end space-y-2">
                             @if($appointment->status === 'scheduled' || $appointment->status === 'missed_reported')
                                <a href="{{ route('patients.appointments.edit', $appointment->id) }}" 
                                    class="p-2 bg-amber-500/10 hover:bg-amber-500 text-amber-600 hover:text-white rounded-xl transition-all shadow-sm border border-amber-500/10 group/edit"
                                    title="Edit Appointment">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                            @endif
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-2">Booked by</p>
                            <div class="flex items-center space-x-2">
                                <div
                                    class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                    {{ substr($appointment->creator->profile->full_name ?? 'U', 0, 1) }}
                                </div>
                                <span
                                    class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $appointment->creator->profile->full_name ?? 'Unknown' }}</span>
                            </div>
                            <span class="text-[10px] text-slate-400">{{ $appointment->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="text-center py-12 bg-white dark:bg-darkcard rounded-3xl border border-dashed border-slate-200 dark:border-white/10">
                    <div
                        class="w-16 h-16 bg-slate-50 dark:bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-slate-800 dark:text-white font-bold text-lg">No appointments yet</h3>
                    <p class="text-slate-500 text-sm mb-6">Schedule the first visit for this patient.</p>
                    <a href="{{ route('patients.appointments.create', $patient->id) }}"
                        class="px-6 py-2 bg-accent text-white font-bold rounded-xl text-sm hover:bg-accent/90 transition shadow-lg shadow-accent/20">
                        Book Appointment
                    </a>
                </div>
            @endforelse
        </div>
    </div>
@endsection