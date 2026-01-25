@extends('layouts.app')

@section('title', 'Attendance Report - ' . ($user->profile->full_name ?? 'User'))
@section('header_title', 'Attendance Report')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-16 px-4">
        @php
            $currentMonth = now();
            if (request('month')) {
                try {
                    $currentMonth = \Carbon\Carbon::createFromFormat('Y-m', request('month'));
                } catch (\Exception $e) {
                    $currentMonth = now();
                }
            }
            $prevMonth = $currentMonth->copy()->subMonth();
            $nextMonth = $currentMonth->copy()->addMonth();

            $startOfMonth = $currentMonth->copy()->startOfMonth();
            $endOfMonth = $currentMonth->copy()->endOfMonth();
            $daysInMonth = $currentMonth->daysInMonth;
            $firstDayOfWeek = $startOfMonth->dayOfWeek;

            $monthAttendances = $attendances->whereBetween('date', [$startOfMonth->format('Y-m-d'), $endOfMonth->format('Y-m-d')]);
            $presentCount = $monthAttendances->where('status', 'present')->count();
            $absentCount = $monthAttendances->where('status', 'absent')->count();
            $totalMarked = $presentCount + $absentCount;
            $rate = $totalMarked > 0 ? round(($presentCount / $totalMarked) * 100) : 0;
        @endphp

        <div class="max-w-5xl mx-auto">
            <!-- Hanging Calendar Container -->
            <div class="relative">
                <!-- Calendar Hooks (Dark Gray Circles) -->
                <div
                    class="absolute -top-8 left-24 w-14 h-24 bg-[#2d2d2d] rounded-full shadow-2xl z-50 border-4 border-white">
                </div>
                <div
                    class="absolute -top-8 right-24 w-14 h-24 bg-[#2d2d2d] rounded-full shadow-2xl z-50 border-4 border-white">
                </div>

                <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_rgba(0,0,0,0.15)] overflow-hidden">

                    <!-- Green Header Section -->
                    <div class="bg-[#789634] pt-24 pb-12 text-center relative">
                        <!-- Month Navigation -->
                        <a href="{{ route('attendance.show', ['user' => $user->id, 'month' => $prevMonth->format('Y-m')]) }}"
                            class="absolute left-8 top-1/2 -translate-y-1/2 w-14 h-14 bg-black/10 hover:bg-black/20 rounded-full flex items-center justify-center text-white transition-all z-20 group">
                            <svg class="w-8 h-8 group-hover:-translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                        <a href="{{ route('attendance.show', ['user' => $user->id, 'month' => $nextMonth->format('Y-m')]) }}"
                            class="absolute right-8 top-1/2 -translate-y-1/2 w-14 h-14 bg-black/10 hover:bg-black/20 rounded-full flex items-center justify-center text-white transition-all z-20 group">
                            <svg class="w-8 h-8 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>

                        <!-- Year and Month -->
                        <div class="text-white select-none space-y-2">
                            <h2 class="text-6xl font-black tracking-[0.2em] opacity-95">{{ $currentMonth->format('Y') }}
                            </h2>
                            <h1 class="text-9xl font-black uppercase tracking-tighter leading-none">
                                {{ $currentMonth->format('F') }}</h1>
                        </div>
                    </div>

                    <!-- Dark Green Weekday Bar -->
                    <div class="bg-[#4a5d2a] py-5">
                        <div class="grid grid-cols-7 gap-0">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                <div class="text-white text-lg font-black text-center uppercase tracking-widest">{{ $day }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Calendar Grid -->
                    <div class="p-16 bg-white">
                        <div class="grid grid-cols-7 gap-8">
                            <!-- Empty cells before first day of month -->
                            @for($i = 0; $i < $firstDayOfWeek; $i++)
                                <div></div>
                            @endfor

                            <!-- Days of the month -->
                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $dateStr = $currentMonth->format('Y-m-') . str_pad($day, 2, '0', STR_PAD_LEFT);
                                    $attendance = $attendances->firstWhere('date', $dateStr);
                                    $isToday = $dateStr === date('Y-m-d');

                                    // Circle colors matching reference image
                                    if ($attendance) {
                                        if ($attendance->status === 'present') {
                                            $bgColor = 'bg-[#8b9a6b]';
                                            $textColor = 'text-[#4a5d2a]';
                                        } else {
                                            $bgColor = 'bg-[#d97b8f]';
                                            $textColor = 'text-[#8b3a52]';
                                        }
                                    } else {
                                        $bgColor = 'bg-[#8b9a6b]';
                                        $textColor = 'text-[#4a5d2a]';
                                    }
                                @endphp
                                <div class="flex items-center justify-center">
                                    <div
                                        class="w-20 h-20 rounded-full {{ $bgColor }} {{ $textColor }} flex items-center justify-center text-4xl font-black shadow-lg transition-all hover:scale-110 cursor-default select-none {{ $isToday ? 'ring-4 ring-[#789634] ring-offset-4' : '' }}">
                                        {{ $day }}
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <!-- Footer with Stats and Legend -->
                        <div class="mt-20 pt-12 border-t-4 border-slate-100">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                                <!-- Legend -->
                                <div class="flex items-center justify-center lg:justify-start space-x-10">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-full bg-[#8b9a6b] shadow-lg"></div>
                                        <span
                                            class="text-base font-black text-slate-600 uppercase tracking-widest">Present</span>
                                    </div>
                                    <div class="flex items-center space-x-4">
                                        <div class="w-10 h-10 rounded-full bg-[#d97b8f] shadow-lg"></div>
                                        <span
                                            class="text-base font-black text-slate-600 uppercase tracking-widest">Absent</span>
                                    </div>
                                </div>

                                <!-- Stats Card -->
                                <div
                                    class="bg-gradient-to-br from-[#789634]/5 to-[#789634]/10 rounded-3xl p-10 border-2 border-[#789634]/20">
                                    <div class="grid grid-cols-2 gap-8">
                                        <div class="text-center">
                                            <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                                                Success Rate</p>
                                            <p class="text-5xl font-black text-[#789634]">{{ $rate }}%</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-3">
                                                This Month</p>
                                            <div class="flex items-center justify-center space-x-3 text-2xl font-black">
                                                <span class="text-[#8b9a6b]">{{ $presentCount }}P</span>
                                                <span class="text-slate-300">/</span>
                                                <span class="text-[#d97b8f]">{{ $absentCount }}A</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back Button -->
            <div class="mt-16 text-center">
                <a href="{{ route('users.show', $user->id) }}"
                    class="inline-flex items-center space-x-4 px-10 py-5 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-black uppercase tracking-[0.2em] text-sm shadow-2xl transition-all hover:scale-105 active:scale-95 group">
                    <svg class="w-6 h-6 group-hover:-translate-x-2 transition-transform" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Back to Profile</span>
                </a>
            </div>
        </div>
    </div>
@endsection