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

    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>

    <!-- AI Background Removal Library -->
    <script src="{{ asset('vendor/background-removal/index.js') }}"></script>

    <!-- Tailwind CSS (Vite Build) -->
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])

    <!-- Tailwind CSS CDN (Fallback for when Vite build is not available) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#1C2434',
                        secondary: '#313D4A',
                        accent: '#3C50E0',
                        success: '#10B981',
                        danger: '#FB4848',
                        warning: '#F2994A',
                        body: '#F5EEDC',
                        bodydark: '#8A99AF',
                        darkbg: '#0F172A',
                        darkcard: '#1E293B',
                        darkaccent: '#3C50E0',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>


    <style>
        /* Glass card styling */
        .glass {
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .dark .glass {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .gradient-bg {
            background-color: #ffdfaf !important;
            /* Solid color requested by user */
            transition: background-color 0.3s ease;
        }

        .dark .gradient-bg {
            background: radial-gradient(circle at 80% 80%, rgba(59, 130, 246, 0.1), transparent 50%),
                radial-gradient(circle at 20% 20%, rgba(30, 58, 138, 0.15), transparent 50%),
                #0f172a !important;
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

        /* Select/Option dark mode text fix */
        .dark select option {
            background-color: #1E293B !important;
            color: #F8FAFC !important;
        }

        /* Badge/Label backgrounds in dark mode */
        .dark .bg-red-100 {
            background-color: rgba(239, 68, 68, 0.15) !important;
        }

        .dark .bg-amber-100 {
            background-color: rgba(245, 158, 11, 0.15) !important;
        }

        .dark .bg-emerald-100 {
            background-color: rgba(16, 185, 129, 0.15) !important;
        }

        .dark .bg-blue-100 {
            background-color: rgba(59, 130, 246, 0.15) !important;
        }

        .dark .bg-rose-100 {
            background-color: rgba(244, 63, 94, 0.15) !important;
        }

        .dark .bg-indigo-100 {
            background-color: rgba(99, 102, 241, 0.15) !important;
        }

        .dark .bg-orange-100 {
            background-color: rgba(249, 115, 22, 0.15) !important;
        }

        .dark .bg-violet-100 {
            background-color: rgba(139, 92, 246, 0.15) !important;
        }

        .dark .bg-pink-100 {
            background-color: rgba(236, 72, 153, 0.15) !important;
        }

        .dark .bg-cyan-100 {
            background-color: rgba(6, 182, 212, 0.15) !important;
        }

        .dark .bg-teal-100 {
            background-color: rgba(20, 184, 166, 0.15) !important;
        }

        .dark .bg-purple-100 {
            background-color: rgba(168, 85, 247, 0.15) !important;
        }

        .dark .bg-lime-100 {
            background-color: rgba(132, 204, 22, 0.15) !important;
        }

        .dark .bg-rose-50 {
            background-color: rgba(244, 63, 94, 0.1) !important;
        }

        .dark .bg-emerald-50 {
            background-color: rgba(16, 185, 129, 0.1) !important;
        }

        .dark .bg-blue-50 {
            background-color: rgba(59, 130, 246, 0.1) !important;
        }

        .dark .bg-amber-50 {
            background-color: rgba(245, 158, 11, 0.1) !important;
        }

        .dark .bg-indigo-50 {
            background-color: rgba(99, 102, 241, 0.1) !important;
        }

        /* Hover state overrides for dark mode */
        .dark .hover\:bg-slate-50:hover {
            background-color: rgba(255, 255, 255, 0.03) !important;
        }

        .dark .hover\:bg-slate-100:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        .dark .hover\:bg-slate-200:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        /* Divide color overrides for dark mode */
        .dark .divide-slate-50> :not([hidden])~ :not([hidden]),
        .dark .divide-slate-100> :not([hidden])~ :not([hidden]) {
            border-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Shadow override for dark mode - reduce harsh shadows */
        .dark .shadow-sm,
        .dark .shadow-md {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.2) !important;
        }

        /* Code/pre elements */
        .dark code {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #cbd5e1 !important;
        }

        /* Ring color overrides */
        .dark .ring-black\/5 {
            --tw-ring-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* ===== Navbar Avatar Rainbow Ring ===== */
        @property --nav-ring-angle {
            syntax: '<angle>';
            initial-value: 0deg;
            inherits: false;
        }

        .nav-avatar-ring {
            padding: 2px;
            border-radius: 50%;
            background: conic-gradient(from var(--nav-ring-angle, 0deg), #6366f1, #8b5cf6, #ec4899, #f59e0b, #10b981, #6366f1);
            animation: nav-ring-spin 1.8s linear infinite, nav-ring-glow 3s ease-in-out infinite alternate;

            box-shadow: 0 0 10px 2px rgba(99, 102, 241, 0.55),
            0 0 22px 5px rgba(139, 92, 246, 0.3),
            @keyframes nav-ring-spin {
                from {
                    --nav-ring-angle: 0deg;
                }

                to {
                    --nav-ring-angle: 360deg;
                }
            }

            @keyframes nav-ring-glow {
                from {
                    box-shadow:
                        0 0 10px 2px rgba(99, 102, 241, 0.65),
                        0 0 22px 5px rgba(139, 92, 246, 0.35),
                        0 0 40px 8px rgba(236, 72, 153, 0.2);
                }

                to {
                    box-shadow:
                        0 0 14px 4px rgba(236, 72, 153, 0.65),
                        0 0 30px 8px rgba(245, 158, 11, 0.35),
                        0 0 55px 12px rgba(16, 185, 129, 0.2);
                }
            }

            .nav-avatar-ring img,
            .nav-avatar-ring>div {
                filter: none !important;
            }

            /* ===== Theme Toggle — Glassmorphic Orb ===== */
            .theme-orb-wrap {
                position: relative;
                width: 42px;
                height: 42px;
                flex-shrink: 0;
            }

            /* Spinning arc ring — sits BEHIND the button */
            .theme-orb-wrap::before {
                content: '';
                position: absolute;
                inset: -3px;
                border-radius: 50%;
                background: conic-gradient(transparent 55%, rgba(251, 191, 36, 0.65) 75%, transparent 95%);
                animation: orb-ring-spin 2.5s linear infinite;
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: 0;
            }

            .theme-orb-wrap:hover::before {
                opacity: 1;
            }

            .theme-orb-wrap.dark-mode::before {
                background: conic-gradient(transparent 55%, rgba(99, 102, 241, 0.7) 75%, transparent 95%);
            }

            @keyframes orb-ring-spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            /* The button itself */
            .theme-orb {
                position: relative;
                z-index: 1;
                width: 42px;
                height: 42px;
                border-radius: 50%;
                border: 1px solid rgba(255, 255, 255, 0.3);
                cursor: pointer;
                outline: none;
                padding: 0;
                background: rgba(255, 255, 255, 0.18);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.5);
                transition: background 0.4s ease, border-color 0.4s ease,
                    box-shadow 0.4s ease, transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
                perspective: 400px;
                overflow: visible;
            }

            .theme-orb-wrap:hover .theme-orb {
                transform: scale(1.1);
                box-shadow:
                    0 0 0 5px rgba(251, 191, 36, 0.12),
                    0 0 18px rgba(251, 191, 36, 0.4),
                    inset 0 1px 0 rgba(255, 255, 255, 0.5);
            }

            .theme-orb-wrap:active .theme-orb {
                transform: scale(0.92);
            }

            /* Dark state */
            .theme-orb-wrap.dark-mode .theme-orb {
                background: rgba(99, 102, 241, 0.18);
                border-color: rgba(99, 102, 241, 0.35);
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }

            .theme-orb-wrap.dark-mode:hover .theme-orb {
                transform: scale(1.1);
                box-shadow:
                    0 0 0 5px rgba(99, 102, 241, 0.15),
                    0 0 18px rgba(99, 102, 241, 0.5),
                    inset 0 1px 0 rgba(255, 255, 255, 0.1);
            }

            /* Icon flip wrapper */
            .theme-orb-inner {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.5s cubic-bezier(0.68, -0.15, 0.32, 1.15);
                transform-style: preserve-3d;
            }

            .theme-orb-wrap.flipping .theme-orb-inner {
                transform: rotateY(180deg);
            }

            /* Sun & Moon icons — JS controls display directly */
            .theme-orb .t-sun,
            .theme-orb .t-moon {
                display: flex;
                align-items: center;
                justify-content: center;
                transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.25s ease;
            }

            /* Ripple */
            .theme-orb-ripple {
                position: absolute;
                border-radius: 50%;
                transform: scale(0);
                opacity: 0.6;
                animation: orb-ripple 0.55s ease-out forwards;
                pointer-events: none;
                z-index: 2;
            }

            @keyframes orb-ripple {
                to {
                    transform: scale(3.5);
                    opacity: 0;
                }
            }

            /* ===== Full-page liquid spread overlay ===== */
            #theme-liquid-overlay {
                position: fixed;
                inset: 0;
                z-index: 99999;
                pointer-events: none;
                clip-path: circle(0px at 50% 50%);
                transition: clip-path 1.4s cubic-bezier(0.25, 0.1, 0.25, 1);
                will-change: clip-path;
            }

            #theme-liquid-overlay.spreading {
                clip-path: circle(200vmax at var(--ox, 50%) var(--oy, 50%));
            }

            /* Subtle wavy inner shimmer */
            #theme-liquid-overlay::after {
                content: '';
                position: absolute;
                inset: 0;
                background: radial-gradient(ellipse at var(--ox, 50%) var(--oy, 50%),
                        rgba(255, 255, 255, 0.12) 0%,
                        transparent 65%);
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
    <!-- Liquid spread overlay for theme transition -->
    <div id="theme-liquid-overlay"></div>
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
                    @if($effectiveUser->designation !== 'staff')
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
                    @if($effectiveUser->isSuperAdmin() || \App\Models\RolePermission::check($effectiveUser->designation, 'can_create_surveys'))
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
                    @if($effectiveUser->isSuperAdmin() || \App\Models\RolePermission::check($effectiveUser->designation, 'can_create_surveys') || $effectiveUser->designation === 'staff')
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
                    @if($effectiveUser->isSuperAdmin() || \App\Models\RolePermission::check($effectiveUser->designation, 'can_manage_appointments'))
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
                    @if($effectiveUser->designation !== 'staff')
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
                    @if($effectiveUser->isSuperAdmin() || $effectiveUser->designation === 'staff' || $effectiveUser->isOfficeInCharge())
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

                    @if($effectiveUser->canViewDownline())
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

                        @if($effectiveUser->isSuperAdmin())
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

                        @if($effectiveUser->isSuperAdmin())
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
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white">@yield('header_title', 'Dashboard')
                    </h2>
                </div>

                <div class="flex items-center space-x-4 md:space-x-6">
                    <!-- Theme Toggle Orb -->
                    <div id="theme-toggle-wrap" class="theme-orb-wrap">
                        <button id="theme-toggle" type="button" class="theme-orb" aria-label="Toggle theme">
                            <div class="theme-orb-inner">
                                <!-- Sun -->
                                <span class="t-sun">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#f59e0b"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="4" fill="#fbbf24" fill-opacity="0.3" />
                                        <circle cx="12" cy="12" r="4" />
                                        <line x1="12" y1="2" x2="12" y2="5" />
                                        <line x1="12" y1="19" x2="12" y2="22" />
                                        <line x1="4.93" y1="4.93" x2="7.05" y2="7.05" />
                                        <line x1="16.95" y1="16.95" x2="19.07" y2="19.07" />
                                        <line x1="2" y1="12" x2="5" y2="12" />
                                        <line x1="19" y1="12" x2="22" y2="12" />
                                        <line x1="4.93" y1="19.07" x2="7.05" y2="16.95" />
                                        <line x1="16.95" y1="7.05" x2="19.07" y2="4.93" />
                                    </svg>
                                </span>
                                <!-- Moon -->
                                <span class="t-moon" style="display:none">
                                    <svg width="17" height="17" viewBox="0 0 24 24" stroke="#a5b4fc" stroke-width="2"
                                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="#6366f1"
                                            fill-opacity="0.4" />
                                        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                                    </svg>
                                </span>
                            </div>
                        </button>
                    </div>

                    <a href="{{ route('users.show', $effectiveUser) }}"
                        class="flex items-center space-x-3 md:space-x-6 hover:opacity-80 transition-all duration-300 group">
                        <div class="text-right hidden sm:block">
                            <p
                                class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-accent transition-colors">
                                {{ $effectiveUser->profile->full_name ?? 'User' }}
                            </p>
                            <p class="text-[10px] text-bodydark font-bold uppercase tracking-wider">
                                {{ $effectiveUser->getDesignationLabel() }}
                            </p>
                        </div>
                        <div class="nav-avatar-ring">
                            <div class="w-11 h-11 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                @if($effectiveUser->profile && $effectiveUser->profile->profile_picture)
                                    <img src="{{ $effectiveUser->profile->getProfilePictureUrl() }}" alt="Avatar"
                                        class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-accent/5 text-accent font-bold text-xs">
                                        {{ substr($effectiveUser->profile->full_name ?? $effectiveUser->employee_id, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            </header>

            @if($isViewAsMode)
                <div class="bg-indigo-600 text-white px-6 py-2 flex items-center justify-between shadow-lg z-30">
                    <div class="flex items-center gap-3">
                        <div class="p-1 bg-white/20 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Viewing dashboard as <strong
                                class="font-bold">{{ $effectiveUser->profile->full_name ?? $effectiveUser->employee_id }}</strong></span>
                    </div>
                    <a href="{{ route('dashboard.clear') }}"
                        class="px-4 py-1 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-bold transition-all border border-white/30">
                        Back to My Dashboard
                    </a>
                </div>
            @endif

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
        class="fixed inset-0 flex items-center justify-center bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl transition-all duration-500 opacity-100"
        style="z-index: 99999; pointer-events: all; touch-action: none;">

        <!-- Lottie Animation -->
        <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js" type="module"></script>
        <dotlottie-wc src="https://lottie.host/1aa4a8a0-06f1-430b-ab43-fe7ca72f6c9a/cSwkMGw50G.lottie"
            style="width: 300px; height: 300px" autoplay loop>
        </dotlottie-wc>

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
                @if(session('success') || session('error') || $errors->any())
                    // Hide loader immediately when a flash message exists (SweetAlert will show instead)
                    hideGlobalLoader();
                @else
                    setTimeout(hideGlobalLoader, 200); // 0.2s of branded splash
                @endif
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

        // Theme Toggle — Liquid Spread
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleWrap = document.getElementById('theme-toggle-wrap');
        const tSun = themeToggleBtn.querySelector('.t-sun');
        const tMoon = themeToggleBtn.querySelector('.t-moon');
        const liquidOverlay = document.getElementById('theme-liquid-overlay');

        function applyThemeToToggle(isDark) {
            if (isDark) {
                themeToggleWrap.classList.add('dark-mode');
                document.documentElement.classList.add('dark');
                tSun.style.display = 'none';
                tMoon.style.display = 'flex';
            } else {
                themeToggleWrap.classList.remove('dark-mode');
                document.documentElement.classList.remove('dark');
                tSun.style.display = 'flex';
                tMoon.style.display = 'none';
            }
        }

        function liquidToggle(nowDark) {
            // Get button center position for the spread origin
            const rect = themeToggleBtn.getBoundingClientRect();
            const ox = Math.round(rect.left + rect.width / 2);
            const oy = Math.round(rect.top + rect.height / 2);

            // Set overlay color = the destination theme background
            liquidOverlay.style.background = nowDark
                ? 'linear-gradient(135deg, #1C2434 0%, #1e1b4b 50%, #0f0f23 100%)'
                : 'linear-gradient(135deg, #F5EEDC 0%, #fffaf3 50%, #F5EEDC 100%)';

            // Set the origin CSS vars
            liquidOverlay.style.setProperty('--ox', ox + 'px');
            liquidOverlay.style.setProperty('--oy', oy + 'px');
            liquidOverlay.style.clipPath = `circle(0px at ${ox}px ${oy}px)`;

            // Force reflow so transition triggers
            liquidOverlay.getBoundingClientRect();

            // Expand the liquid
            liquidOverlay.classList.add('spreading');

            // After half the spread, switch theme (invisible underneath)
            setTimeout(() => {
                applyThemeToToggle(nowDark);
                localStorage.setItem('color-theme', nowDark ? 'dark' : 'light');
                window.dispatchEvent(new Event('theme-changed'));
            }, 700);

            // After full spread, collapse back
            setTimeout(() => {
                liquidOverlay.classList.remove('spreading');
                liquidOverlay.style.clipPath = `circle(0px at ${ox}px ${oy}px)`;
            }, 1450);
        }

        themeToggleBtn.addEventListener('click', function () {
            // 3D flip on wrapper
            themeToggleWrap.classList.add('flipping');
            setTimeout(() => themeToggleWrap.classList.remove('flipping'), 520);

            const nowDark = !document.documentElement.classList.contains('dark');
            liquidToggle(nowDark);
        });

        // Initial state
        const initDark = localStorage.getItem('color-theme') === 'dark' ||
            (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches);
        applyThemeToToggle(initDark);

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