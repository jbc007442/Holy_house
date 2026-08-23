<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Hostel House Management System">
    <meta name="keywords" content="hostel, accommodation, student housing">
    <meta name="author" content="">
    <title>@yield('title', 'Hostel House | Luxury Co-Living')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800">

    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-slate-200 shadow-sm">

        <div class="max-w-7xl mx-auto px-6">

            <div class="h-20 flex items-center justify-between">

                <!-- Logo -->

                <a href="/" class="flex items-center gap-4 group" data-aos="fade-right">

                    <div
                        class="h-14 w-14 rounded-2xl flex items-center justify-center border border-4 group-hover:scale-110 transition duration-300">

                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-16 object-contain">

                    </div>

                    <div>

                        <h1 class="text-2xl font-bold text-slate-800">

                            The Holy House

                        </h1>

                    </div>

                </a>

                <!-- Navigation -->

                <nav class="hidden lg:flex items-center gap-8 font-medium text-slate-700" data-aos="fade-down">

                    <a href="{{ url('/') }}"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        Home

                    </a>

                    <a href="{{ url('/services') }}"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        Our Services

                    </a>

                    <a href="{{ url('/about') }}"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        About Us

                    </a>

                    <a href="{{ url('/contact') }}"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        Contact Us

                    </a>

                </nav>

                <!-- Right -->

                <div class="flex items-center gap-4" data-aos="fade-left">

                    @auth

                        <a href="{{ route('dashboard.index') }}"
                            class="hidden md:inline-flex items-center gap-2 rounded-xl border border-emerald-600 bg-white px-6 py-3 font-semibold text-emerald-600 transition-all duration-300 hover:bg-emerald-600 hover:text-white hover:shadow-lg">

                            <i class="fa-solid fa-gauge-high"></i>

                            Dashboard

                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="hidden md:inline-flex items-center gap-2 rounded-xl border border-blue-600 bg-white px-6 py-3 font-semibold text-blue-600 transition-all duration-300 hover:bg-blue-600 hover:text-white hover:shadow-lg">

                            <i class="fa-solid fa-right-to-bracket"></i>

                            Login

                        </a>

                    @endauth

                    <!-- Mobile Menu -->

                    <button
                        class="lg:hidden h-11 w-11 rounded-xl border border-slate-300 bg-white text-slate-700 transition-all duration-300 hover:bg-slate-100">

                        <i class="fa-solid fa-bars text-lg"></i>

                    </button>

                </div>

            </div>

        </div>

    </header>

    <!-- Our Services -->
    <section id="services" class="bg-white py-14 sm:py-16">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <!-- Section Heading -->
            <div class="mx-auto max-w-2xl text-center" data-aos="fade-up">
                <h2 class="text-3xl font-extrabold tracking-tight text-black sm:text-4xl lg:text-5xl">
                    Our Services
                </h2>

                <p class="mt-3 text-sm text-zinc-600 sm:text-base">
                    Services designed for comfort and ease
                </p>
            </div>


            <!-- Services Grid -->
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">


                <!-- Hotel -->
                <div class="group flex min-h-[350px] flex-col items-center rounded-2xl border border-zinc-200 bg-white px-5 py-6 text-center shadow-[0_4px_18px_rgba(0,0,0,0.15)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_28px_rgba(0,0,0,0.20)]"
                    data-aos="fade-up" data-aos-delay="0">

                    <div class="flex h-24 items-center justify-center">
                        <span class="text-7xl">🏨</span>
                    </div>

                    <h3 class="mt-3 text-2xl font-extrabold text-black">
                        Hotel
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-zinc-600 sm:text-base">
                        Comfortable hotels designed for short stays,
                        business travel and relaxed visits.
                    </p>

                    <a href="https://wa.me/9821120188?text={{ urlencode('Hello Holy House Group, I am interested in your Hotel services. Please share more details.') }}"
                        target="_blank" rel="noopener noreferrer"
                        class="mt-auto inline-flex items-center gap-2 rounded-br-2xl bg-orange-600 px-7 py-3 text-sm font-bold text-white shadow-lg transition-all duration-300 hover:bg-orange-700 hover:shadow-xl">
                        <i class="fa-regular fa-thumbs-up"></i>
                        Enquire Now
                    </a>

                </div>


                <!-- Apartment -->
                <div class="group flex min-h-[350px] flex-col items-center rounded-2xl border border-zinc-200 bg-white px-5 py-6 text-center shadow-[0_4px_18px_rgba(0,0,0,0.15)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_28px_rgba(0,0,0,0.20)]"
                    data-aos="fade-up" data-aos-delay="100">

                    <div class="flex h-24 items-center justify-center">
                        <span class="text-7xl">🏢</span>
                    </div>

                    <h3 class="mt-3 text-2xl font-extrabold text-black">
                        Apartment
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-zinc-600 sm:text-base">
                        Well-managed service apartments offering
                        space, comfort and convenience.
                    </p>

                    <a href="https://wa.me/9821120188?text={{ urlencode('Hello Holy House Group, I am interested in your Apartment services. Please share more details.') }}"
                        target="_blank" rel="noopener noreferrer"
                        class="mt-auto inline-flex items-center gap-2 rounded-br-2xl bg-orange-600 px-7 py-3 text-sm font-bold text-white shadow-lg transition-all duration-300 hover:bg-orange-700 hover:shadow-xl">
                        <i class="fa-regular fa-thumbs-up"></i>
                        Enquire Now
                    </a>

                </div>


                <!-- Farm House -->
                <div class="group flex min-h-[350px] flex-col items-center rounded-2xl border border-zinc-200 bg-white px-5 py-6 text-center shadow-[0_4px_18px_rgba(0,0,0,0.15)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_28px_rgba(0,0,0,0.20)]"
                    data-aos="fade-up" data-aos-delay="200">

                    <div class="flex h-24 items-center justify-center">
                        <span class="text-7xl">🏡</span>
                    </div>

                    <h3 class="mt-3 text-2xl font-extrabold uppercase text-black">
                        Farm House
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-zinc-600 sm:text-base">
                        Private farmhouses perfect for parties,
                        celebrations and weekend getaways.
                    </p>

                    <a href="https://wa.me/9821120188?text={{ urlencode('Hello Holy House Group, I am interested in your Farm House services. Please share more details.') }}"
                        target="_blank" rel="noopener noreferrer"
                        class="mt-auto inline-flex items-center gap-2 rounded-br-2xl bg-orange-600 px-7 py-3 text-sm font-bold text-white shadow-lg transition-all duration-300 hover:bg-orange-700 hover:shadow-xl">
                        <i class="fa-regular fa-thumbs-up"></i>
                        Enquire Now
                    </a>

                </div>


                <!-- Rental -->
                <div class="group flex min-h-[350px] flex-col items-center rounded-2xl border border-zinc-200 bg-white px-5 py-6 text-center shadow-[0_4px_18px_rgba(0,0,0,0.15)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_10px_28px_rgba(0,0,0,0.20)]"
                    data-aos="fade-up" data-aos-delay="300">

                    <div class="flex h-24 items-center justify-center">
                        <span class="text-7xl">🏠</span>
                    </div>

                    <h3 class="mt-3 text-2xl font-extrabold text-black">
                        Rental
                    </h3>

                    <p class="mt-2 text-sm leading-6 text-zinc-600 sm:text-base">
                        Comfortable long-stay homes ideal for families
                        and working professionals.
                    </p>

                    <a href="https://wa.me/9821120188?text={{ urlencode('Hello Holy House Group, I am interested in your Rental services. Please share more details.') }}"
                        target="_blank" rel="noopener noreferrer"
                        class="mt-auto inline-flex items-center gap-2 rounded-br-2xl bg-orange-600 px-7 py-3 text-sm font-bold text-white shadow-lg transition-all duration-300 hover:bg-orange-700 hover:shadow-xl">
                        <i class="fa-regular fa-thumbs-up"></i>
                        Enquire Now
                    </a>

                </div>

            </div>

        </div>

    </section>

    <!-- Footer -->
    <footer class="border-t border-zinc-200 bg-white">

        <!-- Main Footer -->
        <div class="mx-auto max-w-6xl px-5 py-12 lg:px-6">

            <div class="grid gap-10 sm:grid-cols-2 lg:grid-cols-4">

                <!-- Brand -->
                <div data-aos="fade-up">

                    <a href="/" class="inline-flex items-center gap-3">

                        <img src="{{ asset('images/logo.png') }}" alt="The Holy House"
                            class="h-14 w-14 object-contain">

                        <div>
                            <h2 class="text-lg font-extrabold text-zinc-900">
                                The Holy House
                            </h2>

                            <p class="text-xs text-zinc-500">
                                Comfort. Experience. Belong.
                            </p>
                        </div>

                    </a>

                    <p class="mt-5 max-w-xs text-sm leading-6 text-zinc-600">
                        Holy House Group offers carefully curated hotels,
                        service apartments, farmhouses and long-stay homes
                        designed for comfortable and memorable experiences.
                    </p>

                </div>


                <!-- Quick Links -->
                <div data-aos="fade-up" data-aos-delay="100">

                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">
                        Quick Links
                    </h3>

                    <ul class="mt-5 space-y-3 text-sm">

                        <li>
                            <a href="#" class="text-zinc-600 transition hover:text-orange-600">
                                Home
                            </a>
                        </li>

                        <li>
                            <a href="#about" class="text-zinc-600 transition hover:text-orange-600">
                                About Us
                            </a>
                        </li>

                        <li>
                            <a href="#services" class="text-zinc-600 transition hover:text-orange-600">
                                Our Services
                            </a>
                        </li>

                        <li>
                            <a href="#why-choose-us" class="text-zinc-600 transition hover:text-orange-600">
                                Why Choose Us
                            </a>
                        </li>

                        <li>
                            <a href="#testimonials" class="text-zinc-600 transition hover:text-orange-600">
                                Testimonials
                            </a>
                        </li>

                        <li>
                            <a href="#contact" class="text-zinc-600 transition hover:text-orange-600">
                                Contact Us
                            </a>
                        </li>

                    </ul>

                </div>


                <!-- Our Services -->
                <div data-aos="fade-up" data-aos-delay="200">

                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">
                        Our Services
                    </h3>

                    <ul class="mt-5 space-y-3 text-sm text-zinc-600">

                        <li>
                            <a href="#services" class="transition hover:text-orange-600">
                                Hotels
                            </a>
                        </li>

                        <li>
                            <a href="#services" class="transition hover:text-orange-600">
                                Service Apartments
                            </a>
                        </li>

                        <li>
                            <a href="#services" class="transition hover:text-orange-600">
                                Farm Houses
                            </a>
                        </li>

                        <li>
                            <a href="#services" class="transition hover:text-orange-600">
                                Long-Stay Rentals
                            </a>
                        </li>

                    </ul>

                </div>


                <!-- Contact -->
                <div data-aos="fade-up" data-aos-delay="300">

                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-900">
                        Get In Touch
                    </h3>

                    <div class="mt-5 space-y-4 text-sm text-zinc-600">

                        <!-- WhatsApp -->
                        <a href="https://wa.me/919821120188" target="_blank" rel="noopener noreferrer"
                            class="group flex items-center gap-3 transition hover:text-orange-600">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-50 text-orange-600 transition group-hover:bg-orange-600 group-hover:text-white">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                            </span>

                            <span>
                                +91 98211 20188
                            </span>
                        </a>


                        <!-- Email -->
                        <a href="mailto:info@holyhousegroup.com"
                            class="group flex items-center gap-3 transition hover:text-orange-600">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-lg bg-orange-50 text-orange-600 transition group-hover:bg-orange-600 group-hover:text-white">
                                <i class="fa-regular fa-envelope"></i>
                            </span>

                            <span>
                                info@holyhousegroup.com
                            </span>
                        </a>


                        <!-- Location -->
                        <div class="flex items-start gap-3">

                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-orange-50 text-orange-600">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>

                            <span class="leading-6">
                                Sector 52,<br>
                                Gurugram, Haryana
                            </span>

                        </div>

                    </div>


                    <!-- Social Icons -->
                    <div class="mt-6 flex items-center gap-3">

                        <a href="#" aria-label="Instagram"
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 text-zinc-700 transition-all duration-300 hover:bg-orange-600 hover:text-white">
                            <i class="fa-brands fa-instagram"></i>
                        </a>

                        <a href="#" aria-label="Facebook"
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 text-zinc-700 transition-all duration-300 hover:bg-orange-600 hover:text-white">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>

                        <a href="#" aria-label="Google"
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 text-zinc-700 transition-all duration-300 hover:bg-orange-600 hover:text-white">
                            <i class="fa-brands fa-google"></i>
                        </a>

                        <a href="https://wa.me/919821120188" target="_blank" rel="noopener noreferrer"
                            aria-label="WhatsApp"
                            class="flex h-9 w-9 items-center justify-center rounded-lg bg-zinc-100 text-zinc-700 transition-all duration-300 hover:bg-orange-600 hover:text-white">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- Bottom Bar -->
        <div class="border-t border-zinc-200">

            <div
                class="mx-auto flex max-w-6xl flex-col items-center justify-between gap-3 px-5 py-5 text-center sm:flex-row sm:text-left lg:px-6">

                <p class="text-xs text-zinc-500 sm:text-sm">
                    © {{ date('Y') }}
                    <span class="font-semibold text-zinc-700">
                        Holy House Group
                    </span>.
                    All Rights Reserved.
                </p>

                <div class="flex items-center gap-5 text-xs text-zinc-500 sm:text-sm">

                    <a href="#" class="transition hover:text-orange-600">
                        Privacy Policy
                    </a>

                    <a href="#" class="transition hover:text-orange-600">
                        Terms &amp; Conditions
                    </a>

                </div>

            </div>

        </div>

    </footer>


</body>

</html>
