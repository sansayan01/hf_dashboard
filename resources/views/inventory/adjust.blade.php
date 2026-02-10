@extends('layouts.app')

@section('title', 'Manual Stock Adjustment')
@section('header_title', 'NGO Pharmacy | Adjust Stock')

@section('content')
    <div class="max-w-2xl mx-auto">
        <div
            class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-xl overflow-hidden text-slate-800 dark:text-white">
            <div class="p-8 border-b border-slate-100 dark:border-white/5 bg-accent/5">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-accent/10 rounded-2xl flex items-center justify-center text-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-xl">Stock Adjustment</h3>
                        <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Correct Stock Discrepancy</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('inventory.process-adjust', $stock->id) }}" method="POST" class="p-8 space-y-6">
                @csrf

                <!-- Basic Stock Info -->
                <div
                    class="grid grid-cols-2 gap-4 p-4 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                    <div>
                        <span
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Medicine</span>
                        <span class="text-sm font-bold">{{ $stock->medicine->name }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Batch
                            Number</span>
                        <code
                            class="text-xs px-2 py-1 bg-white dark:bg-slate-800 rounded-lg border border-slate-100 dark:border-white/5 font-bold">#{{ $stock->batch_number }}</code>
                    </div>
                    <div>
                        <span
                            class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Warehouse</span>
                        <span class="text-xs font-bold">{{ $stock->warehouse->name }}</span>
                    </div>
                    <div>
                        <span class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Current
                            Stock</span>
                        <span class="text-sm font-black text-accent">{{ $stock->quantity }}
                            {{ $stock->medicine->unit }}s</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">New Total
                            Quantity (Units)</label>
                        <input type="number" name="new_quantity" value="{{ $stock->quantity }}" required min="0"
                            placeholder="Enter new total quantity"
                            class="w-full h-14 px-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-base font-bold focus:ring-4 focus:ring-accent/10 focus:border-accent outline-none transition shadow-sm">
                        <p class="mt-2 text-[10px] text-slate-500 font-bold italic">* This will set the total available
                            units to this exact number.</p>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Reason for
                            Adjustment</label>
                        <textarea name="notes" required
                            placeholder="e.g., Physical count mismatch, Damages found, Recovery of missing items..."
                            class="w-full h-32 p-5 rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm font-medium focus:ring-4 focus:ring-accent/10 focus:border-accent outline-none transition shadow-sm"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4">
                    <a href="{{ route('inventory.index') }}"
                        class="text-sm font-bold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                        Cancel & Go Back
                    </a>
                    <button type="submit"
                        class="h-14 px-10 rounded-2xl bg-accent text-white font-bold hover:scale-[1.02] active:scale-[0.98] transition-all shadow-xl shadow-accent/25 flex items-center space-x-2">
                        <span>Save Adjustment</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection