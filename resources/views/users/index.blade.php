@extends('layouts.app')

@section('title', 'My Team')
@section('header_title', 'My Team')

@section('content')
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h3 class="font-bold text-lg text-slate-800">Team Members</h3>
                    <span
                        class="px-2 py-0.5 bg-accent/10 text-accent text-[10px] font-black rounded-full border border-accent/20">
                        {{ $users->total() }} Total
                    </span>
                </div>
                <p class="text-sm text-slate-500">View and manage your network hierarchy.</p>
            </div>
            <div class="flex items-center space-x-3">
                <button type="button" onclick="toggleFilters()"
                    class="px-4 py-2 bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    <span>Filter</span>
                </button>
                <a href="{{ route('users.create') }}"
                    class="px-4 py-2 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                    + Add Member
                </a>
            </div>
        </div>

        <!-- Advanced Filter Panel -->
        <div id="filter-panel"
            class="{{ request()->anyFilled(['district', 'block', 'gram_panchayat', 'designation', 'search']) ? '' : 'hidden' }} p-6 border-b border-slate-100 bg-slate-50/50 dark:bg-darkbg/20 transition-all">
            <form action="{{ route('users.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search
                            Member</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, ID or Phone..."
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <!-- Designation -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Designation</label>
                        <select name="designation"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All Roles</option>
                            @foreach($allowedFilters as $val => $label)
                                <option value="{{ $val }}" {{ request('designation') == $val ? 'selected' : '' }}>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- District -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">District</label>
                        <select name="district" id="district-filter"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All Districts</option>
                        </select>
                    </div>

                    <!-- Block -->
                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Block</label>
                        <select name="block" id="block-filter"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All Blocks</option>
                        </select>
                    </div>

                    <!-- GP -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Gram
                            Panchayat</label>
                        <select name="gram_panchayat" id="gp-filter"
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            <option value="">All GPs</option>
                        </select>
                    </div>

                    <div class="lg:col-span-3 flex items-end space-x-2">
                        <button type="submit"
                            class="h-10 px-6 bg-accent text-white rounded-xl text-sm font-bold hover:opacity-90 transition">Apply
                            Filters</button>
                        <a href="{{ route('users.index') }}"
                            class="h-10 px-6 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold flex items-center justify-center hover:opacity-90 transition">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Member Detail
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Designation
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Joined On</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <td class="px-6 py-4">
                                <a href="{{ route('users.show', $u->id) }}" class="flex items-center space-x-3 group">
                                    <div
                                        class="w-10 h-10 rounded-full bg-accent/5 text-accent flex items-center justify-center font-bold overflow-hidden border border-slate-100 dark:border-white/5 group-hover:border-accent/30 transition-colors">
                                        @if($u->profile && $u->profile->profile_picture)
                                            <img src="{{ asset('storage/' . $u->profile->profile_picture) }}" alt="Avatar"
                                                class="w-full h-full object-cover">
                                        @else
                                            {{ substr($u->profile->full_name ?? 'U', 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 group-hover:text-accent transition-colors">{{ $u->profile->full_name }}</p>
                                        <p class="text-[10px] text-bodydark font-bold uppercase">{{ $u->employee_id }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-primary/5 text-primary rounded-full text-[10px] font-black uppercase tracking-widest border border-primary/10">
                                    {{ $u->getDesignationLabel() }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-500 font-medium">{{ $u->created_at->format('d M, Y') }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($u->status === 'active')
                                    <span class="inline-flex items-center space-x-1.5 text-success">
                                        <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Active</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center space-x-1.5 text-warning">
                                        <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                                        <span class="text-[10px] font-black uppercase tracking-widest">Pending</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div
                                    class="flex items-center justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('users.show', $u->id) }}"
                                        class="p-2 text-slate-400 hover:text-accent transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>

                                    @if($u->status === 'pending' && auth()->user()->canApprove($u))
                                        <form action="{{ route('users.approve', $u->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 text-success hover:bg-success/10 rounded-lg transition"
                                                title="Approve Member">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST"
                                        onsubmit="return confirm('Move to BIN?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-danger hover:bg-danger/10 rounded-lg transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-20 text-center">
                                <div class="max-w-xs mx-auto text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <p class="font-bold">No downline members found yet.</p>
                                    <p class="text-xs mt-1">Start growing the foundation by adding new members.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="p-6 border-t border-slate-100 italic">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script src="{{ asset('js/locations.js') }}"></script>
    <script>
        function toggleFilters() {
            const panel = document.getElementById('filter-panel');
            panel.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            const districtSelect = document.getElementById('district-filter');
            const blockSelect = document.getElementById('block-filter');
            const gpSelect = document.getElementById('gp-filter');

            const state = "West Bengal";
            const districts = locationData[state];

            // Setup Districts
            Object.keys(districts).forEach(district => {
                const option = new Option(district, district);
                if ("{{ request('district') }}" === district) option.selected = true;
                districtSelect.add(option);
            });

            function updateBlocks() {
                const district = districtSelect.value;
                blockSelect.innerHTML = '<option value="">All Blocks</option>';
                gpSelect.innerHTML = '<option value="">All GPs</option>';

                if (district && districts[district]) {
                    Object.keys(districts[district]).forEach(block => {
                        const option = new Option(block, block);
                        if ("{{ request('block') }}" === block) option.selected = true;
                        blockSelect.add(option);
                    });

                    if ("{{ request('block') }}") {
                        updateGPs();
                    }
                }
            }

            function updateGPs() {
                const district = districtSelect.value;
                const block = blockSelect.value;
                gpSelect.innerHTML = '<option value="">All GPs</option>';

                if (district && block && districts[district][block]) {
                    districts[district][block].forEach(gp => {
                        const option = new Option(gp, gp);
                        if ("{{ request('gram_panchayat') }}" === gp) option.selected = true;
                        gpSelect.add(option);
                    });
                }
            }

            districtSelect.addEventListener('change', updateBlocks);
            blockSelect.addEventListener('change', updateGPs);

            // Initial population if values exist
            if (districtSelect.value) {
                updateBlocks();
            }
        });
    </script>
@endsection