@if(count($pendingUsers) > 0)
    <div
        class="glass bg-white dark:bg-darkbg/40 rounded-3xl border border-rose-200/20 dark:border-rose-500/10 shadow-xl overflow-hidden mb-8 transition-all relative z-20">
        <div
            class="p-6 border-b border-rose-100 dark:border-white/5 bg-rose-50/30 dark:bg-rose-500/5 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-rose-500/10 text-rose-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-black text-lg text-slate-800 dark:text-white tracking-tight">Pending Approvals</h3>
                    <p class="text-[10px] text-rose-400 font-bold uppercase tracking-widest">Action Required</p>
                </div>
            </div>
            <a href="{{ route('users.index', ['status' => 'pending']) }}"
                class="text-[10px] font-black text-rose-500 hover:underline uppercase tracking-widest">View All</a>
        </div>
        <div class="p-0">
            <div class="divide-y divide-slate-100 dark:divide-white/5">
                @foreach($pendingUsers as $p)
                    <div
                        class="p-4 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-10 h-10 rounded-full bg-accent/5 text-accent flex items-center justify-center font-bold text-sm border border-slate-100 dark:border-white/5">
                                @if($p->profile?->profile_picture)
                                    <img src="{{ $p->profile->getProfilePictureUrl() }}"
                                        class="w-full h-full rounded-full object-cover">
                                @else
                                    {{ substr($p->profile?->full_name ?? $p->employee_id, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-black text-slate-800 dark:text-white leading-none mb-1">
                                    {{ $p->profile?->full_name ?? 'Incomplete' }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $p->employee_id }} •
                                    {{ $p->getDesignationLabel() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <form action="{{ route('users.approve', $p->id) }}" method="POST" class="no-loader">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/20">
                                    Approve
                                </button>
                            </form>
                            <a href="{{ route('users.show', $p->id) }}"
                                class="p-1.5 text-slate-400 hover:text-accent transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif