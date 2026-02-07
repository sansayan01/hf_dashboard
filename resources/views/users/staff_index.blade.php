@extends('layouts.app')

@section('title', 'Pharmacist & Office In-Charge')
@section('header_title', 'Pharmacist Management')

@section('content')
    @php
        $canBulkApprove = auth()->user()->isSuperAdmin();
    @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center space-x-3">
                    <h3 class="font-bold text-lg text-slate-800">Pharmacist & Office In-Charge</h3>
                    <span
                        class="px-2 py-0.5 bg-accent/10 text-accent text-[10px] font-black rounded-full border border-accent/20">
                        {{ $users->total() }} Total
                    </span>
                </div>
                <p class="text-sm text-slate-500">Manage your pharmacists and in-charges.</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('users.export', array_merge(request()->all(), ['type' => 'staff'])) }}"
                    class="px-2 sm:px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-all flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span class="hidden lg:inline uppercase tracking-widest">Download CSV</span>
                </a>
                <button type="button" onclick="toggleFilters()"
                    class="px-2 sm:px-4 py-2 bg-slate-100 border border-slate-200 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                        </path>
                    </svg>
                    <span class="hidden lg:inline uppercase tracking-widest">Filter</span>
                </button>

                @if(auth()->user()->isSuperAdmin())
                    <button type="submit" form="bulk-actions-form" id="bulk-approve-header-btn"
                        class="bulk-approve-btn hidden px-2 sm:px-4 py-2 bg-emerald-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20 hover:bg-emerald-600 transition-all flex items-center space-x-2 border border-emerald-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="hidden lg:inline">APPROVE SELECTED</span>
                    </button>

                    <button type="submit" form="bulk-actions-form" formaction="{{ route('users.bulk-print-selection') }}"
                        formtarget="_blank" style="background-color: #e11d48; color: white; border-color: #be185d;"
                        class="px-2 sm:px-4 py-2 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-rose-600/20 hover:opacity-90 transition-all flex items-center space-x-2 border">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM12 17h.01M9 16h6" />
                        </svg>
                        <span class="hidden lg:inline">PRINT SELECTED</span>
                    </button>
                @endif

                @if(auth()->user()->canCreateUsers())
                    <a href="{{ route('users.create', ['type' => 'staff']) }}"
                        class="px-4 py-2 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                        + Add Member
                    </a>
                @endif
            </div>
        </div>

        <div id="filter-panel"
            class="{{ request()->anyFilled(['search']) ? '' : 'hidden' }} p-6 border-b border-slate-100 bg-slate-50/50 dark:bg-darkbg/20 transition-all">
            <form action="{{ route('users.staffIndex') }}" method="GET" class="no-loader space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search
                            Member</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, ID or Phone..."
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    <div class="lg:col-span-3 flex items-end space-x-2">
                        <button type="submit"
                            class="h-10 px-6 bg-accent text-white rounded-xl text-sm font-bold hover:opacity-90 transition">Apply
                            Filters</button>
                        <a href="{{ route('users.staffIndex') }}"
                            class="h-10 px-6 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold flex items-center justify-center hover:opacity-90 transition">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        <form id="bulk-actions-form" action="{{ route('users.bulk-approve') }}" method="POST">
            @csrf
        </form>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50">
                        @if($canBulkApprove)
                            <th class="px-6 py-4 w-10 text-center">
                                <input type="checkbox" id="user-select-all" form="bulk-actions-form"
                                    class="w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent" title="Select All">
                            </th>
                        @endif
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Member Detail
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                            Designation
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Joined On
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Status
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-50 transition-colors group">
                            @if($canBulkApprove)
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="selected_users[]" value="{{ $u->id }}" form="bulk-actions-form"
                                        data-status="{{ $u->status }}"
                                        class="user-checkbox w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent">
                                </td>
                            @endif
                            <td class="px-6 py-4">
                                <a href="{{ route('users.show', $u->id) }}" class="flex items-center space-x-3 group">
                                    <div
                                        class="w-10 h-10 rounded-full bg-accent/5 text-accent flex items-center justify-center font-bold overflow-hidden border border-slate-100 dark:border-white/5 group-hover:border-accent/30 transition-colors">
                                        @if($u->profile?->profile_picture)
                                            <img src="{{ $u->profile->getProfilePictureUrl() }}" alt="Avatar"
                                                class="w-full h-full object-cover">
                                        @else
                                            {{ substr($u->profile?->full_name ?? 'U', 0, 1) }}
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-800 group-hover:text-accent transition-colors">
                                            {{ $u->profile?->full_name ?? 'Incomplete Profile' }}
                                        </p>
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
                                <div class="flex items-center justify-end space-x-2">
                                    @if($u->status === 'pending' && auth()->user()->canApprove($u))
                                        <form action="{{ route('users.approve', $u->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                class="px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg transition-all text-[10px] font-black uppercase tracking-widest flex items-center space-x-1"
                                                title="Approve Member">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                        d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>Approve</span>
                                            </button>
                                        </form>
                                    @endif

                                    <div class="flex items-center justify-end space-x-2 transition-opacity">
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

                                        @if(auth()->user()->isSuperAdmin())
                                            <a href="{{ route('users.edit', $u->id) }}"
                                                class="p-2 text-blue-500 hover:bg-blue-500/10 rounded-lg transition"
                                                title="Edit User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>

                                            <a href="{{ route('users.id-card', $u->id) }}" target="_blank"
                                                class="p-2 text-violet-500 hover:bg-violet-500/10 rounded-lg transition"
                                                title="Generate ID Card">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                                </svg>
                                            </a>

                                            <form action="{{ route('users.destroy', $u->id) }}" method="POST"
                                                onsubmit="return confirm('Move to BIN?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-danger hover:bg-danger/10 rounded-lg transition"
                                                    title="Delete User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-20 text-center">
                                <div class="max-w-xs mx-auto text-slate-400">
                                    <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <p class="font-bold">No pharmacist or office in-charge members found.</p>
                                    <p class="text-xs mt-1">Start by adding a new member.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Bulk Action Bar -->
        <div id="bulk-action-bar"
            class="hidden fixed bottom-8 left-1/2 -translate-x-1/2 z-50 animate-in fade-in slide-in-from-bottom-4 duration-300">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl shadow-2xl p-4 flex items-center space-x-6">
                <div class="px-4 border-r border-slate-700">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Selected</p>
                    <p class="text-white font-black text-lg" id="selected-count">0</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="submit" form="bulk-actions-form"
                        class="bulk-approve-btn px-6 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-emerald-500/20 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Approve Selected</span>
                    </button>
                    @if(auth()->user()->isSuperAdmin())
                        <button type="submit" form="bulk-actions-form" formaction="{{ route('users.bulk-print-selection') }}"
                            formtarget="_blank" style="background-color: #e11d48; color: white; border-color: #be185d;"
                            class="px-2 sm:px-6 py-2.5 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-rose-600/20 hover:opacity-90 flex items-center space-x-2 border">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM12 17h.01M9 16h6" />
                            </svg>
                            <span class="hidden lg:inline">PRINT SELECTED</span>
                        </button>
                    @endif
                    <button type="button" onclick="cancelSelection()"
                        class="px-6 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-black uppercase tracking-widest rounded-xl transition-all">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="p-6 border-t border-slate-100 italic">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        function toggleFilters() {
            const panel = document.getElementById('filter-panel');
            panel.classList.toggle('hidden');
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Bulk Selection Logic
            const selectAll = document.getElementById('user-select-all');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const bulkBar = document.getElementById('bulk-action-bar');
            const selectedCount = document.getElementById('selected-count');

            function updateBulkBar() {
                const checkedBoxes = document.querySelectorAll('.user-checkbox:checked');
                const checkedCount = checkedBoxes.length;
                if (selectedCount) selectedCount.textContent = checkedCount;

                if (checkedCount > 0) {
                    if (bulkBar) bulkBar.classList.remove('hidden');

                    // Check if any active user is selected
                    let hasActive = false;
                    checkedBoxes.forEach(cb => {
                        if (cb.getAttribute('data-status') === 'active') {
                            hasActive = true;
                        }
                    });

                    const approveButtons = document.querySelectorAll('.bulk-approve-btn');
                    approveButtons.forEach(btn => {
                        if (hasActive) {
                            btn.classList.add('hidden');
                        } else {
                            btn.classList.remove('hidden');
                        }
                    });
                } else {
                    if (bulkBar) bulkBar.classList.add('hidden');
                    // Also hide header button if nothing selected
                    const headerBtn = document.getElementById('bulk-approve-header-btn');
                    if (headerBtn) headerBtn.classList.add('hidden');
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    checkboxes.forEach(cb => {
                        cb.checked = selectAll.checked;
                    });
                    updateBulkBar();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkBar);
            });

            window.cancelSelection = function () {
                checkboxes.forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
                updateBulkBar();
            }
        });
    </script>
@endsection