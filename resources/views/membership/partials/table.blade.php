@foreach($patients as $patient)
    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
        <td class="p-6">
            <div class="flex items-center space-x-4">
                <div
                    class="w-10 h-10 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl flex items-center justify-center text-sm font-black">
                    {{ substr($patient->full_name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-black text-slate-800 dark:text-white text-sm">{{ $patient->full_name }}</h4>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-tight">
                        {{ $patient->relative_name ?? 'N/A' }}</p>
                </div>
            </div>
        </td>
        <td class="p-6">
            <div class="space-y-1">
                <p class="text-xs font-black text-slate-700 dark:text-slate-200">{{ $patient->patient_id }}</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ ucfirst($patient->gender) }}
                </p>
            </div>
        </td>
        <td class="p-6">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $patient->phone_number }}</p>
                <p class="text-[10px] text-slate-400 font-medium truncate max-w-[150px]">{{ $patient->address }}</p>
            </div>
        </td>
        <td class="p-6">
            <p class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $patient->created_at->format('d M, Y') }}</p>
            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">
                {{ $patient->created_at->diffForHumans() }}</p>
        </td>
        <td class="p-6">
            @php $hasAppointment = $patient->appointments->isNotEmpty(); @endphp
            <span
                class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $hasAppointment ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500' }}">
                {{ $hasAppointment ? 'Active' : 'Pending Care' }}
            </span>
        </td>
        <td class="p-6 text-right">
            <div class="flex items-center justify-end space-x-2">
                <a href="{{ route('patients.show', $patient->id) }}"
                    class="p-2 bg-slate-100 dark:bg-white/5 text-slate-500 hover:text-accent transition rounded-lg"
                    title="View Profile">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                <a href="{{ route('patients.membership', $patient->id) }}"
                    class="p-2 bg-amber-500/10 text-amber-600 transition rounded-lg" title="Membership Details">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </a>
                <a href="{{ route('membership.card.download', $patient->id) }}"
                    class="p-2 bg-emerald-500/10 text-emerald-600 transition rounded-lg" title="Download PVC Card">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </a>
                <a href="{{ route('membership.card.preview', $patient->id) }}" target="_blank"
                    class="p-2 bg-indigo-500/10 text-indigo-600 transition rounded-lg" title="Preview Card">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </a>
                @if (auth()->user()->isSuperAdmin())
                    <form action="{{ route('patients.membership.cancel', $patient->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to cancel this membership? The patient will be moved to the regular patient section.')">
                        @csrf
                        <button type="submit"
                            class="p-2 bg-orange-500/10 text-orange-500 hover:bg-orange-500 hover:text-white transition rounded-lg"
                            title="Cancel Membership">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </button>
                    </form>
                @endif
                @if (auth()->user()->id === $patient->created_by || auth()->user()->canAccess($patient->creator))
                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST"
                        onsubmit="return confirm('Move this member record to BIN?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="p-2 bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white transition rounded-lg"
                            title="Delete Member">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                @endif
            </div>
        </td>
    </tr>
@endforeach