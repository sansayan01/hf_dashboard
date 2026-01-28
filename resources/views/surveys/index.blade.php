@extends('layouts.app')

@section('title', 'Surveys')
@section('header_title', 'Field Survey Management')

@section('content')
    <div class="bg-white dark:bg-darkbg/40 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h3 class="font-bold text-lg text-slate-800 dark:text-white">
                        {{ request('show_nia') ? 'NIA Records (Not Interested)' : 'Active Surveys' }}
                    </h3>
                    <span class="px-2 py-0.5 bg-accent/10 text-accent text-[10px] font-black rounded-full border border-accent/20">
                        {{ $surveys->total() }} Total
                    </span>
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">Monitoring field data and records.</p>
            </div>
            
            <div class="flex items-center space-x-3">
                @if(request('view_all'))
                    <a href="{{ route('surveys.index', request()->except('view_all')) }}"
                        class="px-2 sm:px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="hidden lg:inline uppercase tracking-widest">Paginate</span>
                    </a>
                @else
                    <a href="{{ route('surveys.index', array_merge(request()->all(), ['view_all' => 1])) }}"
                        class="px-2 sm:px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <span class="hidden lg:inline uppercase tracking-widest">View All</span>
                    </a>
                @endif
                <a href="{{ route('surveys.index', array_merge(request()->query(), ['show_nia' => request('show_nia') ? 0 : 1])) }}"
                    class="px-4 py-2 {{ request('show_nia') ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }} rounded-xl text-sm font-bold hover:opacity-80 transition flex items-center space-x-2">
                    @if(request('show_nia'))
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                        <span>View Active</span>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        <span>View NIA</span>
                    @endif
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
                <a href="{{ route('surveys.create') }}"
                    class="px-4 py-2 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                    + New Survey
                </a>
            </div>
        </div>

        <div id="filter-panel" class="{{ request()->anyFilled(['search', 'gender', 'health_issue', 'date_from', 'date_to', 'collector_id']) ? '' : 'hidden' }} p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-darkbg/20 transition-all">
            <form action="{{ route('surveys.index') }}" method="GET" class="no-loader space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search Records</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
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

                    <!-- District -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">District</label>
                        <select name="district" class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Districts</option>
                            @foreach($districts as $district)
                                <option value="{{ $district }}" {{ request('district') == $district ? 'selected' : '' }}>{{ $district }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Block -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Block</label>
                        <select name="block" class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Blocks</option>
                            @foreach($blocks as $block)
                                <option value="{{ $block }}" {{ request('block') == $block ? 'selected' : '' }}>{{ $block }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Gram Panchayat -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Gram Panchayat</label>
                        <select name="gp" class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All GPs</option>
                            @foreach($gps as $gp)
                                <option value="{{ $gp }}" {{ request('gp') == $gp ? 'selected' : '' }}>{{ $gp }}</option>
                            @endforeach
                        </select>
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
                        @if(request()->anyFilled(['search', 'gender', 'health_issue', 'date_from', 'date_to', 'collector_id', 'district', 'block', 'gp']))
                            <a href="{{ route('surveys.index') }}"
                                class="h-10 px-6 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold flex items-center justify-center hover:opacity-90 transition">Reset</a>
                        @endif
                    </div>
                </div>
            </form>
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
                        if(items[i].innerText === 'All Collectors' && filter === '') {
                             items[i].classList.remove('hidden');
                        } else if (items[i].innerText !== 'All Collectors') {
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
        </script>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-6 py-4 rounded-2xl font-bold text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-6 py-4 rounded-2xl font-bold text-sm mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($surveys->isEmpty())
            <div class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl p-20 text-center">
                <div class="w-24 h-24 bg-accent/10 text-accent rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white mb-3">No Survey Data Yet</h4>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-10 font-medium leading-relaxed">
                    Start collecting field data by creating your first survey record. All submissions will appear here for management review.
                </p>
                <a href="{{ route('surveys.create') }}" class="inline-block text-accent font-black uppercase tracking-[0.2em] text-[10px] hover:underline">Begin Field Work &rarr;</a>
            </div>
        @else
            <!-- Bulk Actions Bar -->
            @if(Auth::user()->isSuperAdmin())
                <div id="bulk-actions-bar" class="hidden sticky top-0 z-20 bg-accent text-white px-6 py-4 flex items-center justify-between shadow-xl">
                    <div class="flex items-center space-x-4">
                        <span id="selected-count" class="font-black text-sm uppercase tracking-widest">0 Records Selected</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <form id="bulk-delete-form" action="{{ route('surveys.bulk-destroy') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all selected surveys?')">
                            @csrf
                            <div id="bulk-ids-container"></div>
                            <button type="submit" class="px-6 py-2 bg-white text-accent rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-50 transition-colors">
                                Delete Selected
                            </button>
                        </form>
                        <button type="button" onclick="cancelSelection()" class="text-white/70 hover:text-white text-xs font-bold uppercase tracking-widest">Cancel</button>
                    </div>
                </div>
            @endif

            <div class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="surveys-table">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                                @if(Auth::user()->isSuperAdmin())
                                    <th class="p-6 w-10">
                                        <input type="checkbox" id="select-all" class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent transition-all cursor-pointer">
                                    </th>
                                @endif
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Participant</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Membership</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Contact Info</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Health Status</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Doc. Appointment</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Collected By</th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @foreach($surveys as $survey)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                    @if(Auth::user()->isSuperAdmin())
                                        <td class="p-6 w-10">
                                            <input type="checkbox" class="survey-checkbox w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent transition-all cursor-pointer" data-id="{{ $survey->id }}">
                                        </td>
                                    @endif
                                    <td class="p-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-10 h-10 bg-accent/10 text-accent dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black">
                                                {{ substr($survey->full_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h4 class="font-black text-slate-800 dark:text-white text-sm">{{ $survey->full_name }}</h4>
                                                <div class="flex items-center space-x-2 text-[10px] text-slate-500 font-bold uppercase tracking-tight mt-0.5">
                                                    <span>{{ ucfirst($survey->gender) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        @if($survey->is_member)
                                            <span class="inline-flex items-center px-4 py-2 bg-emerald-500/10 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/10">
                                                Member
                                            </span>
                                        @else
                                            <a href="{{ route('patients.membership', $survey->id) }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-amber-500/10 text-amber-500 rounded-xl hover:bg-amber-500 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-amber-500/10 shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                </svg>
                                                <span>Become Member</span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="p-6">
                                        <div class="space-y-1">
                                            <div class="flex items-center space-x-2 text-slate-600 dark:text-slate-300">
                                                <i class="fas fa-phone text-[10px] w-4 text-center text-slate-400"></i>
                                                <span class="text-xs font-bold">{{ $survey->phone_number }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        @if($survey->health_issues)
                                            <div class="inline-flex items-center space-x-2 px-3 py-1.5 bg-amber-500/10 text-amber-500 rounded-lg border border-amber-500/10">
                                                <span class="relative flex h-2 w-2">
                                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500 opacity-75"></span>
                                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                                </span>
                                                <span class="text-[10px] font-black uppercase tracking-wider">{{ Str::limit($survey->health_issues, 20) }}</span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center space-x-1">
                                            <a href="{{ route('patients.appointments.create', $survey->id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20 text-[10px] font-black uppercase tracking-wider transition-colors">
                                                Create
                                            </a>
                                            <a href="{{ route('patients.appointments.index', $survey->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 text-[10px] font-black uppercase tracking-wider transition-colors">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg overflow-hidden ring-2 ring-slate-100 dark:ring-slate-800">
                                                @if($survey->creator->profile && $survey->creator->profile->profile_picture)
                                                    <img src="{{ $survey->creator->profile->getProfilePictureUrl() }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-[10px] font-black">
                                                        {{ substr($survey->creator->profile->full_name ?? 'U', 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                @if(auth()->user()->isSuperAdmin())
                                                    <a href="{{ route('users.show', $survey->creator->id) }}" class="text-xs font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                                                        <span>{{ $survey->creator->profile->full_name ?? 'Unknown' }}</span>
                                                        <svg class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                                        </svg>
                                                    </a>
                                                @else
                                                    <p class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $survey->creator->profile->full_name ?? 'Unknown' }}</p>
                                                @endif
                                                <p class="text-[10px] font-medium text-slate-400">{{ $survey->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6 text-right">
                                        @if(Auth::id() === $survey->created_by || Auth::user()->canAccess($survey->creator))
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('surveys.edit', $survey->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-accent hover:text-white dark:bg-slate-800 dark:hover:bg-accent text-slate-400 transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('surveys.destroy', $survey->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this survey?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-red-500 hover:text-white dark:bg-slate-800 dark:hover:bg-red-500 text-slate-400 transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @if($surveys instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="p-6 border-t border-slate-100 italic">
                    {{ $surveys->links() }}
                </div>
            @endif

            @if(Auth::user()->isSuperAdmin())
                <script>
                    const selectAll = document.getElementById('select-all');
                    const checkboxes = document.querySelectorAll('.survey-checkbox');
                    const bulkBar = document.getElementById('bulk-actions-bar');
                    const selectedCount = document.getElementById('selected-count');
                    const bulkIdsContainer = document.getElementById('bulk-ids-container');

                    function updateSelection() {
                        const selected = Array.from(checkboxes).filter(cb => cb.checked);
                        const count = selected.length;
                        
                        if (count > 0) {
                            bulkBar.classList.remove('hidden');
                            selectedCount.innerText = `${count} Records Selected`;
                            
                            // Update hidden inputs for bulk form
                            bulkIdsContainer.innerHTML = '';
                            selected.forEach(cb => {
                                const input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'ids[]';
                                input.value = cb.getAttribute('data-id');
                                bulkIdsContainer.appendChild(input);
                            });
                        } else {
                            bulkBar.classList.add('hidden');
                        }
                    }

                    selectAll.addEventListener('change', () => {
                        checkboxes.forEach(cb => cb.checked = selectAll.checked);
                        updateSelection();
                    });

                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', () => {
                            updateSelection();
                            if (!cb.checked) selectAll.checked = false;
                            if (Array.from(checkboxes).every(c => c.checked)) selectAll.checked = true;
                        });
                    });

                    function cancelSelection() {
                        selectAll.checked = false;
                        checkboxes.forEach(cb => cb.checked = false);
                        updateSelection();
                    }
                </script>
            @endif
        @endif
    </div>
@endsection
