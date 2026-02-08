<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title>{{ config('app.name', 'HF Management') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tailwind CSS (Vite Build) -->
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])

    <!-- Old CDN (Commented out for React Integration) -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->

    <style>
        .glass {
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .gradient-bg {
            background-color: #ffdfaf;
            /* Solid color requested by user */
            transition: background-color 0.3s ease;
        }

        .dark .gradient-bg {
            background: radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1), transparent 50%),
                radial-gradient(circle at 20% 20%, rgba(30, 58, 138, 0.15), transparent 50%),
                #0f172a;
        }

        /* Global Dark Mode Comfort Adjustments */
        .dark body {
            color-scheme: dark;
        }

        .dark ::selection {
            background-color: rgba(60, 80, 224, 0.3);
            color: #fff;
        }

        /* Universal Text Visibility in Dark Mode */
        .dark .text-slate-950,
        .dark .text-gray-950 {
            color: #f8fafc !important;
        }

        .dark .text-slate-900,
        .dark .text-gray-900,
        .dark .text-primary {
            color: #f8fafc !important;
        }

        .dark .text-slate-800,
        .dark .text-gray-800 {
            color: #f1f5f9 !important;
        }

        .dark .text-slate-700,
        .dark .text-gray-700,
        .dark .text-secondary {
            color: #e2e8f0 !important;
        }

        .dark .text-slate-600,
        .dark .text-gray-600 {
            color: #cbd5e1 !important;
        }

        .dark .text-slate-500,
        .dark .text-gray-500,
        .dark .text-bodydark {
            color: #94a3b8 !important;
        }

        .dark .text-slate-400,
        .dark .text-gray-400 {
            color: #94a3b8 !important;
        }

        .dark .text-black {
            color: #ffffff !important;
        }

        /* Standardize Cards in Dark Mode */
        .dark .bg-white {
            background-color: #1E293B !important;
        }

        .dark .border-slate-100,
        .dark .border-slate-200,
        .dark .border-slate-300,
        .dark .border-gray-100,
        .dark .border-gray-200 {
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        .dark .bg-slate-50,
        .dark .bg-slate-100,
        .dark .bg-slate-200,
        .dark .bg-gray-50,
        .dark .bg-gray-100 {
            background-color: rgba(255, 255, 255, 0.03) !important;
        }

        /* Table specific dark mode */
        .dark table thead tr {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .dark table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.02) !important;
        }

        /* Input overrides for global comfort */
        .dark input:not([type="checkbox"]):not([type="radio"]),
        .dark select,
        .dark textarea {
            background-color: #0F172A !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #F8FAFC !important;
        }

        .dark input::placeholder {
            color: #64748b !important;
        }

        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .sidebar-active {
            background: linear-gradient(90deg, rgba(60, 80, 224, 0.15) 0%, rgba(60, 80, 224, 0) 100%);
            border-left: 3px solid #3C50E0;
        }

        .dark .sidebar-active {
            background: linear-gradient(90deg, rgba(60, 80, 224, 0.2) 0%, rgba(60, 80, 224, 0) 100%);
            border-left: 3px solid #3C50E0;
        }

        .sidebar {
            transition: all 0.3s ease;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }
        }
    </style>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @yield('css')
</head>

