@extends('layouts.app')

@section('title', 'Permissions & Controls — ' . ($user->profile->full_name ?? $user->employee_id))
@section('header_title', 'Permissions & Controls')

@section('content')
<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 animate-fade-in">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold text-sm">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold text-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header Card --}}
    <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 p-6 mb-6">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('users.show', $user->id) }}"
                   class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition">
                    <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="flex items-center gap-3">
                    @if($user->profile?->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" alt="Profile"
                             class="w-12 h-12 rounded-xl object-cover ring-2 ring-slate-100">
                    @else
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg">
                            {{ strtoupper(substr($user->profile->full_name ?? $user->employee_id, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h1 class="text-xl font-black text-slate-800">{{ $user->profile->full_name ?? 'N/A' }}</h1>
                        <p class="text-sm text-slate-500 font-semibold">
                            {{ $user->employee_id }} · {{ $user->getDesignationLabel() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 flex-wrap ml-auto">
                {{-- Reset Defaults --}}
                <form method="POST" action="{{ route('users.permissions.reset', $user->id) }}"
                      onsubmit="return confirm('Reset all permissions to defaults? This will remove all custom overrides.')">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl text-[10px] uppercase hover:bg-red-100 transition border border-red-100">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Defaults
                    </button>
                </form>
            </div>
        </div>

        {{-- Override status badge --}}
        <div class="mt-4 flex items-center gap-2">
            @if($hasOverrides)
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-[10px] font-black uppercase border border-blue-100 italic">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Custom Overrides Active
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-500 text-[10px] font-black uppercase border border-slate-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Using Role Defaults
                </span>
            @endif
            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider ml-1">
                {{ collect($userPermissions)->filter(fn($v) => $v)->count() }} / {{ count($userPermissions) }} ENABLED
            </span>
        </div>
    </div>

    {{-- Main Form --}}
    <form method="POST" action="{{ route('users.permissions.update', $user->id) }}" id="permissionsForm">
        @csrf
        @method('PUT')

        <div class="space-y-4" id="categoriesContainer">
            @foreach($categories as $catKey => $category)
                <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden permission-category hover:border-blue-200 transition-colors"
                     data-category="{{ $catKey }}">

                    {{-- Category Header (Accordion Toggle) --}}
                    <div class="w-full flex items-center justify-between px-6 py-4 cursor-pointer hover:bg-slate-50/50 transition group"
                         onclick="toggleCategory('{{ $catKey }}')">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/10 to-indigo-500/10 flex items-center justify-center group-hover:from-blue-500/20 group-hover:to-indigo-500/20 transition">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category['icon'] }}"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <h3 class="font-black text-slate-800 text-sm leading-tight uppercase tracking-tight">{{ $category['label'] }}</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                                    <span id="count-{{ $catKey }}">{{ collect($category['permissions'])->filter(fn($label, $key) => $userPermissions[$key] ?? false)->count() }}</span> / {{ count($category['permissions']) }} enabled
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 group/header">
                            {{-- Local Presets --}}
                            <div class="hidden sm:flex items-center gap-1 bg-slate-100 p-1 rounded-xl border border-slate-200/50" onclick="event.stopPropagation()">
                                <button type="button" 
                                        onclick="applyCategoryPreset('{{ $catKey }}', 'full')" 
                                        title="Full Access"
                                        class="px-2.5 py-1 text-[8px] font-black uppercase rounded-lg hover:bg-emerald-500 hover:text-white hover:shadow-md transition-all text-slate-500">
                                    Full
                                </button>
                                <button type="button" 
                                        onclick="applyCategoryPreset('{{ $catKey }}', 'default')" 
                                        title="Role Default"
                                        class="px-2.5 py-1 text-[8px] font-black uppercase rounded-lg hover:bg-slate-800 hover:text-white hover:shadow-md transition-all text-slate-500">
                                    Default
                                </button>
                                <button type="button" 
                                        onclick="applyCategoryPreset('{{ $catKey }}', 'read')" 
                                        title="Read Only"
                                        class="px-2.5 py-1 text-[8px] font-black uppercase rounded-lg hover:bg-blue-500 hover:text-white hover:shadow-md transition-all text-slate-500">
                                    Read
                                </button>
                                <button type="button" 
                                        onclick="applyCategoryPreset('{{ $catKey }}', 'off')" 
                                        title="Turn Off"
                                        class="px-2.5 py-1 text-[8px] font-black uppercase rounded-lg hover:bg-red-500 hover:text-white hover:shadow-md transition-all text-slate-500">
                                    Off
                                </button>
                            </div>

                            <svg class="w-5 h-5 text-slate-300 transition-transform duration-300 ml-1 group-hover/header:text-blue-400" id="chevron-{{ $catKey }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    {{-- Category Body (Permissions List) --}}
                    <div id="body-{{ $catKey }}" class="hidden border-t border-slate-100">
                        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach($category['permissions'] as $key => $label)
                                @php $isEnabled = $userPermissions[$key] ?? false; @endphp
                                <label class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 transition cursor-pointer group/perm">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full {{ $isEnabled ? 'bg-emerald-500' : 'bg-slate-300' }} transition perm-dot"
                                             data-key="{{ $key }}"></div>
                                        <span class="text-sm font-semibold text-slate-700 group-hover/perm:text-slate-900">{{ $label }}</span>
                                    </div>
                                    <div class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               name="permissions[{{ $key }}]"
                                               value="1"
                                               class="sr-only peer perm-toggle"
                                               data-key="{{ $key }}"
                                               data-category="{{ $catKey }}"
                                               {{ $isEnabled ? 'checked' : '' }}
                                               onchange="updateCounts('{{ $catKey }}')">
                                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Sticky Save Bar --}}
        <div class="sticky bottom-0 bg-white/80 backdrop-blur-xl border-t border-slate-200 -mx-4 sm:-mx-6 px-4 sm:px-6 py-4 mt-6 rounded-b-2xl z-40">
            <div class="flex items-center justify-between max-w-5xl mx-auto">
                <p class="text-xs text-slate-400 font-medium hidden sm:block">
                    Changes are applied immediately after saving. Super Admins always have full access.
                </p>
                <div class="flex items-center gap-3 ml-auto">
                    <a href="{{ route('users.show', $user->id) }}"
                       class="px-6 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-8 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg shadow-blue-600/30 hover:from-blue-500 hover:to-indigo-500 transition inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Permissions
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.3s ease-out; }
</style>

<script>
    function toggleCategory(catKey) {
        const body = document.getElementById('body-' + catKey);
        const chevron = document.getElementById('chevron-' + catKey);

        if (body.classList.contains('hidden')) {
            body.classList.remove('hidden');
            body.style.maxHeight = '0px';
            body.style.overflow = 'hidden';
            body.style.transition = 'max-height 0.3s ease-out';
            requestAnimationFrame(() => {
                body.style.maxHeight = body.scrollHeight + 'px';
            });
            chevron.style.transform = 'rotate(180deg)';
        } else {
            body.style.maxHeight = '0px';
            setTimeout(() => {
                body.classList.add('hidden');
                body.style.maxHeight = '';
                body.style.overflow = '';
                body.style.transition = '';
            }, 300);
            chevron.style.transform = 'rotate(0deg)';
        }
    }

    const permissionDefaults = @json($defaults);

    function applyCategoryPreset(catKey, type) {
        const toggles = document.querySelectorAll('.perm-toggle[data-category="' + catKey + '"]');
        toggles.forEach(toggle => {
            const key = toggle.dataset.key;
            if (type === 'full') {
                toggle.checked = true;
            } else if (type === 'default') {
                toggle.checked = !!permissionDefaults[key];
            } else if (type === 'read') {
                // "Read" includes any view, report viewing, or profile viewing keys
                toggle.checked = key.includes('.view') || key.includes('.report') || key.includes('.view_profile') || key.includes('.view_stats');
            } else if (type === 'off') {
                toggle.checked = false;
            }
        });
        updateCounts(catKey);
    }

    function updateCounts(catKey) {
        const toggles = document.querySelectorAll('.perm-toggle[data-category="' + catKey + '"]');
        const enabledCount = Array.from(toggles).filter(t => t.checked).length;
        const countEl = document.getElementById('count-' + catKey);
        if (countEl) countEl.textContent = enabledCount;

        // Update category toggle
        const catToggle = document.querySelector('.category-toggle[data-category="' + catKey + '"]');
        if (catToggle) {
            catToggle.checked = (enabledCount === toggles.length);
        }

        // Update dots
        toggles.forEach(toggle => {
            const dot = document.querySelector('.perm-dot[data-key="' + toggle.dataset.key + '"]');
            if (dot) {
                if (toggle.checked) {
                    dot.classList.remove('bg-slate-300');
                    dot.classList.add('bg-emerald-500');
                } else {
                    dot.classList.remove('bg-emerald-500');
                    dot.classList.add('bg-slate-300');
                }
            }
        });
    }
</script>
@endsection
