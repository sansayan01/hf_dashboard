@extends('layouts.app')

@section('title', 'Membership Details')

@section('content')
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-8">
            <div class="flex items-center space-x-4 mb-8">
                <div class="w-12 h-12 bg-amber-500/10 text-amber-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-800 dark:text-white">Membership Status</h1>
                    <p class="text-slate-500">For Patient: <span
                            class="font-bold text-slate-700 dark:text-slate-300">{{ $patient->full_name }}</span></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="p-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Registration Fee</p>
                    <p class="text-xl font-black text-emerald-600 dark:text-emerald-400">
                        ₹{{ number_format($patient->membership_fee, 2) }}</p>
                </div>
                <div class="p-6 bg-slate-50 dark:bg-white/5 rounded-2xl border border-slate-100 dark:border-white/5">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Payment Method</p>
                    <p class="text-xl font-black text-slate-700 dark:text-white">{{ $patient->payment_method ?? 'N/A' }}</p>
                </div>
            </div>

            @if($patient->payment_screenshot)
                <div class="mt-8">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Payment Screenshot</p>
                    <div class="relative group max-w-sm">
                        <img src="{{ asset('storage/' . $patient->payment_screenshot) }}"
                            class="rounded-3xl border-4 border-white dark:border-white/5 shadow-xl group-hover:scale-[1.02] transition-transform cursor-zoom-in"
                            onclick="window.open(this.src)">
                        <div
                            class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-3xl flex items-center justify-center pointer-events-none">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                            </svg>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-8 p-6 bg-amber-500/5 rounded-2xl border border-dashed border-amber-500/20">
                <p class="text-slate-600 dark:text-slate-400 font-medium italic">Premium membership details and active care
                    plan records are being tracked under this profile.</p>
            </div>

            <div class="mt-8">
                <a href="{{ route('patients.show', $patient->id) }}"
                    class="text-sm font-bold text-slate-500 hover:text-slate-800 dark:hover:text-white transition">
                    &larr; Back to Patient Profile
                </a>
            </div>
        </div>
    </div>
@endsection