@extends('layouts.app')

@section('title', 'Dashboard')
@section('header_title', 'Dashboard Overview')

@section('content')
    <meta name="live-page" content="dashboard">
    @php
        $user = $user ?? auth()->user();
        $isViewAs = $isViewAs ?? false;
        $canViewReports = $canViewReports ?? false;
        $canViewDownline = $canViewDownline ?? false;
        $canApprove = $canApprove ?? false;
        $stats = $stats ?? [
            'total_downline' => 0,
            'active_downline' => 0,
            'pending_approvals' => 0,
            'direct_children' => 0
        ];
    @endphp
    @if(isset($isViewAs) && $isViewAs)
        <div
            class="bg-indigo-600 text-white rounded-2xl p-4 mb-8 shadow-lg shadow-indigo-600/20 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-white/10 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-sm">Viewing Dashboard As: <span
                            class="font-black underline">{{ $user->profile?->full_name ?? $user->employee_id }}</span></p>
                    <p class="text-[10px] opacity-80 uppercase tracking-widest">You are in read-only view mode</p>
                </div>
            </div>
            <a href="{{ route('dashboard') }}"
                class="px-4 py-2 bg-white text-indigo-600 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-indigo-50 transition border border-transparent hover:border-white/20">Back
                to My Dashboard</a>
        </div>
    @endif
    <!-- Stats Grid -->
    @if($canViewStats)
        <div
            class="grid {{ $canApprove ? 'grid-cols-3' : 'grid-cols-2' }} md:grid-cols-{{ $canApprove ? '3' : '2' }} lg:grid-cols-{{ $canApprove ? '3' : '2' }} gap-2 md:gap-6 mb-2">
            <!-- Total Downline -->
            <div
                class="glass bg-white dark:bg-darkbg/40 {{ auth()->user()->isSuperAdmin() ? 'p-1.5' : 'p-2' }} md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-12 h-12 bg-accent/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                <div class="flex items-center justify-between mb-1 md:mb-4 relative z-10">
                    <div
                        class="w-6 h-6 md:w-12 md:h-12 bg-accent/10 text-accent dark:text-blue-400 rounded md:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-3.5 h-3.5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <span class="text-[7px] md:text-xs font-black text-success bg-success/10 px-1 md:px-2 py-0.5 rounded-full"
                        data-live-sync="stats.direct_children">+{{ $stats['direct_children'] }}</span>
                </div>
                <h3
                    class="text-slate-400 dark:text-slate-500 text-[8px] md:text-sm font-black uppercase tracking-widest mb-0.5 md:mb-2 relative z-10 truncate">
                    Team</h3>
                <p class="text-sm md:text-4xl font-black text-slate-800 dark:text-blue-400 relative z-10"
                    data-live-sync="stats.total_downline">
                    {{ number_format($stats['total_downline']) }}
                </p>
            </div>

            <!-- Active Members -->
            <div
                class="glass bg-white dark:bg-darkbg/40 {{ auth()->user()->isSuperAdmin() ? 'p-1.5' : 'p-2' }} md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-12 h-12 bg-success/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                <div class="flex items-center justify-between mb-1 md:mb-4 relative z-10">
                    <div
                        class="w-6 h-6 md:w-12 md:h-12 bg-success/10 text-success dark:text-emerald-400 rounded md:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-3.5 h-3.5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3
                    class="text-slate-400 dark:text-slate-500 text-[8px] md:text-sm font-black uppercase tracking-widest mb-0.5 md:mb-2 relative z-10 truncate">
                    Active</h3>
                <p class="text-sm md:text-4xl font-black text-slate-800 dark:text-emerald-400 relative z-10"
                    data-live-sync="stats.active_downline">
                    {{ number_format($stats['active_downline']) }}
                </p>
            </div>

            @if($canApprove)
                <!-- Pending Approvals -->
                <div onclick="window.location.href='{{ route('users.index', ['status' => 'pending']) }}'"
                    class="glass bg-white dark:bg-darkbg/40 p-1.5 md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative cursor-pointer">
                    <div class="absolute top-0 right-0 w-12 h-12 bg-pink-500/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                    <div class="flex items-center justify-between mb-1 md:mb-4 relative z-10">
                        <div
                            class="w-6 h-6 md:w-12 md:h-12 bg-pink-500/10 text-pink-500 dark:text-pink-400 rounded md:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <h3
                        class="text-slate-400 dark:text-slate-500 text-[8px] md:text-sm font-black uppercase tracking-widest mb-0.5 md:mb-2 relative z-10 truncate">
                        Pending</h3>
                    <p class="text-sm md:text-4xl font-black text-slate-800 dark:text-pink-400 relative z-10"
                        data-live-sync="stats.pending_approvals">
                        {{ number_format($stats['pending_approvals']) }}
                    </p>
                </div>
            @endif
        </div>
    @endif

    <!-- Earnings Overview (RO, RM, BM, DM) -->
    @if(isset($earnings) && $earnings)
        @php
            $isDab = ($earnings['salary_mode'] ?? 'tab') === 'dab' && isset($earnings['dab']);
            $showTA = $user->isRO() && ($earnings['salary_mode'] ?? 'tab') === 'tab';
            $showDA = ($earnings['salary_mode'] ?? 'tab') === 'dab';
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-6 mb-8">
            <!-- Monthly Earning -->
            <div
                class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-16 h-16 bg-blue-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-10 h-10 bg-blue-500/10 text-blue-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div data-live-sync="salary_mode_badge">
                            {!! (($earnings['salary_mode'] ?? 'tab') === 'dab' ? '<span class="text-[9px] font-black text-violet-500 bg-violet-500/10 px-2 py-0.5 rounded-full uppercase tracking-widest border border-violet-500/20">SERVICE</span>' : '<span class="text-[9px] font-black text-blue-500 bg-blue-500/10 px-2 py-0.5 rounded-full uppercase tracking-widest border border-blue-500/20">TAB</span>') !!}
                        </div>
                        <span
                            class="text-[10px] font-black text-blue-500 bg-blue-500/5 px-2 py-1 rounded-full uppercase tracking-widest">Analytics</span>
                    </div>
                </div>
                <h3 class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Monthly Total Earnings</h3>
                <p class="text-3xl font-black text-slate-800 dark:text-white" data-live-sync="earnings.monthly_total">
                    ₹{{ number_format($earnings['monthly_total'], 2) }}
                </p>
                <div class="mt-2 flex items-center space-x-2">
                    <span
                        class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full uppercase tracking-widest">Today:
                        ₹{{ number_format($earnings['today_total'], 2) }}</span>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-y-2 text-[9px] font-bold uppercase tracking-tight">
                    @if($showTA || $showDA)
                        <div class="flex items-center text-slate-500">
                            <div class="w-1.5 h-1.5 rounded-full bg-slate-300 mr-1.5"></div>
                            <span
                                data-live-sync="earnings.monthly_ta">{{ ($earnings['salary_mode'] ?? 'tab') === 'dab' ? 'DA' : 'TA' }}:
                                ₹{{ number_format($earnings['monthly_breakdown']['ta'], 2) }}</span>
                        </div>
                    @endif
                    <div class="flex items-center text-emerald-500">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500/40 mr-1.5"></div>
                        <span data-live-sync="earnings.monthly_medicines">Med:
                            ₹{{ number_format($earnings['monthly_breakdown']['medicines'], 2) }}</span>
                    </div>
                    <div class="flex items-center text-purple-500">
                        <div class="w-1.5 h-1.5 rounded-full bg-purple-500/40 mr-1.5"></div>
                        <span data-live-sync="earnings.monthly_pathology">Path:
                            ₹{{ number_format($earnings['monthly_breakdown']['pathology'], 2) }}</span>
                    </div>
                    <div class="flex items-center text-amber-500">
                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500/40 mr-1.5"></div>
                        <span data-live-sync="earnings.monthly_membership">Mem:
                            ₹{{ number_format($earnings['monthly_breakdown']['membership'], 2) }}</span>
                    </div>
                    <div class="flex items-center text-rose-400">
                        <div class="w-1.5 h-1.5 rounded-full bg-rose-400/40 mr-1.5"></div>
                        <span data-live-sync="earnings.monthly_ots">OTs:
                            ₹{{ number_format($earnings['monthly_breakdown']['ots'], 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Dynamic Box 2 -->
            @if($isDab)
                {{-- DAB Mode: Doctor Appointment Earnings --}}
                <div
                    class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-violet-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-10 h-10 bg-violet-500/10 text-violet-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-black text-violet-500 bg-violet-500/5 px-2 py-1 rounded-full uppercase tracking-widest">Monthly</span>
                    </div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Service Earnings</h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white" data-live-sync="earnings.dab_earnings">
                        ₹{{ number_format($earnings['dab']['earnings'], 2) }}
                    </p>
                    <p class="text-[9px] text-slate-500 font-bold mt-2 uppercase tracking-widest">{{ $earnings['dab']['count'] }}
                        Successful Appointments</p>
                </div>
            @elseif($showTA)
                {{-- TAB Mode: Travel Allowance --}}
                <div
                    class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-black text-emerald-500 bg-emerald-500/5 px-2 py-1 rounded-full uppercase tracking-widest">Monthly</span>
                    </div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Travel Allowance</h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white" data-live-sync="earnings.monthly_ta">
                        ₹{{ number_format($earnings['monthly_ta'], 2) }}
                    </p>
                    <div class="mt-4">
                        <div class="w-full bg-slate-100 dark:bg-white/5 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-[9px] text-slate-500 font-bold mt-2 uppercase tracking-widest">Fixed Daily Allowance Basis
                        </p>
                    </div>
                </div>
            @else
                {{-- Others: Today's Summary --}}
                <div
                    class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="w-10 h-10 bg-emerald-500/10 text-emerald-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <span
                            class="text-[10px] font-black text-emerald-500 bg-emerald-500/5 px-2 py-1 rounded-full uppercase tracking-widest">Today</span>
                    </div>
                    <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Today's Earnings</h3>
                    <p class="text-3xl font-black text-slate-800 dark:text-white" data-live-sync="earnings.today_total">
                        ₹{{ number_format($earnings['today_total'], 2) }}
                    </p>
                    <div class="mt-4 grid grid-cols-2 gap-1 text-[9px] font-bold uppercase tracking-tight text-slate-500">
                        <span>Med: ₹{{ number_format($earnings['today_breakdown']['medicines'], 2) }}</span>
                        <span>Path: ₹{{ number_format($earnings['today_breakdown']['pathology'], 2) }}</span>
                        <span>OTs: ₹{{ number_format($earnings['today_breakdown']['ots'], 2) }}</span>
                    </div>
                </div>
            @endif

            <!-- Monthly Incentives -->
            <div
                class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-16 h-16 bg-indigo-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="w-10 h-10 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <span
                        class="text-[10px] font-black text-indigo-500 bg-indigo-500/5 px-2 py-1 rounded-full uppercase tracking-widest">Monthly</span>
                </div>
                <h3 class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Activity Incentives</h3>
                <p class="text-3xl font-black text-slate-800 dark:text-white" data-live-sync="earnings.monthly_incentives">
                    ₹{{ number_format($earnings['monthly_incentives'], 2) }}</p>
                <div class="mt-4 grid grid-cols-2 gap-2">
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 uppercase font-black tracking-widest">Med</span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300"
                            data-live-sync="earnings.monthly_medicines">₹{{ number_format($earnings['monthly_breakdown']['medicines'], 2) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 uppercase font-black tracking-widest">Path</span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300"
                            data-live-sync="earnings.monthly_pathology">₹{{ number_format($earnings['monthly_breakdown']['pathology'], 2) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 uppercase font-black tracking-widest">Mem</span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300"
                            data-live-sync="earnings.monthly_membership">₹{{ number_format($earnings['monthly_breakdown']['membership'], 2) }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[8px] text-slate-400 uppercase font-black tracking-widest">OTs</span>
                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300"
                            data-live-sync="earnings.monthly_ots">₹{{ number_format($earnings['monthly_breakdown']['ots'], 2) }}</span>
                    </div>
                </div>
                <div
                    class="mt-2 text-[8px] text-slate-500 font-bold uppercase tracking-widest border-t border-slate-100 dark:border-white/5 pt-2">
                    Performance Based Breakdown
                </div>
            </div>
        </div>
    @endif

    <!-- Reports Grid -->
    @if($canViewReports && !empty($reports))
        <div class="grid grid-cols-2 gap-2 md:gap-6 mb-10">
            <!-- Survey Reports -->
            <a href="{{ route('surveys.index') }}"
                class="block glass bg-white dark:bg-darkbg/40 p-2 md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-12 h-12 bg-indigo-500/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                <div class="flex items-center justify-between mb-2 md:mb-6 relative z-10">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 md:w-12 md:h-12 bg-indigo-500/10 text-indigo-500 rounded-lg md:rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] md:text-xs font-black uppercase tracking-widest text-slate-400">Total Surveys
                            </h3>
                            <p class="text-xl md:text-3xl font-black text-slate-800 dark:text-white"
                                data-live-sync="reports.surveys_total">
                                {{ number_format($reports['surveys']['total']) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 relative z-10">
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Today</p>
                        <p class="text-sm md:text-lg font-black text-indigo-500" data-live-sync="reports.surveys_daily">
                            {{ number_format($reports['surveys']['daily']) }}
                        </p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Week</p>
                        <p class="text-sm md:text-lg font-black text-indigo-500" data-live-sync="reports.surveys_weekly">
                            {{ number_format($reports['surveys']['weekly']) }}
                        </p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Month</p>
                        <p class="text-sm md:text-lg font-black text-indigo-500" data-live-sync="reports.surveys_monthly">
                            {{ number_format($reports['surveys']['monthly']) }}
                        </p>
                    </div>
                </div>
            </a>

            <!-- Appointment Reports -->
            <a href="{{ route('appointments.all') }}"
                class="block glass bg-white dark:bg-darkbg/40 p-2 md:p-6 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg hover:bg-slate-50 dark:hover:bg-darkbg/60 transition-all group overflow-hidden relative">
                <div class="absolute top-0 right-0 w-12 h-12 bg-emerald-500/5 rounded-full -mr-3 -mt-3 blur-xl"></div>
                <div class="flex items-center justify-between mb-2 md:mb-6 relative z-10">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 md:w-12 md:h-12 bg-emerald-500/10 text-emerald-500 rounded-lg md:rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] md:text-xs font-black uppercase tracking-widest text-slate-400">Total Apps
                            </h3>
                            <p class="text-xl md:text-3xl font-black text-slate-800 dark:text-white"
                                data-live-sync="reports.appointments_total">
                                {{ number_format($reports['appointments']['total']) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-2 relative z-10">
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Today</p>
                        <p class="text-sm md:text-lg font-black text-emerald-500" data-live-sync="reports.appointments_daily">
                            {{ number_format($reports['appointments']['daily']) }}
                        </p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Week</p>
                        <p class="text-sm md:text-lg font-black text-emerald-500" data-live-sync="reports.appointments_weekly">
                            {{ number_format($reports['appointments']['weekly']) }}
                        </p>
                    </div>
                    <div
                        class="bg-slate-50 dark:bg-slate-800/50 rounded-lg p-2 md:p-3 text-center border border-slate-100 dark:border-white/5">
                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-1">Month</p>
                        <p class="text-sm md:text-lg font-black text-emerald-500" data-live-sync="reports.appointments_monthly">
                            {{ number_format($reports['appointments']['monthly']) }}
                        </p>
                    </div>
                </div>
            </a>
        </div>
    @endif

    {{-- ── FINANCIAL OVERVIEW ── --}}
    @if(isset($financials) && $financials)
        <div class="mb-10">
            {{-- Quick Actions Bar --}}
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-slate-800 dark:bg-white/10 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 dark:text-white tracking-tight">Financial Overview</h3>
                        <p class="text-[10px] text-slate-400 font-semibold">{{ now()->format('F Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    @if(auth()->user()->hasPermission('finances.create_income'))
                        <a href="{{ route('incomes.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition-all shadow-sm shadow-emerald-500/20 hover:shadow-md hover:shadow-emerald-500/30 hover:-translate-y-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Record Income
                        </a>
                    @endif
                    @if(auth()->user()->hasPermission('finances.create_expense'))
                        <a href="{{ route('expenses.create') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition-all shadow-sm shadow-rose-500/20 hover:shadow-md hover:shadow-rose-500/30 hover:-translate-y-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Record Expense
                        </a>
                    @endif
                    <a href="{{ route('finances.index') }}"
                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-white dark:bg-darkbg/60 border border-slate-200 dark:border-white/10 text-slate-600 dark:text-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                        Finances Hub
                    </a>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-3 gap-2 md:gap-4 mb-5">
                {{-- Income Card --}}
                <a href="{{ route('incomes.index') }}"
                    class="glass bg-white dark:bg-darkbg/40 p-3 md:p-5 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-14 h-14 bg-emerald-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                    <div class="flex items-center justify-between mb-2 md:mb-3 relative z-10">
                        <div
                            class="w-7 h-7 md:w-9 md:h-9 bg-emerald-500/10 text-emerald-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                            </svg>
                        </div>
                        @if($financials['incomeChange'] != 0)
                            <span
                                class="text-[9px] md:text-[10px] font-black px-1.5 py-0.5 rounded-full {{ $financials['incomeChange'] > 0 ? 'text-emerald-600 bg-emerald-500/10' : 'text-rose-500 bg-rose-500/10' }}">
                                {{ $financials['incomeChange'] > 0 ? '+' : '' }}{{ $financials['incomeChange'] }}%
                            </span>
                        @endif
                    </div>
                    <h3
                        class="text-slate-400 dark:text-slate-500 text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5 md:mb-1">
                        Income</h3>
                    <p class="text-sm md:text-2xl font-black text-emerald-600 dark:text-emerald-400"
                        style="font-variant-numeric:tabular-nums">₹{{ number_format($financials['thisMonthIncome'], 2) }}</p>
                </a>

                {{-- Expense Card --}}
                <a href="{{ route('expenses.index') }}"
                    class="glass bg-white dark:bg-darkbg/40 p-3 md:p-5 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 w-14 h-14 bg-rose-500/5 rounded-full -mr-4 -mt-4 blur-xl"></div>
                    <div class="flex items-center justify-between mb-2 md:mb-3 relative z-10">
                        <div
                            class="w-7 h-7 md:w-9 md:h-9 bg-rose-500/10 text-rose-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                            </svg>
                        </div>
                        @if($financials['expenseChange'] != 0)
                            <span
                                class="text-[9px] md:text-[10px] font-black px-1.5 py-0.5 rounded-full {{ $financials['expenseChange'] > 0 ? 'text-rose-500 bg-rose-500/10' : 'text-emerald-600 bg-emerald-500/10' }}">
                                {{ $financials['expenseChange'] > 0 ? '+' : '' }}{{ $financials['expenseChange'] }}%
                            </span>
                        @endif
                    </div>
                    <h3
                        class="text-slate-400 dark:text-slate-500 text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5 md:mb-1">
                        Expenses</h3>
                    <p class="text-sm md:text-2xl font-black text-rose-500 dark:text-rose-400"
                        style="font-variant-numeric:tabular-nums">₹{{ number_format($financials['thisMonthExpense'], 2) }}</p>
                </a>

                {{-- Net Cash Flow Card --}}
                @php $netPositive = $financials['netFlow'] >= 0; @endphp
                <div
                    class="glass bg-white dark:bg-darkbg/40 p-3 md:p-5 rounded-xl md:rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all group overflow-hidden relative">
                    <div
                        class="absolute top-0 right-0 w-14 h-14 {{ $netPositive ? 'bg-blue-500/5' : 'bg-amber-500/5' }} rounded-full -mr-4 -mt-4 blur-xl">
                    </div>
                    <div class="flex items-center justify-between mb-2 md:mb-3 relative z-10">
                        <div
                            class="w-7 h-7 md:w-9 md:h-9 {{ $netPositive ? 'bg-blue-500/10 text-blue-500' : 'bg-amber-500/10 text-amber-500' }} rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <span
                            class="text-[9px] md:text-[10px] font-black px-1.5 py-0.5 rounded-full {{ $netPositive ? 'text-blue-600 bg-blue-500/10' : 'text-amber-600 bg-amber-500/10' }}">
                            {{ $netPositive ? 'SURPLUS' : 'DEFICIT' }}
                        </span>
                    </div>
                    <h3
                        class="text-slate-400 dark:text-slate-500 text-[8px] md:text-[10px] font-black uppercase tracking-widest mb-0.5 md:mb-1">
                        Net Flow</h3>
                    <p class="text-sm md:text-2xl font-black {{ $netPositive ? 'text-blue-600 dark:text-blue-400' : 'text-amber-600 dark:text-amber-400' }}"
                        style="font-variant-numeric:tabular-nums">
                        {{ $netPositive ? '+' : '-' }}₹{{ number_format(abs($financials['netFlow']), 2) }}
                    </p>
                </div>
            </div>

            {{-- Trend Chart + Categories + Recent --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-5">
                {{-- Income vs Expense Trend Chart --}}
                <div
                    class="lg:col-span-2 glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-xs font-black text-slate-800 dark:text-white">Income vs Expenses</h4>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5">Last 6 months trend</p>
                        </div>
                        <div class="flex items-center gap-3 text-[10px] font-bold">
                            <span class="flex items-center gap-1"><span class="w-5 h-[3px] bg-emerald-500 rounded-full"></span>
                                <span class="text-slate-400">Income</span></span>
                            <span class="flex items-center gap-1"><span class="w-5 h-[3px] bg-rose-400 rounded-full"></span>
                                <span class="text-slate-400">Expense</span></span>
                        </div>
                    </div>
                    <div style="height:200px"><canvas id="financialTrendChart"></canvas></div>
                </div>

                {{-- Right Column: Categories + Recent --}}
                <div class="space-y-4 md:space-y-5">
                    {{-- Top Categories --}}
                    <div
                        class="glass bg-white dark:bg-darkbg/40 p-4 md:p-5 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Top Categories</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[9px] font-bold text-emerald-500 uppercase tracking-widest mb-2">Income</p>
                                @forelse($financials['topIncomeCategories'] as $cat)
                                    <div class="mb-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <span
                                                class="text-[10px] font-semibold text-slate-600 dark:text-slate-300 truncate max-w-[80px]">{{ $cat->category }}</span>
                                            <span class="text-[10px] font-bold text-slate-800 dark:text-white"
                                                style="font-variant-numeric:tabular-nums">₹{{ number_format($cat->total) }}</span>
                                        </div>
                                        <div class="w-full h-1 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full"
                                                style="width:{{ $financials['thisMonthIncome'] > 0 ? ($cat->total / $financials['thisMonthIncome']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-slate-400">No data</p>
                                @endforelse
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-rose-400 uppercase tracking-widest mb-2">Expense</p>
                                @forelse($financials['topExpenseCategories'] as $cat)
                                    <div class="mb-2">
                                        <div class="flex items-center justify-between mb-1">
                                            <span
                                                class="text-[10px] font-semibold text-slate-600 dark:text-slate-300 truncate max-w-[80px]">{{ $cat->category }}</span>
                                            <span class="text-[10px] font-bold text-slate-800 dark:text-white"
                                                style="font-variant-numeric:tabular-nums">₹{{ number_format($cat->total) }}</span>
                                        </div>
                                        <div class="w-full h-1 bg-slate-100 dark:bg-white/5 rounded-full overflow-hidden">
                                            <div class="h-full bg-rose-400 rounded-full"
                                                style="width:{{ $financials['thisMonthExpense'] > 0 ? ($cat->total / $financials['thisMonthExpense']) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-[10px] text-slate-400">No data</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Recent Financial Activity --}}
                    <div
                        class="glass bg-white dark:bg-darkbg/40 p-4 md:p-5 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Recent Activity</h4>
                            <span class="flex h-2 w-2 relative"><span
                                    class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span><span
                                    class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span></span>
                        </div>
                        <div class="space-y-2">
                            @forelse($financials['recentFinancials'] as $entry)
                                <div
                                    class="flex items-center justify-between py-1.5 {{ !$loop->last ? 'border-b border-slate-50 dark:border-white/3' : '' }}">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div
                                            class="w-5 h-5 rounded-md flex items-center justify-center flex-shrink-0 {{ $entry['type'] === 'income' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="{{ $entry['type'] === 'income' ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p
                                                class="text-[11px] font-semibold text-slate-700 dark:text-slate-200 truncate max-w-[120px]">
                                                {{ $entry['title'] }}
                                            </p>
                                            <p class="text-[9px] text-slate-400 font-medium">{{ $entry['date']->format('d M') }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-[11px] font-bold flex-shrink-0 {{ $entry['type'] === 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' }}"
                                        style="font-variant-numeric:tabular-nums">
                                        {{ $entry['type'] === 'income' ? '+' : '-' }}₹{{ number_format($entry['amount'], 2) }}
                                    </span>
                                </div>
                            @empty
                                <p class="text-[10px] text-slate-400 text-center py-4">No financial activity yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart.js for Financial Trend --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const isDark = document.documentElement.classList.contains('dark');
                const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
                const trendCtx = document.getElementById('financialTrendChart');
                if (!trendCtx) return;

                const trendData = @json($financials['monthlyTrend']);
                const incomeGradient = trendCtx.getContext('2d').createLinearGradient(0, 0, 0, 200);
                incomeGradient.addColorStop(0, 'rgba(5,150,105,0.12)');
                incomeGradient.addColorStop(1, 'rgba(5,150,105,0.01)');
                const expenseGradient = trendCtx.getContext('2d').createLinearGradient(0, 0, 0, 200);
                expenseGradient.addColorStop(0, 'rgba(244,63,94,0.12)');
                expenseGradient.addColorStop(1, 'rgba(244,63,94,0.01)');

                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: trendData.map(d => d.label),
                        datasets: [
                            {
                                label: 'Income',
                                data: trendData.map(d => d.income),
                                borderColor: '#059669', backgroundColor: incomeGradient,
                                borderWidth: 2.5, fill: true, tension: 0.4,
                                pointBackgroundColor: '#059669', pointBorderColor: isDark ? '#1a1f2e' : '#fff',
                                pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
                            },
                            {
                                label: 'Expenses',
                                data: trendData.map(d => d.expense),
                                borderColor: '#f43f5e', backgroundColor: expenseGradient,
                                borderWidth: 2.5, fill: true, tension: 0.4,
                                pointBackgroundColor: '#f43f5e', pointBorderColor: isDark ? '#1a1f2e' : '#fff',
                                pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6,
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? '#1e293b' : '#fff',
                                titleColor: isDark ? '#e2e8f0' : '#1e293b',
                                bodyColor: isDark ? '#94a3b8' : '#64748b',
                                borderColor: isDark ? 'rgba(255,255,255,0.08)' : '#e5e7eb',
                                borderWidth: 1, cornerRadius: 10, padding: 10,
                                titleFont: { weight: 700, size: 12 }, bodyFont: { weight: 500, size: 12 },
                                callbacks: {
                                    label: ctx => ctx.dataset.label + ': ₹' + ctx.parsed.y.toLocaleString('en-IN', { minimumFractionDigits: 2 })
                                }
                            }
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11, weight: 600 }, color: isDark ? '#64748b' : '#94a3b8' } },
                            y: { grid: { color: gridColor }, border: { display: false }, ticks: { font: { size: 10 }, color: isDark ? '#64748b' : '#94a3b8', callback: v => '₹' + (v >= 1000 ? (v / 1000).toFixed(0) + 'k' : v) } }
                        }
                    }
                });
            });
        </script>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @if($canViewDownline)
            <!-- Hierarchy Tree Preview -->
            <div id="down-tree-card"
                class="col-span-1 lg:col-span-2 glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden flex flex-col transition-all h-[60vh] relative z-20">
                <div
                    class="p-8 border-b border-slate-200/5 flex items-center justify-between bg-white dark:bg-white/5 dark:backdrop-blur-sm">
                    <div class="flex items-center space-x-2 md:space-x-4">
                        <h3 class="font-black text-xs md:text-xl text-slate-800 dark:text-white tracking-tight">Down Tree</h3>
                        <!-- Search Input (Hidden by default, shown in fullscreen or via better layout) -->
                        <div id="tree-search-container"
                            class="hidden md:flex items-center bg-slate-100 dark:bg-slate-800 rounded-xl px-3 py-1.5 border border-slate-200/50 dark:border-white/5 focus-within:ring-2 focus-within:ring-accent/20 transition-all">
                            <svg class="w-3.5 h-3.5 text-slate-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" id="tree-search-input" placeholder="Search downline..."
                                class="bg-transparent border-none p-0 text-xs text-slate-700 dark:text-slate-200 focus:ring-0 placeholder:text-slate-500 w-32 md:w-48">
                        </div>
                        <!-- Zoom Controls -->
                        <div
                            class="hidden md:flex items-center bg-slate-100 dark:bg-slate-800 rounded-lg p-1 border border-slate-200/50 dark:border-white/5">
                            <button onclick="adjustZoom(-0.1)"
                                class="p-1.5 hover:bg-white dark:hover:bg-slate-700 rounded-md transition-all text-slate-500 hover:text-accent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                </svg>
                            </button>
                            <span id="zoom-level"
                                class="text-[10px] font-black text-slate-400 px-2 min-w-[40px] text-center">100%</span>
                            <button onclick="adjustZoom(0.1)"
                                class="p-1.5 hover:bg-white dark:hover:bg-slate-700 rounded-md transition-all text-slate-500 hover:text-accent">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- View Switcher -->
                        <div class="flex items-center p-1 bg-slate-100 dark:bg-slate-800 rounded-xl border border-slate-200/50 dark:border-white/5 mr-2">
                            <button onclick="switchTreeView('list')" id="btn-list-view" 
                                class="px-3 py-1.5 text-[10px] font-black rounded-lg transition-all bg-white dark:bg-slate-700 shadow-sm text-slate-800 dark:text-white">
                                List View
                            </button>
                            <button onclick="switchTreeView('chart')" id="btn-chart-view" 
                                class="px-3 py-1.5 text-[10px] font-black rounded-lg transition-all text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                                Visual Chart
                            </button>
                        </div>
                        <button onclick="toggleFullscreenTree()" id="expand-btn"
                            class="text-xs font-bold text-accent dark:text-blue-400 hover:text-white hover:bg-accent px-4 py-2 rounded-xl transition-all border border-accent/20">Expand
                            Viewer</button>
                    </div>
                </div>
                <div class="p-6 flex-1 bg-slate-50/30 dark:bg-transparent overflow-auto relative" id="tree-canvas">
                    <!-- List View Container -->
                    <div id="tree-zoom-container" class="origin-top-left transition-transform duration-200">
                        <div id="tree-root" class="space-y-2">
                            @if($user->isOfficeInCharge() && $user->upline)
                                @include('dashboard.partials.tree_item', ['item' => $user->upline])
                            @else
                                @include('dashboard.partials.tree_item', ['item' => $user])
                            @endif
                        </div>
                    </div>
                    
                    <!-- Visual Chart Container (Hidden by default) -->
                    <div id="org-chart-container" class="hidden absolute inset-0 w-full h-full bg-[#f1f5f9] dark:bg-[#0c1221]/90" style="font-family: 'Outfit', sans-serif;">
                        <div id="org-chart-render" class="w-full h-full"></div>
                        
                        <!-- Floating Toolbar -->
                        <div class="chart-toolbar">
                            <button onclick="orgChart.fit()" class="toolbar-btn" title="Fit to Screen">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                            </button>
                            <button onclick="orgChart.zoomIn()" class="toolbar-btn" title="Zoom In">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                            </button>
                            <button onclick="orgChart.zoomOut()" class="toolbar-btn" title="Zoom Out">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4" /></svg>
                            </button>
                            <div class="w-full h-px bg-slate-200 dark:bg-white/10 my-1"></div>
                            <button onclick="toggleChartLayout()" class="toolbar-btn" title="Toggle Layout">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v14a1 1 0 01-1 1H5a1 1 0 01-1-1V5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 4v16M15 4v16" /></svg>
                            </button>
                            <button onclick="exportChart()" class="toolbar-btn" title="Export Image">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            </button>
                        </div>

                        <!-- Loading Overlay -->
                        <div id="chart-loader" class="absolute inset-0 flex items-center justify-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm z-50 hidden">
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 border-4 border-accent border-t-transparent rounded-full animate-spin mb-3"></div>
                                <p class="text-xs font-black text-slate-500 uppercase tracking-widest">Generating Chart...</p>
                            </div>
                        </div>
                        <!-- Export Button -->
                        <div class="absolute bottom-4 right-4 z-40">
                            <button onclick="exportChart()" class="flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 rounded-lg text-[10px] font-black text-slate-600 dark:text-slate-300 shadow-lg hover:shadow-xl transition-all active:scale-95">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export Image
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Legend -->
                <div
                    class="px-8 py-4 border-t border-slate-200/5 bg-white dark:bg-white/5 dark:backdrop-blur-sm flex items-center space-x-4">
                    <div class="flex items-center space-x-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.3)]"></div>
                        <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Active</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.3)]"></div>
                        <span class="text-[9px] text-slate-500 font-black uppercase tracking-widest">Pending</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Recent Activity & Pending Approvals -->
        <div class="space-y-8">
            <!-- Pending Approvals Quick Action -->
            @include('dashboard.partials.pending_approvals')

            <!-- Recent Activity -->
            <div id="timeline-card"
                class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden transition-all hover:shadow-2xl hover:shadow-accent/5 h-[60vh] flex flex-col relative z-20">
                <div
                    class="p-6 border-b border-slate-200/5 bg-white dark:bg-white/5 dark:backdrop-blur-sm flex items-center justify-between">
                    <h3 class="font-black text-lg text-slate-800 dark:text-white tracking-tight">Timeline</h3>
                    <button onclick="toggleFullscreenTimeline()" id="expand-timeline-btn"
                        class="text-[10px] font-bold text-accent dark:text-blue-400 hover:text-white hover:bg-accent px-3 py-1.5 rounded-xl transition-all border border-accent/20">Expand
                        Viewer</button>
                </div>
                <div class="p-6 flex-1 overflow-y-auto space-y-6 custom-scrollbar" id="live-timeline">
                    @include('dashboard.partials.timeline_items', ['recentActivities' => $recentActivities])
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <style>
        .tree-item .tree-children {
            transition: all 0.3s ease-in-out;
        }

        .rotate-90 {
            transform: rotate(90deg);
        }

        /* Fullscreen Mode Styles */
        #down-tree-card.fullscreen-tree {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 10000 !important;
            margin: 0 !important;
            border-radius: 0 !important;
            background: #F8FAFC !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .dark #down-tree-card.fullscreen-tree {
            background: #0F172A !important;
        }

        #down-tree-card.fullscreen-tree .p-6.flex-1 {
            height: auto !important;
            overflow-y: auto !important;
        }

        #down-tree-card.fullscreen-tree .sticky.bottom-0 {
            position: fixed !important;
            bottom: 2rem !important;
            right: 2rem !important;
            left: auto !important;
            width: auto !important;
            padding: 1rem !important;
            border-radius: 1rem !important;
            border: 1px solid rgba(0, 0, 0, 0.1) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }

        .dark #down-tree-card.fullscreen-tree .sticky.bottom-0 {
            border-color: rgba(255, 255, 255, 0.05) !important;
            background: rgba(30, 41, 59, 0.8) !important;
        }

        /* Fullscreen Timeline Styles */
        #timeline-card.fullscreen-timeline {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            z-index: 10000 !important;
            margin: 0 !important;
            border-radius: 0 !important;
            background: #F8FAFC !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .dark #timeline-card.fullscreen-timeline {
            background: #0F172A !important;
        }

        #timeline-card.fullscreen-timeline .p-6.flex-1 {
            height: auto !important;
            overflow-y: auto !important;
        }

        /* Simple custom scrollbar */
        /* Simple custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar,
        .overflow-auto::-webkit-scrollbar {
            width: 4px;
            height: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb,
        .overflow-auto::-webkit-scrollbar-thumb {
            background: rgba(30, 66, 255, 0.3);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        #tree-zoom-container {
            transform-origin: 0 0;
            will-change: transform;
        }

        /* Highlight search result */
        @keyframes highlightPulse {
            0% {
                ring-color: rgba(60, 80, 224, 0.4);
                background-color: rgba(60, 80, 224, 0.1);
            }

            50% {
                ring-color: rgba(60, 80, 224, 0.8);
                background-color: rgba(60, 80, 224, 0.2);
            }

            100% {
                ring-color: rgba(60, 80, 224, 0.4);
                background-color: rgba(60, 80, 224, 0.1);
            }
        }

        .search-highlight {
            animation: highlightPulse 2s infinite;
            ring-width: 4px;
            border-radius: 12px;
        }

        /* Super Premium Org Chart Custom Styles */
        .premium-node-wrapper {
            width: 100%;
            height: 100%;
            padding: 10px;
        }

        .premium-node-card {
            width: 100%;
            height: 100%;
            border-radius: 24px;
            background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.8));
            backdrop-filter: blur(20px) saturate(200%);
            -webkit-backdrop-filter: blur(20px) saturate(200%);
            border: 2px solid rgba(255, 255, 255, 1);
            box-shadow: 
                0 15px 35px -5px rgba(31, 38, 135, 0.1),
                0 5px 15px rgba(0,0,0,0.05),
                inset 0 1px 0 rgba(255,255,255,1);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            padding: 8px 16px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .dark .premium-node-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.8));
            border-color: rgba(255, 255, 255, 0.15);
            box-shadow: 
                0 20px 50px -10px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255,255,255,0.1);
        }

        .premium-node-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 
                0 30px 60px -15px rgba(60, 80, 224, 0.3),
                inset 0 1px 0 rgba(255,255,255,0.9);
        }
        .dark .premium-node-card:hover {
            box-shadow: 
                0 30px 60px -15px rgba(0, 0, 0, 0.8),
                0 0 40px -10px rgba(60, 80, 224, 0.4),
                inset 0 1px 0 rgba(255,255,255,0.2);
        }

        /* Ambient Glow behind Avatar */
        .premium-avatar-container {
            position: relative;
            width: 60px;
            height: 60px;
            margin-right: 16px;
        }

        .premium-avatar-glow {
            position: absolute;
            inset: -5px;
            border-radius: 50%;
            background: currentColor;
            filter: blur(10px);
            opacity: 0.5;
            z-index: 0;
            animation: slow-pulse 3s ease-in-out infinite alternate;
        }

        @keyframes slow-pulse {
            0% { transform: scale(0.9); opacity: 0.4; }
            100% { transform: scale(1.1); opacity: 0.7; }
        }

        .premium-avatar {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.8);
            position: relative;
            z-index: 10;
            overflow: hidden;
            background: #f1f5f9;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .dark .premium-avatar {
            border-color: rgba(255,255,255,0.2);
            background: #0f172a;
            box-shadow: 0 4px 15px rgba(0,0,0,0.4);
        }

        .premium-status-dot {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid #fff;
            z-index: 20;
            box-shadow: 0 0 10px currentColor;
        }
        .dark .premium-status-dot {
            border-color: #1e293b;
        }

        .premium-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .premium-name {
            font-size: 15px;
            font-weight: 900;
            letter-spacing: -0.02em;
            margin-bottom: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            background: linear-gradient(90deg, #1e293b, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .dark .premium-name {
            background: linear-gradient(90deg, #f8fafc, #cbd5e1);
            -webkit-background-clip: text;
        }

        .premium-role {
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: currentColor;
            opacity: 0.9;
            margin-bottom: 6px;
        }

        .premium-badges {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .premium-badge {
            background: rgba(60, 80, 224, 0.1);
            color: #3C50E0;
            font-size: 9px;
            font-weight: 800;
            padding: 3px 8px;
            border-radius: 20px;
            border: 1px solid rgba(60, 80, 224, 0.2);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .dark .premium-badge {
            background: rgba(96, 165, 250, 0.1);
            color: #60a5fa;
            border-color: rgba(96, 165, 250, 0.2);
        }

        /* Glass shine effect overlay */
        .premium-shine {
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-20deg);
            animation: shine-sweep 6s infinite;
            pointer-events: none;
            z-index: 5;
        }
        .dark .premium-shine {
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0) 100%);
        }

        @keyframes shine-sweep {
            0% { left: -100%; opacity: 0; }
            20% { opacity: 1; }
            40% { left: 200%; opacity: 0; }
            100% { left: 200%; opacity: 0; }
        }

        .status-pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            position: relative;
        }

        .status-pulse::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: currentColor;
            opacity: 0.4;
            animation: pulse-ring 2s infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.5); opacity: 0.5; }
            100% { transform: scale(2.5); opacity: 0; }
        }

        /* Chart Toolbar Style */
        .chart-toolbar {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 50;
            display: flex;
            flex-direction: column;
            gap: 8px;
            pointer-events: auto;
        }

        .toolbar-btn {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: white;
            border: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .dark .toolbar-btn {
            background: #1e293b;
            border-color: rgba(255,255,255,0.05);
            color: #94a3b8;
        }

        .toolbar-btn:hover {
            color: #3C50E0;
            transform: scale(1.1);
            box-shadow: 0 8px 20px rgba(60, 80, 224, 0.15);
        }

        .node-card:hover .node-action-hint {
            opacity: 1 !important;
            transform: translateX(-5px);
        }

        /* Connection Lines */
        .link {
            transition: all 0.5s ease;
            stroke-linecap: round;
        }
    </style>
    <!-- Font Load -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- D3 and Org Chart Libraries -->
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.1.2/build/d3-flextree.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/d3-org-chart@3.1.0"></script>
@endsection

@section('js')
    <script>
        window.APP_URL = "{{ url('/') }}";

        async function toggleTreeItem(event, itemId) {
            event.stopPropagation();
            const children = document.getElementById(`children-${itemId}`);
            const icon = document.getElementById(`toggle-icon-${itemId}`);

            if (children) {
                if (children.classList.contains('hidden')) {
                    // Load children via AJAX if not already loaded
                    if (children.dataset.loaded === 'false') {
                        const shimmer = children.querySelector('.tree-loading-shimmer');
                        if (shimmer) shimmer.classList.remove('hidden');
                        children.classList.remove('hidden');

                        try {
                            const response = await fetch(`${window.APP_URL}/hierarchy-children/${itemId}`);
                            if (!response.ok) {
                                throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                            }
                            const data = await response.json();

                            // Remove shimmer and add content
                            if (shimmer) shimmer.remove();
                            children.innerHTML = data.html;
                            children.dataset.loaded = 'true';
                        } catch (error) {
                            console.error('Error loading children:', error);
                            if (shimmer) shimmer.innerText = `Error: ${error.message}`;
                            return;
                        }
                    } else {
                        children.classList.remove('hidden');
                    }
                    if (icon) icon.classList.add('rotate-90');
                } else {
                    children.classList.add('hidden');
                    if (icon) icon.classList.remove('rotate-90');
                }
            }
        }

        let orgChart = null;
        let chartInitialized = false;

        async function switchTreeView(viewType) {
            const listBtn = document.getElementById('btn-list-view');
            const chartBtn = document.getElementById('btn-chart-view');
            const listContainer = document.getElementById('tree-zoom-container');
            const chartContainer = document.getElementById('org-chart-container');
            const zoomControls = document.querySelector('.hidden.md\\:flex.items-center.bg-slate-100'); // Zoom controls for list

            if (viewType === 'chart') {
                listContainer.classList.add('hidden');
                chartContainer.classList.remove('hidden');
                if (zoomControls) zoomControls.classList.add('hidden');

                listBtn.classList.remove('bg-white', 'dark:bg-slate-700', 'shadow-sm', 'text-slate-800', 'dark:text-white');
                listBtn.classList.add('text-slate-500', 'dark:text-slate-400');
                chartBtn.classList.add('bg-white', 'dark:bg-slate-700', 'shadow-sm', 'text-slate-800', 'dark:text-white');
                chartBtn.classList.remove('text-slate-500', 'dark:text-slate-400');

                if (!chartInitialized) {
                    await initOrgChart();
                }
            } else {
                listContainer.classList.remove('hidden');
                chartContainer.classList.add('hidden');
                if (zoomControls) zoomControls.classList.remove('hidden');

                chartBtn.classList.remove('bg-white', 'dark:bg-slate-700', 'shadow-sm', 'text-slate-800', 'dark:text-white');
                chartBtn.classList.add('text-slate-500', 'dark:text-slate-400');
                listBtn.classList.add('bg-white', 'dark:bg-slate-700', 'shadow-sm', 'text-slate-800', 'dark:text-white');
                listBtn.classList.remove('text-slate-500', 'dark:text-slate-400');
            }
        }

        async function initOrgChart() {
            const loader = document.getElementById('chart-loader');
            loader.classList.remove('hidden');

            try {
                const response = await fetch(`${window.APP_URL}/hierarchy-tree`);
                const data = await response.json();
                renderOrgChart(data);
                chartInitialized = true;
            } catch (error) {
                console.error('Error loading org chart data:', error);
                alert('Failed to load hierarchy data for the visual chart.');
            } finally {
                loader.classList.add('hidden');
            }
        }

        function renderOrgChart(data) {
            const isDark = () => document.documentElement.classList.contains('dark');
            
            orgChart = new d3.OrgChart()
                .container('#org-chart-render')
                .data(flattenTree(data))
                .nodeWidth(n => 280)
                .nodeHeight(n => 160)
                .childrenMargin(n => 100)
                .compactMarginBetween(n => 50)
                .compactMarginPair(n => 40)
                .neighbourMargin(n => 60)
                .onNodeClick(d => {
                    // Navigate to user profile on node click
                    window.location.href = `${window.APP_URL}/users/${d}`;
                })
                .linkUpdate(function(d, i, arr) {
                    d3.select(this)
                        .attr('stroke', isDark() ? 'rgba(255,255,255,0.1)' : 'rgba(30, 66, 255, 0.1)')
                        .attr('stroke-width', 2);
                })
                .nodeContent(function(d, i, arr, state) {
                    const statusColor = d.data.status === 'active' ? '#10b981' : '#f59e0b';
                    const profilePic = d.data.profile_picture ? `${window.APP_URL}/storage-render/${d.data.profile_picture}` : '';
                    const initials = d.data.name.charAt(0);
                    const dark = isDark();
                    const textColor = dark ? '#f8fafc' : '#1e293b';
                    const subTextColor = dark ? '#94a3b8' : '#64748b';
                    const cardBg = dark ? 'rgba(30, 41, 59, 0.8)' : 'rgba(255, 255, 255, 0.8)';

                    return `
                        <div class="premium-node-wrapper">
                            <div class="premium-node-card">
                                <div class="premium-shine"></div>
                                
                                <div class="premium-avatar-container" style="color: ${statusColor}">
                                    <div class="premium-avatar-glow"></div>
                                    <div class="premium-avatar">
                                        ${profilePic ? `<img src="${profilePic}" style="width:100%; height:100%; object-fit:cover;"/>` : 
                                          `<span style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-size:22px; font-weight:900; color:#3C50E0;">${initials}</span>`}
                                    </div>
                                    <div class="premium-status-dot" style="background: ${statusColor}; color: ${statusColor};"></div>
                                </div>

                                <div class="premium-content">
                                    <div class="premium-name">${d.data.name}</div>
                                    <div class="premium-role" style="color: ${statusColor}">${d.data.designation}</div>
                                    <div class="premium-badges">
                                        <div class="premium-badge" title="Employee ID">
                                            <svg style="width:10px; height:10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                                            ${d.data.employee_id}
                                        </div>
                                        <div class="premium-badge" title="Total Team Size">
                                            <svg style="width:10px; height:10px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                            ${d.data.total_downline || 0}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                })
                .render();

            // Initial focus on current user
            const currentUserId = "{{ $user->id }}";
            setTimeout(() => {
                orgChart.setCentered(currentUserId).render();
            }, 500);
        }

        function toggleChartLayout() {
            if (!orgChart) return;
            const layouts = ['top', 'left', 'right', 'bottom'];
            const current = orgChart.state().layout;
            const next = layouts[(layouts.indexOf(current) + 1) % layouts.length];
            orgChart.layout(next).render().fit();
        }

        function flattenTree(node, parentId = null) {
            let list = [];
            const { children, ...rest } = node;
            list.push({ ...rest, parentId });
            if (children) {
                children.forEach(child => {
                    list = list.concat(flattenTree(child, node.id));
                });
            }
            return list;
        }

        function exportChart() {
            if (orgChart) {
                orgChart.exportImg({
                    full: true,
                    copyStyles: true,
                    onSuccess: () => {
                        // Optional notification
                    }
                });
            }
        }

        function toggleFullscreenTree() {
            const card = document.getElementById('down-tree-card');
            const btn = document.getElementById('expand-btn');
            const isFullscreen = card.classList.toggle('fullscreen-tree');

            if (isFullscreen) {
                btn.innerText = 'Exit Fullscreen';
                document.body.style.overflow = 'hidden'; 
                if (orgChart) setTimeout(() => orgChart.fit(), 300);
            } else {
                btn.innerText = 'Expand Viewer';
                document.body.style.overflow = '';
                if (orgChart) setTimeout(() => orgChart.fit(), 300);
            }
        }

        function toggleFullscreenTimeline() {
            const card = document.getElementById('timeline-card');
            const btn = document.getElementById('expand-timeline-btn');
            const isFullscreen = card.classList.toggle('fullscreen-timeline');

            if (isFullscreen) {
                btn.innerText = 'Exit Fullscreen';
                document.body.style.overflow = 'hidden';
            } else {
                btn.innerText = 'Expand Viewer';
                document.body.style.overflow = '';
            }
        }

        // Zoom Logic
        let currentZoom = 1;
        const minZoom = 0.5;
        const maxZoom = 2;
        const zoomContainer = document.getElementById('tree-zoom-container');
        const zoomLevelDisplay = document.getElementById('zoom-level');
        const canvas = document.getElementById('tree-canvas');

        function updateZoom() {
            zoomContainer.style.transform = `scale(${currentZoom})`;
            if (zoomLevelDisplay) {
                zoomLevelDisplay.innerText = `${Math.round(currentZoom * 100)}%`;
            }
        }

        function adjustZoom(delta) {
            currentZoom = Math.min(Math.max(currentZoom + delta, minZoom), maxZoom);
            updateZoom();
        }

        // Wheel Zoom (Ctrl + Scroll)
        canvas.addEventListener('wheel', (e) => {
            if (e.ctrlKey) {
                e.preventDefault();
                const delta = e.deltaY < 0 ? 0.1 : -0.1;
                adjustZoom(delta);
            }
        }, { passive: false });

        // Pinch to Zoom
        let initialDist = -1;
        canvas.addEventListener('touchstart', (e) => {
            if (e.touches.length === 2) {
                initialDist = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
            }
        });

        canvas.addEventListener('touchmove', (e) => {
            if (e.touches.length === 2 && initialDist > 0) {
                e.preventDefault();
                const currentDist = Math.hypot(
                    e.touches[0].pageX - e.touches[1].pageX,
                    e.touches[0].pageY - e.touches[1].pageY
                );
                const delta = (currentDist - initialDist) / 1000;
                adjustZoom(delta);
                initialDist = currentDist;
            }
        }, { passive: false });

        canvas.addEventListener('touchend', () => {
            initialDist = -1;
        });

        // Search Logic
        const searchInput = document.getElementById('tree-search-input');

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            if (query.length < 2) return;

            // Find all tree items
            const items = document.querySelectorAll('.tree-item');
            let found = false;

            items.forEach(item => {
                const nameElement = item.querySelector('span.text-xs.font-black');
                const idElement = item.querySelector('span.text-\\[9px\\]');

                const name = nameElement ? nameElement.innerText.toLowerCase() : '';
                const id = idElement ? idElement.innerText.toLowerCase() : '';

                if (name.includes(query) || id.includes(query)) {
                    if (!found) { // Scroll to the first match
                        found = true;
                        revealAndFocus(item);
                    }
                }
            });
        });

        function revealAndFocus(item) {
            // 1. Expand all parents
            let parent = item.parentElement;
            while (parent && parent.id !== 'tree-root') {
                if (parent.classList.contains('tree-children')) {
                    parent.classList.remove('hidden');
                    // Also rotate the parent icon
                    const parentId = parent.id.replace('children-', '');
                    const parentIcon = document.getElementById(`toggle-icon-${parentId}`);
                    if (parentIcon) parentIcon.classList.add('rotate-90');
                }
                parent = parent.parentElement;
            }

            // 2. Set Zoom to 100%
            currentZoom = 1;
            updateZoom();

            // 3. Scroll Into View
            item.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });

            // 4. Highlight Effect
            const header = item.querySelector('div.flex.items-center.group');
            header.classList.add('search-highlight', 'ring-2', 'ring-accent');

            setTimeout(() => {
                header.classList.remove('search-highlight', 'ring-2', 'ring-accent');
            }, 5000);
        }

        // Auto-expand root
        document.addEventListener('DOMContentLoaded', () => {
            const rootId = "{{ ($user->isOfficeInCharge() && $user->upline) ? $user->upline->id : $user->id }}";
            const rootChildren = document.getElementById(`children-${rootId}`);
            if (rootChildren) {
                // Manually trigger toggle for first load to fetch children
                const pseudoEvent = { stopPropagation: () => { } };
                toggleTreeItem(pseudoEvent, rootId);
            }

            // Set default zoom to 100% for all devices
            currentZoom = 1.0;
            updateZoom();
        });

    </script>
@endsection