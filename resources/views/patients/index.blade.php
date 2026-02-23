@extends('layouts.app')

@section('title', 'Patients')
@section('header_title', 'Patient Management')

@section('content')
    <div class="space-y-8">
        <!-- Dashboard Stats Section -->
        <div class="grid grid-cols-3 gap-2 md:gap-6 mb-8">
            <div class="glass bg-white dark:bg-darkbg/40 p-2 md:p-6 rounded-2xl md:rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all flex flex-col items-center text-center">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-accent/10 text-accent rounded-xl md:rounded-2xl flex items-center justify-center mb-1.5 md:mb-4">
                    <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h3 class="text-lg md:text-3xl font-black text-slate-800 dark:text-white leading-none">{{ $patients->total() }}</h3>
                <p class="text-[7px] md:text-[10px] text-slate-500 font-bold uppercase tracking-tighter md:tracking-widest mt-1.5">Total Registry</p>
            </div>

            <div class="glass bg-white dark:bg-darkbg/40 p-2 md:p-6 rounded-2xl md:rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all flex flex-col items-center text-center">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-amber-500/10 text-amber-500 rounded-xl md:rounded-2xl flex items-center justify-center mb-1.5 md:mb-4">
                    <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg md:text-3xl font-black text-slate-800 dark:text-white leading-none">
                    {{ $patients->filter(fn($p) => $p->health_issues && !in_array($p->health_issues, ['Normal', 'None']))->count() }}
                </h3>
                <p class="text-[7px] md:text-[10px] text-slate-500 font-bold uppercase tracking-tighter md:tracking-widest mt-1.5">High Risks</p>
            </div>

            <div class="glass bg-white dark:bg-darkbg/40 p-2 md:p-6 rounded-2xl md:rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-sm hover:shadow-lg transition-all flex flex-col items-center text-center">
                <div class="w-8 h-8 md:w-12 md:h-12 bg-emerald-500/10 text-emerald-500 rounded-xl md:rounded-2xl flex items-center justify-center mb-1.5 md:mb-4">
                    <svg class="w-4 h-4 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg md:text-3xl font-black text-slate-800 dark:text-white leading-none">
                    {{ $patients->where('created_at', '>=', now()->subDays(7))->count() }}
                </h3>
                <p class="text-[7px] md:text-[10px] text-slate-500 font-bold uppercase tracking-tighter md:tracking-widest mt-1.5">Weekly Gain</p>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
            <div>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">Active Patients Registry</h3>
                <div class="flex items-center space-x-2">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest mt-1 italic">Verified Field Submissions</p>
                    <span class="text-slate-300 dark:text-slate-700 font-black">|</span>
                    <a href="{{ route('patients.bin') }}" class="text-[10px] text-rose-500 font-black uppercase tracking-widest mt-1 hover:underline">View Deleted Records</a>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                @if(request('view_all'))
                    <a href="{{ route('patients.index', request()->except('view_all')) }}"
                        class="px-2 sm:px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="hidden lg:inline uppercase tracking-widest">Paginate</span>
                    </a>
                @else
                    <a href="{{ route('patients.index', array_merge(request()->all(), ['view_all' => 1])) }}"
                        class="px-2 sm:px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span class="hidden lg:inline uppercase tracking-widest">View All</span>
                    </a>
                @endif
                <a href="{{ route('patients.export', request()->all()) }}"
                    class="px-2 sm:px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-all flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="hidden lg:inline uppercase tracking-widest">Download CSV</span>
                </a>
                <button type="button" onclick="toggleFilters()"
                    class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    <span>Filter</span>
                </button>
            </div>
        </div>

        <div id="filter-panel" class="{{ request()->anyFilled(['search', 'gender', 'health_issue', 'date_from', 'date_to', 'collector_id']) ? '' : 'hidden' }} p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 bg-white dark:bg-darkbg/40 shadow-sm mb-8 transition-all">
            <form action="{{ route('patients.index') }}" method="GET" class="no-loader space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search Records</label>
                        <div class="relative">
                            <input type="text" name="search" id="search-input" value="{{ request('search') }}" 
                                placeholder="Patient Name, Phone, or Collector ID..."
                                class="w-full h-10 pl-10 pr-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Collected By -->
                    <div class="relative group/collector">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Collected By</label>
                        <input type="hidden" name="collector_id" id="collector_id_hidden" value="{{ request('collector_id') }}">
                        <div class="relative">
                            <input type="text" id="collector_search" placeholder="Search Collector..." 
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition truncate pr-8 dark:text-white"
                                autocomplete="off"
                                onfocus="showCollectorList()"
                                oninput="filterCollectors()"
                                onblur="setTimeout(hideCollectorList, 200)"
                                value="{{ $collectors->firstWhere('id', request('collector_id'))->profile->full_name ?? ($collectors->firstWhere('id', request('collector_id'))->employee_id ?? '') }}"
                            >
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>

                        <!-- Dropdown List -->
                        <div id="collector_list" class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl max-h-[40vh] overflow-y-auto hidden">
                            <div class="p-2 space-y-1">
                                <div class="collector-option px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer text-xs font-bold text-slate-600 dark:text-slate-300 transition-colors"
                                     onclick="selectCollector('', 'All Collectors')">
                                    All Collectors
                                </div>
                                @foreach($collectors as $collector)
                                    <div class="collector-option px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer text-xs font-bold text-slate-600 dark:text-slate-300 transition-colors"
                                         data-name="{{ strtolower($collector->profile->full_name ?? '') }} {{ strtolower($collector->employee_id) }}"
                                         onclick="selectCollector('{{ $collector->id }}', '{{ $collector->profile->full_name ?? $collector->employee_id }} ({{ $collector->employee_id }})')">
                                        {{ $collector->profile->full_name ?? $collector->employee_id }} <span class="text-slate-400 font-medium ml-1">#{{ $collector->employee_id }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Gender</label>
                        <select name="gender" class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Health Category -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Health Category</label>
                        <select name="health_issue" class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Issues</option>
                            <option value="Gas" {{ request('health_issue') == 'Gas' ? 'selected' : '' }}>Gas</option>
                            <option value="Sugar" {{ request('health_issue') == 'Sugar' ? 'selected' : '' }}>Sugar</option>
                            <option value="Pressure" {{ request('health_issue') == 'Pressure' ? 'selected' : '' }}>Pressure</option>
                            <option value="Thyroid" {{ request('health_issue') == 'Thyroid' ? 'selected' : '' }}>Thyroid</option>
                            <option value="Uric Acid" {{ request('health_issue') == 'Uric Acid' ? 'selected' : '' }}>Uric Acid</option>
                            <option value="Skin/Hair" {{ request('health_issue') == 'Skin/Hair' ? 'selected' : '' }}>Skin/Hair</option>
                            <option value="Heart" {{ request('health_issue') == 'Heart' ? 'selected' : '' }}>Heart</option>
                            <option value="Eye" {{ request('health_issue') == 'Eye' ? 'selected' : '' }}>Eye</option>
                            <option value="ENT" {{ request('health_issue') == 'ENT' ? 'selected' : '' }}>ENT</option>
                            <option value="Dental" {{ request('health_issue') == 'Dental' ? 'selected' : '' }}>Dental</option>
                            <option value="Normal" {{ request('health_issue') == 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Other" {{ request('health_issue') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Date From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition text-slate-600 dark:text-slate-300">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Date To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition text-slate-600 dark:text-slate-300">
                    </div>

                    <!-- Buttons -->
                    <div class="lg:col-span-3 flex items-end justify-end space-x-2 pt-2">
                        <button type="submit"
                            class="h-10 px-6 bg-accent text-white rounded-xl text-sm font-bold hover:opacity-90 transition shadow-lg shadow-accent/10">Apply Filters</button>
                        @if(request()->anyFilled(['search', 'gender', 'health_issue', 'date_from', 'date_to', 'collector_id']))
                            <a href="{{ route('patients.index') }}"
                                class="h-10 px-6 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold flex items-center justify-center hover:opacity-90 transition">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-6 py-4 rounded-2xl font-bold text-sm">
                {{ session('success') }}
            </div>
        @endif

<div id="patients-container">
        @if($patients->isEmpty())
            <div class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl p-20 text-center">
                <div class="w-24 h-24 bg-accent/10 text-accent rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white mb-3">No Patient Data Yet</h4>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-10 font-medium leading-relaxed">
                    Start collecting field data by creating your first patient record. All submissions will appear here for management review.
                </p>
                <a href="{{ route('membership.index') }}" class="inline-block text-accent font-black uppercase tracking-[0.2em] text-[10px] hover:underline">Register via Membership &rarr;</a>
            </div>
        @else
            <div class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Participant</th>
                                @if(auth()->user()->designation !== 'staff')
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Membership</th>
                                @endif
                                @if(auth()->user()->designation === 'staff')
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Dispense</th>
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Dashboard</th>
                                @else
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Dashboard</th>
                                @endif
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Health Status</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Contact Info</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Reported By</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @foreach($patients as $patient)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                    <td class="p-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-accent/10 text-accent dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black">
                                                {{ substr($patient->full_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="font-black text-slate-800 dark:text-white text-sm">{{ $patient->full_name }}</h4>
                                                <div class="flex items-center space-x-2 text-[10px] text-slate-500 font-bold uppercase tracking-tight mt-0.5">
                                                    <span>{{ $patient->patient_id }}</span>
                                                    <span class="text-slate-300 dark:text-slate-700 font-black">•</span>
                                                    <span>{{ ucfirst($patient->gender) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @if(auth()->user()->designation !== 'staff')
                                        <td class="p-6">
                                            @if($patient->is_member)
                                                <span class="inline-flex items-center px-4 py-2 bg-emerald-500/10 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/10">
                                                    Member
                                                </span>
                                            @elseif(auth()->user()->designation !== 'staff')
                                                <a href="{{ route('patients.membership', $patient->id) }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-amber-500/10 text-amber-500 rounded-xl hover:bg-amber-500 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-amber-500/10 shadow-sm">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    <span>Become Member</span>
                                                </a>
                                            @else
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Non-Member</span>
                                            @endif
                                        </td>
                                    @endif
                                    @if(auth()->user()->designation === 'staff')
                                    <td class="p-6">
                                        <a href="{{ route('medicine.distribute', $patient->id) }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-emerald-600/10 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-emerald-600/10">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                            </svg>
                                            <span>Dispense</span>
                                        </a>
                                    </td>
                                    @endif
                                    <td class="p-6">
                                        <a href="{{ route('patients.show', $patient->id) }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-indigo-600/10 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-indigo-600/10">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <span>Overview</span>
                                        </a>
                                    </td>
                                    <td class="p-6">
                                        @if($patient->health_issues)
                                            <div class="inline-flex items-center space-x-2 px-3 py-1.5 bg-amber-500/10 text-amber-500 rounded-lg border border-amber-500/10">
                                                <span class="relative flex h-2 w-2">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                </span>
                                                <span class="text-[10px] font-black uppercase tracking-wider">{{ Str::limit($patient->health_issues, 20) }}</span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-6">
                                        <div class="space-y-1">
                                            <div class="flex items-center space-x-2 text-slate-600 dark:text-slate-300">
                                                <i class="fas fa-phone text-[10px] w-4 text-center text-slate-400"></i>
                                                <span class="text-xs font-bold">{{ $patient->phone_number }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg overflow-hidden ring-2 ring-slate-100 dark:ring-slate-800">
                                                @if($patient->creator && $patient->creator->profile && $patient->creator->profile->profile_picture)
                                                    <img src="{{ $patient->creator->profile->getProfilePictureUrl() }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-[10px] font-black">
                                                        {{ substr($patient->creator->profile->full_name ?? ($patient->creator->employee_id ?? 'U'), 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                @if(auth()->user()->isSuperAdmin() && $patient->creator)
                                                    <a href="{{ route('users.show', $patient->creator->id) }}" class="text-xs font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                                                        <span>{{ $patient->creator->profile->full_name ?? ($patient->creator->employee_id ?? 'Unknown User') }}</span>
                                                        <svg class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $patient->creator->profile->full_name ?? ($patient->creator->employee_id ?? 'Unknown User') }}</p>
                                                @endif
                                                <p class="text-[10px] font-medium text-slate-400">{{ $patient->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6 text-right flex justify-end space-x-2">
                                        <a href="{{ route('pathology.create', $patient->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 transition-all border border-rose-500/10 shadow-sm" title="Record Pathology">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.675.337a4 4 0 01-2.5.467l-3.21.321a2 2 0 00-1.554 1.554l-.321 3.21a2 2 0 001.554 1.554l3.21-.321a2 2 0 001.554-1.554l.321-3.21a2 2 0 00-1.554-1.554z" />
                                            </svg>
                                        </a>
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->designation == 'office_in_charge')
                                            <a href="{{ route('medicine.distribute', $patient->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500 hover:text-white dark:bg-blue-500/10 dark:hover:bg-blue-500 text-blue-500 transition-all border border-blue-500/10 shadow-sm" title="Dispense Medicine">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                            </a>
                                         @endif
                                     </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($patients instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="p-6 border-t border-slate-100 italic">
                    {{ $patients->links() }}
                </div>
            @endif
        @endif
        </div>
        <script>
            function toggleFilters() {
                const filters = document.getElementById('filter-panel');
                filters.classList.toggle('hidden');
            }

            // Searchable Dropdown Logic
            function showCollectorList() {
                document.getElementById('collector_list').classList.remove('hidden');
            }

            function hideCollectorList() {
                document.getElementById('collector_list').classList.add('hidden');
            }

            function filterCollectors() {
                const input = document.getElementById('collector_search');
                const filter = input.value.toLowerCase();
                const items = document.getElementsByClassName('collector-option');

                for (let i = 0; i < items.length; i++) {
                    const txtValue = items[i].getAttribute('data-name');
                    if (txtValue && txtValue.indexOf(filter) > -1) {
                        items[i].classList.remove('hidden');
                    } else {
                        // Don't hide the "All Collectors" option if filter is empty
                        if(items[i].innerText.trim() === 'All Collectors' && filter === '') {
                             items[i].classList.remove('hidden');
                        } else if (items[i].innerText.trim() !== 'All Collectors') {
                             items[i].classList.add('hidden');
                        }
                    }
                }
            }

            function selectCollector(id, name) {
                document.getElementById('collector_id_hidden').value = id;
                document.getElementById('collector_search').value = name;
                hideCollectorList();
            }

            // Live Search Implementation
            document.addEventListener('DOMContentLoaded', function() {
                const searchInput = document.getElementById('search-input');
                const searchForm = searchInput ? searchInput.closest('form') : null;
                const patientsContainer = document.getElementById('patients-container');
                let debounceTimer;

                if (searchInput && searchForm && patientsContainer) {
                    searchInput.addEventListener('input', function() {
                        clearTimeout(debounceTimer);
                        debounceTimer = setTimeout(() => {
                            const formData = new FormData(searchForm);
                            const params = new URLSearchParams(formData);
                            params.delete('page'); // Reset pagination on new search
                            
                            const url = `${searchForm.action}?${params.toString()}`;

                            // Add loading state opacity
                            if (patientsContainer) {
                                    patientsContainer.classList.add('opacity-50', 'transition-opacity', 'duration-200');
                            }

                            fetch(url, {
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newContainer = doc.getElementById('patients-container');
                                
                                if (newContainer && patientsContainer) {
                                    patientsContainer.innerHTML = newContainer.innerHTML;
                                    window.history.pushState({}, '', url);
                                }
                            })
                            .catch(error => console.error('Error fetching patients:', error))
                            .finally(() => {
                                if (patientsContainer) {
                                    patientsContainer.classList.remove('opacity-50');
                                }
                            });
                        }, 300); // 300ms debounce
                    });
                }
            });
        </script>
    </div>
@endsection