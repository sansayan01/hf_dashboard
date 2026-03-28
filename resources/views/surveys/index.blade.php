@extends('layouts.app')

@section('title', 'Surveys')
@section('header_title', 'Field Survey Management')

@section('content')
    <div
        class="bg-white dark:bg-darkbg/40 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden">
        <!-- Header -->
        <div
            class="p-6 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h3 id="page-title" class="font-bold text-lg text-slate-800 dark:text-white">
                        {{ request('show_nia') ? 'NIA Records (Not Interested)' : 'Active Surveys' }}
                    </h3>
                    <span
                        class="px-2 py-0.5 bg-accent/10 text-accent text-[10px] font-black rounded-full border border-accent/20">
                        <span id="stat-total">{{ $surveys->total() }}</span> Total
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
                @if(auth()->user()->hasPermission('survey.view_nia'))
                    <button type="button" onclick="toggleNIA()" id="nia-toggle-btn"
                        class="px-4 py-2 {{ request('show_nia') ? 'bg-amber-100 text-amber-700 border border-amber-200' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300' }} rounded-xl text-sm font-bold hover:opacity-80 transition flex items-center space-x-2">
                        @if(request('show_nia'))
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <span>View Active</span>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                            <span>View NIA</span>
                        @endif
                    </button>
                    <input type="hidden" name="show_nia" value="{{ request('show_nia', 0) }}" form="filterForm">
                @endif

                <button type="button" onclick="toggleFilters()"
                    class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    <span>Filter</span>
                </button>
                @if(auth()->user()->hasPermission('survey.create'))
                    <a href="{{ route('surveys.create') }}"
                        class="px-4 py-2 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                        + New Survey
                    </a>
                @endif
            </div>
        </div>

        <div id="filter-panel"
            class="{{ request()->anyFilled(['search', 'gender', 'health_issue', 'date_from', 'date_to', 'collector_id']) ? '' : 'hidden' }} p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-darkbg/20 transition-all">
            <form action="{{ route('surveys.index') }}" method="GET" class="no-loader space-y-4" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <!-- Search -->
                    <div class="lg:col-span-3">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search
                            Records</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Patient Name, Phone, or Collector ID..."
                                class="w-full h-10 pl-10 pr-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Collected By -->
                    <div class="relative group/collector">
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Collected
                            By</label>
                        <input type="hidden" name="collector_id" id="collector_id_hidden"
                            value="{{ request('collector_id') }}">
                        <div class="relative">
                            <input type="text" id="collector_search" placeholder="Search Collector..."
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition truncate pr-8 dark:text-white"
                                autocomplete="off" onfocus="showCollectorList()" oninput="filterCollectors()"
                                onblur="setTimeout(hideCollectorList, 200)"
                                value="{{ $collectors->firstWhere('id', request('collector_id'))->profile->full_name ?? ($collectors->firstWhere('id', request('collector_id'))->employee_id ?? '') }}">
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>

                        <!-- Dropdown List -->
                        <div id="collector_list"
                            class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl max-h-[40vh] overflow-y-auto hidden">
                            <div class="p-2 space-y-1">
                                <div class="collector-option px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer text-xs font-bold text-slate-600 dark:text-slate-300 transition-colors"
                                    onclick="selectCollector('', 'All Collectors')">
                                    All Collectors
                                </div>
                                @foreach($collectors as $collector)
                                    <div class="collector-option px-3 py-2 rounded-lg hover:bg-slate-50 dark:hover:bg-white/5 cursor-pointer text-xs font-bold text-slate-600 dark:text-slate-300 transition-colors"
                                        data-name="{{ strtolower($collector->profile->full_name ?? '') }} {{ strtolower($collector->employee_id) }}"
                                        onclick="selectCollector('{{ $collector->id }}', '{{ $collector->profile->full_name ?? $collector->employee_id }} ({{ $collector->employee_id }})')">
                                        {{ $collector->profile->full_name ?? $collector->employee_id }} <span
                                            class="text-slate-400 font-medium ml-1">#{{ $collector->employee_id }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- District -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">District</label>
                        <select name="district" id="district-select"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Districts</option>
                            <!-- Populated by JS -->
                        </select>
                    </div>

                    <!-- Block -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Block</label>
                        <select name="block" id="block-select"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Blocks</option>
                            <!-- Populated by JS -->
                        </select>
                    </div>

                    <!-- Gram Panchayat -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Gram
                            Panchayat</label>
                        <select name="gp" id="gp-select"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All GPs</option>
                            <!-- Populated by JS -->
                        </select>
                    </div>

                    <script src="{{ asset('js/locations.js') }}"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const districtSelect = document.getElementById('district-select');
                            const blockSelect = document.getElementById('block-select');
                            const gpSelect = document.getElementById('gp-select');

                            const currentDistrict = "{{ request('district') }}";
                            const currentBlock = "{{ request('block') }}";
                            const currentGp = "{{ request('gp') }}";

                            // 1. Populate Districts from all States
                            if (window.locationData) {
                                let allDistricts = [];
                                for (const state in window.locationData) {
                                    allDistricts = allDistricts.concat(Object.keys(window.locationData[state]));
                                }
                                allDistricts = [...new Set(allDistricts)].sort(); // Deduplicate and sort

                                allDistricts.forEach(district => {
                                    const option = new Option(district, district);
                                    if (district === currentDistrict) option.selected = true;
                                    districtSelect.add(option);
                                });
                            }

                            // 2. Cascading Logic
                            function updateBlocks() {
                                const selectedDistrict = districtSelect.value;
                                blockSelect.innerHTML = '<option value="">All Blocks</option>';
                                gpSelect.innerHTML = '<option value="">All GPs</option>';

                                if (selectedDistrict && window.locationData) {
                                    // Find which state this district belongs to
                                    let selectedState = null;
                                    for (const state in window.locationData) {
                                        if (window.locationData[state][selectedDistrict]) {
                                            selectedState = state;
                                            break;
                                        }
                                    }

                                    if (selectedState) {
                                        const blocks = Object.keys(window.locationData[selectedState][selectedDistrict]).sort();
                                        blocks.forEach(block => {
                                            const option = new Option(block, block);
                                            if (block === currentBlock) option.selected = true;
                                            blockSelect.add(option);
                                        });
                                    }
                                }
                                updateGps();
                            }

                            function updateGps() {
                                const selectedDistrict = districtSelect.value;
                                const selectedBlock = blockSelect.value;
                                gpSelect.innerHTML = '<option value="">All GPs</option>';

                                if (selectedDistrict && selectedBlock && window.locationData) {
                                    let selectedState = null;
                                    for (const state in window.locationData) {
                                        if (window.locationData[state][selectedDistrict]) {
                                            selectedState = state;
                                            break;
                                        }
                                    }

                                    if (selectedState && window.locationData[selectedState][selectedDistrict][selectedBlock]) {
                                        const gps = window.locationData[selectedState][selectedDistrict][selectedBlock].sort();
                                        gps.forEach(gp => {
                                            const option = new Option(gp, gp);
                                            if (gp === currentGp) option.selected = true;
                                            gpSelect.add(option);
                                        });
                                    }
                                }
                            }

                            districtSelect.addEventListener('change', updateBlocks);
                            blockSelect.addEventListener('change', updateGps);

                            // Initial Call to set lists if values are pre-selected
                            updateBlocks();
                        });
                    </script>

                    <!-- Gender -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Gender</label>
                        <select name="gender"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Genders</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ request('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Health Category -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Health
                            Category</label>
                        <select name="health_issue"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition dark:text-white">
                            <option value="">All Issues</option>
                            <option value="Gas" {{ request('health_issue') == 'Gas' ? 'selected' : '' }}>Gas</option>
                            <option value="Sugar" {{ request('health_issue') == 'Sugar' ? 'selected' : '' }}>Sugar</option>
                            <option value="Pressure" {{ request('health_issue') == 'Pressure' ? 'selected' : '' }}>Pressure
                            </option>
                            <option value="Thyroid" {{ request('health_issue') == 'Thyroid' ? 'selected' : '' }}>Thyroid
                            </option>
                            <option value="Uric Acid" {{ request('health_issue') == 'Uric Acid' ? 'selected' : '' }}>Uric Acid
                            </option>
                            <option value="Skin/Hair" {{ request('health_issue') == 'Skin/Hair' ? 'selected' : '' }}>Skin/Hair
                            </option>
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
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Date
                            From</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition text-slate-600 dark:text-slate-300">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Date
                            To</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition text-slate-600 dark:text-slate-300">
                    </div>

                    <!-- Buttons -->
                    <div
                        class="lg:col-span-3 flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                        <button type="button" id="clearFilters"
                            class="text-xs font-bold text-rose-500 hover:underline uppercase tracking-widest flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            <span>Clear All Filters</span>
                        </button>
                        <button type="submit"
                            class="bg-accent text-white px-8 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:shadow-lg hover:shadow-accent/30 transition-all active:scale-95">
                            Apply Filters
                        </button>
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
                        if (items[i].innerText === 'All Collectors' && filter === '') {
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
            <div
                class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-6 py-4 rounded-2xl font-bold text-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-500/10 border border-red-500/20 text-red-500 px-6 py-4 rounded-2xl font-bold text-sm mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if($surveys->isEmpty())
            <div
                class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl p-20 text-center">
                <div class="w-24 h-24 bg-accent/10 text-accent rounded-3xl flex items-center justify-center mx-auto mb-8">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h4 class="text-2xl font-black text-slate-800 dark:text-white mb-3">No Survey Data Yet</h4>
                <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-10 font-medium leading-relaxed">
                    Start collecting field data by creating your first survey record. All submissions will appear here for
                    management review.
                </p>
                <a href="{{ route('surveys.create') }}"
                    class="inline-block text-accent font-black uppercase tracking-[0.2em] text-[10px] hover:underline">Begin
                    Field Work &rarr;</a>
            </div>
        @else
            <!-- Bulk Actions Bar -->
            @if(auth()->user()->hasPermission('survey.bulk_delete'))
                <div id="bulk-actions-bar"
                    class="hidden sticky top-0 z-20 bg-accent text-white px-6 py-4 flex items-center justify-between shadow-xl">
                    <div class="flex items-center space-x-4">
                        <span id="selected-count" class="font-black text-sm uppercase tracking-widest">0 Records Selected</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <form id="bulk-delete-form" action="{{ route('surveys.bulk-destroy') }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete all selected surveys?')">
                            @csrf
                            <div id="bulk-ids-container"></div>
                            <button type="submit"
                                class="px-6 py-2 bg-white text-accent rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-50 transition-colors">
                                Delete Selected
                            </button>
                        </form>
                        <button type="button" onclick="cancelSelection()"
                            class="text-white/70 hover:text-white text-xs font-bold uppercase tracking-widest">Cancel</button>
                    </div>
                </div>
            @endif

            <div
                class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse" id="surveys-table">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                                @if(auth()->user()->hasPermission('survey.bulk_delete'))
                                    <th class="p-6 w-10">
                                        <input type="checkbox" id="select-all"
                                            class="w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent transition-all cursor-pointer">
                                    </th>
                                @endif
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Participant</th>
                                @if(auth()->user()->designation !== 'staff')
                                    <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Membership</th>
                                @endif
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Contact Info
                                </th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Health Status
                                </th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Doc. Appointment
                                </th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Collected By
                                </th>
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody" class="divide-y divide-slate-100 dark:divide-white/5">
                            @include('surveys.partials.table', ['surveys' => $surveys])
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="paginationContainer">
                @if($surveys instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="p-6 border-t border-slate-100 italic">
                        {{ $surveys->links() }}
                    </div>
                @endif
            </div>

            @if(auth()->user()->hasPermission('survey.bulk_delete'))
                <script>
                    function initBulkSelection() {
                        const selectAll = document.getElementById('select-all');
                        const bulkBar = document.getElementById('bulk-actions-bar');
                        const selectedCount = document.getElementById('selected-count');
                        const bulkIdsContainer = document.getElementById('bulk-ids-container');

                        function updateSelection() {
                            const checkboxes = document.querySelectorAll('.survey-checkbox');
                            const selected = Array.from(checkboxes).filter(cb => cb.checked);
                            const count = selected.length;
                            if (count > 0) {
                                bulkBar.classList.remove('hidden');
                                selectedCount.innerText = `${count} Records Selected`;
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

                        if (selectAll) {
                            selectAll.addEventListener('change', () => {
                                document.querySelectorAll('.survey-checkbox').forEach(cb => cb.checked = selectAll.checked);
                                updateSelection();
                            });
                        }

                        document.querySelectorAll('.survey-checkbox').forEach(cb => {
                            cb.addEventListener('change', () => {
                                updateSelection();
                                if (!cb.checked && selectAll) selectAll.checked = false;
                                const checkboxes = document.querySelectorAll('.survey-checkbox');
                                if (selectAll && Array.from(checkboxes).every(c => c.checked)) selectAll.checked = true;
                            });
                        });

                        window.cancelSelection = function () {
                            if (selectAll) selectAll.checked = false;
                            document.querySelectorAll('.survey-checkbox').forEach(cb => cb.checked = false);
                            updateSelection();
                        }
                    }
                    document.addEventListener('DOMContentLoaded', initBulkSelection);
                </script>
            @endif

            <script src="{{ asset('js/live-filter.js') }}"></script>
            <script>
                function toggleNIA() {
                    const btn = document.getElementById('nia-toggle-btn');
                    const form = document.getElementById('filterForm');
                    const niaInput = form.querySelector('input[name="show_nia"]');
                    niaInput.value = niaInput.value == '1' ? '0' : '1';
                    if (window._liveFilter) window._liveFilter.applyFilters();
                }

                document.addEventListener('DOMContentLoaded', function () {
                    window._liveFilter = new LiveFilter({
                        formId: 'filterForm',
                        tableBodyId: 'tableBody',
                        paginationId: 'paginationContainer',
                        onAfterUpdate: function (data) {
                            if (data && data.total !== undefined) {
                                if (document.getElementById('stat-total')) document.getElementById('stat-total').textContent = data.total;
                            }
                            if (data && data.show_nia !== undefined) {
                                const titleEl = document.getElementById('page-title');
                                const btn = document.getElementById('nia-toggle-btn');
                                if (titleEl) titleEl.textContent = data.show_nia ? 'NIA Records (Not Interested)' : 'Active Surveys';
                                if (btn) {
                                    if (data.show_nia) {
                                        btn.className = 'px-4 py-2 bg-amber-100 text-amber-700 border border-amber-200 rounded-xl text-sm font-bold hover:opacity-80 transition flex items-center space-x-2';
                                        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg><span>View Active</span>';
                                    } else {
                                        btn.className = 'px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl text-sm font-bold hover:opacity-80 transition flex items-center space-x-2';
                                        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg><span>View NIA</span>';
                                    }
                                }
                            }
                            if (typeof initBulkSelection === 'function') initBulkSelection();
                        }
                    });
                });
            </script>
        @endif
    </div>
@endsection