<body
    class="bg-body dark:bg-darkbg text-primary dark:text-slate-100 transition-colors duration-300 font-sans antialiased gradient-bg min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar"
            class="sidebar fixed inset-y-0 left-0 z-50 w-72 bg-primary/95 dark:bg-darkbg/40 backdrop-blur-xl text-white lg:static lg:block overflow-y-auto flex flex-col border-r border-slate-200/10 dark:border-white/5 sidebar-scroll">
            <!-- Sidebar Header -->
            <div class="p-6 flex items-center justify-between border-b border-secondary">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-transparent rounded-xl flex items-center justify-center shadow-lg shadow-black/10 transition-transform hover:scale-105 p-1">
                        <img src="{{ asset('img/hf_gold_logo.png') }}" class="w-10 h-10 object-contain"
                            style="mix-blend-mode: screen;" alt="Logo">
                    </div>
                    <div>
                        <h1 class="font-bold text-lg tracking-tight text-white leading-none">Humanity</h1>
                        <p class="text-[10px] text-bodydark uppercase tracking-widest font-semibold mt-1">Foundation</p>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden text-bodydark hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Sidebar Nav -->
            <nav class="flex-1 p-4">
                <ul class="space-y-1">
                    @if(auth()->user()->designation !== 'staff')
                        <li>
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('dashboard') ? 'bg-accent text-white shadow-lg' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                    </path>
                                </svg>
                                <span>Dashboard</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->isSuperAdmin() || \App\Models\RolePermission::check(auth()->user()->designation, 'can_create_surveys'))
                        <li>
                            <a href="{{ route('surveys.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('surveys.*') ? 'bg-accent text-white shadow-lg' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span>Survey</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->isSuperAdmin() || \App\Models\RolePermission::check(auth()->user()->designation, 'can_create_surveys') || auth()->user()->designation === 'staff')
                        <li>
                            <a href="{{ route('patients.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('patients.*') ? 'bg-accent text-white shadow-lg' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                    </path>
                                </svg>
                                <span>Patients</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->isSuperAdmin() || \App\Models\RolePermission::check(auth()->user()->designation, 'can_manage_appointments'))
                        <li>
                            <a href="{{ route('appointments.all') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('appointments.all') ? 'bg-accent text-white shadow-lg' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <span>Appointments</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->designation !== 'staff')
                        <li>
                            <a href="{{ route('membership.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('membership.*') ? 'bg-accent text-white shadow-lg' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                                    </path>
                                </svg>
                                <span>Membership</span>
                            </a>
                        </li>
                    @endif
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->designation === 'staff' || auth()->user()->isOfficeInCharge())
                        <li>
                            <a href="{{ route('inventory.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('inventory.*') && !request()->routeIs('inventory.camps.*') ? 'bg-accent text-white shadow-lg' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.183.244l-.28.14a2 2 0 00-.774 2.58l.14.28a2 2 0 002.58.774l.28-.14a2 2 0 001.183-.244l2.143-.357a6 6 0 013.86-.517l.318-.158a6 6 0 003.86-.517l2.143.428a2 2 0 001.183-.244l.28-.14a2 2 0 00.774-2.58l-.14-.28z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 9.75l.136-.068a2 2 0 012.728.894l.272.544a2 2 0 01-.894 2.728L17.106 14M12 7.5l.136-.068a2 2 0 012.728.894l.272.544a2 2 0 01-.894 2.728L14.106 11.75M9 5.25l.136-.068a2 2 0 012.728.894l.272.544a2 2 0 01-.894 2.728L11.106 9.5">
                                    </path>
                                </svg>
                                <span>Inventory</span>
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->canViewDownline())
                        <li>
                            <a href="{{ route('users.index') }}"
                                class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('users.*') && !request()->routeIs('users.bin') && !request()->routeIs('users.staffIndex') ? 'bg-accent text-white shadow-lg' : '' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                    </path>
                                </svg>
                                <span>My Team</span>
                            </a>
                        </li>

                        @if(auth()->user()->isSuperAdmin())
                            <li>
                                <a href="{{ route('users.staffIndex') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('users.staffIndex') ? 'bg-accent text-white shadow-lg' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    <span>Staffs</span>
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isSuperAdmin())
                            <li>
                                <a href="{{ route('users.bin') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('users.bin') ? 'bg-accent text-white shadow-lg' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    <span>BIN Recovery</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    <li>
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('profile.*') ? 'bg-accent text-white shadow-lg' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ auth()->user()->isSuperAdmin() ? 'Admin Controls' : 'Account Settings' }}</span>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-secondary">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-danger hover:bg-danger/10 transition font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Wrapper -->
        <main class="flex-1 flex flex-col overflow-hidden relative">
            <!-- Background Glows (Dark Mode Only) -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none hidden dark:block">
                <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-purple-600/20 rounded-full blur-[120px]"></div>
                <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px]"></div>
            </div>

            <!-- Header -->
            <header
                class="h-20 bg-white/50 backdrop-blur-md dark:bg-darkbg/40 border-b border-slate-200 dark:border-white/5 px-6 lg:px-8 flex items-center justify-between sticky top-0 z-40 transition-colors duration-300">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="lg:hidden text-slate-800 dark:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16m-7 6h7"></path>
                        </svg>
                    </button>
                    <h2 class="text-xl font-bold text-slate-800">@yield('header_title', 'Dashboard')</h2>
                </div>

                <div class="flex items-center space-x-4 md:space-x-6">
                    <!-- Theme Toggle Button -->
                    <button id="theme-toggle" type="button"
                        class="text-slate-500 dark:text-bodydark hover:bg-slate-100 dark:hover:bg-slate-700/50 focus:outline-none focus:ring-4 focus:ring-slate-200 dark:focus:ring-slate-700 rounded-xl text-sm p-2.5 transition-all">
                        <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464l-.707-.707a1 1 0 00-1.414 1.414l.707.707a1 1 0 001.414-1.414zm2.12 10.607a1 1 0 010-1.414l.706-.707a1 1 0 111.414 1.414l-.707.707a1 1 0 01-1.414 0zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z">
                            </path>
                        </svg>
                    </button>

                    <a href="{{ route('users.show', auth()->user()) }}"
                        class="flex items-center space-x-3 md:space-x-6 hover:opacity-80 transition-all duration-300 group">
                        <div class="text-right hidden sm:block">
                            <p
                                class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-accent transition-colors">
                                {{ auth()->user()->profile->full_name ?? 'User' }}
                            </p>
                            <p class="text-[10px] text-bodydark font-bold uppercase tracking-wider">
                                {{ auth()->user()->getDesignationLabel() }}
                            </p>
                        </div>
                        <div
                            class="w-11 h-11 rounded-full bg-slate-100 dark:bg-slate-800 border-2 border-white dark:border-slate-700 shadow-sm overflow-hidden ring-2 ring-accent/10 group-hover:ring-accent/30 transition-all">
                            @if(auth()->user()->profile && auth()->user()->profile->profile_picture)
                                <img src="{{ auth()->user()->profile->getProfilePictureUrl() }}" alt="Avatar"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-accent/5 text-accent font-bold text-xs">
                                    {{ substr(auth()->user()->profile->full_name ?? auth()->user()->employee_id, 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-10 flex flex-col justify-between">
                <div class="pb-24 md:pb-0">
                    @yield('content')
                </div>

                <footer class="mt-12 pb-2">
                    <p
                        class="text-center text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">
                        Copyright © 2026 Humanity Foundation. All rights reserved.
                    </p>
                </footer>
            </div>
        </main>
    </div>

    <!-- Global Loading Overlay / Splash Screen -->
    <div id="global-loader"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/80 dark:bg-slate-900/80 backdrop-blur-sm transition-all duration-500 opacity-100 cursor-wait">
        
        <!-- Minimal Branded Pulse -->
        <div class="relative flex flex-col items-center animate-pulse">
            <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-100 dark:border-white/5 flex items-center justify-center p-3 transition-transform">
                <img src="{{ asset('img/hf_gold_logo.png') }}" class="w-full h-full object-contain" alt="Loading">
            </div>
            <p class="mt-4 text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest animate-pulse">Please Wait...</p>
        </div>
        
    </div>

    <script>
        function showGlobalLoader() {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.classList.remove('hidden');
                setTimeout(() => {
                    loader.classList.add('opacity-100');
                    loader.classList.remove('opacity-0');
                }, 10);
            }
        }

        function hideGlobalLoader() {
            const loader = document.getElementById('global-loader');
            if (loader) {
                loader.classList.remove('opacity-100');
                loader.classList.add('opacity-0');
                setTimeout(() => {
                    loader.classList.add('hidden');
                }, 500);
            }
        }

        // Global Splash Screen Logic
        window.addEventListener('load', () => {
            const loader = document.getElementById('global-loader');
            if (loader) {
                setTimeout(hideGlobalLoader, 1000); // 1.0s of branded splash
            }
        });

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Global Form Loading Logic
        document.addEventListener('submit', function (e) {
            setTimeout(() => {
                if (e.defaultPrevented) return;
                if (e.target.classList.contains('no-loader')) return;

                const loader = document.getElementById('global-loader');
                if (loader) {
                    loader.classList.remove('hidden');
                    setTimeout(() => {
                        loader.classList.add('opacity-100');
                    }, 10);
                }

                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn) {
                    if (submitBtn.disabled) return;
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    const btnText = submitBtn.innerText.trim();
                    if (btnText && btnText.length < 30) {
                        const lowText = btnText.toLowerCase();
                        if (lowText.includes('update')) submitBtn.innerText = 'Updating...';
                        else if (lowText.includes('save')) submitBtn.innerText = 'Saving...';
                        else if (lowText.includes('delete')) submitBtn.innerText = 'Deleting...';
                        else if (lowText.includes('submit')) submitBtn.innerText = 'Submitting...';
                        else submitBtn.innerText = 'Processing...';
                    }
                }
            }, 0);
        });

        // Theme Toggle Script
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');
        const themeToggleBtn = document.getElementById('theme-toggle');

        // Initial icon state
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
            document.documentElement.classList.add('dark');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
            document.documentElement.classList.remove('dark');
        }

        themeToggleBtn.addEventListener('click', function () {
            // toggle icons
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // if set via local storage previously
            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }

            // Dispatch event for other components (like SweetAlert dynamic color check)
            window.dispatchEvent(new Event('theme-changed'));
        });

        // Global SweetAlert2 Theme Helper
        function getSwalConfig() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                background: isDark ? '#1E293B' : '#FFFFFF',
                color: isDark ? '#F1F5F9' : '#1C2434',
                confirmButtonColor: '#3C50E0'
            };
        }

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                const config = getSwalConfig();
                toast.style.background = config.background;
                toast.style.color = config.color;
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                ...getSwalConfig(),
                @if(session('view_appointment_url'))
                                                                                                                                                                                                                                                                                                                                                                                                                                                    showDenyButton: true,
                    denyButtonText: 'View Appointment',
                    denyButtonColor: '#10B981',
                @endif
                                                                                                                                                                                                                            }).then((result) => {
                    @if(session('view_appointment_url'))
                        if (result.isDenied) {
                            window.location.href = "{{ session('view_appointment_url') }}";
                        }
                    @endif
                                                                                                                                                                                                                            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "{{ session('error') }}",
                ...getSwalConfig(),
                confirmButtonColor: '#FB4848',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Form Validation Error',
                html: `<div class="text-left"><p class="font-bold mb-2">Check the following fields:</p><ul class="list-disc list-inside text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>`,
                ...getSwalConfig(),
                confirmButtonColor: '#F2994A',
            });
        @endif
    </script>
    @include('layouts.partials.ai_assistant')
    @yield('js')
</body>

</html>