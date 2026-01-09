<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Humanity Foundation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1C2434',
                        accent: '#3C50E0',
                        body: '#F5EEDC',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>

<body class="font-sans text-primary" style="background: linear-gradient(135deg, #FAF3E0 0%, #E6D5B8 100%);">
    <div class="flex min-h-screen">
        <!-- Left Side: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-20 bg-white shadow-2xl z-10">
            <div class="w-full max-w-md">
                <div class="flex items-center space-x-3 mb-10">
                    <div
                        class="w-12 h-12 bg-accent rounded-2xl flex items-center justify-center shadow-xl shadow-accent/30">
                        <span class="text-2xl font-bold text-white">HF</span>
                    </div>
                    <div>
                        <h1 class="font-black text-2xl tracking-tight leading-none">Humanity Foundation</h1>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold mt-1">Management
                            Information System</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-slate-800 mb-2">Welcome Back</h2>
                    <p class="text-slate-500">Enter your Employee ID or Email to access your dashboard.</p>
                </div>

                <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                    @csrf

                    @if($errors->any())
                        <div class="p-4 bg-red-50 border border-red-100 text-red-600 rounded-xl text-sm font-medium">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Employee ID / Email</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </span>
                            <input type="text" name="login" required placeholder="HFDM050126"
                                class="w-full pl-11 pr-4 py-4 bg-slate-50 border border-slate-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-accent/10 focus:bg-white focus:border-accent transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                        <div class="relative group">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-11V7a4 4 0 11-8 0v4h8z">
                                    </path>
                                </svg>
                            </span>
                            <input type="password" name="password" id="password" required placeholder="••••••••"
                                class="w-full pl-11 pr-12 py-4 bg-slate-50 border border-slate-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-accent/10 focus:bg-white focus:border-accent transition-all">

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

                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 cursor-pointer group">
                            <input type="checkbox" name="remember"
                                class="w-5 h-5 rounded-md border-slate-300 text-accent focus:ring-accent">
                            <span
                                class="text-sm font-medium text-slate-500 group-hover:text-slate-800 transition">Remember
                                me</span>
                        </label>
                        <a href="#" class="text-sm font-bold text-accent hover:text-accent/80 transition">Forgot
                            password?</a>
                    </div>

                    <button type="submit"
                        class="w-full py-4 bg-accent text-white font-bold rounded-xl shadow-xl shadow-accent/30 hover:shadow-accent/40 active:transform active:scale-[0.98] transition-all">
                        Sign In to Dashboard
                    </button>
                </form>

                <div class="mt-12 pt-8 border-t border-slate-100 text-center">
                    <p class="text-slate-500 text-sm italic italic">"Empowering Lives, Together."</p>
                </div>
            </div>
        </div>

        <!-- Right Side: Brand Experience -->
        <div class="hidden lg:block lg:w-1/2 relative overflow-hidden bg-primary">
            <div
                class="absolute inset-0 opacity-40 bg-[url('https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1470&q=80')] bg-cover bg-center">
            </div>
            <div class="absolute inset-0 bg-gradient-to-tr from-primary via-primary/80 to-transparent"></div>

            <div class="relative h-full flex flex-col justify-end p-20">
                <div class="max-w-md">
                    <span
                        class="inline-block px-4 py-1.5 bg-accent text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-full mb-6">Internal
                        System v1.0</span>
                    <h2 class="text-5xl font-black text-white leading-tight mb-8">Building a Better Tomorrow</h2>
                    <blockquote class="text-xl text-slate-300 font-medium leading-relaxed mb-8">
                        "Your contribution to the foundation helps thousands of families across the region. Manage your
                        team efficiently and grow the impact."
                    </blockquote>
                    <div class="flex items-center space-x-4">
                        <div class="flex -space-x-3">
                            <div class="w-10 h-10 rounded-full border-2 border-primary bg-white/20"></div>
                            <div class="w-10 h-10 rounded-full border-2 border-primary bg-white/40"></div>
                            <div class="w-10 h-10 rounded-full border-2 border-primary bg-white/60"></div>
                        </div>
                        <p class="text-slate-400 text-sm font-semibold">Joined by 500+ volunteers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
    </script>
</body>


</html>