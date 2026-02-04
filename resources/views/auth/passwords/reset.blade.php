<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set New Password - Humanity Foundation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
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
                    <img src="{{ asset('img/logo.png') }}" class="w-full h-full object-contain" alt="Logo">
                </div>
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-slate-900 mb-2">Set New Password</h1>
                <p class="text-slate-600">Please enter your new password below.</p>
            </div>

            <!-- Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                @if($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-sm font-semibold">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Email Display -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Email Address</label>
                    <div class="w-full px-4 py-3 bg-slate-100 border border-slate-300 rounded-xl text-slate-700">
                        {{ $email ?? old('email') }}
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-bold text-slate-700 mb-2">New Password</label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="Enter new password (min. 8 characters)">
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                        placeholder="Confirm new password">
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-lg rounded-xl shadow-lg transition-colors">
                    Reset Password
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
