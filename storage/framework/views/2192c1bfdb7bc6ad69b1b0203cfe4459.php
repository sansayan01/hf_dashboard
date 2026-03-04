<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title><?php echo e(config('app.name', 'HF Management')); ?> - <?php echo $__env->yieldContent('title'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Cropper.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>





    <!-- Tailwind CSS (Vite Build) -->
    <?php echo app('Illuminate\Foundation\Vite')->reactRefresh(); ?>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.tsx']); ?>

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
                0 0 22px 5px rgba(139, 92, 246, 0.3);
        }

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
    <?php echo $__env->yieldContent('css'); ?>
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
                        <img src="<?php echo e(asset('img/hf_gold_logo.png')); ?>" class="w-10 h-10 object-contain"
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
            <nav class="flex-1 p-4" id="live-sidebar-nav">
                <?php echo $__env->make('layouts.partials.sidebar_nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-secondary">
                <form action="<?php echo e(route('logout')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
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
                    <h2 class="text-xl font-bold text-slate-800 dark:text-white"><?php echo $__env->yieldContent('header_title', 'Dashboard'); ?>
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

                    <a href="<?php echo e(route('users.show', $effectiveUser)); ?>"
                        class="flex items-center space-x-3 md:space-x-6 hover:opacity-80 transition-all duration-300 group">
                        <div class="text-right hidden sm:block">
                            <p
                                class="text-sm font-bold text-slate-800 dark:text-white group-hover:text-accent transition-colors">
                                <?php echo e($effectiveUser->profile->full_name ?? 'User'); ?>

                            </p>
                            <div class="flex items-center justify-end space-x-2">
                                <p class="text-[10px] text-bodydark font-bold uppercase tracking-wider"
                                    data-live-sync="designation_label">
                                    <?php echo e($effectiveUser->getDesignationLabel()); ?>

                                </p>
                                <div data-live-sync="salary_mode_badge">
                                    <?php echo ($effectiveUser->salary_mode === 'dab' ? '<span class="text-[9px] font-black text-violet-500 bg-violet-500/10 px-2 py-0.5 rounded-full uppercase tracking-widest border border-violet-500/20">DAB</span>' : '<span class="text-[9px] font-black text-blue-500 bg-blue-500/10 px-2 py-0.5 rounded-full uppercase tracking-widest border border-blue-500/20">TAB</span>'); ?>

                                </div>
                            </div>
                        </div>
                        <div class="nav-avatar-ring">
                            <div class="w-11 h-11 rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden">
                                <?php if($effectiveUser->profile && $effectiveUser->profile->profile_picture): ?>
                                    <img src="<?php echo e($effectiveUser->profile->getProfilePictureUrl()); ?>" alt="Avatar"
                                        class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-accent/5 text-accent font-bold text-xs">
                                        <?php echo e(substr($effectiveUser->profile->full_name ?? $effectiveUser->employee_id, 0, 1)); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </div>
            </header>

            <?php if($isViewAsMode): ?>
                <div class="bg-indigo-600 text-white px-6 py-2 flex items-center justify-between shadow-lg z-30">
                    <div class="flex items-center gap-3">
                        <div class="p-1 bg-white/20 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium">Viewing dashboard as <strong
                                class="font-bold"><?php echo e($effectiveUser->profile->full_name ?? $effectiveUser->employee_id); ?></strong></span>
                    </div>
                    <a href="<?php echo e(route('dashboard.clear')); ?>"
                        class="px-4 py-1 bg-white/20 hover:bg-white/30 rounded-lg text-xs font-bold transition-all border border-white/30">
                        Back to My Dashboard
                    </a>
                </div>
            <?php endif; ?>

            <!-- Dashboard Content -->
            <div class="flex-1 overflow-y-auto p-6 lg:p-10 flex flex-col justify-between">
                <div class="pb-24 md:pb-0">
                    <?php echo $__env->yieldContent('content'); ?>
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

        <div class="relative flex items-center justify-center">
            <!-- Fallback CSS Spinner (Visible if Lottie fails) -->
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-16 h-16 border-4 border-accent/20 border-t-accent rounded-full animate-spin"></div>
            </div>

            <!-- Lottie Animation -->
            <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.11/dist/dotlottie-wc.js"
                type="module"></script>
            <dotlottie-wc src="https://lottie.host/1aa4a8a0-06f1-430b-ab43-fe7ca72f6c9a/cSwkMGw50G.lottie"
                style="width: 300px; height: 300px; position: relative; z-index: 1;" autoplay loop>
            </dotlottie-wc>
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
                <?php if(session('success') || session('error') || $errors->any()): ?>
                    // Hide loader immediately when a flash message exists (SweetAlert will show instead)
                    hideGlobalLoader();
                <?php else: ?>
                    setTimeout(hideGlobalLoader, 200); // 0.2s of branded splash
                <?php endif; ?>
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
                    showGlobalLoader();
                }

                const submitBtn = e.target.querySelector('button[type="submit"]');
                if (submitBtn) {
                    if (submitBtn.disabled) return;
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                    const btnText = submitBtn.innerText.trim();
                    const lowText = btnText.toLowerCase();

                    if (lowText.includes('membership')) submitBtn.innerText = 'Upgrading to Premium...';
                    else if (lowText.includes('update')) submitBtn.innerText = 'Updating...';
                    else if (lowText.includes('save')) submitBtn.innerText = 'Saving...';
                    else if (lowText.includes('delete')) submitBtn.innerText = 'Deleting...';
                    else if (lowText.includes('submit')) submitBtn.innerText = 'Submitting...';
                    else if (lowText.includes('confirm')) submitBtn.innerText = 'Processing...';
                    else if (btnText.length < 30) submitBtn.innerText = 'Processing...';
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

        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "<?php echo e(session('success')); ?>",
                ...getSwalConfig(),
                <?php if(session('view_appointment_url')): ?>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            showDenyButton: true,
                    denyButtonText: 'View Appointment',
                    denyButtonColor: '#10B981',
                <?php endif; ?>
                                                                                                                                                                                                                                                                                                                                                                }).then((result) => {
                    <?php if(session('view_appointment_url')): ?>
                        if (result.isDenied) {
                            window.location.href = "<?php echo e(session('view_appointment_url')); ?>";
                        }
                    <?php endif; ?>
                                                                                                                                                                                                                                                                                                                                                                });
        <?php endif; ?>

        <?php if(session('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "<?php echo e(session('error')); ?>",
                ...getSwalConfig(),
                confirmButtonColor: '#FB4848',
            });
        <?php endif; ?>

        <?php if($errors->any()): ?>
            Swal.fire({
                icon: 'warning',
                title: 'Form Validation Error',
                html: `<div class="text-left"><p class="font-bold mb-2">Check the following fields:</p><ul class="list-disc list-inside text-sm"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul></div>`,
                ...getSwalConfig(),
                confirmButtonColor: '#F2994A',
            });
        <?php endif; ?>
    </script>



    <?php echo $__env->make('layouts.partials.ai_assistant', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->yieldContent('js'); ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views\layouts\app.blade.php ENDPATH**/ ?>