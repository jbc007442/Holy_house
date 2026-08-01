<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>@yield('title', 'Login - ' . config('app.name'))</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Notyf -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    @stack('styles')

</head>

<body
    class="h-full bg-zinc-50 text-zinc-900 flex items-center justify-center min-h-screen relative overflow-hidden selection:bg-zinc-900 selection:text-white">

    <!-- Background -->
    <div
        class="absolute inset-0 bg-[linear-gradient(to_right,#e4e4e7_1px,transparent_1px),linear-gradient(to_bottom,#e4e4e7_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] opacity-45 pointer-events-none">
    </div>

    <!-- Card -->
    <div
        class="relative z-10 w-full max-w-md bg-white border border-zinc-200 rounded-xl shadow-sm p-8 sm:p-10 m-4">

        <!-- Header -->
        <div class="flex flex-col items-center text-center mb-8">

            <div
                class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-900 text-white mb-4 shadow-sm">
                ✦
            </div>

            <h1 class="text-xl font-semibold tracking-tight">
                Welcome back
            </h1>

            <p class="mt-1 text-sm text-zinc-500">
                Enter your credentials to access your account
            </p>

        </div>

        <!-- Login Form -->
        <form method="POST"
            action="{{ route('login.post') }}"
            class="space-y-5">

            @csrf

            <!-- Email -->
            <div class="space-y-2">

                <label for="email"
                    class="text-xs font-medium text-zinc-900">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    autofocus
                    required
                    placeholder="m@example.com"
                    class="flex h-10 w-full rounded-md border border-zinc-200 bg-transparent px-3 py-2 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900">

            </div>

            <!-- Password -->
            <div class="space-y-2">

                <label for="password"
                    class="text-xs font-medium text-zinc-900">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    autocomplete="current-password"
                    required
                    placeholder="••••••••"
                    class="flex h-10 w-full rounded-md border border-zinc-200 bg-transparent px-3 py-2 text-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900">

            </div>

            <!-- Remember -->
            <div class="flex items-center justify-between">

                <label class="flex items-center gap-2 cursor-pointer">

                    <input
                        type="checkbox"
                        name="remember"
                        id="remember"
                        {{ old('remember') ? 'checked' : '' }}
                        class="accent-zinc-900 rounded">

                    <span class="text-xs text-zinc-600">
                        Remember me
                    </span>

                </label>

                <a href="{{ route('forgot-password') }}"
                    class="text-xs text-zinc-600 hover:text-zinc-900 hover:underline">
                    Forgot password?
                </a>

            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full h-10 rounded-md bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-800 transition">

                Sign in

            </button>

        </form>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-zinc-500">

            Don't have an account?

            <a href="{{ route('register') }}"
                class="font-medium text-zinc-900 hover:underline">

                Create account

            </a>

        </div>

    </div>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- App JS -->
    <script src="{{ asset('js/script.js') }}"></script>

    <!-- Notyf -->
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const notyf = new Notyf({
                duration: 3000,
                dismissible: true,
                ripple: true,
                position: {
                    x: 'center',
                    y: 'top'
                }
            });

            @if(session('success'))
                notyf.success(@json(session('success')));
            @endif

            @if(session('status'))
                notyf.success(@json(session('status')));
            @endif

            @if(session('error'))
                notyf.error(@json(session('error')));
            @endif

            @if($errors->any())
                @foreach($errors->all() as $error)
                    notyf.error(@json($error));
                @endforeach
            @endif

            // Show loading notification on submit
            document.querySelector('form').addEventListener('submit', function() {
                notyf.open({
                    type: 'success',
                    message: 'Signing in...'
                });
            });

        });
    </script>

    @stack('scripts')

</body>

</html>