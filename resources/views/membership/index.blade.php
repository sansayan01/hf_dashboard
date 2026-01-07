@extends('layouts.app')

@section('title', 'Membership')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-100 dark:border-white/5 shadow-sm p-8">
            <div class="border-b border-slate-100 dark:border-white/5 pb-6 mb-6">
                <h1 class="text-2xl font-black text-slate-800 dark:text-white">Premium Membership</h1>
                <p class="text-slate-500 mt-2">Manage premium patients requiring extra care.</p>
            </div>

            <div class="text-center py-12">
                <div
                    class="w-16 h-16 bg-amber-500/10 text-amber-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Coming Soon</h3>
                <p class="text-slate-500 max-w-md mx-auto">The membership management module is currently under development.
                </p>
            </div>
        </div>
    </div>
@endsection