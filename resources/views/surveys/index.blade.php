@extends('layouts.app')

@section('title', 'Surveys')
@section('header_title', 'Field Survey Management')

@section('content')
    <div class="space-y-8">
        <!-- Header Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h3 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Active Surveys</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Monitoring {{ $surveys->count() }} total field records</p>
            </div>
            <a href="{{ route('surveys.create') }}" 
               class="inline-flex items-center justify-center px-8 py-4 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-xl shadow-accent/20 hover:scale-105 active:scale-95 transition-all space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>New Survey Record</span>
            </a>
        </div>

        <!-- Search Bar -->
        <div class="glass bg-white dark:bg-darkbg/40 p-4 md:p-6 rounded-2xl border border-slate-200/10 dark:border-white/5 shadow-sm">
            <form action="{{ route('surveys.index') }}" method="GET" class="flex flex-col md:flex-row items-center gap-4">
                <div class="flex-1 w-full relative">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search by Patient Name, Phone, or Collector..."
                        class="w-full pl-12 pr-4 py-4 bg-slate-100/50 dark:bg-slate-800 border-2 border-transparent focus:border-accent focus:bg-white dark:focus:bg-slate-700 rounded-2xl transition-all outline-none text-sm font-bold text-slate-700 dark:text-white">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3 w-full md:w-auto">
                    <button type="submit" class="flex-1 md:flex-none px-8 py-4 bg-accent text-white font-black uppercase tracking-widest text-[10px] rounded-2xl shadow-lg shadow-accent/20 hover:scale-105 active:scale-95 transition-all">
                        Search
                    </button>
                    @if(request()->filled('search'))
                        <a href="{{ route('surveys.index') }}" class="px-6 py-4 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-danger rounded-2xl transition-all text-[10px] font-black uppercase tracking-widest border border-transparent hover:border-danger/20">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-6 py-4 rounded-2xl font-bold text-sm">
                {{ session('success') }}
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
            <div class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-slate-200/10 dark:border-white/5 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/5">
                                <th class="p-6 text-[10px] font-black uppercase tracking-widest text-slate-400">Participant</th>
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
                                            <a href="{{ route('surveys.appointments.create', $survey->id) }}" class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20 text-[10px] font-black uppercase tracking-wider transition-colors">
                                                Create
                                            </a>
                                            <a href="{{ route('surveys.appointments.index', $survey->id) }}" class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 text-[10px] font-black uppercase tracking-wider transition-colors">
                                                View
                                            </a>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-8 h-8 rounded-lg overflow-hidden ring-2 ring-slate-100 dark:ring-slate-800">
                                                @if($survey->creator->profile && $survey->creator->profile->profile_picture)
                                                    <img src="{{ asset('storage/' . $survey->creator->profile->profile_picture) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-[10px] font-black">
                                                        {{ substr($survey->creator->profile->full_name ?? 'U', 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-slate-700 dark:text-slate-200">{{ $survey->creator->profile->full_name ?? 'Unknown' }}</p>
                                                <p class="text-[10px] font-medium text-slate-400">{{ $survey->created_at->format('M d, Y') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6 text-right">
                                        @if(Auth::id() === $survey->created_by || Auth::user()->canAccess($survey->creator))
                                            <a href="{{ route('surveys.edit', $survey->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-accent hover:text-white dark:bg-slate-800 dark:hover:bg-accent text-slate-400 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
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
        @endif
    </div>
@endsection