@extends('layouts.app')

@section('title', 'Member Profile')
@section('header_title', 'Member Profile')

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
            will-change: transform;
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

            0%,
            100% {
                transform: translateY(0px) scale(1);
            }

            50% {
                transform: translateY(-15px) scale(1.05);
            }
        }

        .profile-avatar-ring {
            position: relative;
            padding: 3px;
            border-radius: 28px;
            background: conic-gradient(from var(--ring-angle, 0deg), #6366f1, #8b5cf6, #ec4899, #f59e0b, #10b981, #6366f1);
            animation: ring-spin 1.8s linear infinite, ring-glow 3s ease-in-out infinite alternate;
            box-shadow:
                0 0 18px 4px rgba(99, 102, 241, 0.6),
                0 0 36px 8px rgba(139, 92, 246, 0.35),
                0 0 60px 12px rgba(236, 72, 153, 0.2);
        }

        @property --ring-angle {
            syntax: '<angle>';
            initial-value: 0deg;
            inherits: false;
        }

        @keyframes ring-spin {
            from {
                --ring-angle: 0deg;
            }

            to {
                --ring-angle: 360deg;
            }
        }

        @keyframes ring-glow {
            from {
                box-shadow:
                    0 0 18px 4px rgba(99, 102, 241, 0.7),
                    0 0 36px 8px rgba(139, 92, 246, 0.4),
                    0 0 60px 14px rgba(236, 72, 153, 0.25);
            }

            to {
                box-shadow:
                    0 0 24px 6px rgba(236, 72, 153, 0.7),
                    0 0 48px 12px rgba(245, 158, 11, 0.4),
                    0 0 80px 20px rgba(16, 185, 129, 0.25);
            }
        }

        .profile-avatar-ring img,
        .profile-avatar-ring>div {
            filter: none !important;
        }


        .stat-card {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .stat-card:hover {
            transform: translateY(-4px) scale(1.02);
        }

        .info-card {
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
            /* Strong shadow to pop against the cream #F5EEDC body */
            box-shadow: 0 2px 12px 0 rgba(28, 36, 52, 0.08), 0 0 0 1px rgba(28, 36, 52, 0.06);
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, #3C50E0, #8b5cf6, transparent);
            transition: left 0.5s ease;
        }

        .info-card:hover::before {
            left: 100%;
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

        .btn-action:active {
            transform: translateY(0) scale(0.98);
        }

        .timeline-dot {
            animation: pulse-dot 2s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(99, 102, 241, 0.4);
            }

            50% {
                transform: scale(1.1);
                box-shadow: 0 0 0 4px rgba(99, 102, 241, 0);
            }
        }

        .donation-card {
            background: linear-gradient(135deg, #4338ca 0%, #6d28d9 50%, #5b21b6 100%);
            position: relative;
            overflow: hidden;
        }

        .donation-card::after {
            content: '₹';
            position: absolute;
            right: -10px;
            bottom: -20px;
            font-size: 8rem;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.06);
            line-height: 1;
        }

        .network-card {
            background: linear-gradient(145deg, #1C2434 0%, #1e1b4b 60%, #1C2434 100%);
            position: relative;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(28, 36, 52, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.05);
        }

        .network-stat {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            cursor: default;
        }

        .network-stat:hover {
            transform: scale(1.06);
            background: rgba(60, 80, 224, 0.2) !important;
            border-color: rgba(60, 80, 224, 0.4) !important;
        }

        .data-field {
            transition: all 0.2s ease;
            border-radius: 12px;
            padding: 8px;
            margin: -8px;
        }

        .data-field:hover {
            background: rgba(99, 102, 241, 0.04);
        }

        .badge-shine {
            position: relative;
            overflow: hidden;
        }

        .badge-shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 30%;
            height: 200%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transform: rotate(20deg);
            animation: shine 3s ease-in-out infinite;
        }

        @keyframes shine {
            0% {
                left: -50%;
            }

            100% {
                left: 150%;
            }
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s cubic-bezier(0.2, 1, 0.3, 1);
            will-change: transform, opacity;
        }

        .scroll-reveal {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        .scroll-reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
@endsection

@section('content')
    <div class="max-w-5xl mx-auto space-y-6 pb-12">

        {{-- ===== HERO HEADER CARD ===== --}}
        <div class="rounded-3xl shadow-2xl overflow-hidden scroll-reveal"
            style="background: linear-gradient(135deg, #1C2434 0%, #1e1b4b 45%, #1C2434 100%); box-shadow: 0 25px 60px rgba(28,36,52,0.5);">
            <div class="mesh-grid"></div>
            <div class="orb orb-1"></div>
            <div class="orb orb-2"></div>
            <div class="orb orb-3"></div>

            {{-- Watermark --}}
            <div class="absolute right-0 top-0 select-none pointer-events-none opacity-[0.04] hidden lg:block">
                <span class="text-[15rem] font-black text-white uppercase tracking-tighter leading-none">
                    {{ substr($user->profile?->full_name ?? $user->employee_id, 0, 2) }}
                </span>
            </div>

            <div class="relative p-8">
                {{-- Top Row: Avatar + Info + Actions --}}
                <div class="flex flex-col lg:flex-row lg:items-start gap-6">

                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        <div class="profile-avatar-ring w-28 h-28">
                            <div
                                class="w-full h-full rounded-3xl bg-slate-900 overflow-hidden flex items-center justify-center">
                                @if($user->profile?->profile_picture)
                                    <img src="{{ $user->profile->getProfilePictureUrl() }}"
                                        class="w-full h-full object-cover hover:scale-110 transition-transform duration-500">
                                @else
                                    <span class="text-4xl font-black text-white">
                                        {{ substr($user->profile?->full_name ?? $user->employee_id, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @if($user->status === 'active')
                            <div class="flex justify-center mt-2">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/20 border border-emerald-500/40 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                    <span
                                        class="text-[10px] font-black text-emerald-300 uppercase tracking-widest">Active</span>
                                </span>
                            </div>
                        @else
                            <div class="flex justify-center mt-2">
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/20 border border-amber-500/40 rounded-full">
                                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                                    <span class="text-[10px] font-black text-amber-300 uppercase tracking-widest">Pending</span>
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Name & Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-start gap-3 mb-2">
                            <h2 class="text-4xl font-black text-white tracking-tight leading-none">
                                {{ $user->profile?->full_name ?? 'Incomplete Profile' }}
                            </h2>
                            <span
                                class="badge-shine mt-1 px-3 py-1.5 bg-white/10 border border-white/20 rounded-full text-[10px] font-black text-white uppercase tracking-widest backdrop-blur-sm">
                                {{ $user->getDesignationLabel() }}
                            </span>
                            @if($user->is_office_in_charge && $user->designation !== 'office_in_charge')
                                <span
                                    class="mt-1 px-3 py-1.5 bg-amber-500/20 border border-amber-500/30 rounded-full text-[10px] font-black text-amber-300 uppercase tracking-widest">
                                    Officer In Charge
                                </span>
                            @endif
                        </div>

                        {{-- Employee ID pill --}}
                        <div
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl mb-5">
                            <svg class="w-4 h-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span class="text-white font-black tracking-widest text-sm">{{ $user->employee_id }}</span>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex flex-wrap gap-2">
                            @if((auth()->user()->isSuperAdmin() || auth()->user()->hasFinancePermission('view')) && auth()->user()->canAccess($user) && auth()->user()->id !== $user->id)
                                <a href="{{ route('dashboard', ['as_user' => $user->id]) }}"
                                    class="btn-action inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-600/30 hover:bg-indigo-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View Dashboard
                                </a>
                            @endif

                            @if(in_array(auth()->user()->employee_id, ['HFSA000001', 'HFSA000002']) && str_starts_with($user->employee_id, 'HFSA') && auth()->user()->id !== $user->id)
                                <div class="relative inline-block text-left">
                                    <button type="button"
                                        onclick="document.getElementById('financeDropdown').classList.toggle('hidden')"
                                        class="btn-action inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-600/30 hover:bg-emerald-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Finance Access
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div id="financeDropdown"
                                        class="absolute z-50 mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 overflow-hidden hidden">
                                        <div class="py-1">
                                            <button onclick="updateFinancePermission('none')"
                                                class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ $user->finance_permission == null ? 'font-bold bg-gray-50' : '' }}">
                                                None @if($user->finance_permission == null) ✓ @endif
                                            </button>
                                            <button onclick="updateFinancePermission('view')"
                                                class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ $user->finance_permission == 'view' ? 'font-bold bg-gray-50' : '' }}">
                                                Can view only @if($user->finance_permission == 'view') ✓ @endif
                                            </button>
                                            <button onclick="updateFinancePermission('edit')"
                                                class="w-full text-left block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 {{ $user->finance_permission == 'edit' ? 'font-bold bg-gray-50' : '' }}">
                                                Can view & edit @if($user->finance_permission == 'edit') ✓ @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if(auth()->user()->isSuperAdmin())
                                <div class="relative">
                                    <button type="button" onclick="toggleIDCardDropdown()"
                                        class="btn-action inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-violet-600/30 hover:bg-violet-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3 0 00-2.83 2M15 11h3m-3 4h2" />
                                        </svg>
                                        ID Card
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="id-card-dropdown"
                                        class="hidden absolute top-full mt-2 left-0 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50 min-w-[190px] p-1">
                                        <a href="{{ route('users.id-card', ['user' => $user->id, 'format' => 'png']) }}"
                                            target="_blank"
                                            class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-violet-50 hover:text-violet-600 transition rounded-xl">
                                            <div class="w-8 h-8 bg-violet-50 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-violet-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            PNG Image
                                        </a>
                                        <a href="{{ route('users.id-card', ['user' => $user->id, 'format' => 'pdf']) }}"
                                            target="_blank"
                                            class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-violet-50 hover:text-violet-600 transition rounded-xl">
                                            <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            PDF Document
                                        </a>
                                        <a href="{{ route('users.id-card', ['user' => $user->id, 'format' => 'jpg']) }}"
                                            target="_blank"
                                            class="flex items-center gap-3 px-4 py-3 text-sm font-bold text-slate-700 hover:bg-violet-50 hover:text-violet-600 transition rounded-xl">
                                            <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                            JPG (Canva)
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if(auth()->user()->canAccess($user))
                                <a href="{{ route('users.joining-letter', $user->id) }}" target="_blank"
                                    class="btn-action inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-amber-500/30 hover:bg-amber-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Offer Letter
                                </a>
                            @endif

                            @if($user->isRO() && (auth()->user()->isSuperAdmin() || \App\Models\RolePermission::check(auth()->user()->designation, 'can_assign_oic')))
                                <form action="{{ route('users.toggle-oic', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn-action inline-flex items-center gap-2 px-5 py-2.5 font-bold rounded-xl text-xs shadow-lg transition
                                                                                                                {{ $user->is_office_in_charge ? 'bg-orange-50 text-orange-600 border border-orange-200 hover:bg-orange-100' : 'bg-amber-600 text-white shadow-amber-600/30 hover:bg-amber-500' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        {{ $user->is_office_in_charge ? 'Remove OIC' : 'Assign OIC' }}
                                    </button>
                                </form>
                            @endif

                            @if($currentUser->canEdit($user))
                                <a href="{{ route('users.edit', $user->id) }}"
                                    class="btn-action inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 border border-white/20 text-white font-bold rounded-xl text-xs hover:bg-white/20 backdrop-blur-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit Profile
                                </a>
                            @endif

                            @if($user->status === 'pending' && auth()->user()->canApprove($user))
                                <form action="{{ route('users.approve', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="btn-action inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-500/30 hover:bg-emerald-400">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Approve Member
                                    </button>
                                </form>
                            @endif

                            @if(auth()->user()->isSuperAdmin() && auth()->user()->id !== $user->id && $user->employee_id !== 'HFSA000001')
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to move this user to BIN?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="btn-action inline-flex items-center gap-2 px-5 py-2.5 bg-red-500/10 border border-red-500/20 text-red-400 font-bold rounded-xl text-xs hover:bg-red-500/20">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ===== VITAL STATS BAR ===== --}}
                @php
                    $uplineUser = null;
                    if ($user->isOfficeInCharge()) {
                        $uplineUser = $user->upline ?? $user->parent;
                    } else {
                        $uplineUser = $user->parent;
                    }
                    $uplineName = $uplineUser?->profile?->full_name ?? 'ROOT / Super Admin';
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mt-8 pt-6 border-t border-white/10">
                    <div class="hero-stat-card rounded-2xl p-4 cursor-default">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">Upline Manager</p>
                        @if((auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge()) && $uplineUser)
                            <a href="{{ route('users.show', $uplineUser->id) }}"
                                class="flex items-center gap-1 text-sm font-black text-accent hover:text-white transition-colors group">
                                <span class="truncate">{{ $uplineName }}</span>
                                <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transform group-hover:translate-x-0.5 transition-all flex-shrink-0"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        @else
                            <p class="text-sm font-black text-white truncate">{{ $uplineName }}</p>
                        @endif
                    </div>
                    <div class="hero-stat-card rounded-2xl p-4 cursor-default">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">Direct Downline</p>
                        <p class="text-sm font-black text-white">
                            <span class="text-xl" style="color:#3C50E0">{{ $user->children->count() }}</span>&nbsp;Members
                        </p>
                    </div>
                    <div class="hero-stat-card rounded-2xl p-4 cursor-default">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">Date Joined</p>
                        <p class="text-sm font-black text-white">{{ $user->created_at->format('d M, Y') }}</p>
                    </div>
                    <div class="hero-stat-card rounded-2xl p-4 cursor-default">
                        <p class="text-[9px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">Contact No.</p>
                        <p class="text-sm font-black text-white">{{ $user->profile?->phone_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== DETAILS GRID ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- LEFT: 2-col details --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Identity Details --}}
                <div class="info-card scroll-reveal bg-white rounded-3xl p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-black text-xl text-slate-900">Identity Details</h3>
                            <p class="text-xs text-slate-400 font-medium">Personal & contact information</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="data-field space-y-1.5">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Email Address</p>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-300 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <p class="font-bold text-slate-800 truncate">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="data-field space-y-1.5">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Blood Group</p>
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full bg-red-500 ring-2 ring-red-100 flex-shrink-0"></span>
                                <p class="font-bold text-slate-800">{{ $user->profile?->blood_group ?? 'Not Specified' }}
                                </p>
                            </div>
                        </div>
                        <div class="data-field space-y-1.5">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Aadhaar Card No.</p>
                            <p class="font-bold text-slate-800 font-mono tracking-wider">
                                {{ $user->profile?->aadhaar_number ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="data-field space-y-1.5">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">PAN Card No.</p>
                            <p class="font-bold text-slate-800 uppercase font-mono tracking-wider">
                                {{ $user->profile?->pan_number ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">Current Address</p>
                            <div
                                class="p-4 bg-slate-50 hover:bg-slate-100 transition-colors rounded-2xl border border-slate-100 group">
                                <p class="font-bold text-slate-800 leading-relaxed">{{ $user->profile?->address ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-slate-500 mt-2 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-accent flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ collect([$user->profile?->gram_panchayat, $user->profile?->block, $user->profile?->district, $user->profile?->state])->filter()->join(', ') }}{{ $user->profile?->pin_code ? ' - ' . $user->profile->pin_code : '' }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- Super Admin Password Reveal --}}
                        @if(auth()->user()->isSuperAdmin())
                            <div
                                class="md:col-span-2 data-field space-y-1.5 bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 mt-2">
                                <p class="text-[9px] font-black text-indigo-500 uppercase tracking-[0.2em]">Current Password
                                    (Super Admin Only)</p>
                                <div class="flex items-center justify-between mt-1">
                                    @if($user->password_plain)
                                        <p class="font-black text-slate-700 font-mono tracking-[0.3em] text-lg"
                                            id="plain-password-text">••••••••</p>
                                        <button type="button" onclick="togglePlainPassword()" id="password-toggle-btn"
                                            class="px-3 py-1 bg-white border border-indigo-200 text-[10px] font-black text-indigo-600 uppercase tracking-widest rounded-lg hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                                            Show
                                        </button>
                                    @else
                                        <p class="text-sm font-bold text-slate-400 italic">Not Captured (Pre-existing User)</p>
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="text-[10px] bg-white border border-slate-200 text-slate-400 font-black uppercase tracking-widest p-1 px-4 rounded-lg hover:bg-indigo-600 hover:text-white transition shadow-sm">
                                            Update Password
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Financial Row --}}
                @if(auth()->user()->hasFinancePermission('view'))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Bank Details --}}
                        <div class="info-card scroll-reveal bg-white rounded-3xl p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div
                                    class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-200">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <h3 class="font-black text-lg text-slate-900">Bank Details</h3>
                            </div>
                            <div class="space-y-3">
                                <div
                                    class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors border border-transparent hover:border-emerald-100">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Bank</span>
                                    <span
                                        class="font-bold text-slate-700 text-sm">{{ $user->bankDetails?->bank_name ?? 'N/A' }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors border border-transparent hover:border-emerald-100">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">A/C No.</span>
                                    <div class="flex items-center gap-2">
                                        <span class="font-black text-slate-700 text-sm font-mono tracking-widest" id="account-number-text">••••{{ substr($user->bankDetails?->account_number ?? '0000', -4) }}</span>
                                        @if($user->bankDetails?->account_number)
                                            <button type="button" onclick="toggleAccountNumber()" id="account-toggle-btn"
                                                class="flex items-center justify-center p-1.5 bg-white border border-emerald-200 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm"
                                                title="Toggle Visibility">
                                                <svg class="w-4 h-4 toggle-icon-show" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                                <div
                                    class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-emerald-50 transition-colors border border-transparent hover:border-emerald-100">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">IFSC</span>
                                    <span
                                        class="font-bold text-slate-700 text-sm font-mono uppercase">{{ $user->bankDetails?->ifsc_code ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Donation --}}
                        <div class="info-card scroll-reveal rounded-3xl overflow-hidden">
                            <div class="donation-card p-6 h-full">
                                <div class="flex items-center gap-3 mb-6">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="font-black text-lg text-white">Joining Donation</h3>
                                </div>
                                <div class="relative mb-4">
                                    <p class="text-[9px] font-black text-white/50 uppercase tracking-widest mb-1">Amount</p>
                                    <p class="text-5xl font-black text-white">₹{{ number_format($user->joining_donation, 0) }}
                                    </p>
                                </div>
                                <div class="space-y-2">
                                    <div
                                        class="flex items-center justify-between p-3 bg-white/10 border border-white/10 rounded-xl">
                                        <span class="text-[9px] font-black text-white/50 uppercase tracking-widest">Payment
                                            Status</span>
                                        @if($user->payment_status === 'completed')
                                            <span class="flex items-center gap-1.5 text-xs font-black text-emerald-300 uppercase">
                                                <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span>
                                                Completed
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1.5 text-xs font-black text-amber-300 uppercase">
                                                <span class="w-1.5 h-1.5 bg-amber-400 rounded-full"></span>
                                                Pending
                                            </span>
                                        @endif
                                    </div>
                                    @if($user->payment_reference)
                                        <div
                                            class="flex items-center justify-between p-3 bg-white/10 border border-white/10 rounded-xl">
                                            <span class="text-[9px] font-black text-white/50 uppercase tracking-widest">Ref
                                                ID</span>
                                            <span
                                                class="text-xs font-bold text-white font-mono">{{ $user->payment_reference }}</span>
                                        </div>
                                    @endif
                                    @if($user->payment_screenshot)
                                        <a href="{{ route('storage.bridge', ['path' => $user->payment_screenshot]) }}"
                                            target="_blank"
                                            class="flex items-center justify-center gap-2 p-3 bg-white/10 border border-white/20 rounded-xl text-xs font-bold text-white hover:bg-white/20 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View Screenshot
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            {{-- RIGHT: Sidebar --}}
            <div class="space-y-6">

                {{-- Attendance Summary (Admins/RMs only for ROs) --}}
                @if($attendanceSummary)
                    <div class="scroll-reveal relative rounded-3xl p-6 mb-6"
                        style="background: linear-gradient(135deg, #065f46 0%, #064e3b 100%); box-shadow: 0 20px 60px rgba(6,78,59,0.3), 0 0 0 1px rgba(255,255,255,0.05);">
                        <h3 class="relative font-black text-white text-lg mb-4 flex items-center justify-between">
                            <span class="flex items-center gap-2">
                                <span class="w-2 h-6 rounded-full bg-emerald-400"></span>
                                {{ $attendanceSummary['month_name'] }} Attendance
                            </span>
                            <div class="flex items-center gap-2">
                                @php
                                    $effectiveUser = \App\Models\User::getEffectiveUser();
                                    $canMark = $effectiveUser->isSuperAdmin() || $effectiveUser->id === $user->parent_id;
                                    $isTabMode = ($user->salary_mode ?? 'tab') === 'tab';
                                    $isMarkableRole = $user->isRO() || $user->isRM() || $user->isBM() || $user->isDM();
                                @endphp



                                <a href="{{ route('attendance.show', $user->id) }}"
                                    class="text-[10px] bg-white/10 hover:bg-white/20 px-2 py-1 rounded-lg transition">FULL
                                    LOG</a>
                            </div>
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10 hover:bg-white/15 transition">
                                <p class="text-[9px] font-black text-emerald-200 uppercase tracking-widest mb-1">Present</p>
                                <p class="text-2xl font-black text-white">{{ $attendanceSummary['present'] }} <span
                                        class="text-xs opacity-50 font-medium">Days</span></p>
                            </div>
                            <div class="bg-white/10 rounded-2xl p-4 border border-white/10 hover:bg-white/15 transition">
                                <p class="text-[9px] font-black text-emerald-200 uppercase tracking-widest mb-1">Earning</p>
                                <p class="text-2xl font-black text-white">
                                    ₹{{ number_format($attendanceSummary['total_amount']) }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Network Overview --}}
                @php
                    $directCount = $user->children->count();
                    $totalCount = $user->getDownlineCount();
                @endphp
                <div class="scroll-reveal relative rounded-3xl p-6"
                    style="background: linear-gradient(145deg, #1C2434 0%, #1e1b4b 60%, #1C2434 100%); box-shadow: 0 20px 60px rgba(28,36,52,0.5), 0 0 0 1px rgba(255,255,255,0.05);">

                    <h3 class="relative font-black text-white text-lg mb-5 flex items-center gap-2">
                        <span class="w-2 h-6 rounded-full" style="background:#3C50E0"></span>
                        Network Overview
                    </h3>
                    <div class="relative grid grid-cols-2 gap-3 mb-4">
                        <div class="rounded-2xl p-4 text-center transition-all duration-300 hover:scale-105 cursor-default"
                            style="background:rgba(255,255,255,0.07); border:1px solid rgba(255,255,255,0.1);">
                            <p class="text-3xl font-black text-white mb-1">{{ $directCount }}</p>
                            <p class="text-[9px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.4);">
                                Direct</p>
                        </div>
                        <div class="rounded-2xl p-4 text-center transition-all duration-300 hover:scale-105 cursor-default"
                            style="background:rgba(60,80,224,0.15); border:1px solid rgba(60,80,224,0.3);">
                            <p class="text-3xl font-black mb-1" style="color:#3C50E0;">{{ $totalCount }}</p>
                            <p class="text-[9px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,0.4);">
                                Total</p>
                        </div>
                    </div>
                    @if($totalCount > 0)
                        <div class="relative h-2 rounded-full overflow-hidden" style="background:rgba(255,255,255,0.1);">
                            <div class="h-full rounded-full transition-all duration-1000"
                                style="width: {{ $directCount > 0 ? min(100, ($directCount / max($totalCount, 1)) * 100) : 0 }}%; background: linear-gradient(90deg, #3C50E0, #8b5cf6);">
                            </div>
                        </div>
                        <p class="text-[9px] mt-2 text-center" style="color:rgba(255,255,255,0.3);">Direct / Total ratio</p>
                    @endif
                </div>

                {{-- Signed Offer Letter Upload (Upline/Admin Only) --}}
                @if($user->status === 'pending' && (auth()->user()->isSuperAdmin() || auth()->user()->id === $user->parent_id))
                    <div class="info-card scroll-reveal bg-white rounded-3xl p-6 border-2 border-dashed border-indigo-100">
                        <h3 class="font-black text-slate-900 mb-4 flex items-center gap-2">
                            <span class="w-2 h-6 bg-indigo-500 rounded-full"></span>
                            Signed Offer Letter
                        </h3>

                        @if($user->offer_letter_signed)
                            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500 flex items-center justify-center text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black text-emerald-700 uppercase">File Uploaded</p>
                                        <a href="{{ route('storage.bridge', ['path' => $user->offer_letter_signed]) }}"
                                            target="_blank"
                                            class="text-xs font-bold text-indigo-600 hover:text-indigo-500 underline">View Signed
                                            Document</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('users.upload-signed-letter', $user->id) }}" method="POST"
                            enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div class="space-y-2">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest px-1">Upload Signed
                                    PDF</label>
                                <input type="file" name="signed_letter" accept=".pdf" required
                                    class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                                <p class="text-[9px] text-slate-400 px-1 italic">Max size: 10MB (PDF only)</p>
                            </div>
                            <button type="submit"
                                class="w-full btn-action py-3 bg-indigo-600 text-white font-black rounded-xl text-xs shadow-lg shadow-indigo-200">
                                {{ $user->offer_letter_signed ? 'Change Signed Letter' : 'Upload Signed Letter' }}
                            </button>
                        </form>
                    </div>
                @elseif($user->offer_letter_signed)
                    <div class="info-card scroll-reveal bg-white rounded-3xl p-6">
                        <h3 class="font-black text-slate-900 mb-4 flex items-center gap-2">
                            <span class="w-2 h-6 bg-emerald-500 rounded-full"></span>
                            Verified Offer Letter
                        </h3>
                        <a href="{{ route('storage.bridge', ['path' => $user->offer_letter_signed]) }}" target="_blank"
                            class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-100 rounded-2xl hover:bg-indigo-50 hover:border-indigo-100 transition-all group">
                            <div
                                class="w-10 h-10 rounded-xl bg-indigo-500 flex items-center justify-center text-white group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Signed Document</p>
                                <p class="text-xs font-bold text-slate-700">Download/View Signed Letter</p>
                            </div>
                        </a>
                    </div>
                @endif


                {{-- Recent Actions Timeline --}}
                <div class="info-card scroll-reveal bg-white rounded-3xl p-6">
                    <h3 class="font-black text-slate-900 mb-5 flex items-center gap-2">
                        <span class="w-2 h-6 bg-violet-500 rounded-full"></span>
                        Recent Actions
                    </h3>
                    <div class="space-y-4">
                        @forelse($user->activityLogs as $index => $log)
                            <div class="flex gap-3 group">
                                <div class="flex flex-col items-center flex-shrink-0">
                                    <div class="timeline-dot w-3 h-3 rounded-full bg-gradient-to-br from-indigo-400 to-violet-500 ring-4 ring-indigo-50 group-hover:ring-indigo-100 transition-all"
                                        style="animation-delay: {{ $index * 0.3 }}s"></div>
                                    @if(!$loop->last)
                                        <div class="w-px flex-1 bg-gradient-to-b from-indigo-100 to-transparent my-1 min-h-[1rem]">
                                        </div>
                                    @endif
                                </div>
                                <div class="pb-1 flex-1 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 leading-snug mb-0.5">{{ $log->description }}</p>
                                    <p class="text-[10px] text-slate-400 font-medium">{{ $log->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div
                                    class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                                    <svg class="w-7 h-7 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-400 font-medium">No activity yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Dropdown toggle
        function toggleIDCardDropdown() {
            const dropdown = document.getElementById('id-card-dropdown');
            dropdown.classList.toggle('hidden');
        }

        document.addEventListener('click', function (event) {
            const dropdown = document.getElementById('id-card-dropdown');
            const button = event.target.closest('button[onclick="toggleIDCardDropdown()"]');
            if (!button && dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Password Reveal Toggle
        let isShowingPassword = false;

        function togglePlainPassword() {
            const passwordText = document.getElementById('plain-password-text');
            const toggleBtn = document.getElementById('password-toggle-btn');
            const plainValue = @json($user->password_plain);

            if (!isShowingPassword) {
                passwordText.innerText = plainValue;
                passwordText.style.letterSpacing = '0.05em'; // Reduce spacing for readability
                toggleBtn.innerText = 'Hide';
                isShowingPassword = true;
            } else {
                passwordText.innerText = '••••••••';
                passwordText.style.letterSpacing = '0.3em'; // Restore spacing for dots
                toggleBtn.innerText = 'Show';
                isShowingPassword = false;
            }
        }

        // Account Number Reveal Toggle
        let isShowingAccount = false;
        function toggleAccountNumber() {
            const accountText = document.getElementById('account-number-text');
            const toggleBtn = document.getElementById('account-toggle-btn');
            const fullValue = @json($user->bankDetails?->account_number);
            
            if (!fullValue) return;

            if (!isShowingAccount) {
                accountText.innerText = fullValue;
                toggleBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                `;
                isShowingAccount = true;
            } else {
                accountText.innerText = "••••" + fullValue.slice(-4);
                toggleBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                `;
                isShowingAccount = false;
            }
        }

        // Scroll reveal animation
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, i * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('.scroll-reveal').forEach(el => observer.observe(el));

        function markAttendance(userId, status, element) {
            if (!status) return;
            fetch('{{ route("attendance.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    user_id: userId,
                    status: status,
                    date: '{{ date("Y-m-d") }}'
                })
            }).then(response => response.json()).then(data => {
                if (data.attendance) {
                    if (typeof Toast !== 'undefined') {
                        Toast.fire({
                            icon: 'success',
                            title: data.message
                        });
                    } else {
                        alert(data.message);
                    }
                } else {
                    if (typeof Toast !== 'undefined') {
                        Toast.fire({
                            icon: 'error',
                            title: data.message || 'Something went wrong'
                        });
                    } else {
                        alert(data.message || 'Something went wrong');
                    }
                }
            }).catch(error => {
                console.error('Error:', error);
                if (typeof Toast !== 'undefined') {
                    Toast.fire({
                        icon: 'error',
                        title: 'System Error'
                    });
                } else {
                    alert('System Error');
                }
            });
        }

        // Handle Finance Permission update
        function updateFinancePermission(permission) {
            document.getElementById('financeDropdown').classList.add('hidden');

            fetch("{{ route('users.finance-permission', $user->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    finance_permission: permission
                })
            }).then(async response => {
                const data = await response.json().catch(() => ({}));
                if (response.ok && data.success) {
                    if (typeof Toast !== 'undefined') {
                        Toast.fire({ icon: 'success', title: data.message });
                    } else {
                        alert(data.message);
                    }
                    setTimeout(() => location.reload(), 1000);
                } else {
                    throw new Error(data.message || 'Server returned ' + response.status);
                }
            }).catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            });
        }
    </script>
@endsection