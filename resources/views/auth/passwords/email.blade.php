<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Humanity Foundation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#0F172A',
                        accent: '#3C50E0',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>

<body class="font-sans antialiased bg-slate-950 overflow-y-auto min-h-screen flex items-center justify-center p-4">
    <!-- Background -->
    <div class="fixed inset-0 z-0 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-[#0F172A] to-[#1e1b4b]"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-md">
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center shadow-lg p-2">
                    <img src="{{ asset('img/hf_gold_logo.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-slate-900 mb-2">Reset Password</h1>
                <p class="text-slate-600">Enter your email address and we'll send you a link to reset your password.</p>
            </div>

            <!-- Form -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                @csrf

                @if(session('success'))
                    <div
                        class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-sm font-semibold">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-semibold">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" required value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="your.email@example.com">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg rounded-xl shadow-lg transition-colors">
                    Send Reset Link
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <a href="{{ route('login') }}"
                        class="text-sm font-semibold text-accent hover:text-accent/80 transition-colors">
                        ← Back to Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-400 mt-6">© 2026 Humanity Foundation</p>
    </div>
</body>

</html>