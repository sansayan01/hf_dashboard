<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Access - Humanity Foundation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0F172A',
                        accent: '#2563EB',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        'blob': 'blob 25s infinite alternate',
                        'blob-reverse': 'blob-reverse 20s infinite alternate',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.2)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.8)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        },
                        'blob-reverse': {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(-30px, 50px) scale(0.8)' },
                            '66%': { transform: 'translate(20px, -20px) scale(1.2)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* User's Custom Blob Background CSS */
        .blob-outer-container {
            position: fixed;
            height: 100%;
            width: 100%;
            z-index: 0;
            inset: 0;
            margin: auto;
            filter: blur(100px);
        }

        .blob-inner-container {
            border-radius: 9999px;
            position: absolute;
            inset: 0;
            margin: auto;
            width: 100vw;
            height: 100vh;
            min-width: 1000px;
            overflow: hidden;
            background-color: #fff;
            /* Kept from snippet, acts as a base */
            transform: scale(0.8);
        }

        .blob {
            position: absolute;
            width: 100vw;
            height: 100vh;
            inset: 0;
            margin: auto;
            background: conic-gradient(from 0deg, #08f, #f60, #bbffa1, #4c00ff, #ab2666, #09f);
            animation: spinBlob 8s linear infinite;
        }

        @keyframes spinBlob {
            0% {
                transform: rotate(0deg) scale(2);
            }

            100% {
                transform: rotate(1turn) scale(2);
            }
        }

        /* Glass Panel - iOS Mirror Glossy Style */
        .glass-panel {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.02) 100%);
            backdrop-filter: blur(40px) saturate(130%);
            -webkit-backdrop-filter: blur(40px) saturate(130%);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow:
                0 25px 45px rgba(0, 0, 0, 0.5),
                inset 0 0 0 1px rgba(255, 255, 255, 0.1),
                /* Inner rim */
                inset 0 1px 0 0 rgba(255, 255, 255, 0.5);
            /* Mirror top reflection */
        }

        .auth-input {
            @apply w-full px-4 py-4 bg-white/5 border border-white/10 text-white rounded-2xl focus:outline-none focus:ring-4 focus:ring-accent/20 focus:bg-white/10 focus:border-accent transition-all duration-300 placeholder-slate-500;
        }
    </style>
</head>

<body class="font-sans antialiased bg-black overflow-y-auto text-slate-200">
    <!-- Custom Background Structure -->
    <div class="blob-outer-container">
        <div class="blob-inner-container">
            <div class="blob"></div>
        </div>
    </div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-4">
        <!-- Main Container -->
        <div
            class="w-full max-w-6xl grid lg:grid-cols-2 gap-0 overflow-hidden rounded-[2.5rem] shadow-[0_32px_128px_-16px_rgba(0,0,0,0.5)] glass-panel ring-1 ring-white/10">

            <!-- Left Side: Interactive Branding -->
            <div class="hidden lg:flex flex-col justify-between p-16 relative overflow-hidden group">
                <!-- content background -->
                <div class="absolute inset-0 bg-black/20 backdrop-blur-sm z-0"></div>

                <div class="relative z-10">
                    <div class="flex items-center space-x-4 mb-12" id="logo-anim">
                        <div
                            class="w-16 h-16 bg-transparent flex items-center justify-center transition-transform hover:scale-105">
                            <img src="{{ asset('img/hf_gold_logo.png') }}" class="w-14 h-14 object-contain"
                                style="mix-blend-mode: screen;" alt="Humanity Foundation Logo">
                        </div>
                        <span class="text-2xl font-black text-white tracking-tight drop-shadow-md">Humanity
                            Foundation</span>
                    </div>

                    <div id="title-anim">
                        <span
                            class="inline-block px-4 py-1.5 bg-white/10 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6 border border-white/20 backdrop-blur-md shadow-lg">Secure
                            Portal v2.0</span>
                        <h2 class="text-6xl font-black text-white leading-[1.1] mb-8 drop-shadow-lg">Empower Your
                            <br><span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Mission.</span>
                        </h2>
                        <p class="text-xl text-slate-100 font-medium leading-relaxed max-w-sm drop-shadow-md">
                            Access the foundation's core management systems and lead your team towards impact.
                        </p>
                    </div>
                </div>

                <div class="relative z-10" id="footer-anim">
                    <div class="flex items-center space-x-4 mb-8">
                        <div class="flex -space-x-3">
                            <img src="https://i.pravatar.cc/100?u=1"
                                class="w-10 h-10 rounded-full border-2 border-white/50 shadow-sm">
                            <img src="https://i.pravatar.cc/100?u=2"
                                class="w-10 h-10 rounded-full border-2 border-white/50 shadow-sm">
                            <img src="https://i.pravatar.cc/100?u=3"
                                class="w-10 h-10 rounded-full border-2 border-white/50 shadow-sm">
                        </div>
                        <p class="text-white text-sm font-bold uppercase tracking-widest drop-shadow-md">Active
                            Volunteers Network</p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="bg-white/5 backdrop-blur-sm p-8 lg:p-16 flex flex-col justify-center relative shadow-[inset_0_1px_0_rgba(255,255,255,0.1)] overflow-hidden group/form"
                id="login-container">
                <!-- Faded Logo Watermark Background -->
                <div
                    class="absolute inset-0 opacity-[0.04] pointer-events-none flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('img/hf_gold_logo.png') }}"
                        class="w-[120%] h-auto opacity-[0.08] rotate-[-15deg] scale-110"
                        style="mix-blend-mode: screen;">
                </div>
                <!-- NGO-themed Decorative Pattern -->
                <div class="absolute inset-0 opacity-[0.05] pointer-events-none z-0">
                    <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg" width="100" height="100"
                        viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="40" fill="none" stroke="currentColor" stroke-width="1"
                            class="text-white" />
                        <path d="M50 10v80M10 50h80" stroke="currentColor" stroke-width="0.5" class="text-white" />
                    </svg>
                </div>
                <!-- Dynamic Glow Overlay -->
                <div id="form-glow"
                    class="absolute pointer-events-none opacity-0 group-hover/form:opacity-100 transition-opacity duration-500 w-[400px] h-[400px] bg-accent/10 rounded-full blur-[80px] -translate-x-1/2 -translate-y-1/2 z-0">
                </div>

                <div class="max-w-md mx-auto w-full relative z-10 py-6">
                    <div id="form-header" class="mb-8 text-center lg:text-left">
                        <div
                            class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 mb-4 scale-90 lg:scale-100 origin-left shadow-lg">
                            <span
                                class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse box-shadow-glow-green"></span>
                            <span
                                class="text-[10px] font-black uppercase tracking-widest text-white drop-shadow-sm">System
                                Online</span>
                        </div>
                        <h3
                            class="text-4xl lg:text-5xl font-black text-white mb-4 tracking-tighter leading-none drop-shadow-xl">
                            Account Sign In</h3>
                        <p class="text-slate-200 font-medium leading-relaxed drop-shadow-md">Secure authentication
                            required for Humanity Foundation MIS access.</p>
                    </div>

                    <form action="{{ url('/login') }}" method="POST" class="space-y-6" id="login-form">
                        @csrf

                        @if($errors->any())
                            <div
                                class="p-4 bg-rose-50 border border-rose-100 text-rose-600 rounded-2xl text-xs font-bold animate-in fade-in slide-in-from-top-2 duration-500">
                                @foreach($errors->all() as $error)
                                    <p class="flex items-center"><svg class="w-4 h-4 mr-2" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                clip-rule="evenodd"></path>
                                        </svg> {{ $error }}</p>
                                @endforeach
                            </div>
                        @endif

                        <!-- Username Field -->
                        <div class="relative group">
                            <div
                                class="absolute -inset-0.5 bg-gradient-to-r from-accent/0 to-accent/0 rounded-2xl blur opacity-0 group-within:from-accent/20 group-within:to-indigo-500/20 group-within:opacity-100 transition duration-500">
                            </div>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-within:text-accent transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                </span>
                                <input type="text" name="login" required id="login_id"
                                    class="peer w-full pl-12 pr-4 py-5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-0 focus:bg-white focus:border-accent transition-all duration-300 placeholder-transparent"
                                    placeholder="Credential ID">
                                <label for="login_id"
                                    class="absolute left-12 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm pointer-events-none transition-all duration-300 
                                    peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-accent peer-focus:uppercase peer-focus:tracking-widest peer-focus:bg-white peer-focus:px-2 peer-focus:left-10
                                    peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-[10px] peer-[:not(:placeholder-shown)]:text-accent peer-[:not(:placeholder-shown)]:uppercase peer-[:not(:placeholder-shown)]:tracking-widest peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:left-10">
                                    Credential ID
                                </label>
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div class="relative group">
                            <div
                                class="absolute -inset-0.5 bg-gradient-to-r from-accent/0 to-accent/0 rounded-2xl blur opacity-0 group-within:from-accent/20 group-within:to-indigo-500/20 group-within:opacity-100 transition duration-500">
                            </div>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-within:text-accent transition-colors duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-11V7a4 4 0 11-8 0v4h8z">
                                        </path>
                                    </svg>
                                </span>
                                <input type="password" name="password" id="password" required
                                    class="peer w-full pl-12 pr-12 py-5 bg-slate-50/50 border border-slate-200 rounded-2xl focus:outline-none focus:ring-0 focus:bg-white focus:border-accent transition-all duration-300 placeholder-transparent"
                                    placeholder="Secure Password">
                                <label for="password"
                                    class="absolute left-12 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-sm pointer-events-none transition-all duration-300 
                                    peer-focus:-top-2 peer-focus:text-[10px] peer-focus:text-accent peer-focus:uppercase peer-focus:tracking-widest peer-focus:bg-white peer-focus:px-2 peer-focus:left-10
                                    peer-[:not(:placeholder-shown)]:-top-2 peer-[:not(:placeholder-shown)]:text-[10px] peer-[:not(:placeholder-shown)]:text-accent peer-[:not(:placeholder-shown)]:uppercase peer-[:not(:placeholder-shown)]:tracking-widest peer-[:not(:placeholder-shown)]:bg-white peer-[:not(:placeholder-shown)]:px-2 peer-[:not(:placeholder-shown)]:left-10">
                                    Secure Password
                                </label>

                                <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-accent transition-colors">
                                    <svg id="eye-icon" class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eye-off-icon" class="w-5 h-5 hidden" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between py-2">
                            <label class="flex items-center space-x-3 cursor-pointer group/check">
                                <div
                                    class="relative w-11 h-6 bg-slate-200 rounded-full group-hover/check:bg-slate-300 transition-all duration-300">
                                    <input type="checkbox" name="remember" class="sr-only peer" checked>
                                    <div
                                        class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 peer-checked:translate-x-5 peer-checked:bg-white shadow-sm ring-1 ring-black/5 peer-checked:ring-accent">
                                    </div>
                                    <div
                                        class="absolute inset-x-0 inset-y-0 rounded-full bg-accent scale-0 peer-checked:scale-100 transition-transform duration-300 origin-center z-0">
                                    </div>
                                    <div
                                        class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-all duration-300 peer-checked:translate-x-5 shadow-sm z-10">
                                    </div>
                                </div>
                                <span
                                    class="text-sm font-bold text-slate-500 group-hover/check:text-slate-900 transition-colors">Remember
                                    Session</span>
                            </label>
                            <a href="{{ route('password.request') }}"
                                class="text-sm font-black text-accent hover:text-accent/80 transition-all group/forgot">
                                Forgot Access?
                                <span
                                    class="block h-0.5 w-0 group-hover/forgot:w-full bg-accent transition-all duration-300"></span>
                            </a>
                        </div>

                        <!-- Submit -->
                        <!-- Submit Button Section -->
                        <div class="mt-6">
                            <button type="submit" id="submit-btn"
                                style="opacity: 1 !important; visibility: visible !important;"
                                class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg rounded-xl shadow-lg transition-colors">
                                Sign In
                            </button>
                        </div>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.4em] mb-4">Secured by HF
                            Encryption</p>
                        <div
                            class="flex items-center justify-center space-x-8 opacity-30 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-500">
                            <div class="h-4 w-4 rounded-full border-2 border-slate-400"></div>
                            <div class="h-4 w-4 rotate-45 border-2 border-slate-400"></div>
                            <div class="h-4 w-4 border-2 border-slate-400"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        .active-zoom {
            transition: transform 20s linear;
            transform: scale(1);
        }

        .active-zoom.scaled {
            transform: scale(1.2);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Entrance Animations
            const tl = gsap.timeline({ defaults: { ease: "power4.out" } });

            tl.from(".lg\\:grid-cols-2", { duration: 1.5, y: 100, opacity: 0, scale: 0.9 })
                .from("#logo-anim", { duration: 1, x: -50, opacity: 0 }, "-=1")
                .from("#title-anim > *", { duration: 1, y: 30, opacity: 0, stagger: 0.1 }, "-=0.8")
                .from("#form-header", { duration: 1, y: 20, opacity: 0 }, "-=1")
                .from("#login-form > div:not(:last-child)", { duration: 1, y: 20, opacity: 0, stagger: 0.1 }, "-=0.8")
                .from("#submit-btn", { duration: 0.8, y: 20, opacity: 0, scale: 0.95, ease: "back.out(1.7)" }, "-=0.4");

            // Hero Image Zoom
            setTimeout(() => {
                document.getElementById('hero-img').classList.add('scaled');
            }, 100);

            // Dynamic Glow Tracking
            const container = document.getElementById('login-container');
            const glow = document.getElementById('form-glow');

            container.addEventListener('mousemove', (e) => {
                const rect = container.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;

                gsap.to(glow, {
                    duration: 0.6,
                    x: x,
                    y: y,
                    ease: "power2.out"
                });

                // Subtle Tilt Effect
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                const rotateX = (y - centerY) / 50;
                const rotateY = (centerX - x) / 50;

                gsap.to(container, {
                    duration: 0.5,
                    rotateX: rotateX,
                    rotateY: rotateY,
                    ease: "power2.out",
                    perspective: 1000
                });
            });

            container.addEventListener('mouseleave', () => {
                gsap.to(container, {
                    duration: 0.8,
                    rotateX: 0,
                    rotateY: 0,
                    ease: "elastic.out(1, 0.3)"
                });
            });

            // Button Interaction
            const btn = document.getElementById('submit-btn');
            btn.addEventListener('mouseenter', () => {
                gsap.to(btn, { duration: 0.3, scale: 1.02, y: -4 });
            });
            btn.addEventListener('mouseleave', () => {
                gsap.to(btn, { duration: 0.3, scale: 1, y: 0 });
            });

            // Form Submission Loading State
            const form = document.getElementById('login-form');
            form.addEventListener('submit', () => {
                const btn = document.getElementById('submit-btn');
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                btn.innerHTML = `<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            });
        });

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        function showForgotPasswordInfo() {
            Swal.fire({
                icon: 'info',
                title: 'Password Recovery',
                html: `
                    <div class="text-left">
                        <p class="mb-3">To reset your password, please contact your administrator:</p>
                        <ul class="list-disc list-inside space-y-2 text-sm">
                            <li>Email your supervisor or HR department</li>
                            <li>Provide your Volunteer ID for verification</li>
                            <li>Request a password reset link</li>
                        </ul>
                        <p class="mt-4 text-xs text-gray-500">For security reasons, password resets must be verified by an administrator.</p>
                    </div>
                `,
                confirmButtonText: 'Understood',
                confirmButtonColor: '#3C50E0'
            });
        }
    </script>
</body>

</html>