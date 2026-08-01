<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Register - ' . config('app.name'))</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- App CSS -->
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

<body class="h-full bg-zinc-50 text-zinc-900 flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Background -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#e4e4e7_1px,transparent_1px),linear-gradient(to_bottom,#e4e4e7_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] opacity-45"></div>

    <!-- Card -->
    <div class="relative z-10 w-full max-w-md bg-white border border-zinc-200 rounded-xl shadow-sm p-8">

        <!-- Header -->
        <div class="text-center mb-8">

            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-900 text-white mb-4">
                ✦
            </div>

            <h1 class="text-2xl font-semibold">
                Create Account
            </h1>

            <p class="text-sm text-zinc-500 mt-2">
                Create your account to continue
            </p>

        </div>

        <!-- Form -->
        <form action="{{ route('register.store') }}" method="POST" class="space-y-4">

            @csrf

            <!-- Name -->
            <div>

                <label class="text-sm font-medium">
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autocomplete="name"
                    class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900 outline-none">

            </div>

            <!-- Email -->
            <div>

                <label class="text-sm font-medium">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="email"
                    class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900 outline-none">

            </div>

            <!-- Password -->
            <div>

                <label class="text-sm font-medium">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900 outline-none">

            </div>

            <!-- Confirm Password -->
            <div>

                <label class="text-sm font-medium">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="mt-2 w-full rounded-md border border-zinc-300 px-3 py-2 focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900 outline-none">

            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full rounded-md bg-zinc-900 py-2.5 text-white hover:bg-zinc-800 transition">

                Create Account

            </button>

        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-zinc-500">

            Already have an account?

            <a href="{{ route('login') }}"
                class="font-medium text-zinc-900 hover:underline">

                Sign In

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

            document.querySelector('form').addEventListener('submit', function() {

                notyf.open({
                    type: 'success',
                    message: 'Creating your account...'
                });

            });

        });
    </script>

    @stack('scripts')

</body>

</html>