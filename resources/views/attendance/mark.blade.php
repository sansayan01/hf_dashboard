@extends('layouts.app')

@section('title', 'Mark Team Attendance')
@section('header_title', 'Mark Daily Attendance')

@section('content')
    <div class="p-6 max-w-5xl mx-auto overflow-y-auto h-full pb-20">
        <div
            class="bg-white dark:bg-darkcard rounded-3xl shadow-xl overflow-hidden border border-slate-200 dark:border-white/5">
            <div
                class="p-8 border-b border-slate-100 dark:border-white/5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-2xl font-bold text-slate-800 dark:text-white">Daily Roster</h3>
                    <p class="text-slate-500 text-sm font-medium mt-1">Marking attendance for
                        {{ now()->format('l, d M Y') }}</p>
                </div>
                <div class="px-4 py-2 bg-accent/10 rounded-2xl border border-accent/20">
                    <span class="text-accent font-bold text-sm">{{ now()->format('d M, Y') }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr
                            class="bg-slate-50/50 dark:bg-white/5 text-slate-500 dark:text-slate-400 text-xs uppercase tracking-widest font-bold">
                            <th class="px-8 py-4">Relationship Officer</th>
                            <th class="px-8 py-4 text-center">Incentive Plan</th>
                            <th class="px-8 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($ros as $ro)
                            @php
                                $todayAttendance = $ro->attendances()->whereDate('date', now()->toDateString())->first();
                                $config = $ro->getCurrentIncentive();
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div
                                            class="w-11 h-11 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-bold text-accent shadow-inner">
                                            {{ substr($ro->profile->full_name ?? $ro->employee_id, 0, 1) }}
                                        </div>
                                        <div>
                                            <p
                                                class="font-bold text-slate-800 dark:text-white group-hover:text-accent transition-colors">
                                                {{ $ro->profile->full_name ?? 'N/A' }}</p>
                                            <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                                                {{ $ro->employee_id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @if($config)
                                        <div class="inline-flex flex-col items-center">
                                            <span
                                                class="text-sm font-bold text-slate-700 dark:text-slate-200">₹{{ number_format($config->incentive_amount) }}
                                                + ₹{{ number_format($config->ta_amount) }} TA</span>
                                            <span
                                                class="text-[10px] text-emerald-500 font-bold uppercase tracking-wider mt-1">Active
                                                Plan</span>
                                        </div>
                                    @else
                                        <span class="text-rose-400 text-xs font-bold italic">No config set</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center justify-center space-x-3">
                                        @if($todayAttendance && $todayAttendance->isLocked())
                                            <span
                                                class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest {{ $todayAttendance->status === 'present' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                                {{ $todayAttendance->status }} (LOCKED)
                                            </span>
                                        @else
                                            <button onclick="markAttendance({{ $ro->id }}, 'present')"
                                                class="attendance-btn-{{ $ro->id }} px-5 py-2.5 rounded-xl text-xs font-bold transition-all {{ ($todayAttendance && $todayAttendance->status === 'present') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' : 'bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white' }}">
                                                Present
                                            </button>
                                            <button onclick="markAttendance({{ $ro->id }}, 'absent')"
                                                class="attendance-btn-{{ $ro->id }} px-5 py-2.5 rounded-xl text-xs font-bold transition-all {{ ($todayAttendance && $todayAttendance->status === 'absent') ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/30' : 'bg-rose-100 text-rose-600 hover:bg-rose-500 hover:text-white' }}">
                                                Absent
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-12 text-center text-slate-400 italic font-medium">No Relationship
                                    Officers found in your team.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        async function markAttendance(roId, status) {
            try {
                const response = await fetch('{{ route("attendance.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        user_id: roId,
                        status: status,
                        date: '{{ now()->toDateString() }}'
                    })
                });

                const data = await response.json();

                if (response.ok) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        position: 'top-end',
                        toast: true,
                        background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                        color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Something went wrong');
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Operation Failed',
                    text: error.message,
                    background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#f8fafc' : '#1e293b',
                });
            }
        }
    </script>
@endsection