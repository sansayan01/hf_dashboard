@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800 dark:text-white tracking-tight">Account Settings</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Manage your professional profile and security
                credentials.</p>
        </div>

        @if (session('success'))
            <div
                class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center space-x-3 text-emerald-600 dark:text-emerald-400 text-sm font-bold shadow-sm shadow-emerald-500/5 transition-all animate-in fade-in slide-in-from-top-4">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Sidebar Info -->
            <div class="lg:col-span-4 space-y-6">
                <div
                    class="bg-white dark:bg-darkcard p-6 rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 relative overflow-hidden group">
                    <div class="relative z-10 flex flex-col items-center">
                        <div class="relative group/avatar mb-4">
                            <div
                                class="w-24 h-24 rounded-3xl overflow-hidden ring-4 ring-slate-50 dark:ring-white/5 shadow-xl transition-transform group-hover/avatar:scale-105 duration-500">
                                @if ($user->profile && $user->profile->profile_picture)
                                    <img src="{{ asset('storage/' . $user->profile->profile_picture) }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center text-3xl font-black">
                                        {{ substr($user->profile->full_name ?? $user->email, 0, 1) }}
                                    </div>
                                @endif
                                <div
                                    class="absolute inset-0 bg-black/40 opacity-0 group-hover/avatar:opacity-100 transition-opacity flex items-center justify-center text-white text-[10px] font-black uppercase tracking-widest cursor-pointer">
                                    Change
                                </div>
                            </div>
                        </div>
                        <h2 class="text-lg font-black text-slate-800 dark:text-white text-center line-clamp-1">
                            {{ $user->profile->full_name ?? 'User' }}
                        </h2>
                        <span
                            class="mt-1 px-3 py-1 bg-accent/10 text-accent text-[10px] font-black uppercase tracking-widest rounded-full">
                            {{ str_replace('_', ' ', $user->designation) }}
                        </span>

                        <div class="w-full h-[1px] bg-slate-100 dark:bg-white/5 my-6"></div>

                        <div class="w-full space-y-4">
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider">Employee ID</span>
                                <span
                                    class="text-slate-700 dark:text-slate-200 tracking-tight">{{ $user->employee_id }}</span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-bold">
                                <span class="text-slate-400 dark:text-slate-500 uppercase tracking-wider">Join Date</span>
                                <span
                                    class="text-slate-700 dark:text-slate-200 tracking-tight">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Decor -->
                    <div
                        class="absolute -bottom-6 -right-6 w-24 h-24 bg-accent/5 rounded-full blur-2xl group-hover:bg-accent/10 transition-all duration-700">
                    </div>
                </div>
            </div>

            <!-- Form Sections -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Profile Info -->
                <div
                    class="bg-white dark:bg-darkcard rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02]">
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Personal
                            Information</h3>
                    </div>
                    <div class="p-8">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Full
                                        Name</label>
                                    <input type="text" name="full_name"
                                        value="{{ old('full_name', $user->profile->full_name ?? '') }}"
                                        class="w-full px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all text-sm font-medium text-slate-700 dark:text-slate-200 outline-none">
                                    @error('full_name') <p class="text-[10px] text-rose-500 font-bold mt-1 pl-1">
                                    {{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Email
                                        Address (Read-only)</label>
                                    <input type="email" value="{{ $user->email }}" disabled
                                        class="w-full px-4 py-3 rounded-xl bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-white/10 text-sm font-medium text-slate-400 dark:text-slate-500 outline-none cursor-not-allowed">
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Profile
                                    Picture</label>
                                <div class="flex items-center justify-center w-full">
                                    <label
                                        class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl cursor-pointer hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-3 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="text-xs font-bold text-slate-500">Click to upload new image</p>
                                        </div>
                                        <input type="file" name="profile_picture" class="hidden" />
                                    </label>
                                </div>
                                @error('profile_picture') <p class="text-[10px] text-rose-500 font-bold mt-1 pl-1">
                                {{ $message }}</p> @enderror
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit"
                                    class="px-8 py-3 bg-slate-800 dark:bg-white text-white dark:text-slate-900 rounded-xl text-[11px] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-slate-800/10">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security / Password -->
                <div
                    class="bg-white dark:bg-darkcard rounded-3xl shadow-sm border border-slate-100 dark:border-white/5 overflow-hidden">
                    <div
                        class="p-6 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.02] flex items-center justify-between">
                        <h3 class="text-sm font-black text-slate-800 dark:text-white uppercase tracking-widest">Security &
                            Password</h3>
                        <div
                            class="px-3 py-1 bg-rose-500/10 text-rose-500 text-[8px] font-black uppercase tracking-tighter rounded-full border border-rose-500/10">
                            High Priority
                        </div>
                    </div>
                    <div class="p-8">
                        <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="space-y-1.5">
                                <label
                                    class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Current
                                    Password</label>
                                <input type="password" name="current_password" required
                                    class="w-full px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 transition-all text-sm font-medium text-slate-700 dark:text-slate-200 outline-none">
                                @error('current_password') <p class="text-[10px] text-rose-500 font-bold mt-1 pl-1">
                                {{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1.5">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">New
                                        Password</label>
                                    <input type="password" name="password" required
                                        class="w-full px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all text-sm font-medium text-slate-700 dark:text-slate-200 outline-none">
                                    @error('password') <p class="text-[10px] text-rose-500 font-bold mt-1 pl-1">
                                    {{ $message }}</p> @enderror
                                </div>

                                <div class="space-y-1.5">
                                    <label
                                        class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider pl-1">Confirm
                                        New Password</label>
                                    <input type="password" name="password_confirmation" required
                                        class="w-full px-4 py-3 rounded-xl bg-slate-50/50 dark:bg-white/5 border border-slate-200 dark:border-white/10 focus:border-accent focus:ring-4 focus:ring-accent/10 transition-all text-sm font-medium text-slate-700 dark:text-slate-200 outline-none">
                                </div>
                            </div>

                            <div class="pt-4 flex justify-end">
                                <button type="submit"
                                    class="px-8 py-3 bg-accent text-white rounded-xl text-[11px] font-black uppercase tracking-widest hover:scale-[1.02] active:scale-[0.98] transition-all shadow-lg shadow-accent/20">
                                    Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection