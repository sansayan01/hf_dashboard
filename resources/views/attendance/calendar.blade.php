@extends('layouts.app')

@section('title', 'Attendance Dashboard')
@section('header_title', 'My Attendance')

@section('css')
    <style>
        .calendar-container {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 12px;
        }

        .calendar-day {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
        }

        .calendar-day:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .status-present {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
        }

        .status-absent {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
            color: white;
        }

        .status-future {
            background: rgba(255, 255, 255, 0.5);
            border: 1px dashed #cbd5e1;
            color: #94a3b8;
        }

        .dark .status-future {
            background: rgba(30, 41, 59, 0.5);
            border: 1px dashed rgba(255, 255, 255, 0.1);
            color: #475569;
        }

        .calendar-day-label {
            color: #64748b;
            font-size: 0.875rem;
            font-weight: 700;
            text-align: center;
            padding-bottom: 8px;
        }

        .indicator {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: white;
            margin-top: 4px;
            opacity: 0.8;
        }

        .summary-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 24px;
            transition: all 0.3s ease;
        }

        .dark .summary-card {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection

@section('content')
    <div class="p-6 space-y-8 max-w-7xl mx-auto overflow-y-auto h-full pb-20">
        <!-- Summary Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 {{ $user->isRO() ? 'lg:grid-cols-5' : 'lg:grid-cols-4' }} gap-6">
            <div class="summary-card p-6 flex flex-col items-center">
                <span class="text-emerald-600 text-3xl font-bold">{{ $summary['present'] }}</span>
                <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Present Days</span>
            </div>
            <div class="summary-card p-6 flex flex-col items-center">
                <span class="text-rose-600 text-3xl font-bold">{{ $summary['absent'] }}</span>
                <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Absent Days</span>
            </div>
            <div class="summary-card p-6 flex flex-col items-center">
                <span class="text-accent text-3xl font-bold">₹{{ number_format($summary['incentive'], 0) }}</span>
                <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">Incentives</span>
            </div>
            @if($user->isRO())
                <div class="summary-card p-6 flex flex-col items-center">
                    <span class="text-amber-600 text-3xl font-bold">₹{{ number_format($summary['ta'], 0) }}</span>
                    <span class="text-sm font-semibold text-slate-500 uppercase tracking-wider mt-2">TA Earned</span>
                </div>
            @endif
            <div
                class="summary-card p-6 flex flex-col @if(app()->getLocale() == 'en') lg:col-span-1 @endif items-center bg-accent/5 !border-accent/20">
                <span class="text-accent text-3xl font-extrabold">₹{{ number_format($summary['total'], 0) }}</span>
                <span class="text-sm font-bold text-accent uppercase tracking-wider mt-2">Total Combined</span>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="summary-card p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center">
                    <div class="w-12 h-12 rounded-2xl bg-accent/10 text-accent flex items-center justify-center mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-slate-800 dark:text-white leading-tight">
                            {{ $targetDate->format('F Y') }}
                        </h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Attendance Sheet</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    @php
                        $prevMonth = $targetDate->copy()->subMonth();
                        $nextMonth = $targetDate->copy()->addMonth();
                    @endphp
                    <a href="{{ request()->fullUrlWithQuery(['month' => $prevMonth->month, 'year' => $prevMonth->year]) }}"
                        class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['month' => now()->month, 'year' => now()->year]) }}"
                        class="px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold rounded-xl hover:bg-slate-200 transition-all">
                        Today
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['month' => $nextMonth->month, 'year' => $nextMonth->year]) }}"
                        class="p-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all">
                        <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div class="calendar-container">
                @php
                    $startOfMonth = $targetDate->copy()->startOfMonth();
                    $endOfMonth = $targetDate->copy()->endOfMonth();
                    $daysInMonth = $targetDate->daysInMonth;
                    $startDay = $startOfMonth->dayOfWeek; // 0 (Sun) to 6 (Sat)
                    $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                @endphp

                @foreach($days as $day)
                    <div class="calendar-day-label">{{ $day }}</div>
                @endforeach

                @for($i = 0; $i < $startDay; $i++)
                    <div></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        $currentDate = $startOfMonth->copy()->addDays($day - 1);
                        $dateStr = $currentDate->format('Y-m-d');

                        // Robust matching: ensure we compare date strings
                        $attendance = $allAttendances->first(function ($item) use ($dateStr) {
                            return $item->date->format('Y-m-d') === $dateStr;
                        });

                        $isToday = $currentDate->isToday();
                        $isFuture = $currentDate->isFuture() && !$isToday;

                        $statusClass = 'status-future';
                        if ($attendance) {
                            $statusClass = $attendance->status === 'present' ? 'status-present' : 'status-absent';
                        }
                    @endphp

                    <div class="calendar-day {{ $statusClass }} {{ $isToday ? 'ring-4 ring-accent ring-offset-2' : '' }}"
                        @if($attendance)
                            onclick="showDetails('{{ $currentDate->format('d M Y') }}', '{{ ucfirst($attendance->status) }}', '{{ $attendance->incentive_amount }}', '{{ $attendance->ta_amount }}', '{{ $attendance->medicines_amount }}', '{{ $attendance->pathology_amount }}', '{{ $attendance->membership_amount }}', '{{ $attendance->ots_amount }}', '{{ $attendance->total_amount }}', '{{ $attendance->markedBy->profile->full_name ?? 'System' }}', '{{ $attendance->created_at->format('H:i') }}')"
                        @elseif(!$isFuture && !$isToday)
                            onclick="showDetails('{{ $currentDate->format('d M Y') }}', 'Pending/Absent', 0, 0, 0, 0, 0, 0, 0, 'N/A', 'N/A')"
                        @endif>
                        {{ $day }}
                        @if($isToday)
                            <span class="text-[8px] absolute top-1 font-black opacity-60">TODAY</span>
                        @endif
                        @if($attendance && $attendance->status === 'present')
                            <div class="indicator"></div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
    </div>

    <script>
        function showDetails(date, status, incentive, ta, med, path, mem, ots, total, markedBy, time) {
            Swal.fire({
                title: `<span class="text-2xl font-bold">${date}</span>`,
                html: `
                                                <div class="text-left space-y-4 p-4">
                                                    <div class="flex justify-between items-center border-b border-slate-100 pb-2 dark:border-slate-700">
                                                        <span class="text-slate-500 font-medium">Status:</span>
                                                        <span class="px-3 py-1 rounded-full text-xs font-bold ${status === 'Present' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'}">
                                                            ${status}
                                                        </span>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-3">
                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Basic Inc.</span>
                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(incentive).toLocaleString()}</p>
                                                        </div>
                                                        @if($user->isRO())
                                                            <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                                <span class="text-[9px] text-slate-400 uppercase font-black">Daily TA</span>
                                                                <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(ta).toLocaleString()}</p>
                                                            </div>
                                                        @endif
                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Medicines</span>
                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(med).toLocaleString()}</p>
                                                        </div>
                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Pathology</span>
                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(path).toLocaleString()}</p>
                                                        </div>
                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                            <span class="text-[9px] text-slate-400 uppercase font-black">Membership</span>
                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(mem).toLocaleString()}</p>
                                                        </div>
                                                        <div class="bg-slate-50 dark:bg-slate-800/50 p-2 rounded-xl">
                                                            <span class="text-[9px] text-slate-400 uppercase font-black">OTs</span>
                                                            <p class="text-sm font-bold text-slate-700 dark:text-slate-200">₹${parseFloat(ots).toLocaleString()}</p>
                                                        </div>
                                                    </div>
                                                    <div class="bg-accent/10 p-4 rounded-xl flex justify-between items-center">
                                                        <span class="font-bold text-accent">Total Earning</span>
                                                        <span class="text-xl font-black text-accent">₹${parseFloat(total).toLocaleString()}</span>
                                                    </div>
                                                    <div class="pt-2 text-center">
                                                        <p class="text-[11px] text-slate-400">Marked by <span class="text-slate-500 font-semibold">${markedBy}</span> at ${time}</p>
                                                    </div>
                                                </div>
                                            `,
                showConfirmButton: false,
                showCloseButton: true,
                background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                borderRadius: '24px'
            });
        }
    </script>
@endsection