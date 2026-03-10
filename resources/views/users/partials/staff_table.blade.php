@php $canBulkApprove = auth()->user()->isSuperAdmin(); @endphp
@forelse($users as $u)
    <tr class="hover:bg-slate-50 transition-colors group">
        @if($canBulkApprove)
            <td class="px-6 py-4">
                <input type="checkbox" name="selected_users[]" value="{{ $u->id }}" form="bulk-actions-form"
                    data-status="{{ $u->status }}"
                    class="user-checkbox w-4 h-4 rounded border-slate-300 text-accent focus:ring-accent">
            </td>
        @endif
        <td class="px-6 py-4">
            <a href="{{ route('users.show', $u->id) }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-full bg-accent/5 text-accent flex items-center justify-center font-bold overflow-hidden border border-slate-100 dark:border-white/5 group-hover:border-accent/30 transition-colors">
                    @if($u->profile?->profile_picture)
                        <img src="{{ $u->profile->getProfilePictureUrl() }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{ substr($u->profile?->full_name ?? 'U', 0, 1) }}
                    @endif
                </div>
                <div>
                    <p class="text-sm font-bold text-slate-800 group-hover:text-accent transition-colors">{{ $u->profile?->full_name ?? 'Incomplete Profile' }}</p>
                    <p class="text-[10px] text-bodydark font-bold uppercase">{{ $u->employee_id }}</p>
                </div>
            </a>
        </td>
        <td class="px-6 py-4">
            <span class="px-3 py-1 bg-primary/5 text-primary rounded-full text-[10px] font-black uppercase tracking-widest border border-primary/10">
                {{ $u->getDesignationLabel() }}
            </span>
        </td>
        <td class="px-6 py-4">
            <p class="text-xs text-slate-500 font-medium">{{ $u->created_at->format('d M, Y') }}</p>
        </td>
        <td class="px-6 py-4">
            @if($u->status === 'active')
                <span class="inline-flex items-center space-x-1.5 text-success">
                    <span class="w-1.5 h-1.5 rounded-full bg-success animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Active</span>
                </span>
            @else
                <span class="inline-flex items-center space-x-1.5 text-warning">
                    <span class="w-1.5 h-1.5 rounded-full bg-warning"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest">Pending</span>
                </span>
            @endif
        </td>
        <td class="px-6 py-4 text-right">
            <div class="flex items-center justify-end space-x-2">
                @if($u->status === 'pending' && auth()->user()->canApprove($u))
                    <form action="{{ route('users.approve', $u->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg transition-all text-[10px] font-black uppercase tracking-widest flex items-center space-x-1" title="Approve Member">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            <span>Approve</span>
                        </button>
                    </form>
                @endif
                <div class="flex items-center justify-end space-x-2 transition-opacity">
                    <a href="{{ route('users.show', $u->id) }}" class="p-2 text-slate-400 hover:text-accent transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </a>
                    @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('users.edit', $u->id) }}" class="p-2 text-blue-500 hover:bg-blue-500/10 rounded-lg transition" title="Edit User">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        <a href="{{ route('users.id-card', $u->id) }}" target="_blank" class="p-2 text-violet-500 hover:bg-violet-500/10 rounded-lg transition" title="Generate ID Card">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" /></svg>
                        </a>
                        @if($u->employee_id !== 'HFSA000001')
                        <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Move to BIN?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-danger hover:bg-danger/10 rounded-lg transition" title="Delete User">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endif
                    @endif
                </div>
            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="px-6 py-20 text-center">
            <div class="max-w-xs mx-auto text-slate-400">
                <svg class="w-12 h-12 mx-auto mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <p class="font-bold">No pharmacist or office in-charge members found.</p>
                <p class="text-xs mt-1">Start by adding a new member.</p>
            </div>
        </td>
    </tr>
@endforelse
