@extends('layouts.app')

@section('title', 'Medicines')
@section('header_title', 'Medicine Management')

@section('content')
    <div
        class="bg-white dark:bg-darkbg/40 rounded-2xl border border-slate-100 dark:border-white/5 shadow-sm overflow-hidden text-slate-800 dark:text-white">
        <div
            class="p-6 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg">Medicine Registry</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">Manage definitions and general info for NGO medicines.
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('inventory.categories.index') }}"
                    class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-xl text-xs font-bold hover:bg-slate-200 transition">
                    Manage Categories
                </a>
                <a href="{{ route('inventory.medicines.create') }}"
                    class="px-4 py-2 bg-accent text-white rounded-xl text-xs font-bold shadow-lg shadow-accent/10 hover:opacity-90 transition">
                    + Add Medicine
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-white/5">
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Medicine Details
                        </th>
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Generic Name</th>
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Unit / Dosage / Cost
                        </th>
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400">Stock Status</th>
                        <th class="p-4 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                    @forelse($medicines as $medicine)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                            <td class="p-4">
                                <div class="flex flex-col">
                                    <span class="font-bold text-sm">{{ $medicine->name }}</span>
                                    <span
                                        class="text-[10px] text-slate-400 font-medium italic">{{ $medicine->category?->name ?? 'Uncategorized' }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-300">
                                    {{ $medicine->generic_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex flex-col text-xs font-bold text-slate-600 dark:text-slate-300">
                                    <span>{{ $medicine->unit }}</span>
                                    <span class="text-slate-400 font-medium">{{ $medicine->dosage ?? '-' }}</span>
                                    @if($medicine->market_price)
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-1">
                                            ₹{{ $medicine->market_price }}
                                            @if($medicine->unit === 'Tablet' && $medicine->market_price_unit_count)
                                                / {{ $medicine->market_price_unit_count }} tablets
                                                <span class="block text-[9px] text-slate-500 font-medium">
                                                    (@₹{{ number_format($medicine->market_price / $medicine->market_price_unit_count, 2) }} / tab)
                                                </span>
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4">
                                @php
                                    $totalStock = $medicine->totalStock;
                                    $isLow = $totalStock <= $medicine->min_stock_level;
                                @endphp
                                <div class="flex flex-col">
                                    <span class="text-xs font-black {{ $isLow ? 'text-red-500' : 'text-emerald-500' }}">
                                        {{ $totalStock }} In Stock
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-medium mt-0.5">Min:
                                        {{ $medicine->min_stock_level }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('inventory.medicines.edit', $medicine->id) }}"
                                        class="p-2 text-slate-400 hover:text-accent transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('inventory.medicines.destroy', $medicine->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Delete this medicine registry?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-danger transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-10 text-center text-slate-500 font-medium">No medicines registered. Click
                                "+ Add Medicine" to start.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection