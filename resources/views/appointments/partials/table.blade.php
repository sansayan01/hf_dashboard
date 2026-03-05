@foreach($appointments as $appointment)
    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
        <td class="p-6">
            <span
                class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $appointment->appointment_id }}</span>
        </td>
        <td class="p-6">
            <div class="flex items-center space-x-4">
                @if($appointment->survey)
                    <div
                        class="w-10 h-10 bg-accent/10 text-accent dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black">
                        {{ substr($appointment->survey->full_name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="font-black text-slate-800 dark:text-white text-sm">{{ $appointment->survey->full_name }}</h4>
                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight mt-0.5">
                            {{ $appointment->survey->patient_id }}</p>
                    </div>
                @else
                    <div
                        class="w-10 h-10 bg-slate-100 text-slate-400 rounded-xl flex items-center justify-center text-sm font-black">
                        ?</div>
                    <div>
                        <h4 class="font-black text-slate-400 text-sm">Deleted Patient</h4>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight mt-0.5">N/A</p>
                    </div>
                @endif
            </div>
        </td>
        <td class="p-6">
            <div class="flex items-center space-x-3">
                <div
                    class="flex-shrink-0 w-10 h-10 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-100 dark:border-white/5 flex flex-col items-center justify-center text-[8px] font-black uppercase">
                    <span class="text-accent">{{ $appointment->appointment_date->format('M') }}</span>
                    <span
                        class="text-slate-600 dark:text-white text-xs">{{ $appointment->appointment_date->format('d') }}</span>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-800 dark:text-white">
                        {{ $appointment->appointment_date->format('Y') }}</p>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                        {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                    </p>
                </div>
            </div>
        </td>
        <td class="p-6">
            <span
                class="px-3 py-1.5 bg-indigo-500/10 text-indigo-500 rounded-lg text-[10px] font-black uppercase tracking-wider border border-indigo-500/10">
                {{ $appointment->doctor_type }}
            </span>
        </td>
        <td class="p-6">
            <div class="flex items-center space-x-2 text-slate-600 dark:text-slate-300">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-xs font-bold">{{ $appointment->location }}</span>
            </div>
            @if($appointment->status === 'missed_reported')
                <span
                    class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-amber-100 text-amber-700 uppercase tracking-tighter">Pending
                    Confirmation</span>
            @elseif($appointment->status === 'not_attended')
                <span
                    class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[8px] font-black bg-rose-100 text-rose-700 uppercase tracking-tighter">Confirmed
                    Missed</span>
            @endif
        </td>
        <td class="p-6">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg overflow-hidden ring-2 ring-slate-100 dark:ring-slate-800">
                    @if($appointment->creator && $appointment->creator->profile && $appointment->creator->profile->profile_picture)
                        <img src="{{ $appointment->creator->profile->getProfilePictureUrl() }}"
                            class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-[10px] font-black">
                            {{ substr($appointment->creator->profile->full_name ?? ($appointment->creator->employee_id ?? 'U'), 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    @if(auth()->user()->isSuperAdmin() && $appointment->creator)
                        <a href="{{ route('users.show', $appointment->creator->id) }}"
                            class="text-xs font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                            <span>{{ $appointment->creator->profile->full_name ?? ($appointment->creator->employee_id ?? 'Unknown User') }}</span>
                            <svg class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-200">
                            {{ $appointment->creator->profile->full_name ?? ($appointment->creator->employee_id ?? 'Unknown User') }}
                        </p>
                    @endif
                    <p class="text-[10px] font-medium text-slate-400">{{ $appointment->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </td>
        <td class="p-6 text-right space-x-2 whitespace-nowrap">
            @if($appointment->status === 'scheduled' && (auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge()))
                <form action="{{ route('appointments.complete', $appointment->id) }}" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to mark this appointment as successful?')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500/10 hover:bg-emerald-500 hover:text-white text-emerald-500 transition-all border border-emerald-500/10"
                        title="Mark as Successful">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </form>
                <form action="{{ route('appointments.report_missed', $appointment->id) }}" method="POST" class="inline"
                    onsubmit="return confirm('Report this appointment as missed?')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 transition-all border border-rose-500/10"
                        title="Report Missed">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
            @endif
            @if($appointment->status === 'missed_reported' && (auth()->user()->isSuperAdmin() || auth()->user()->isOfficeInCharge()))
                <form action="{{ route('appointments.complete', $appointment->id) }}" method="POST" class="inline"
                    onsubmit="return confirm('Confirm this appointment as SUCCESSFUL?')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-all shadow-lg shadow-emerald-500/20"
                        title="Confirm Success">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </form>
                <form action="{{ route('appointments.confirm_missed', $appointment->id) }}" method="POST" class="inline"
                    onsubmit="return confirm('Confirm this appointment as NOT ATTENDED?')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500 hover:bg-rose-600 text-white transition-all shadow-lg shadow-rose-500/20"
                        title="Confirm Not Attended">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </form>
            @endif
            @if($appointment->status === 'scheduled' || $appointment->status === 'missed_reported')
                <a href="{{ route('patients.appointments.edit', $appointment->id) }}"
                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-500/10 hover:bg-amber-500 hover:text-white text-amber-500 transition-all border border-amber-500/10"
                    title="Edit Appointment">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form action="{{ route('patients.appointments.destroy', $appointment->id) }}" method="POST" class="inline"
                    onsubmit="return confirm('Are you sure you want to delete this appointment?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-500/10 hover:bg-rose-500 hover:text-white text-rose-500 transition-all border border-rose-500/10"
                        title="Delete Appointment">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            @endif
            <a href="{{ route('patients.show', $appointment->survey_id) }}"
                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-accent hover:text-white dark:bg-slate-800 dark:hover:bg-accent text-slate-400 transition-all border border-transparent"
                title="View Patient Profile">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </a>
        </td>
    </tr>
@endforeach