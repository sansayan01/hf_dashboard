@extends('layouts.app')

@section('title', 'Attendance History')
@section('header_title', 'Attendance History')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-white/5 p-6">
            <form action="{{ route('attendance.history') }}" method="GET" class="flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Start Date</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-accent outline-none">
                </div>
                <div class="flex-1 space-y-1">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">End Date</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-white/10 rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-accent outline-none">
                </div>
                <button type="submit"
                    class="bg-accent text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-md hover:bg-accent/90 transition-all">
                    Filter Results
                </button>
            </form>
        </div>

        <!-- Results -->
        <div
            class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-white/5 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-900/50">
                        <tr>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Date</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Employee</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Status</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Recorded By</th>
                            <th
                                class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                                Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($attendances as $attendance)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors text-sm">
                                <td class="px-6 py-4 font-medium text-slate-800 dark:text-white">
                                    {{ $attendance->date->format('d M, Y') }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">
                                    {{ $attendance->user->profile->full_name ?? $attendance->user->employee_id }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClasses = [
                                            'present' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                            'absent' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                            'half_day' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                            'leave' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                            'holiday' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                        ];
                                        $class = $statusClasses[$attendance->status] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $class }}">
                                        {{ str_replace('_', ' ', $attendance->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                    {{ $attendance->recorder->profile->full_name ?? $attendance->recorder->employee_id }}
                                </td>
                                <td class="px-6 py-4 italic text-slate-500 dark:text-slate-400">
                                    {{ $attendance->remarks ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    No attendance records found for the selected period.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($attendances->hasPages())
                <div class="p-6 border-t border-slate-200 dark:border-white/5">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection