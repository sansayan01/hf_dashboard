@php
    $children = $item->getDirectChildren();
    $hasChildren = $children->count() > 0;
    $statusColor = $item->status === 'active' ? 'text-emerald-500' : 'text-amber-500';
@endphp

<div class="tree-item" data-id="{{ $item->id }}">
    <div
        class="flex items-center group py-1 px-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800/50 transition-colors">

        <!-- Toggle Icon (Click only here to expand/collapse) -->
        <div class="w-6 h-6 flex items-center justify-center mr-1 cursor-pointer hover:bg-slate-200 dark:hover:bg-slate-700 rounded-md transition-colors"
            onclick="toggleTreeItem(event, {{ $item->id }})">
            @if($hasChildren)
                <svg id="toggle-icon-{{ $item->id }}"
                    class="w-3 h-3 text-slate-400 group-hover:text-accent transform transition-transform duration-200"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                </svg>
            @else
                <div class="w-1 h-1 bg-slate-300 dark:bg-slate-600 rounded-full"></div>
            @endif
        </div>

        <!-- Main Content Area (Clicking here goes to profile) -->
        <a href="{{ route('users.show', $item->id) }}" class="flex items-center flex-1 min-w-0 group/link">
            <!-- Small Profile Picture -->
            <div
                class="w-8 h-8 rounded-lg overflow-hidden mr-3 bg-slate-100 dark:bg-slate-800 flex-shrink-0 border border-slate-200/50 dark:border-white/5 ring-2 ring-transparent group-hover/link:ring-accent/20 transition-all">
                @if($item->profile && $item->profile->profile_picture)
                    <img src="{{ asset('storage/' . $item->profile->profile_picture) }}" alt="Avatar"
                        class="w-full h-full object-cover">
                @else
                    <div
                        class="w-full h-full flex items-center justify-center bg-accent/5 text-accent text-[10px] font-black">
                        {{ substr($item->profile->full_name ?? 'U', 0, 1) }}
                    </div>
                @endif
            </div>

            <!-- Member Info -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center space-x-2">
                    <span
                        class="text-xs font-black text-slate-700 dark:text-slate-200 truncate group-hover/link:text-accent transition-colors">
                        {{ $item->profile->full_name ?? 'N/A' }}
                    </span>
                    <span
                        class="text-[9px] font-black px-1.5 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-500 tracking-tighter">
                        {{ $item->employee_id }}
                    </span>
                </div>
                <p
                    class="text-[9px] font-bold uppercase tracking-widest text-slate-400 group-hover/link:text-accent/70 mt-0.5 transition-colors">
                    {{ $item->getDesignationLabel() }}
                </p>
            </div>

            <!-- Status Dot -->
            <div
                class="w-2 h-2 rounded-full {{ $item->status === 'active' ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]' : 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]' }} mr-2">
            </div>
        </a>
    </div>

    @if($hasChildren)
        <div id="children-{{ $item->id }}"
            class="tree-children hidden ml-6 pl-4 border-l border-slate-200 dark:border-slate-800 mt-1 space-y-1">
            @foreach($children as $child)
                @include('dashboard.partials.tree_item', ['item' => $child])
            @endforeach
        </div>
    @endif

</div>