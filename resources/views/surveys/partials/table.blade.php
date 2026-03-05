@foreach($surveys as $survey)
    <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors group">
        @if(Auth::user()->isSuperAdmin())
            <td class="p-6 w-10">
                <input type="checkbox"
                    class="survey-checkbox w-5 h-5 rounded border-slate-300 text-accent focus:ring-accent accent-accent transition-all cursor-pointer"
                    data-id="{{ $survey->id }}">
            </td>
        @endif
        <td class="p-6">
            <div class="flex items-center space-x-4">
                <div
                    class="w-10 h-10 bg-accent/10 text-accent dark:text-blue-400 rounded-xl flex items-center justify-center text-sm font-black">
                    {{ substr($survey->full_name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-black text-slate-800 dark:text-white text-sm">{{ $survey->full_name }}</h4>
                    <div
                        class="flex items-center space-x-2 text-[10px] text-slate-500 font-bold uppercase tracking-tight mt-0.5">
                        <span>{{ ucfirst($survey->gender) }}</span>
                    </div>
                </div>
            </div>
        </td>
        @if(auth()->user()->designation !== 'staff')
            <td class="p-6">
                @if($survey->is_member)
                    <span
                        class="inline-flex items-center px-4 py-2 bg-emerald-500/10 text-emerald-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-emerald-500/10">
                        Member
                    </span>
                @elseif(auth()->user()->designation !== 'staff')
                    <a href="{{ route('patients.membership', $survey->id) }}"
                        class="inline-flex items-center space-x-2 px-4 py-2 bg-amber-500/10 text-amber-500 rounded-xl hover:bg-amber-500 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest border border-amber-500/10 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <span>Become Member</span>
                    </a>
                @else
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">Non-Member</span>
                @endif
            </td>
        @endif
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
                <div
                    class="inline-flex items-center space-x-2 px-3 py-1.5 bg-amber-500/10 text-amber-500 rounded-lg border border-amber-500/10">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-500 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                    </span>
                    <span
                        class="text-[10px] font-black uppercase tracking-wider">{{ Str::limit($survey->health_issues, 20) }}</span>
                </div>
            @else
                <span
                    class="inline-flex items-center px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-lg text-[10px] font-black uppercase tracking-wider">
                    Normal
                </span>
            @endif
        </td>
        <td class="p-6">
            <div class="flex items-center space-x-1">
                <a href="{{ route('patients.appointments.create', $survey->id) }}"
                    class="px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-400 dark:hover:bg-indigo-500/20 text-[10px] font-black uppercase tracking-wider transition-colors">
                    Create
                </a>
                <a href="{{ route('patients.appointments.index', $survey->id) }}"
                    class="px-3 py-1.5 rounded-lg bg-slate-50 text-slate-500 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-400 dark:hover:bg-slate-700 text-[10px] font-black uppercase tracking-wider transition-colors">
                    View
                </a>
            </div>
        </td>
        <td class="p-6">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-lg overflow-hidden ring-2 ring-slate-100 dark:ring-slate-800">
                    @if($survey->creator->profile && $survey->creator->profile->profile_picture)
                        <img src="{{ $survey->creator->profile->getProfilePictureUrl() }}" class="w-full h-full object-cover">
                    @else
                        <div
                            class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-[10px] font-black">
                            {{ substr($survey->creator->profile->full_name ?? 'U', 0, 1) }}
                        </div>
                    @endif
                </div>
                <div>
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('users.show', $survey->creator->id) }}"
                            class="text-xs font-bold text-accent hover:text-accent/80 transition-colors inline-flex items-center space-x-1 group">
                            <span>{{ $survey->creator->profile->full_name ?? 'Unknown' }}</span>
                            <svg class="w-2.5 h-2.5 opacity-0 group-hover:opacity-100 group-hover:translate-x-0.5 transition-all"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <p class="text-xs font-bold text-slate-700 dark:text-slate-200">
                            {{ $survey->creator->profile->full_name ?? 'Unknown' }}</p>
                    @endif
                    <p class="text-[10px] font-medium text-slate-400">{{ $survey->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </td>
        <td class="p-6 text-right">
            @if(Auth::id() === $survey->created_by || Auth::user()->canAccess($survey->creator))
                <div class="flex items-center justify-end space-x-2">
                    <a href="{{ route('surveys.edit', $survey->id) }}"
                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-accent hover:text-white dark:bg-slate-800 dark:hover:bg-accent text-slate-400 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </a>
                    <form action="{{ route('surveys.destroy', $survey->id) }}" method="POST" class="inline-block"
                        onsubmit="return confirm('Are you sure you want to delete this survey?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-red-500 hover:text-white dark:bg-slate-800 dark:hover:bg-red-500 text-slate-400 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </form>
                </div>
            @endif
        </td>
    </tr>
@endforeach