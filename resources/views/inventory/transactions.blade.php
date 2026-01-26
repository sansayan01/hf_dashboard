@extends('layouts.app')

@section('title', 'Inventory Logs')
@section('header_title', 'Transaction History')

@section('content')
    <div class="space-y-6">
        <!-- Tab Navigation -->
        <div class="flex items-center space-x-2 bg-slate-100 dark:bg-white/5 p-1 rounded-2xl w-fit">
            @unless(auth()->user()->designation === 'staff')
                <a href="{{ route('inventory.transactions', ['view' => 'movements']) }}"
                    class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $view === 'movements' ? 'bg-white dark:bg-slate-800 text-accent shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                    Inventory Movements
                </a>
            @endunless
            <a href="{{ route('inventory.transactions', ['view' => 'dispenses']) }}"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $view === 'dispenses' ? 'bg-white dark:bg-slate-800 text-accent shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                Patient Dispenses
            </a>
            <a href="{{ route('inventory.transactions', ['view' => 'sponsors']) }}"
                class="px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $view === 'sponsors' ? 'bg-white dark:bg-slate-800 text-accent shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                Sponsor Estimation
            </a>
        </div>

        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white">
            <div class="p-6 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg">
                        @if($view === 'dispenses')
                            Medicine Dispense History
                        @elseif($view === 'sponsors')
                            Sponsor-wise Dispense Analysis
                        @else
                            Internal Stock Movements
                        @endif
                    </h3>
                    <p class="text-sm text-slate-500">
                        @if($view === 'dispenses')
                            Records of all medicines given to patients.
                        @elseif($view === 'sponsors')
                            Analysis of medicines dispensed under various sponsors.
                        @else
                            Log of stock arrivals, transfers, and adjustments.
                        @endif
                    </p>
                    @if($view === 'dispenses' || $view === 'sponsors')
                        <div
                            class="mt-2 inline-flex items-center space-x-2 px-3 py-1 bg-accent/5 border border-accent/10 rounded-full">
                            <span class="text-[10px] font-black uppercase tracking-widest text-accent/60">Collection
                                Total:</span>
                            <span class="text-xs font-black text-accent">₹{{ number_format($totalGrandSum, 2) }}</span>
                        </div>
                    @endif
                </div>
                <div class="flex items-center space-x-3">
                    @if(request('view_all'))
                        <a href="{{ route('inventory.transactions', request()->except('view_all')) }}"
                            class="p-2.5 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 transition flex items-center justify-center"
                            title="Paginate Results">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('inventory.transactions', array_merge(request()->all(), ['view_all' => 1])) }}"
                            class="p-2.5 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 transition flex items-center justify-center"
                            title="View All Records">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                        </a>
                    @endif

                    <a href="{{ route('inventory.transactions.export', request()->all()) }}"
                        class="p-2.5 bg-emerald-500 text-white rounded-xl hover:opacity-90 transition flex items-center justify-center shadow-lg shadow-emerald-500/20"
                        title="Export CSV">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </a>
                    <button onclick="toggleFilters()"
                        class="p-2.5 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 transition flex items-center justify-center"
                        title="Filter Transactions">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </button>
                    <a href="{{ route('inventory.index') }}"
                        class="p-2.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 rounded-xl hover:bg-slate-200 transition flex items-center justify-center"
                        title="Back to Overview">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Filter Bar -->
            <div id="filterSection"
                class="{{ request()->anyFilled(['search', 'date_from', 'date_to']) ? '' : 'hidden' }} bg-slate-50/50 dark:bg-white/5 p-6 border-b border-slate-100 dark:border-white/5">
                <form action="{{ route('inventory.transactions') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="hidden" name="view" value="{{ $view }}">

                    <div>
                        <label
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Patient, Medicine or Camp..."
                            class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                    </div>

                    @if($view === 'sponsors')
                        <div>
                            <label
                                class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Sponsor</label>
                            <select name="sponsor_id"
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                                <option value="">All Sponsors</option>
                                @foreach($sponsors as $sponsor)
                                    <option value="{{ $sponsor->id }}" {{ request('sponsor_id') == $sponsor->id ? 'selected' : '' }}>
                                        {{ $sponsor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">From
                                Date</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}"
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">To
                                Date</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}"
                                class="w-full h-10 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-xs focus:ring-2 focus:ring-accent/20 outline-none transition">
                        </div>
                    </div>

                    <div class="flex items-end space-x-2">
                        <button type="submit"
                            class="flex-1 h-10 bg-accent text-white rounded-xl text-xs font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                            Apply Filters
                        </button>
                        <a href="{{ route('inventory.transactions', ['view' => $view]) }}"
                            class="h-10 px-4 flex items-center justify-center bg-white dark:bg-slate-800 text-slate-500 rounded-xl text-xs font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-white/5">
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Date & Time</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">
                                @if($view === 'sponsors')
                                    <span class="text-accent">Sponsor</span>
                                @else
                                    Entity Link
                                @endif
                            </th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine &
                                Location</th>
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Qty</th>
                            @if($view === 'dispenses')
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Grand Total</th>
                            @elseif($view === 'sponsors')
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-accent">
                                    Medicine Value</th>
                            @else
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Type</th>
                            @endif
                            @unless($view === 'sponsors')
                                <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Performed By
                                </th>
                            @endunless
                            <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($transactions as $transaction)
                                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                            <td class="p-4">
                                                <span
                                                    class="text-xs font-medium text-slate-500">{{ $transaction->created_at->format('M d, Y') }}</span><br>
                                                <span
                                                    class="text-[10px] font-bold text-slate-400 uppercase">{{ $transaction->created_at->format('h:i A') }}</span>
                                            </td>
                                            <td class="p-4">
                                                @if($view === 'sponsors')
                                                    @php
                                                        $sponsor = $transaction->sponsor;
                                                        if (!$sponsor && $transaction->stock) {
                                                            // Fallback to the 'in' transaction of this stock
                                                            $inTransaction = $transaction->stock->transactions->where('type', 'in')->first();
                                                            $sponsor = $inTransaction?->sponsor;
                                                        }
                                                    @endphp
                                                    <span
                                                        class="text-xs font-black uppercase text-accent bg-accent/5 px-3 py-1.5 rounded-xl border border-accent/10 inline-block">
                                                        {{ $sponsor->name ?? 'N/A' }}
                                                    </span>
                                                @elseif($transaction->patient)
                                                    <a href="{{ route('patients.show', $transaction->patient_id) }}"
                                                        class="text-[10px] font-black uppercase text-accent hover:underline">
                                                        Patient: {{ Str::limit($transaction->patient->full_name, 15) }}
                                                    </a>
                                                @else
                                                    <span class="text-[10px] font-bold text-slate-400 italic">System Log</span>
                                                @endif
                                            </td>
                                            <td class="p-4">
                                                <div class="flex flex-col">
                                                    <span
                                                        class="font-black text-sm {{ $view === 'sponsors' ? 'text-accent' : '' }}">{{ $transaction->stock?->medicine?->name ?? 'Deleted Medicine' }}</span>
                                                    <div class="flex items-center flex-wrap gap-x-2 gap-y-1 text-slate-400 font-medium">
                                                        <span class="text-[10px]">Batch:
                                                            #{{ $transaction->stock?->batch_number ?? 'N/A' }}</span>
                                                        <span class="text-slate-200 dark:text-white/10 text-[10px]">•</span>
                                                        <span
                                                            class="text-[10px] text-accent font-black uppercase tracking-widest">{{ $transaction->warehouse?->name ?? 'Main Warehouse' }}</span>
                                                    </div>
                                                </div>
                            </div>
                            </td>
                            <td class="p-4">
                                <span
                                    class="text-sm font-black {{ in_array($transaction->type, ['in', 'adjustment', 'in']) ? 'text-emerald-500' : 'text-red-500' }}">
                                    {{ in_array($transaction->type, ['in', 'adjustment', 'in']) ? '+' : '-' }}{{ $transaction->quantity }}
                                </span>
                            </td>
                            <td class="p-4">
                                @if($view === 'dispenses')
                                    @php
                                        $distId = filter_var($transaction->notes, FILTER_SANITIZE_NUMBER_INT);
                                        $distribution = \App\Models\MedicineDistribution::find($distId);
                                    @endphp
                                    @if($distribution)
                                        <span class="text-sm font-black text-slate-800 dark:text-white">
                                            ₹{{ number_format($distribution->final_amount, 2) }}
                                        </span>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-400">N/A</span>
                                    @endif
                                @elseif($view === 'sponsors')
                                    @php
                                        $distId = filter_var($transaction->notes, FILTER_SANITIZE_NUMBER_INT);
                                        $lineValue = 0;
                                        if ($distId) {
                                            $distItem = \App\Models\MedicineDistributionItem::where('distribution_id', $distId)
                                                ->where('medicine_id', $transaction->stock?->medicine_id)
                                                ->first();
                                            if ($distItem) {
                                                $lineValue = $distItem->unit_price * $transaction->quantity;
                                            }
                                        }
                                    @endphp
                                    <span class="text-sm font-black text-accent">
                                        ₹{{ number_format($lineValue, 2) }}
                                    </span>
                                @else
                                    @php
                                        $colors = [
                                            'in' => 'bg-emerald-100 text-emerald-600',
                                            'out' => 'bg-red-100 text-red-600',
                                            'dispense' => 'bg-blue-100 text-blue-600',
                                            'adjustment' => 'bg-slate-100 text-slate-600',
                                            'expired' => 'bg-amber-100 text-amber-600',
                                            'damaged' => 'bg-red-100 text-red-600',
                                        ];
                                        $color = $colors[$transaction->type] ?? 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="px-2 py-1 {{ $color }} text-[10px] font-black rounded-lg uppercase tracking-tight">
                                        {{ ucfirst($transaction->type) }}
                                    </span>
                                @endif
                            </td>
                            @unless($view === 'sponsors')
                                <td class="p-4">
                                    <span
                                        class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $transaction->user->profile->full_name ?? $transaction->user->employee_id }}</span>
                                </td>
                            @endunless
                            <td class="p-4">
                                <span
                                    class="text-[10px] text-accent font-black uppercase tracking-widest">{{ $transaction->warehouse?->name ?? 'N/A' }}</span>
                            </td>
                            <td class="p-4 text-right">
                                @if(auth()->user()->isSuperAdmin())
                                    <div class="flex items-center justify-end space-x-2">
                                        <button
                                            onclick="openEditModal({{ $transaction->id }}, {{ $transaction->quantity }}, '{{ addslashes($transaction->notes ?? '') }}')"
                                            class="p-2 text-slate-400 hover:text-accent transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('inventory.transactions.destroy', $transaction->id) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure? This will revert the stock levels based on this record.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-danger transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-[10px] font-bold text-slate-400">Locked</span>
                                @endif
                            </td>
                            </tr>
                        @empty
                <tr>
                    <td colspan="7" class="p-10 text-center text-slate-500 font-medium">No transactions recorded
                        yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
            </table>
        </div>

        @if($transactions instanceof \Illuminate\Pagination\LengthAwarePaginator && $transactions->hasPages())
            <div class="p-6 border-t border-slate-100 dark:border-white/5 italic">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>
    </div>

    <!-- Edit Modal -->
    <div id="edit-modal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-slate-900 bg-opacity-75" aria-hidden="true"
                onclick="closeEditModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white dark:bg-slate-800 rounded-3xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="edit-form" method="POST">
                    @csrf @method('PUT')
                    <div class="bg-white dark:bg-slate-800 px-8 pt-8 pb-4">
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6" id="modal-title">Edit
                            Transaction
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Action
                                    Quantity</label>
                                <input type="number" name="quantity" id="edit-quantity" required min="1"
                                    class="w-full h-11 px-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition">
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Notes
                                    / Reference</label>
                                <textarea name="notes" id="edit-notes" rows="3"
                                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm focus:ring-2 focus:ring-accent/20 outline-none transition"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800/50 px-8 py-6 flex flex-row-reverse space-x-2 space-x-reverse">
                        <button type="submit"
                            class="inline-flex justify-center px-6 py-2.5 bg-accent text-white rounded-xl text-sm font-bold shadow-lg shadow-accent/20 hover:opacity-90 transition">
                            Save Changes
                        </button>
                        <button type="button" onclick="closeEditModal()"
                            class="inline-flex justify-center px-6 py-2.5 bg-white dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold border border-slate-200 dark:border-slate-600 hover:bg-slate-50 transition">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleFilters() {
            const section = document.getElementById('filterSection');
            section.classList.toggle('hidden');
        }

        function openEditModal(id, quantity, notes) {
            const modal = document.getElementById('edit-modal');
            const form = document.getElementById('edit-form');
            const quantityInput = document.getElementById('edit-quantity');
            const notesInput = document.getElementById('edit-notes');

            form.action = `/inventory/transactions/${id}`;
            quantityInput.value = quantity;
            notesInput.value = notes;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
@endsection