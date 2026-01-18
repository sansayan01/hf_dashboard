@extends('layouts.app')

@section('title', 'Mark Attendance')
@section('header_title', 'Mark Attendance')

@section('content')
    <div class="max-w-6xl mx-auto">
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-white/5 overflow-hidden">
            <div
                class="p-6 border-b border-slate-200 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Daily Attendance</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400">Mark attendance for Relationship Officers (ROs)
                    </p>
                </div>

                <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date }}"
                        class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-accent outline-none"
                        onchange="this.form.submit()">
                </form>
            </div>

            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 dark:bg-slate-900/50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Employee</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                    Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                            @forelse($ros as $ro)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-3">
                                            <div
                                                class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center text-accent font-bold text-xs">
                                                {{ substr($ro->profile->full_name ?? $ro->employee_id, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800 dark:text-white">
                                                    {{ $ro->profile->full_name ?? 'RO' }}</p>
                                                <p class="text-[10px] text-slate-500 font-medium">{{ $ro->employee_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            @php
                                                $currentStatus = $attendances[$ro->id]->status ?? 'present';
                                            @endphp
                                            <select name="attendance[{{ $ro->id }}][status]"
                                                class="bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl px-3 py-1.5 text-xs focus:ring-2 focus:ring-accent outline-none">
                                                <option value="present" {{ $currentStatus == 'present' ? 'selected' : '' }}>
                                                    Present</option>
                                                <option value="absent" {{ $currentStatus == 'absent' ? 'selected' : '' }}>Absent
                                                </option>
                                                <option value="half_day" {{ $currentStatus == 'half_day' ? 'selected' : '' }}>Half
                                                    Day</option>
                                                <option value="leave" {{ $currentStatus == 'leave' ? 'selected' : '' }}>Leave
                                                </option>
                                                <option value="holiday" {{ $currentStatus == 'holiday' ? 'selected' : '' }}>
                                                    Holiday</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <input type="text" name="attendance[{{ $ro->id }}][remarks]"
                                            value="{{ $attendances[$ro->id]->remarks ?? '' }}" placeholder="Add notes..."
                                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-1.5 text-xs focus:ring-2 focus:ring-accent outline-none">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-slate-300 mb-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                                </path>
                                            </svg>
                                            <p class="text-slate-500 font-medium">No Relationship Officers found reporting to
                                                you.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($ros->count() > 0)
                    <div
                        class="p-6 bg-slate-50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-white/5 flex justify-end">
                        <button type="submit"
                            class="bg-accent text-white px-8 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-accent/20 hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Save Attendance
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection