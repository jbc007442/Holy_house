<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Access Denied | The Hostel House</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="min-h-screen bg-zinc-50 flex items-center justify-center px-4">

    <div class="w-full max-w-lg">

        <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="px-8 pt-10 text-center">

                <div class="mx-auto mb-6 w-20 h-20 rounded-2xl
                    bg-red-50 border border-red-100
                    flex items-center justify-center">

                    <i class="fa-solid fa-lock text-3xl text-red-500"></i>

                </div>

                <div class="text-sm font-semibold text-amber-600 tracking-wider uppercase mb-2">
                    Access Restricted
                </div>

                <h1 class="text-3xl font-bold text-zinc-900">
                    Permission Denied
                </h1>

                <p class="mt-3 text-zinc-500 leading-relaxed">
                    You don't have permission to access this page.
                    Please contact your administrator if you believe
                    this is a mistake.
                </p>

            </div>


            {{-- Error Code --}}
            <div class="px-8 py-8">

                <div class="bg-zinc-50 border border-zinc-200 rounded-2xl p-5">

                    <div class="flex items-center gap-4">

                        <div class="w-11 h-11 rounded-xl bg-white border border-zinc-200
                            flex items-center justify-center shrink-0">

                            <i class="fa-solid fa-shield-halved text-zinc-500"></i>

                        </div>

                        <div>
                            <p class="text-xs font-medium text-zinc-400 uppercase tracking-wide">
                                Error Code
                            </p>

                            <p class="text-lg font-bold text-zinc-800">
                                403 — Forbidden
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="px-8 pb-10">

                <div class="flex flex-col sm:flex-row gap-3">

                    @auth
                        <a href="{{ route('dashboard.index') }}"
                            class="flex-1 inline-flex items-center justify-center gap-2
                            px-5 py-3 rounded-xl
                            bg-amber-500 hover:bg-amber-600
                            text-white font-semibold
                            transition-colors">

                            <i class="fa-solid fa-house"></i>

                            Back to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex-1 inline-flex items-center justify-center gap-2
                            px-5 py-3 rounded-xl
                            bg-amber-500 hover:bg-amber-600
                            text-white font-semibold
                            transition-colors">

                            <i class="fa-solid fa-right-to-bracket"></i>

                            Go to Login
                        </a>
                    @endauth

                    <button type="button"
                        onclick="history.back()"
                        class="flex-1 inline-flex items-center justify-center gap-2
                        px-5 py-3 rounded-xl
                        bg-white hover:bg-zinc-50
                        border border-zinc-200
                        text-zinc-700 font-semibold
                        transition-colors">

                        <i class="fa-solid fa-arrow-left"></i>

                        Go Back
                    </button>

                </div>

            </div>

        </div>


        {{-- Footer --}}
        <p class="text-center text-xs text-zinc-400 mt-6">
            © {{ date('Y') }} The Hostel House. All rights reserved.
        </p>

    </div>

</body>

</html>

