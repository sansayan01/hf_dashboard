@extends('layouts.app')

@section('css')
<style>
    /* Humanity Foundation Premium 3D Elements */
    
    /* 1. Admin Save Button */
    .admin-save-btn {
        appearance: none;
        background: linear-gradient(135deg, #3C50E0 0%, #2A3BB7 100%);
        border: none;
        border-radius: 20px;
        color: #FFFFFF !important;
        cursor: pointer;
        display: inline-block;
        font-size: 11px;
        font-weight: 900;
        letter-spacing: 2px;
        min-height: 60px;
        padding: 0 45px;
        text-transform: uppercase;
        transition: all 0.2s cubic-bezier(0.23, 1, 0.32, 1);
        box-shadow: 
            0 8px 0 #1D2A8E,
            0 15px 25px rgba(60, 80, 224, 0.4);
        position: relative;
        overflow: hidden;
    }

    .admin-save-btn:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 10px 0 #1D2A8E,
            0 20px 30px rgba(60, 80, 224, 0.5);
    }

    .admin-save-btn:active {
        transform: translateY(6px);
        box-shadow: 0 2px 0 #1D2A8E;
    }

    /* 2. 3D Dial Toggle */
    .dial-container {
        display: inline-flex !important;
        background: #f1f5f9;
        padding: 5px;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.05);
        gap: 2px !important;
    }

    .dark .dial-container {
        background: rgba(15, 23, 42, 0.5);
        border-color: rgba(255,255,255,0.05);
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.2);
    }

    .dial-label {
        margin: 0 !important;
        padding: 0 !important;
        cursor: pointer;
    }

    .dial-input {
        display: none !important;
    }

    .dial-btn {
        width: 65px;
        height: 36px;
        line-height: 36px;
        text-align: center;
        font-size: 10px;
        font-weight: 900;
        border-radius: 10px;
        color: #64748b;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Active ON State */
    .dial-input-on:checked + .dial-btn-on {
        background: #22c55e;
        color: white;
        box-shadow: 
            0 4px 0 #15803d,
            0 8px 20px rgba(34, 197, 94, 0.3);
        transform: translateY(-2px);
    }

    /* Active OFF State */
    .dial-input-off:checked + .dial-btn-off {
        background: #ef4444;
        color: white;
        box-shadow: 
            0 4px 0 #b91c1c,
            0 8px 20px rgba(239, 68, 68, 0.3);
        transform: translateY(-2px);
    }

    .dial-btn:active {
        transform: translateY(2px) !important;
        box-shadow: none !important;
    }

    .dark .dial-btn {
        color: #94a3b8;
    }
</style>
@endsection

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">
                    {{ auth()->user()->isSuperAdmin() ? 'Admin Controls' : 'Account Settings' }}
                </h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">
                    {{ auth()->user()->isSuperAdmin() ? 'Manage global system permissions and role access levels.' : 'Manage your professional profile and security credentials.' }}
                </p>
            </div>
            @if(auth()->user()->isSuperAdmin())
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-black uppercase tracking-widest rounded-full border border-accent/20">System Administrator</span>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center space-x-3 text-emerald-600 dark:text-emerald-400 text-sm font-bold shadow-sm shadow-emerald-500/5 transition-all animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(auth()->user()->isSuperAdmin())
            <!-- Admin Permissions Interface -->
            <div class="bg-white dark:bg-darkcard rounded-[2rem] shadow-xl border border-slate-100 dark:border-white/5 overflow-hidden">
                <form action="{{ route('profile.permissions') }}" method="POST">
                    @csrf
                    <div class="flex flex-col lg:flex-row">
                        <!-- Sidebar Tabs -->
                        <div class="lg:w-72 bg-slate-50/50 dark:bg-white/[0.02] border-r border-slate-100 dark:border-white/5 p-6">
                            <nav class="space-y-2" id="permission-tabs">
                                @php
                                    $roles = [
                                        'office_in_charge' => ['label' => 'Office In-Charge', 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04M12 21a11.955 11.955 0 01-8.618-3.04m17.236 0A11.955 11.955 0 0112 21'],
                                        'hs' => ['label' => 'Head of State', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                        'dm' => ['label' => 'District Manager', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                        'bm' => ['label' => 'Block Manager', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                        'rm' => ['label' => 'Relationship Manager', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z'],
                                        'ro' => ['label' => 'Relationship Officer', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z']
                                    ];
                                @endphp

                                @foreach($roles as $key => $data)
                                    <button type="button" onclick="switchTab('{{ $key }}')" id="tab-btn-{{ $key }}"
                                        class="permission-tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all font-bold text-xs uppercase tracking-widest group">
                                        <div class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-400 group-hover:text-accent transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $data['icon'] }}" />
                                            </svg>
                                        </div>
                                        <span class="text-slate-500 group-hover:text-slate-800 dark:group-hover:text-white">{{ $data['label'] }}</span>
                                    </button>
                                @endforeach

                                <div class="pt-8 mt-8 border-t border-slate-100 dark:border-white/5">
                                    <button type="button" onclick="switchTab('my-account')" id="tab-btn-my-account"
                                        class="permission-tab-btn w-full flex items-center space-x-3 px-4 py-3 rounded-2xl transition-all font-bold text-xs uppercase tracking-widest group">
                                        <div class="w-8 h-8 rounded-xl bg-white dark:bg-slate-800 shadow-sm flex items-center justify-center text-slate-400 group-hover:text-accent transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            </svg>
                                        </div>
                                        <span class="text-slate-500 group-hover:text-slate-800 dark:group-hover:text-white">Admin Profile</span>
                                    </button>
                                </div>
                            </nav>
                        </div>

                        <!-- Content Area -->
                        <div class="flex-1 p-8 lg:p-12">
                            @foreach($roles as $key => $data)
                                <div id="permission-content-{{ $key }}" class="permission-content hidden animate-in fade-in slide-in-from-right-4 duration-500">
                                    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                                        <div>
                                            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight">{{ $data['label'] }} Permissions</h2>
                                            <p class="text-slate-500 font-medium">Define what users with the {{ $data['label'] }} role are allowed to perform in the system.</p>
                                        </div>
                                        <div class="flex items-center space-x-6 bg-slate-50/80 dark:bg-white/[0.03] px-8 py-5 rounded-[2rem] border border-slate-100 dark:border-white/5 shadow-sm">
                                            <div class="hidden sm:block">
                                                <p class="text-[10px] font-black uppercase tracking-widest text-accent mb-0.5">Global Controller</p>
                                                <p class="text-xs font-bold text-slate-600 dark:text-slate-300">Permit All Actions Currently Shown</p>
                                            </div>
                                            <div class="sm:hidden">
                                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Master Control</p>
                                            </div>
                                            <x-dial-toggle 
                                                id="master_toggle_{{ $key }}" 
                                                onchange="toggleAllPermissions('{{ $key }}', this.checked)" 
                                            />
                                        </div>

                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        @php
                                            $perms = $rolePermissions[$key] ?? collect();
                                        @endphp

                                        @foreach($perms as $perm)
                                            <label class="group flex items-center justify-between p-5 bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 rounded-3xl cursor-pointer hover:border-accent/30 hover:bg-white dark:hover:bg-white/[0.05] transition-all">
                                                <div class="flex-1 pr-4">
                                                    <p class="text-xs font-black text-slate-800 dark:text-white uppercase tracking-widest mb-1">{{ str_replace('_', ' ', $perm->permission_key) }}</p>
                                                    <p class="text-[10px] text-slate-400 font-bold uppercase">Grant Access</p>
                                                </div>
                                                <x-dial-toggle 
                                                    name="permissions[{{ $key }}][{{ $perm->permission_key }}]" 
                                                    :checked="$perm->is_enabled" 
                                                />
                                            </label>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-12 pt-8 border-t border-slate-100 dark:border-white/5 flex justify-end">
                                        <button type="submit" class="admin-save-btn">
                                            Save All Permissions
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                            <!-- My Account Tab (Hidden content from original profile) -->
                            <div id="permission-content-my-account" class="permission-content hidden animate-in fade-in slide-in-from-right-4 duration-500">
                                <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
                                    <!-- Simplified forms from original profile -->
                                    <div class="bg-slate-50/50 dark:bg-white/[0.02] p-8 rounded-[2rem] border border-slate-100 dark:border-white/5">
                                        <h3 class="text-xl font-black text-slate-800 dark:text-white mb-6 uppercase tracking-tight">Admin Profile Information</h3>
                                        <div class="space-y-6">
                                            <p class="text-xs text-slate-500 font-bold">Manage your own profile details and security independently.</p>
                                            <div class="flex flex-wrap gap-4">
                                                <a href="{{ route('users.edit', $user->id) }}" class="px-6 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-slate-200 font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-slate-50 transition-all">Edit Official Details</a>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-8 p-8 border border-rose-500/10 bg-rose-500/[0.02] rounded-[2rem]">
                                        <h3 class="text-lg font-black text-rose-500 mb-4 uppercase tracking-tighter">Security Notice</h3>
                                        <p class="text-xs text-slate-500 leading-relaxed">Admin accounts have elevated privileges. Ensure you use strong passwords and rotate them periodically to maintain system integrity.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @else
            <!-- Regular User Interface (Original) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- User Profile View (Keep original layout for non-admins) -->
                <!-- Copy of original content -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white dark:bg-darkcard p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 relative overflow-hidden group">
                        <div class="relative z-10 flex flex-col items-center">
                            <div class="relative group/avatar mb-4">
                                <div class="w-24 h-24 rounded-3xl overflow-hidden ring-4 ring-slate-50 dark:ring-white/5 shadow-xl transition-transform group-hover/avatar:scale-105 duration-500">
                                    @if ($user->profile && $user->profile->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-3xl font-black">
                                            {{ substr($user->profile->full_name ?? $user->email, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <h2 class="text-lg font-black text-slate-800 dark:text-white text-center">{{ $user->profile->full_name ?? 'User' }}</h2>
                            <span class="mt-1 px-3 py-1 bg-accent/10 text-accent text-[10px] font-black uppercase tracking-widest rounded-full">
                                {{ $user->getDesignationLabel() }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-8 space-y-8">
                    <!-- Original Personal Info Form -->
                    <div class="bg-white dark:bg-darkcard rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Personal Information</h3>
                        </div>
                        <div class="p-8">
                            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Full Name</label>
                                        <input type="text" name="full_name" value="{{ old('full_name', $user->profile->full_name ?? '') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all text-sm font-medium text-slate-700 dark:text-slate-200 outline-none">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Email Address</label>
                                        <input type="email" value="{{ $user->email }}" disabled class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-sm font-medium text-slate-400 dark:text-slate-500 cursor-not-allowed">
                                    </div>
                                    <div class="space-y-1.5 md:col-span-2">
                                        <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Profile Picture</label>
                                        <div class="mt-1 flex items-center gap-4">
                                            <div class="w-16 h-16 rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 border-2 border-slate-200 dark:border-white/10">
                                                @if ($user->profile && $user->profile->profile_picture)
                                                    <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold">
                                                        {{ substr($user->profile->full_name ?? $user->email, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <input type="file" name="profile_picture" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-accent/10 file:text-accent hover:file:bg-accent/20 transition-all">
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-4 flex justify-end">
                                    <button type="submit" class="px-8 py-3 bg-slate-800 dark:bg-white text-white dark:text-slate-900 rounded-xl text-[11px] font-black uppercase tracking-widest hover:scale-105 transition-all">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Security / Password -->
                    <div class="bg-white dark:bg-darkcard rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 overflow-hidden">
                        <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                            <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Security & Password</h3>
                        </div>
                        <div class="p-8">
                            <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                                @csrf
                                <div class="space-y-1.5">
                                    <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Current Password</label>
                                    <input type="password" name="current_password" required class="w-full px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all text-sm outline-none">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">New Password</label>
                                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all text-sm outline-none">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all text-sm outline-none">
                                    </div>
                                </div>
                                <div class="pt-4 flex justify-end">
                                    <button type="submit" class="px-8 py-3 bg-accent text-white rounded-xl text-[11px] font-black uppercase tracking-widest hover:scale-105 transition-all">Update Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    @if(auth()->user()->isSuperAdmin())
    <script>
        function switchTab(roleId) {
            // Hide all content
            document.querySelectorAll('.permission-content').forEach(el => el.classList.add('hidden'));
            
            // Remove active style from all buttons
            document.querySelectorAll('.permission-tab-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'dark:bg-slate-800', 'shadow-md', 'scale-[1.02]');
                btn.querySelector('div').classList.remove('bg-accent', 'text-white');
                btn.querySelector('div').classList.add('bg-white', 'dark:bg-slate-800', 'text-slate-400');
                btn.querySelector('span').classList.remove('text-slate-800', 'dark:text-white');
                btn.querySelector('span').classList.add('text-slate-500');
            });

            // Show target content
            document.getElementById('permission-content-' + roleId).classList.remove('hidden');
            
            // Apply active style to target button
            const activeBtn = document.getElementById('tab-btn-' + roleId);
            activeBtn.classList.add('bg-white', 'dark:bg-slate-800', 'shadow-md', 'scale-[1.02]');
            activeBtn.querySelector('div').classList.remove('bg-white', 'dark:bg-slate-800', 'text-slate-400');
            activeBtn.querySelector('div').classList.add('bg-accent', 'text-white');
            activeBtn.querySelector('span').classList.remove('text-slate-500');
            activeBtn.querySelector('span').classList.add('text-slate-800', 'dark:text-white');
        }

        function toggleAllPermissions(roleKey, isChecked) {
            const container = document.getElementById('permission-content-' + roleKey);
            const checkboxes = container.querySelectorAll('.role-permission-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = isChecked;
                
                // Update visual state of dial if present
                const dial = cb.closest('.dial-container');
                if (dial) {
                    const radioOff = dial.querySelector('.dial-input-off');
                    const radioOn = dial.querySelector('.dial-input-on');
                    if (isChecked && radioOn) radioOn.checked = true;
                    if (!isChecked && radioOff) radioOff.checked = true;
                }
            });
        }


        // Initialize with first tab
        document.addEventListener('DOMContentLoaded', () => {
            switchTab('office_in_charge');
        });
    </script>
    @else
    <script>
        function togglePassword(button) {
            const container = button.parentElement;
            const input = container.querySelector('input');
            const eyeIcon = container.querySelector('.eye-icon');
            const eyeOffIcon = container.querySelector('.eye-off-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
    @endif
@endsection