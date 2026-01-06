<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            background: #F1F5F9;
            /* Default light background */
        }

        .dark .gradient-bg {
            background: radial-gradient(circle at 80% 80%, rgba(139, 92, 246, 0.15), transparent 50%),
                radial-gradient(circle at 20% 20%, rgba(30, 58, 138, 0.2), transparent 50%),
                #020617;
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
        // Default to light theme for new users (ignore OS preference)
        if (localStorage.getItem('color-theme') === 'dark') {
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
            class="sidebar fixed inset-y-0 left-0 z-50 w-72 bg-primary/95 dark:bg-darkbg/40 backdrop-blur-xl text-white lg:static lg:block overflow-hidden flex flex-col border-r border-slate-200/10 dark:border-white/5">
            <!-- Sidebar Header -->
            <div class="p-6 flex items-center justify-between border-b border-secondary">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-accent rounded-xl flex items-center justify-center shadow-lg shadow-accent/20">
                        <span class="text-xl font-bold">HF</span>
                    </div>
                    <div>
                        <h1 class="font-bold text-lg tracking-tight">Humanity</h1>
                        <p class="text-[10px] text-bodydark uppercase tracking-widest font-semibold">Foundation</p>
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
            <nav class="flex-1 overflow-y-auto p-4 sidebar-scroll">
                <div class="mb-4">
                    <p class="text-xs font-semibold text-bodydark uppercase tracking-widest px-4 mb-3">Menu</p>
                    <ul class="space-y-1">
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
                    </ul>
                </div>

                @if(auth()->user()->canCreateUsers())
                    <div class="mb-4">
                        <p class="text-xs font-semibold text-bodydark uppercase tracking-widest px-4 mb-3">Management</p>
                        <ul class="space-y-1">
                            <li>
                                <a href="{{ route('users.index') }}"
                                    class="flex items-center space-x-3 px-4 py-3 rounded-lg text-bodydark hover:text-white hover:bg-secondary transition font-medium {{ request()->routeIs('users.*') && !request()->routeIs('users.bin') ? 'bg-accent text-white shadow-lg' : '' }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                        </path>
                                    </svg>
                                    <span>My Team</span>
                                </a>
                            </li>

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
                        </ul>
                    </div>
                @endif

                <div class="mb-4">
                    <p class="text-xs font-semibold text-bodydark uppercase tracking-widest px-4 mb-3">System</p>
                    <ul class="space-y-1">
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
                                <span>Account Settings</span>
                            </a>
                        </li>
                    </ul>
                </div>
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
                class="h-20 bg-white dark:bg-darkbg/40 dark:backdrop-blur-md border-b border-slate-200 dark:border-white/5 px-6 lg:px-8 flex items-center justify-between sticky top-0 z-40 transition-colors duration-300">
                <div class="flex items-center space-x-4">
                    <button onclick="toggleSidebar()" class="lg:hidden text-primary">
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
                                <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}" alt="Avatar"
                                    class="w-full h-full object-cover">
                            @else
                                <div
                                    class="w-full h-full flex items-center justify-center bg-accent/5 text-accent font-bold">
                                    {{ substr(auth()->user()->profile->full_name ?? 'U', 0, 1) }}
                                </div>
                            @endif
                        </div>
                    </a>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-10 bg-body flex flex-col justify-between">
                <div>
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

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Theme Toggle Script
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons inside the button based on previous settings
        if (localStorage.getItem('color-theme') === 'dark') {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function () {
            // toggle icons inside button
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

                // if NOT set via local storage previously
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });

        // Global SweetAlert2 Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
            color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1C2434',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#3C50E0',
                background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1C2434',
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
                confirmButtonColor: '#FB4848',
                background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1C2434',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'warning',
                title: 'Form Validation Error',
                html: `<div class="text-left"><p class="font-bold mb-2">Check the following fields:</p><ul class="list-disc list-inside text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>`,
                confirmButtonColor: '#F2994A',
                background: document.documentElement.classList.contains('dark') ? '#1E293B' : '#FFFFFF',
                color: document.documentElement.classList.contains('dark') ? '#F1F5F9' : '#1C2434',
            });
        @endif
    </script>
    @yield('js')
</body>

</html>