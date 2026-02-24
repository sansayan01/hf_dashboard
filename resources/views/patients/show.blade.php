@extends('layouts.app')

@section('title', 'Patient Profile')
@section('header_title', 'Patient Profile')

@section('css')
    <style>
        /* === Profile Page Custom Styles === */
        .profile-hero {
            position: relative;
            background: linear-gradient(135deg, #1C2434 0%, #1e1b4b 40%, #1C2434 100%);
            overflow: hidden;
        }

        .profile-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 70% 50%, rgba(60, 80, 224, 0.35) 0%, transparent 60%),
                radial-gradient(ellipse at 20% 80%, rgba(124, 58, 237, 0.25) 0%, transparent 50%);
        }

        .profile-hero .mesh-grid {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Stat cards inside hero - strong glassy look */
        .hero-stat-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .hero-stat-card:hover {
            background: rgba(255, 255, 255, 0.14);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px);
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            animation: float 6s ease-in-out infinite;
        }

        .orb-1 {
            width: 200px;
            height: 200px;
            background: rgba(99, 102, 241, 0.3);
            top: -60px;
            right: -40px;
            animation-delay: 0s;
        }

        .orb-2 {
            width: 150px;
            height: 150px;
            background: rgba(139, 92, 246, 0.25);
            bottom: -40px;
            left: 40%;
            animation-delay: 2s;
        }

        .orb-3 {
            width: 120px;
            height: 120px;
            background: rgba(59, 130, 246, 0.2);
            top: 20px;
            left: 30%;
            animation-delay: 4s;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-15px) scale(1.05); }
        }

        .profile-avatar-ring {
            position: relative;
            padding: 3px;
            border-radius: 28px;
            background: conic-gradient(from var(--ring-angle, 0deg), #6366f1, #8b5cf6, #ec4899, #f59e0b, #10b981, #6366f1);
            animation: ring-spin 1.8s linear infinite, ring-glow 3s ease-in-out infinite alternate;
            box-shadow: 0 0 18px 4px rgba(99, 102, 241, 0.6), 0 0 36px 8px rgba(139, 92, 246, 0.35);
        }

        @property --ring-angle { syntax: '<angle>'; initial-value: 0deg; inherits: false; }

        @keyframes ring-spin { from { --ring-angle: 0deg; } to { --ring-angle: 360deg; } }

        @keyframes ring-glow {
            from { box-shadow: 0 0 18px 4px rgba(99, 102, 241, 0.7), 0 0 36px 8px rgba(139, 92, 246, 0.4); }
            to { box-shadow: 0 0 24px 6px rgba(236, 72, 153, 0.7), 0 0 48px 12px rgba(245, 158, 11, 0.4); }
        }

        .info-card {
            background: white;
            border-radius: 24px;
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 12px 0 rgba(28, 36, 52, 0.08), 0 0 0 1px rgba(28, 36, 52, 0.06);
        }

        .info-card:hover {
            box-shadow: 0 16px 48px 0 rgba(28, 36, 52, 0.14), 0 0 0 1px rgba(60, 80, 224, 0.1);
            transform: translateY(-1px);
        }

        .btn-action {
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .btn-action:hover {
            transform: translateY(-2px) scale(1.05);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6 pb-20">

        {{-- ===== HERO HEADER CARD ===== --}}
        <div class="profile-hero rounded-[2.5rem] shadow-2xl overflow-hidden">
            <div class="mesh-grid"></div>
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            {{-- Watermark --}}
            <div class="absolute right-0 top-0 select-none pointer-events-none opacity-[0.04] hidden lg:block">
                <span class="text-[15rem] font-black text-white uppercase tracking-tighter leading-none">
                    {{ substr($patient->full_name, 0, 2) }}
                </span>
            </div>

            <div class="relative p-8 lg:p-10">
                <div class="flex flex-col lg:flex-row lg:items-center gap-8">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        <div class="profile-avatar-ring w-32 h-32">
                            <div class="w-full h-full rounded-[1.6rem] bg-slate-900 overflow-hidden flex items-center justify-center">
                                <span class="text-5xl font-black text-white uppercase">{{ substr($patient->full_name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex justify-center mt-3">
                            @if($patient->health_issues && !in_array($patient->health_issues, ['Normal', 'None']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-500/20 border border-rose-500/40 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full animate-pulse"></span>
                                    <span class="text-[9px] font-black text-rose-300 uppercase tracking-widest">Medical Attention</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 border border-emerald-500/40 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                    <span class="text-[9px] font-black text-emerald-300 uppercase tracking-widest">Stable Health</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-4 mb-3">
                            <h1 class="text-5xl font-black text-white tracking-tight leading-none">{{ $patient->full_name }}</h1>
                            <span class="px-3 py-1 bg-white/10 border border-white/20 rounded-full text-[10px] font-black text-white uppercase tracking-widest backdrop-blur-sm">
                                Patient File
                            </span>
                        </div>

                        <div class="inline-flex items-center gap-3 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl mb-6">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="text-white font-black tracking-widest text-sm uppercase">{{ $patient->patient_id }}</span>
                        </div>

                        <div class="flex flex-wrap gap-3">
                            <button type="button" onclick="openHistoryModal()" class="btn-action px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-xs hover:bg-indigo-500 shadow-lg shadow-indigo-600/20">Medicine History</button>
                            <button type="button" onclick="openPathologyModal()" class="btn-action px-5 py-2.5 bg-violet-600 text-white font-bold rounded-xl text-xs hover:bg-violet-500 shadow-lg shadow-violet-600/20">Pathology History</button>
                            @if(!in_array(auth()->user()->designation, ['ro', 'rm', 'bm', 'dm']))
                            <a href="{{ route('pathology.create', $patient->id) }}" class="btn-action px-5 py-2.5 bg-white/10 border border-white/20 text-white font-bold rounded-xl text-xs hover:bg-white/20 backdrop-blur-sm">New Lab Test</a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Vital Stats Bar --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-8 pt-8 border-t border-white/10">
                    <div class="hero-stat-card rounded-2xl p-4">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">Age · Gender</p>
                        <p class="text-base font-black text-white">{{ $patient->age }}Y · {{ $patient->gender }}</p>
                    </div>
                    <div class="hero-stat-card rounded-2xl p-4">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">Blood Type</p>
                        <p class="text-base font-black text-white">{{ $patient->blood_group ?? 'N/A' }}</p>
                    </div>
                    <div class="hero-stat-card rounded-2xl p-4">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">Tests Done</p>
                        <p class="text-base font-black text-white">{{ $patient->pathologyTests->count() }} Records</p>
                    </div>
                    <div class="hero-stat-card rounded-2xl p-4">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">Lead Source</p>
                        <p class="text-sm font-black text-white truncate">{{ $patient->creator->profile->full_name ?? 'System' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== DETAILS GRID ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Personal & Medical Information -->
                <div class="lg:col-span-2 space-y-8">
                    @if($patient->is_member)
                        <!-- Membership Details -->
                        <div class="info-card p-8 border-l-4 border-emerald-500">
                            <div class="flex items-center justify-between mb-8">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-emerald-500/10 text-emerald-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-lg text-slate-800">Premium Membership</h3>
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Active Status</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-lg shadow-emerald-500/20">
                                        Verified Member
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="bg-slate-50 dark:bg-white/5 p-4 rounded-2xl border border-slate-100 dark:border-white/5">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Joining Fee</p>
                                    <p class="font-black text-slate-700 dark:text-white">₹{{ number_format($patient->membership_fee, 2) }}</p>
                                </div>
                                <div class="bg-emerald-500/5 p-4 rounded-2xl border border-emerald-500/10">
                                    <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">Paid Amount</p>
                                    <p class="font-black text-emerald-600">₹{{ number_format($patient->amount_paid, 2) }}</p>
                                </div>
                                <div class="bg-indigo-500/5 p-4 rounded-2xl border border-indigo-500/10">
                                    <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">Method</p>
                                    <p class="text-xs font-black text-indigo-600 truncate" title="{{ $patient->payment_method }}">{{ $patient->payment_method }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                <!-- Identity Details -->
                <div class="info-card p-8">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="font-black text-lg text-slate-800">Identity Details</h3>
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
                                    {{ $patient->pan_number ?? 'N/A' }}
                                </p>
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
                <div class="info-card p-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full -mr-16 -mt-16"></div>

                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="font-black text-lg text-slate-800">Health & Medical Status</h3>
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
                                                {{ $appointment->doctor_type }}
                                            </p>
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
                                    <a href="{{ route('users.show', $patient->creator->id) }}"
                                        class="text-xs font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                                        <span>{{ $patient->creator->profile->full_name ?? 'N/A' }}</span>
                                        <svg class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                @else
                                    <p class="text-xs font-bold text-slate-700 dark:text-white">
                                        {{ $patient->creator->profile->full_name ?? 'N/A' }}
                                    </p>
                                @endif
                                <p class="text-[9px] text-slate-400 font-medium uppercase mt-0.5">
                                    {{ $patient->creator->employee_id }}
                                </p>
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
<div id="history-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75 backdrop-blur-sm" aria-hidden="true"
            onclick="closeHistoryModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-white/10">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-accent/10 text-accent rounded-2xl flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white" id="modal-title">Dispense
                                History</h3>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-widest">
                                {{ $patient->full_name }} ({{ $patient->patient_id }})</p>
                        </div>
                    </div>
                    <button onclick="closeHistoryModal()"
                        class="p-2 hover:bg-slate-100 dark:hover:bg-white/5 rounded-xl transition">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-auto max-h-[60vh] rounded-2xl border border-slate-100 dark:border-white/5">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/5">
                                <th
                                    class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">
                                    Date & Time</th>
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    Medicines</th>
                                <th
                                    class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">
                                    Location</th>
                                <th
                                    class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 whitespace-nowrap">
                                    Pharmacist</th>
                                <th
                                    class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                    Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($patient->medicineDistributions as $dist)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                    <td class="p-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-bold text-slate-700 dark:text-white">{{ $dist->created_at->format('M d, Y') }}</span>
                                            <span
                                                class="text-[10px] font-medium text-slate-400 uppercase">{{ $dist->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($dist->items as $item)
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-accent/5 text-accent border border-accent/10 text-[9px] font-black uppercase">
                                                    {{ $item->medicine->name }} x{{ $item->quantity }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span
                                            class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $dist->camp->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="p-4 whitespace-nowrap">
                                        <span
                                            class="text-xs font-bold text-slate-500">{{ $dist->pharmacist->profile->full_name ?? $dist->pharmacist->employee_id }}</span>
                                    </td>
                                    <td class="p-4 text-right">
                                        <a href="{{ route('medicine.invoice', $dist->id) }}" target="_blank"
                                            class="p-2 text-slate-400 hover:text-accent transition"
                                            title="Download Invoice">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center">
                                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest italic">No
                                            records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-6 flex flex-row-reverse">
                <button type="button" onclick="closeHistoryModal()"
                    class="px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition">
                    Close History
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Pathology History Modal -->
<div id="pathology-history-modal" class="hidden fixed inset-0 z-[100] overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" onclick="closePathologyModal()"></div>

        <div
            class="glass bg-white dark:bg-slate-800 rounded-3xl shadow-2xl overflow-hidden w-full max-w-4xl relative z-10 border border-slate-200/10 dark:border-white/5">
            <div class="bg-rose-500 px-8 py-6 text-white flex justify-between items-center">
                <div>
                    <h3 class="text-xl font-black uppercase tracking-widest">Pathology History</h3>
                    <p class="text-xs font-bold opacity-80">{{ $patient->full_name }}</p>
                </div>
                <button type="button" onclick="closePathologyModal()" class="text-white/80 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-0">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-white/5 border-b border-slate-200 dark:border-white/10">
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    Date & Test</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">RO
                                    / Incentive</th>
                                <th class="px-8 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    Payment</th>
                                <th
                                    class="px-8 py-4 text-right pr-8 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    Amount</th>
                                <th
                                    class="px-8 py-4 w-12 text-center text-[10px] font-black uppercase tracking-widest text-slate-400">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($patient->pathologyTests as $test)
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="text-xs font-black text-rose-500 uppercase tracking-tighter mb-0.5">
                                            {{ $test->date->format('d M, Y') }}</div>
                                        <div class="text-sm font-bold text-slate-700 dark:text-slate-200">
                                            {{ $test->test_name }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-2">
                                            <div class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                                {{ $test->ro->profile->full_name ?? 'N/A' }}</div>
                                            <span
                                                class="bg-emerald-500/10 text-emerald-500 px-1.5 py-0.5 rounded text-[9px] font-black uppercase">Earned</span>
                                        </div>
                                        <div class="text-[10px] text-slate-400 font-mono">
                                            {{ $test->ro->employee_id ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-xs font-bold text-slate-600 dark:text-slate-400">{{ ucfirst($test->payment_method) }}</span>
                                            @if($test->discount_percentage > 0)
                                                <span
                                                    class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">{{ $test->discount_percentage }}%
                                                    Off</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right pr-8">
                                        <div class="text-sm font-black text-slate-800 dark:text-white">
                                            ₹{{ number_format($test->final_amount, 2) }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">
                                            {{ $test->camp->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <form action="{{ route('pathology.destroy', $test->id) }}" method="POST"
                                            onsubmit="return confirm('Stop! This will delete the pathology record and cannot be undone. Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-slate-300 hover:text-rose-500 transition opacity-0 group-hover:opacity-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center">
                                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest italic">No
                                            pathology records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-6 flex flex-row-reverse">
                <button type="button" onclick="closePathologyModal()"
                    class="px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition">
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

        function openPathologyModal() {
            const modal = document.getElementById('pathology-history-modal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePathologyModal() {
            const modal = document.getElementById('pathology-history-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection