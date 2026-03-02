@extends('layouts.app')

@section('title', 'Add Camp Record')
@section('header_title', 'New Camp Record')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* ═══════════════════════════════════════════
                                                                       ULTRA-PREMIUM DESIGN SYSTEM
                                                                       ═══════════════════════════════════════════ */

        /* ── Animated Mesh Background ── */
        .mesh-bg {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }

        .mesh-bg .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.15;
            animation: orbFloat 20s ease-in-out infinite;
        }

        .dark .mesh-bg .orb {
            opacity: 0.08;
        }

        .mesh-bg .orb-1 {
            width: 600px;
            height: 600px;
            background: #3C50E0;
            top: -20%;
            right: -10%;
            animation-delay: 0s;
        }

        .mesh-bg .orb-2 {
            width: 500px;
            height: 500px;
            background: #8B5CF6;
            bottom: -15%;
            left: -5%;
            animation-delay: -7s;
        }

        .mesh-bg .orb-3 {
            width: 400px;
            height: 400px;
            background: #06B6D4;
            top: 40%;
            left: 30%;
            animation-delay: -14s;
        }

        @keyframes orbFloat {

            0%,
            100% {
                transform: translate(0, 0) scale(1);
            }

            33% {
                transform: translate(30px, -30px) scale(1.05);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.95);
            }
        }

        /* ── Hero Glow Card ── */
        .hero-card {
            position: relative;
            border-radius: 1.5rem;
            overflow: hidden;
            isolation: isolate;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #3C50E0 0%, #6366f1 30%, #8b5cf6 60%, #a855f7 100%);
            z-index: 0;
        }

        .hero-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.06'/%3E%3C/svg%3E");
            z-index: 1;
            mix-blend-mode: overlay;
        }

        /* ── Glowing Section Cards ── */
        .glow-card {
            position: relative;
            background: white;
            border-radius: 1.25rem;
            border: 1px solid rgba(226, 232, 240, 0.8);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .dark .glow-card {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.06);
        }

        .glow-card:hover {
            border-color: rgba(99, 102, 241, 0.25);
            box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.05),
                0 8px 40px -12px rgba(99, 102, 241, 0.15);
        }

        .glow-card:not(.no-transform):hover {
            transform: translateY(-2px);
        }

        .dark .glow-card:hover {
            border-color: rgba(99, 102, 241, 0.3);
            box-shadow: 0 0 0 1px rgba(99, 102, 241, 0.1),
                0 8px 40px -12px rgba(99, 102, 241, 0.2);
        }

        /* Animated top accent line */
        .glow-card .accent-line {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--line-color, #3C50E0), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .glow-card:hover .accent-line {
            opacity: 1;
        }

        /* ── Section Header ── */
        .sec-header {
            padding: 1.5rem 1.75rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .sec-icon {
            width: 2.75rem;
            height: 2.75rem;
            border-radius: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
            position: relative;
        }

        /* Subtle pulse ring behind icon on hover */
        .glow-card:hover .sec-icon::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 1rem;
            background: inherit;
            opacity: 0.3;
            filter: blur(8px);
            animation: iconPulse 2s ease-in-out infinite;
        }

        @keyframes iconPulse {

            0%,
            100% {
                opacity: 0.25;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(1.15);
            }
        }

        .sec-body {
            padding: 0 1.75rem 1.75rem;
        }

        /* ── Ultra Premium Inputs ── */
        .ultra-input {
            width: 100%;
            height: 3.25rem;
            padding: 0 1rem;
            border-radius: 0.875rem;
            border: 1.5px solid #e2e8f0;
            background: #fafbfc;
            font-size: 0.875rem;
            font-weight: 500;
            color: #0f172a;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            outline: none;
        }

        .dark .ultra-input {
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(51, 65, 85, 0.6);
            color: #f1f5f9;
        }

        .ultra-input:hover {
            border-color: #c7d2fe;
            background: #fff;
        }

        .dark .ultra-input:hover {
            border-color: #475569;
            background: rgba(15, 23, 42, 0.8);
        }

        .ultra-input:focus {
            background: #fff;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1),
                0 1px 2px rgba(0, 0, 0, 0.06);
        }

        .dark .ultra-input:focus {
            background: #0f172a;
            border-color: #818cf8;
            box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.15),
                0 1px 2px rgba(0, 0, 0, 0.2);
        }

        .ultra-input.with-icon {
            padding-left: 2.85rem;
        }

        .ultra-input.capitalize {
            text-transform: capitalize;
        }

        /* Input icon container */
        .input-icon {
            position: absolute;
            inset-block: 0;
            left: 0;
            width: 2.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            color: #94a3b8;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }

        .input-group:focus-within .input-icon {
            color: #6366f1;
        }

        .dark .input-group:focus-within .input-icon {
            color: #818cf8;
        }

        /* Input label */
        .input-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
            letter-spacing: 0.01em;
        }

        .dark .input-label {
            color: #94a3b8;
        }

        .input-label .req {
            color: #ef4444;
            margin-left: 2px;
        }

        /* ── TomSelect Ultra Override ── */
        .ts-wrapper .ts-control {
            height: 3.25rem !important;
            border-radius: 0.875rem !important;
            border: 1.5px solid #e2e8f0 !important;
            background: #fafbfc !important;
            padding: 0 1rem 0 2.85rem !important;
            display: flex !important;
            align-items: center !important;
            box-shadow: none !important;
            font-size: 0.875rem !important;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1) !important;
        }

        .dark .ts-wrapper .ts-control {
            background: rgba(15, 23, 42, 0.6) !important;
            border-color: rgba(51, 65, 85, 0.6) !important;
            color: #f1f5f9 !important;
        }

        .ts-wrapper.focus {
            z-index: 1000 !important;
        }

        .dark .ts-wrapper .ts-control.focus {
            background: #0f172a !important;
            border-color: #818cf8 !important;
            box-shadow: 0 0 0 4px rgba(129, 140, 248, 0.15) !important;
        }

        .ts-dropdown {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 0.875rem !important;
            margin-top: 6px !important;
            box-shadow: 0 20px 40px -12px rgba(0, 0, 0, 0.12) !important;
            overflow: hidden !important;
            padding: 0.25rem !important;
            z-index: 9999 !important;
        }

        .dark .ts-dropdown {
            background: #1e293b !important;
            border-color: #334155 !important;
        }

        .ts-dropdown .option {
            border-radius: 0.5rem !important;
            padding: 0.625rem 0.75rem !important;
        }

        .ts-dropdown .active {
            background: #f1f5f9 !important;
        }

        .dark .ts-dropdown .active {
            background: #334155 !important;
        }

        /* ── Expense Row TomSelect Compact Style ── */
        .expense-row .ts-wrapper .ts-control {
            height: 2.5rem !important;
            padding: 0 0.75rem !important;
            border-radius: 0.75rem !important;
            background: white !important;
        }

        .dark .expense-row .ts-wrapper .ts-control {
            background: rgba(255, 255, 255, 0.03) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .expense-row .ts-wrapper.focus .ts-control {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
        }

        .expense-row:focus-within {
            z-index: 50 !important;
            position: relative;
        }

        /* ── Financial Display Cards ── */
        .fin-card {
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            position: relative;
            transition: all 0.3s ease;
        }

        .fin-card:hover {
            transform: translateY(-1px);
        }

        .fin-card .fin-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.625rem;
        }

        .fin-card .fin-dot {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .fin-card .fin-title {
            font-size: 0.625rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .fin-card input {
            background: transparent;
            border: none;
            outline: none;
            font-size: 1.5rem;
            font-weight: 800;
            width: 100%;
            font-family: 'Outfit', system-ui, sans-serif;
            padding: 0;
        }

        .fin-card input::placeholder {
            opacity: 0.3;
        }

        .fin-card input:focus {
            box-shadow: none;
        }

        /* Net profit animated gradient border */
        .net-card {
            position: relative;
        }

        .net-card::before {
            content: '';
            position: absolute;
            inset: -1px;
            border-radius: 1.05rem;
            padding: 1.5px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6, #a855f7, #6366f1);
            background-size: 300% 300%;
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            animation: borderGlow 4s linear infinite;
        }

        @keyframes borderGlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* ── Submit Button ── */
        .btn-premium {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 2rem;
            border-radius: 0.875rem;
            font-size: 0.875rem;
            font-weight: 700;
            color: white;
            background: linear-gradient(135deg, #3C50E0, #6366f1);
            border: none;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .btn-premium::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-premium:hover::before {
            opacity: 1;
        }

        .btn-premium:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 30px -8px rgba(99, 102, 241, 0.5);
        }

        .btn-premium:active {
            transform: scale(0.97);
        }

        .btn-premium>* {
            position: relative;
            z-index: 1;
        }

        /* ── Stagger Animations ── */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .reveal {
            opacity: 0;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .reveal-d1 {
            animation-delay: 0.08s;
        }

        .reveal-d2 {
            animation-delay: 0.16s;
        }

        .reveal-d3 {
            animation-delay: 0.24s;
        }

        .reveal-d4 {
            animation-delay: 0.32s;
        }

        .reveal-d5 {
            animation-delay: 0.40s;
        }

        /* ── Step indicator dots ── */
        .step-dots {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .step-dot {
            width: 2rem;
            height: 4px;
            border-radius: 9999px;
            transition: all 0.3s ease;
        }
    </style>
@endsection


@section('content')
    {{-- Animated Mesh Background --}}
    <div class="mesh-bg">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <div class="max-w-5xl mx-auto pb-16 px-4 sm:px-6 relative z-10">

        {{-- ═══ HERO ═══ --}}
        <div class="hero-card mb-10 reveal">
            <div class="relative z-10 px-7 sm:px-10 py-9 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span
                            class="inline-flex items-center gap-1.5 text-white/70 text-[10px] font-black uppercase tracking-[0.15em]">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-400 animate-pulse"></span>
                            Finances
                        </span>
                        <span class="w-px h-3 bg-white/20"></span>
                        <span class="text-white/50 text-[10px] font-bold uppercase tracking-[0.15em]">Camp Records</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">New Camp Record
                    </h1>
                    <p class="text-indigo-200/80 text-sm mt-2 max-w-lg leading-relaxed">Capture every detail — from
                        logistics and medical team to patient stats and financial outcomes.</p>
                </div>
                <a href="{{ route('camp_records.index') }}"
                    class="group inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-xl border border-white/15 transition-all duration-300 shrink-0">
                    <i class="fas fa-arrow-left text-xs group-hover:-translate-x-0.5 transition-transform"></i>
                    All Records
                </a>
            </div>
        </div>

        {{-- Step indicator --}}
        <div class="flex items-center justify-between mb-8 reveal reveal-d1">
            <div class="step-dots">
                <div class="step-dot bg-accent"></div>
                <div class="step-dot bg-emerald-500"></div>
                <div class="step-dot bg-amber-500"></div>
                <div class="step-dot bg-slate-200 dark:bg-slate-700"></div>
            </div>
            <span class="text-[11px] font-bold text-slate-400 tracking-wide uppercase">Step 1 of 3</span>
        </div>

        <form action="{{ route('camp_records.store') }}" method="POST" class="space-y-7" id="campForm">
            @csrf

            {{-- ═══ SECTION 1: LOGISTICS ═══ --}}
            <div class="glow-card reveal reveal-d2" style="--line-color: #3C50E0;">
                <div class="accent-line"></div>
                <div class="sec-header">
                    <div class="sec-icon bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400">
                        <i class="fas fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h3 class="text-[0.9375rem] font-bold text-slate-800 dark:text-white leading-none tracking-tight">
                            Camp Logistics</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Location, scheduling & regional manager</p>
                    </div>
                </div>
                <div class="sec-body">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-5">

                        {{-- Camp Name --}}
                        <div class="sm:col-span-2 input-group">
                            <label class="input-label" for="camp_name">Camp Name <span class="req">*</span></label>
                            <div class="relative">
                                <span class="input-icon"><i class="fas fa-hospital"></i></span>
                                <input type="text" name="camp_name" id="camp_name" value="{{ old('camp_name') }}" required
                                    class="ultra-input with-icon capitalize" placeholder="e.g. Free Eye Checkup Camp">
                            </div>
                            @error('camp_name') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Date --}}
                        <div class="input-group">
                            <label class="input-label" for="date">Date of Camp <span class="req">*</span></label>
                            <div class="relative">
                                <span class="input-icon"><i class="fas fa-calendar-day"></i></span>
                                <input type="date" name="date" id="date" value="{{ old('date', date('Y-m-d')) }}" required
                                    class="ultra-input with-icon">
                            </div>
                            @error('date') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- RM --}}
                        <div class="input-group">
                            <label class="input-label" for="rm_select">Regional Manager</label>
                            <div class="relative">
                                <span class="input-icon z-[11]"><i class="fas fa-user-tie"></i></span>
                                <select name="rm" id="rm_select">
                                    <option value=""></option>
                                    @foreach($rms as $rm)
                                        <option value="{{ $rm->profile->full_name }}"
                                            data-phone="{{ $rm->profile->phone_number }}" data-hfid="{{ $rm->employee_id }}">
                                            {{ $rm->profile->full_name }} | {{ $rm->employee_id }} |
                                            {{ $rm->profile->phone_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('rm') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p> @enderror
                        </div>

                        {{-- Location --}}
                        <div class="sm:col-span-2 input-group">
                            <label class="input-label" for="location">Location / Full Address</label>
                            <div class="relative">
                                <span class="input-icon"><i class="fas fa-location-dot"></i></span>
                                <input type="text" name="location" id="location" value="{{ old('location') }}"
                                    class="ultra-input with-icon capitalize" placeholder="Village, Block, District, State">
                            </div>
                            @error('location') <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- ═══ SECTION 2: MEDICAL TEAM ═══ --}}
            <div class="glow-card reveal reveal-d3" style="--line-color: #10b981;">
                <div class="accent-line"></div>
                <div class="sec-header">
                    <div class="sec-icon bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                        <i class="fas fa-user-doctor"></i>
                    </div>
                    <div>
                        <h3 class="text-[0.9375rem] font-bold text-slate-800 dark:text-white leading-none tracking-tight">
                            Medical Team</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Attending healthcare professionals</p>
                    </div>
                </div>
                <div class="sec-body">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                        <div class="input-group">
                            <label class="input-label" for="doctor_name">Consulting Doctor</label>
                            <div class="relative">
                                <span class="input-icon"><i class="fas fa-stethoscope"></i></span>
                                <input type="text" name="doctor_name" id="doctor_name" value="{{ old('doctor_name') }}"
                                    class="ultra-input with-icon capitalize" placeholder="Dr. Name">
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="input-label" for="pathologist">Pathologist</label>
                            <div class="relative">
                                <span class="input-icon"><i class="fas fa-microscope"></i></span>
                                <input type="text" name="pathologist" id="pathologist" value="{{ old('pathologist') }}"
                                    class="ultra-input with-icon capitalize" placeholder="Name">
                            </div>
                        </div>

                        <div class="input-group">
                            <label class="input-label" for="pharmacists_name">Pharmacist</label>
                            <div class="relative">
                                <span class="input-icon"><i class="fas fa-pills"></i></span>
                                <input type="text" name="pharmacists_name" id="pharmacists_name"
                                    value="{{ old('pharmacists_name') }}" class="ultra-input with-icon capitalize"
                                    placeholder="Name">
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ═══ SECTION 3: FINANCIAL BREAKDOWN ═══ --}}
            <div class="glow-card no-transform reveal reveal-d4" style="--line-color: #f59e0b;">
                <div class="accent-line"></div>
                <div class="sec-header">
                    <div class="sec-icon bg-amber-100 text-amber-600 dark:bg-amber-500/20 dark:text-amber-400">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h3 class="text-[0.9375rem] font-bold text-slate-800 dark:text-white leading-none tracking-tight">
                            Financial Breakdown</h3>
                        <p class="text-xs text-slate-400 mt-1 font-medium">Revenue, expenses & profitability</p>
                    </div>
                </div>
                <div class="sec-body space-y-7">

                    {{-- Primary Financial Inputs --}}
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div class="input-group">
                            <label class="input-label" for="patients_count">Total Patients</label>
                            <div class="relative">
                                <span class="input-icon"><i class="fas fa-users"></i></span>
                                <input type="number" name="patients_count" id="patients_count"
                                    value="{{ old('patients_count') }}" min="0" step="1"
                                    class="ultra-input with-icon font-semibold" placeholder="0">
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="medicine_mrp">Medicine MRP</label>
                            <div class="relative">
                                <span class="input-icon font-bold text-slate-500">₹</span>
                                <input type="number" name="medicine_mrp" id="medicine_mrp" value="{{ old('medicine_mrp') }}"
                                    min="0" step="0.01" class="ultra-input with-icon" placeholder="0.00">
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="medicine_discount">Discounted Prize</label>
                            <div class="relative">
                                <span class="input-icon font-bold text-red-500">₹</span>
                                <input type="number" name="medicine_discount" id="medicine_discount"
                                    value="{{ old('medicine_discount') }}" min="0" step="0.01"
                                    class="ultra-input with-icon text-indigo-600 dark:text-indigo-400" placeholder="0.00">
                            </div>
                        </div>
                        <div class="input-group">
                            <label class="input-label" for="total_discount">
                                Total Discount
                                <span
                                    class="ml-1 px-1 py-0.5 rounded-md text-[8px] font-black tracking-wider bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400">AUTO</span>
                            </label>
                            <div class="relative">
                                <span class="input-icon font-bold text-red-400">₹</span>
                                <input type="number" name="total_discount" id="total_discount"
                                    value="{{ old('total_discount') }}" min="0" step="0.01" readonly tabindex="-1"
                                    class="ultra-input with-icon text-red-600 dark:text-red-400 opacity-80 cursor-default"
                                    placeholder="0.00">
                            </div>
                        </div>
                        <div class="input-group col-span-2 sm:col-span-1 lg:col-span-1">
                            <label class="input-label" for="buying_percentage">Buying Percentage</label>
                            <div class="relative">
                                <span class="input-icon font-bold text-slate-500">%</span>
                                <input type="number" name="buying_percentage" id="buying_percentage"
                                    value="{{ old('buying_percentage') }}" min="0" max="100" step="0.01"
                                    class="ultra-input with-icon font-bold" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    {{-- Detailed Expenses Section --}}
                    <div class="mt-8 mb-6 reveal reveal-d4" style="position: relative; z-index: 20;">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    <i class="fas fa-receipt text-red-500"></i>
                                    Detailed Camp Expenses
                                </h3>
                                <p class="text-[11px] text-slate-500 mt-0.5">Add individual expense items to calculate the
                                    total automatically.</p>
                            </div>
                            <button type="button" id="add_expense_row"
                                class="px-3 py-1.5 text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-2 border border-slate-200/50 dark:border-slate-700/50">
                                <i class="fas fa-plus text-[10px]"></i> Add Item
                            </button>
                        </div>

                        <div id="expense_rows_container" class="space-y-3">
                            {{-- Rows will be injected here --}}
                        </div>

                        {{-- Template for New Row --}}
                        <template id="expense_row_template">
                            <div
                                class="expense-row group border border-slate-200/40 dark:border-white/5 rounded-2xl transition-all hover:border-red-200/60 dark:hover:border-red-500/20 bg-slate-50/50 dark:bg-white/[0.02] p-3">
                                <div class="grid grid-cols-12 gap-3 items-start">
                                    {{-- Category Dropdown --}}
                                    <div class="col-span-11 sm:col-span-5">
                                        <select name="expense_details[{index}][category]" class="expense-category-select"
                                            required>
                                            <option value="">Select Category...</option>
                                            <option value="Decorator">Decorator</option>
                                            <option value="Lunch">Lunch</option>
                                            <option value="Doctor">Doctor</option>
                                            <option value="Pharmacist">Pharmacist</option>
                                            <option value="Extra Transportation">Extra Transportation</option>
                                            <option value="Official Driver">Official Driver</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>

                                    {{-- Amount --}}
                                    <div class="col-span-11 sm:col-span-6 relative">
                                        <span
                                            class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-bold text-slate-400">₹</span>
                                        <input type="number" name="expense_details[{index}][amount]" step="0.01" min="0"
                                            required
                                            class="expense-amount w-full bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 focus:border-red-500 focus:ring-1 focus:ring-red-500/20 rounded-xl text-sm text-right pr-3 pl-7 py-2 font-mono dark:text-slate-200 transition-all"
                                            placeholder="0.00">
                                    </div>

                                    {{-- Remove Button --}}
                                    <div class="col-span-1 flex items-center justify-center pt-2 sm:pt-0">
                                        <button type="button"
                                            class="remove-expense-row text-slate-300 hover:text-red-500 transition-colors p-1">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>

                                    {{-- Custom Note (Hidden by default, shown if "Other" is selected) --}}
                                    <div class="col-span-12 other-note-container hidden mt-2">
                                        <input type="text" name="expense_details[{index}][note]"
                                            class="other-note-input w-full bg-white/50 dark:bg-black/20 border border-dashed border-slate-200 dark:border-slate-700 focus:border-red-400 focus:ring-0 rounded-lg text-xs py-1.5 px-3 dark:text-slate-300 transition-all"
                                            placeholder="Type custom expense description here...">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Summary Row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4" style="position: relative; z-index: 1;">
                        {{-- Gross Profit --}}
                        <div
                            class="fin-card bg-gradient-to-br from-emerald-50 to-green-50/50 dark:from-emerald-500/10 dark:to-emerald-500/5 border border-emerald-200/60 dark:border-emerald-500/15">
                            <div class="fin-label">
                                <span class="fin-dot bg-emerald-500 shadow-lg shadow-emerald-500/30"></span>
                                <span class="fin-title text-emerald-700 dark:text-emerald-400">Gross Profit</span>
                                <span
                                    class="ml-auto px-1.5 py-0.5 rounded-md text-[8px] font-black tracking-wider bg-emerald-100 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400">AUTO</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-emerald-500 font-extrabold text-xl">₹</span>
                                <input type="number" name="profit" id="profit" value="{{ old('profit') }}" step="0.01"
                                    readonly tabindex="-1" class="text-emerald-700 dark:text-emerald-300 cursor-default"
                                    placeholder="0.00">
                            </div>
                        </div>

                        {{-- Camp Expenses --}}
                        <div
                            class="fin-card bg-gradient-to-br from-red-50 to-rose-50/50 dark:from-red-500/10 dark:to-red-500/5 border border-red-200/60 dark:border-red-500/15">
                            <div class="fin-label">
                                <span class="fin-dot bg-red-500 shadow-lg shadow-red-500/30"></span>
                                <span class="fin-title text-red-700 dark:text-red-400">Camp Expenses</span>
                                <span
                                    class="ml-auto px-1.5 py-0.5 rounded-md text-[8px] font-black tracking-wider bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400">AUTO</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-red-500 font-extrabold text-xl">₹</span>
                                <input type="number" name="expenses" id="expenses" value="{{ old('expenses') }}" step="0.01"
                                    min="0" readonly tabindex="-1" class="text-red-700 dark:text-red-300 cursor-default"
                                    placeholder="0.00">
                            </div>
                        </div>

                        {{-- Net Profit --}}
                        <div class="fin-card net-card bg-slate-50/80 dark:bg-white/[0.03]">
                            <div class="fin-label">
                                <span class="fin-title text-slate-500">Net Profit / Loss</span>
                                <span
                                    class="px-1.5 py-0.5 rounded-md text-[8px] font-black tracking-wider bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-500/20 dark:to-purple-500/20 text-indigo-600 dark:text-indigo-400">AUTO</span>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="font-extrabold text-xl text-slate-400 transition-colors duration-500"
                                    id="net_sign">₹</span>
                                <input type="number" id="net_profit_loss_display"
                                    value="{{ old('net_profit_loss') ? abs(old('net_profit_loss')) : '' }}" step="0.01"
                                    readonly class="text-slate-800 dark:text-white transition-colors duration-500"
                                    tabindex="-1" placeholder="—">
                                <input type="hidden" name="net_profit_loss" id="net_profit_loss"
                                    value="{{ old('net_profit_loss') }}">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ═══ ACTION BAR ═══ --}}
            <div class="flex items-center justify-between pt-3 reveal reveal-d5">
                <button type="reset"
                    class="px-5 py-2.5 text-sm font-semibold text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-all">
                    <i class="fas fa-rotate-left text-xs mr-1.5 opacity-60"></i> Reset
                </button>
                <button type="submit" class="btn-premium">
                    <span>Save Camp Record</span>
                    <i class="fas fa-arrow-right text-xs opacity-80"></i>
                </button>
            </div>

        </form>
    </div>

    {{-- ═══ SCRIPTS ═══ --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // TomSelect
            new TomSelect('#rm_select', {
                create: true,
                placeholder: "Search by name, phone or HF ID...",
                allowEmptyOption: true,
                maxOptions: 50,
                searchField: ['text', 'phone', 'hfid'],
                render: {
                    option: function (data, escape) {
                        return `<div class="py-2 px-2">
                                                                                                <div class="font-semibold text-sm text-slate-800 dark:text-white">${escape(data.text)}</div>
                                                                                                <div class="flex items-center gap-3 text-[11px] text-slate-400 mt-0.5">
                                                                                                    <span><i class="fas fa-id-badge mr-1"></i>${escape(data.hfid)}</span>
                                                                                                    <span><i class="fas fa-phone mr-1"></i>${escape(data.phone)}</span>
                                                                                                </div>
                                                                                            </div>`;
                    },
                    item: function (data, escape) {
                        return `<div class="text-sm font-medium">${escape(data.text)}</div>`;
                    }
                }
            });

            // --- Dynamic Expenses Logic ---
            const container = document.getElementById('expense_rows_container');
            const template = document.getElementById('expense_row_template');
            const addButton = document.getElementById('add_expense_row');
            let rowCount = 0;

            function addExpenseRow() {
                const html = template.innerHTML.replace(/{index}/g, rowCount++);
                const div = document.createElement('div');
                div.innerHTML = html;
                const row = div.firstElementChild;
                container.appendChild(row);

                // Initialize TomSelect for the new dropdown
                const select = row.querySelector('.expense-category-select');
                const otherContainer = row.querySelector('.other-note-container');
                const otherInput = row.querySelector('.other-note-input');

                const ts = new TomSelect(select, {
                    create: false,
                    placeholder: "Select Category...",
                    onChange: function (value) {
                        if (value === 'Other') {
                            otherContainer.classList.remove('hidden');
                            otherInput.required = true;
                            otherInput.focus();
                        } else {
                            otherContainer.classList.add('hidden');
                            otherInput.required = false;
                            otherInput.value = '';
                        }
                        calc();
                    }
                });

                // Add listeners to new inputs
                row.querySelector('.expense-amount').addEventListener('input', calc);
                row.querySelector('.remove-expense-row').addEventListener('click', () => {
                    ts.destroy();
                    row.remove();
                    calc();
                });
            }

            addButton.addEventListener('click', addExpenseRow);

            // Calculations
            const mrpEl = document.getElementById('medicine_mrp');
            const discPrizeEl = document.getElementById('medicine_discount');
            const totalDiscEl = document.getElementById('total_discount');
            const buyingPercEl = document.getElementById('buying_percentage');

            const profitEl = document.getElementById('profit');
            const expensesEl = document.getElementById('expenses');
            const netEl = document.getElementById('net_profit_loss');
            const netDisplayEl = document.getElementById('net_profit_loss_display');
            const signEl = document.getElementById('net_sign');

            function calc() {
                const mrp = parseFloat(mrpEl.value) || 0;
                const dPrize = parseFloat(discPrizeEl.value) || 0;
                const bPerc = parseFloat(buyingPercEl.value) || 0;

                // 1. Total Discount = MRP - Discounted Prize
                totalDiscEl.value = Math.max(0, mrp - dPrize).toFixed(2);

                // 2. Gross Profit = Discounted Prize - (MRP * Buying%)
                const cost = mrp * (bPerc / 100);
                const grossProfit = dPrize - cost;
                profitEl.value = grossProfit.toFixed(2);

                // 3. Detailed Expenses Summation
                let totalExp = 0;
                document.querySelectorAll('.expense-amount').forEach(input => {
                    totalExp += parseFloat(input.value) || 0;
                });
                expensesEl.value = totalExp.toFixed(2);

                // 4. Net Profit = Gross Profit - Total Expenses
                const n = grossProfit - totalExp;
                netEl.value = n.toFixed(2);

                if (n >= 0) {
                    netDisplayEl.value = n.toFixed(2);
                    netDisplayEl.style.color = '#059669';
                    signEl.style.color = '#059669';
                    signEl.textContent = '+₹';
                } else {
                    netDisplayEl.value = Math.abs(n).toFixed(2);
                    netDisplayEl.style.color = '#dc2626';
                    signEl.style.color = '#dc2626';
                    signEl.textContent = '-₹';
                }
            }

            mrpEl.addEventListener('input', calc);
            discPrizeEl.addEventListener('input', calc);
            buyingPercEl.addEventListener('input', calc);
            // No need for expensesEl listener as it's now AUTO
            calc();

            // Initial row
            addExpenseRow();
        });
    </script>
@endsection