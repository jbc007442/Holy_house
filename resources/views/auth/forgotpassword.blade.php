<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Forgot Password - ' . config('app.name'))</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

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

<body class="h-full bg-zinc-50 text-zinc-900 flex items-center justify-center min-h-screen relative overflow-hidden selection:bg-zinc-900 selection:text-white">

    <!-- Background -->
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#e4e4e7_1px,transparent_1px),linear-gradient(to_bottom,#e4e4e7_1px,transparent_1px)] bg-[size:4rem_4rem] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_50%,#000_70%,transparent_100%)] opacity-45 pointer-events-none"></div>

    <!-- Card -->
    <div class="relative z-10 w-full max-w-md bg-white border border-zinc-200 rounded-xl shadow-sm p-8 sm:p-10 m-4">

        <!-- Header -->
        <div class="flex flex-col items-center text-center mb-8">

            <div class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-zinc-900 text-white shadow-sm mb-4">
                <i class="fa-solid fa-lock text-sm"></i>
            </div>

            <h1 class="text-xl font-semibold tracking-tight">
                Forgot password?
            </h1>

            <p class="mt-1 text-sm text-zinc-500">
                No worries, we'll send you reset instructions.
            </p>

        </div>

        <!-- Form -->
        <form method="POST"
              action="{{ route('forgot-password.post') }}"
              class="space-y-5">

            @csrf

            <!-- Email -->
            <div class="space-y-2">

                <label for="email"
                    class="text-xs font-medium text-zinc-900">
                    Email
                </label>

                <div class="relative">

                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-400">
                        <i class="fa-regular fa-envelope"></i>
                    </span>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        required
                        placeholder="m@example.com"
                        class="w-full h-10 rounded-md border border-zinc-200 pl-10 pr-3 text-sm bg-transparent placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-zinc-900">

                </div>

            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full h-10 rounded-md bg-zinc-900 text-white text-sm font-medium hover:bg-zinc-800 transition flex items-center justify-center gap-2">

                <span>Send password reset link</span>

                <i class="fa-solid fa-paper-plane text-xs"></i>

            </button>

        </form>

        <!-- Footer -->
        <div class="mt-8 text-center text-xs text-zinc-500">

            Remember your password?

            <a href="{{ route('login') }}"
                class="font-medium text-zinc-900 hover:underline ml-1">

                Sign in

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
        document.addEventListener('DOMContentLoaded', function () {

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

            document.querySelector('form').addEventListener('submit', function () {
                notyf.open({
                    type: 'success',
                    message: 'Sending reset link...'
                });
            });

        });
    </script>

    @stack('scripts')

</body>

</html>