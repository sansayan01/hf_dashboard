@extends('layouts.app')

@section('title', 'Patient Profile')
@section('header_title', 'Patient Profile')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 pb-20">
        <!-- Header Section Card -->
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden">
            <div class="h-32 bg-accent relative">
                <div class="absolute inset-0 bg-gradient-to-r from-white/10 to-transparent"></div>
            </div>
            <div class="px-8 pb-8">
                <div
                    class="relative flex flex-col md:flex-row md:items-end -mt-16 mb-6 space-y-4 md:space-y-0 md:space-x-6">
                    <!-- Patient Initial Avatar -->
                    <div
                        class="w-32 h-32 rounded-3xl bg-white dark:bg-slate-800 p-2 shadow-xl ring-1 ring-slate-100 dark:ring-white/5">
                        <div
                            class="w-full h-full rounded-2xl overflow-hidden bg-accent/10 flex items-center justify-center">
                            <span
                                class="text-4xl font-black text-accent uppercase">{{ substr($patient->full_name, 0, 1) }}</span>
                        </div>
                    </div>

                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h2 class="text-3xl font-black text-slate-800 dark:text-white">{{ $patient->full_name }}</h2>
                            <span
                                class="px-3 py-1 bg-accent/10 text-accent rounded-full text-[10px] font-black uppercase tracking-widest">
                                Patient Record
                            </span>
                            @if($patient->health_issues && !in_array($patient->health_issues, ['Normal', 'None']))
                                <span
                                    class="px-3 py-1 bg-amber-500/10 text-amber-500 rounded-full text-[10px] font-black uppercase tracking-widest">Medical
                                    Attention</span>
                            @else
                                <span
                                    class="px-3 py-1 bg-success/10 text-success rounded-full text-[10px] font-black uppercase tracking-widest">Healthy</span>
                            @endif
                        </div>
                        <p class="text-slate-500 font-bold uppercase tracking-widest text-xs italic">Patient ID:
                            {{ $patient->patient_id }}</p>
                    </div>

                    <div class="flex flex-wrap gap-2 md:gap-3">
                        @if(Auth::id() === $patient->created_by || Auth::user()->canAccess($patient->creator))
                            <button type="button" onclick="openHistoryModal()"
                                class="px-6 py-3 bg-accent/10 text-accent border border-accent/20 font-bold rounded-xl text-sm hover:bg-accent hover:text-white transition flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                Dispense History
                            </button>
                            @if($patient->is_member && auth()->user()->designation !== 'staff')
                                <a href="{{ route('patients.membership', $patient->id) }}"
                                    class="px-6 py-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-500 border border-emerald-500/20 font-bold rounded-xl text-sm hover:bg-emerald-500 hover:text-white transition flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    Membership Profile
                                </a>
                            @elseif(auth()->user()->designation !== 'staff')
                                <a href="{{ route('patients.membership', $patient->id) }}"
                                    class="px-6 py-3 bg-amber-500/10 text-amber-600 dark:text-amber-500 border border-amber-500/20 font-bold rounded-xl text-sm hover:bg-amber-500 hover:text-white transition flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                    </svg>
                                    Membership Registration
                                </a>
                            @endif
                            <a href="{{ route('patients.edit', $patient->id) }}"
                                class="px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/5 text-slate-700 dark:text-white font-bold rounded-xl text-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition">
                                Edit Record
                            </a>
                            @if(auth()->user()->designation !== 'staff')
                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" id="delete-patient-form" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete()"
                                        class="px-6 py-3 bg-rose-500/10 text-rose-500 border border-rose-500/20 font-bold rounded-xl text-sm hover:bg-rose-500 hover:text-white transition">
                                        Delete Record
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Quick Stats Row -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pt-6 border-t border-slate-50 dark:border-white/5">
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Age / Gender</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white">
                            {{ $patient->age }} Years • {{ ucfirst($patient->gender) }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Blood Group</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white">
                            {{ $patient->blood_group ?? 'Not Known' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Registered On</p>
                        <p class="text-sm font-bold text-slate-700 dark:text-white">
                            {{ $patient->created_at->format('d M, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Collector</p>
                        @if(auth()->user()->isSuperAdmin() && $patient->creator)
                            <a href="{{ route('users.show', $patient->creator->id) }}" class="text-sm font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                                <span>{{ $patient->creator->profile->full_name ?? 'System' }}</span>
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <p class="text-sm font-bold text-slate-700 dark:text-white">
                                {{ $patient->creator->profile->full_name ?? 'System' }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Personal & Medical Information -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Identity Details -->
                <div
                    class="bg-white dark:bg-darkbg/40 p-8 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-accent/10 text-accent rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">Personal & Identity Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Father/Husband
                                Name</p>
                            <p class="font-bold text-slate-800 dark:text-white">{{ $patient->relative_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Phone Number</p>
                            <p class="font-bold text-slate-800 dark:text-white">{{ $patient->phone_number }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Aadhaar Card No.
                            </p>
                            <p class="font-bold text-slate-800 dark:text-white italic">
                                {{ $patient->aadhar_number ? '•••• •••• ' . substr($patient->aadhar_number, -4) : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">PAN Card No.</p>
                            <p class="font-bold text-slate-800 dark:text-white uppercase">
                                {{ $patient->pan_number ?? 'N/A' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Full Address</p>
                            <p class="font-bold text-slate-800 dark:text-white leading-relaxed">{{ $patient->address }}</p>
                            <p class="text-xs text-slate-500 font-medium mt-1">
                                {{ $patient->gp ? $patient->gp . ', ' : '' }}
                                {{ $patient->block ? $patient->block . ', ' : '' }}
                                {{ $patient->district }} - {{ $patient->pin }}
                            </p>
                            @if($patient->landmark)
                                <p class="text-xs text-accent font-bold mt-2 flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Landmark: {{ $patient->landmark }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Health Information -->
                <div
                    class="bg-white dark:bg-darkbg/40 p-8 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full -mr-16 -mt-16"></div>

                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-bold text-lg text-slate-800 dark:text-white">Health & Medical Status</h3>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Primary Health
                                Issue</p>
                            <div class="p-4 bg-amber-500/5 border border-amber-500/10 rounded-2xl">
                                <p class="font-black text-amber-600 dark:text-amber-400">{{ $patient->health_issues }}</p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Past Medical
                                History / Diseases</p>
                            <p
                                class="text-sm font-bold text-slate-700 dark:text-slate-300 leading-relaxed bg-slate-50 dark:bg-white/5 p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                                {{ $patient->past_diseases ?: 'No previous diseases reported.' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Insurance / Loan
                                Requirements</p>
                            <p class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                {{ $patient->insurance_loan_req ?: 'Not Specified' }}
                            </p>
                        </div>
                    </div>
                </div>


            </div>

            <!-- Right Column Sidebar Content -->
            <div class="space-y-8">
                <!-- Appointment Summary -->
                <div
                    class="bg-white dark:bg-darkbg/40 p-8 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="font-bold text-slate-800 dark:text-white">Appointments</h3>
                        <a href="{{ route('patients.appointments.create', $patient->id) }}"
                            class="p-2 bg-accent/10 text-accent rounded-lg hover:bg-accent hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                    </div>

                    <div class="space-y-6">
                        @forelse($patient->appointments as $appointment)
                            <div class="flex space-x-4 group">
                                <div
                                    class="flex-shrink-0 w-10 h-10 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 flex flex-col items-center justify-center text-[8px] font-black uppercase">
                                    <span class="text-accent">{{ $appointment->appointment_date->format('M') }}</span>
                                    <span
                                        class="text-slate-600 dark:text-white text-xs">{{ $appointment->appointment_date->format('d') }}</span>
                                </div>
                                <div>
                                    <div class="flex items-center space-x-2">
                                        <p class="text-xs font-black text-slate-800 dark:text-white">
                                            {{ $appointment->doctor_type }}</p>
                                        @if($appointment->status === 'successful')
                                            <span class="text-[8px] font-black text-emerald-500 uppercase">Success</span>
                                        @elseif($appointment->status === 'not_attended')
                                            <span class="text-[8px] font-black text-red-500 uppercase">Missed</span>
                                        @elseif($appointment->status === 'missed_reported')
                                            <span class="text-[8px] font-black text-amber-500 uppercase">Pending</span>
                                        @endif
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div
                                class="text-center py-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-dashed border-slate-200 dark:border-white/10">
                                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">No Appointments</p>
                            </div>
                        @endforelse
                    </div>

                    @if($patient->appointments->isNotEmpty())
                        <div class="mt-8 pt-6 border-t border-slate-50 dark:border-white/5">
                            <a href="{{ route('patients.appointments.index', $patient->id) }}"
                                class="block text-center text-[10px] font-black text-accent uppercase tracking-widest hover:underline">View
                                All Schedule</a>
                        </div>
                    @endif
                </div>

                <!-- Registration Context -->
                <div
                    class="bg-white dark:bg-darkbg/40 p-8 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm">
                    <h3 class="font-bold text-slate-800 dark:text-white mb-6">Field Visit Context</h3>
                    <div class="space-y-4">
                        <div
                            class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Assigned
                                Collector</p>
                            @if(auth()->user()->isSuperAdmin() && $patient->creator)
                                <a href="{{ route('users.show', $patient->creator->id) }}" class="text-xs font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                                    <span>{{ $patient->creator->profile->full_name ?? 'N/A' }}</span>
                                    <svg class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            @else
                                <p class="text-xs font-bold text-slate-700 dark:text-white">
                                    {{ $patient->creator->profile->full_name ?? 'N/A' }}
                                </p>
                            @endif
                            <p class="text-[9px] text-slate-400 font-medium uppercase mt-0.5">
                                {{ $patient->creator->employee_id }}</p>
                        </div>
                        <div
                            class="p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Data Source</p>
                            <p class="text-xs font-bold text-slate-700 dark:text-white">HF Field Survey App</p>
                            <p class="text-[9px] text-slate-400 font-medium uppercase mt-0.5">Verified Entry</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- History Modal -->
<div id="history-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75 backdrop-blur-sm" aria-hidden="true" onclick="closeHistoryModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-white/10">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-accent/10 text-accent rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white" id="modal-title">Dispense History</h3>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">{{ $patient->full_name }} ({{ $patient->patient_id }})</p>
                        </div>
                    </div>
                    <button onclick="closeHistoryModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-white/5 rounded-xl transition">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-auto max-h-[60vh] rounded-2xl border border-slate-100 dark:border-white/5">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/5">
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Date & Time</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Medicines</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Location</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">Pharmacist</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($patient->medicineDistributions as $dist)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-slate-700 dark:text-white">{{ $dist->created_at->format('M d, Y') }}</span>
                                            <span class="text-[10px] font-medium text-slate-400 uppercase">{{ $dist->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($dist->items as $item)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-accent/5 text-accent border border-accent/10 text-[9px] font-black uppercase">
                                                    {{ $item->medicine->name }} x{{ $item->quantity }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $dist->camp->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span class="text-xs font-bold text-slate-500">{{ $dist->pharmacist->profile->full_name ?? $dist->pharmacist->employee_id }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('medicine.invoice', $dist->id) }}" target="_blank" class="p-2 text-slate-400 hover:text-accent transition" title="Download Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center">
                                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest italic">No records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-6 flex flex-row-reverse">
                <button type="button" onclick="closeHistoryModal()" class="px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition">
                    Close History
                </button>
            </div>
        </div>
    </div>
</div>

@section('js')
    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Delete Patient Record?',
                text: "This action cannot be undone. All clinical data for {{ $patient->full_name }} will be permanently removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#F43F5E',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Yes, Delete Record',
                cancelButtonText: 'Cancel',
                background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1E293B',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-patient-form').submit();
                }
            })
        }

        function openHistoryModal() {
            const modal = document.getElementById('history-modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeHistoryModal() {
            const modal = document.getElementById('history-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection