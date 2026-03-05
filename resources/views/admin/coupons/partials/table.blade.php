@foreach($coupons as $coupon)
    <tr class="group/row hover:bg-accent/5 dark:hover:bg-accent/5 transition-colors">
        <td class="px-8 py-6">
            <div class="flex flex-col">
                <code class="text-sm font-black text-accent tracking-tighter">{{ $coupon->code }}</code>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter mt-1 items-center flex gap-1">
                    <i class="far fa-calendar-alt text-[8px]"></i>
                    {{ $coupon->created_at->format('M d, Y • h:i A') }}
                </span>
            </div>
        </td>
        <td class="px-8 py-6">
            @if($coupon->designation)
                <div
                    class="inline-flex items-center px-3 py-1 bg-slate-100 dark:bg-white/10 rounded-full text-[10px] font-black text-slate-500 dark:text-slate-300 uppercase tracking-widest">
                    {{ $coupon->designation }}
                </div>
            @else
                <div
                    class="inline-flex items-center px-3 py-1 bg-blue-500/10 rounded-full text-[10px] font-black text-blue-500 uppercase tracking-widest">
                    Universal
                </div>
            @endif
        </td>
        <td class="px-8 py-6 text-right">
            <span
                class="text-lg font-black text-slate-800 dark:text-white tracking-tighter">₹{{ number_format($coupon->original_amount) }}</span>
        </td>
        <td class="px-8 py-6 text-center">
            @if($coupon->is_used)
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-blue-500/10 rounded-full text-[9px] font-black text-blue-500 uppercase tracking-widest border border-blue-500/10 shadow-sm shadow-blue-500/10">
                    <i class="fas fa-check-double text-[8px]"></i>
                    Redeemed
                </span>
            @elseif($coupon->expires_at && \Carbon\Carbon::parse($coupon->expires_at)->isPast())
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-rose-500/10 rounded-full text-[9px] font-black text-rose-500 uppercase tracking-widest border border-rose-500/10">
                    <i class="fas fa-history text-[8px]"></i>
                    Expired
                </span>
            @else
                <span
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-emerald-500/10 rounded-full text-[9px] font-black text-emerald-500 uppercase tracking-widest border border-emerald-500/10">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Available
                </span>
            @endif

            @if($coupon->expires_at && !$coupon->is_used && !\Carbon\Carbon::parse($coupon->expires_at)->isPast())
                <div class="text-[8px] font-black text-amber-500 uppercase mt-2">
                    Exp: {{ \Carbon\Carbon::parse($coupon->expires_at)->format('M d, Y') }}
                </div>
            @endif
        </td>
        <td class="px-8 py-6">
            @if($coupon->usedBy)
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-accent/5 flex items-center justify-center text-accent">
                        <i class="fas fa-user text-[10px]"></i>
                    </div>
                    <div class="flex flex-col">
                        <a href="{{ route('users.show', $coupon->usedBy->id) }}"
                            class="text-xs font-black text-slate-700 dark:text-slate-200 hover:text-accent transition-colors">
                            {{ $coupon->usedBy->profile->full_name }}
                        </a>
                        <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">
                            {{ \Carbon\Carbon::parse($coupon->used_at)->format('M d, h:i A') }}
                        </span>
                    </div>
                </div>
            @else
                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">— Available —</span>
            @endif
        </td>
        <td class="px-8 py-6 text-right">
            <div class="flex justify-end gap-2">
                @if(!$coupon->is_used)
                    <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" class="inline"
                        onsubmit="return confirm('Archive this unused coupon?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-10 h-10 flex items-center justify-center bg-rose-500/5 hover:bg-rose-500 text-rose-500 hover:text-white rounded-xl transition-all border border-rose-500/10">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </form>
                @else
                    <div
                        class="w-10 h-10 flex items-center justify-center bg-slate-100 dark:bg-white/5 text-slate-400 rounded-xl cursor-not-allowed border border-transparent">
                        <i class="fas fa-lock text-xs opacity-30"></i>
                    </div>
                @endif
            </div>
        </td>
    </tr>
@endforeach