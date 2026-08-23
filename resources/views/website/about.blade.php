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

    <!-- About Us -->
    <section id="welcome" class="bg-white py-16 sm:py-20">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:gap-16">

                <!-- Content -->
                <div data-aos="fade-right">

                    <p class="text-sm font-bold uppercase tracking-wide text-slate-800">
                        Welcome
                    </p>

                    <h2 class="mt-5 text-3xl font-extrabold tracking-tight text-slate-800 sm:text-4xl">
                        Holy House Group
                    </h2>

                    <div class="mt-7 space-y-6 text-sm leading-7 text-zinc-500 sm:text-base sm:leading-8">

                        <p>
                            At
                            <strong class="font-bold text-zinc-700">
                                Holy House Group
                            </strong>,
                            we don’t just deal in properties — we deliver
                            <strong class="font-bold text-zinc-700">
                                trust, clarity, and long-term value.
                            </strong>
                        </p>

                        <p>
                            We are a professional real estate service provider specializing in
                            <strong class="font-bold text-zinc-700">
                                property rent, sale, and purchase
                            </strong>
                            for both
                            <strong class="font-bold text-zinc-700">
                                residential and commercial
                            </strong>
                            spaces. Our approach is simple: honest advice, verified properties,
                            and complete support from start to finish.
                        </p>

                        <p>
                            Whether you are a
                            <strong class="font-bold text-zinc-700">
                                first-time buyer
                            </strong>,
                            a property owner looking to
                            <strong class="font-bold text-zinc-700">
                                sell or rent
                            </strong>,
                            or a
                            <strong class="font-bold text-zinc-700">
                                business searching for the right space,
                            </strong>
                            our team ensures a smooth, transparent, and hassle-free experience.
                        </p>

                        <p>
                            We believe real estate decisions are important, and that’s why we focus on:
                        </p>

                    </div>


                    <!-- Key Points -->
                    <ul class="mt-5 space-y-4 text-sm text-zinc-500 sm:text-base">

                        <li class="flex items-center gap-3">
                            <span class="text-orange-600">•</span>
                            <span>Genuine property options</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <span class="text-orange-600">•</span>
                            <span>Fair market pricing</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <span class="text-orange-600">•</span>
                            <span>Clear documentation</span>
                        </li>

                        <li class="flex items-center gap-3">
                            <span class="text-orange-600">•</span>
                            <span>Personalized guidance</span>
                        </li>

                    </ul>


                    <p class="mt-7 text-sm leading-7 text-zinc-500 sm:text-base sm:leading-8">

                        At
                        <strong class="font-bold text-zinc-700">
                            Holy House Group
                        </strong>,
                        every client matters. Our goal is not just closing deals,
                        but building
                        <strong class="font-bold text-zinc-700">
                            long-term relationships
                        </strong>
                        based on trust and results.

                    </p>

                </div>


                <!-- Logo -->
                <div class="flex items-center justify-center" data-aos="fade-left">

                    <div class="flex items-center justify-center">

                        <img src="{{ asset('images/logo.png') }}" alt="Holy House Group"
                            class="w-64 object-contain sm:w-80 lg:w-[380px]">

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Milestones -->
    <section id="milestones" class="bg-white py-14 sm:py-16">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <!-- Heading -->
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-3xl font-extrabold tracking-tight text-black sm:text-4xl">
                    Milestones Achieved
                </h2>
            </div>


            <!-- Milestone Cards -->
            <div class="mt-10 grid gap-5 md:grid-cols-3">

                <!-- Experience -->
                <div class="flex min-h-[190px] flex-col items-center justify-center border border-zinc-200 bg-white px-6 py-8 text-center shadow-[0_4px_16px_rgba(0,0,0,0.18)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.22)]"
                    data-aos="fade-up" data-aos-delay="0">

                    <div class="text-5xl font-extrabold leading-none text-black">
                        5+
                    </div>

                    <p class="mt-10 text-lg text-zinc-600 sm:text-xl">
                        Years of Real Estate Expertise
                    </p>

                </div>


                <!-- Clients -->
                <div class="flex min-h-[190px] flex-col items-center justify-center border border-zinc-200 bg-white px-6 py-8 text-center shadow-[0_4px_16px_rgba(0,0,0,0.18)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.22)]"
                    data-aos="fade-up" data-aos-delay="100">

                    <div class="text-5xl font-extrabold leading-none text-black">
                        500+
                    </div>

                    <p class="mt-10 text-lg text-zinc-600 sm:text-xl">
                        Happy Clients Served
                    </p>

                </div>


                <!-- Repeat Business -->
                <div class="flex min-h-[190px] flex-col items-center justify-center border border-zinc-200 bg-white px-6 py-8 text-center shadow-[0_4px_16px_rgba(0,0,0,0.18)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.22)]"
                    data-aos="fade-up" data-aos-delay="200">

                    <div class="text-5xl font-extrabold leading-none text-black">
                        90%+
                    </div>

                    <p class="mt-10 text-lg text-zinc-600 sm:text-xl">
                        Repeat/Referral Business Rate
                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- Our Happy Clients -->
    <section id="happy-clients" class="bg-white py-14 sm:py-16">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <!-- Heading -->
            <div class="text-center" data-aos="fade-up">
                <h2 class="text-3xl font-extrabold tracking-tight text-black sm:text-4xl">
                    Our Happy Clients
                </h2>
            </div>


            <!-- Client Reviews -->
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">


                <!-- Client 1 -->
                <div class="overflow-hidden rounded-md border border-slate-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    data-aos="fade-up" data-aos-delay="0">

                    <div class="border-b border-slate-200 px-5 py-4">

                        <div class="flex items-center justify-between">

                            <h3 class="text-base font-bold text-slate-800">
                                Sandeep Kumar
                            </h3>

                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                G+
                            </span>

                        </div>

                        <div class="mt-1 flex gap-0.5 text-orange-400">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star text-sm"></i>
                            @endfor
                        </div>

                    </div>

                    <div class="px-5 py-4">

                        <p class="text-sm leading-6 text-slate-700">
                            Highly recommended for rent and purchase.
                            Professional approach, polite communication,
                            and complete support. A trustworthy name in
                            real estate services.
                        </p>

                    </div>

                </div>


                <!-- Client 2 -->
                <div class="overflow-hidden rounded-md border border-slate-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    data-aos="fade-up" data-aos-delay="100">

                    <div class="border-b border-slate-200 px-5 py-4">

                        <div class="flex items-center justify-between">

                            <h3 class="text-base font-bold text-slate-800">
                                Pooja Mehta
                            </h3>

                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                G+
                            </span>

                        </div>

                        <div class="mt-1 flex gap-0.5 text-orange-400">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star text-sm"></i>
                            @endfor
                        </div>

                    </div>

                    <div class="px-5 py-4">

                        <p class="text-sm leading-6 text-slate-700">
                            Quick response and genuine listings.
                            Unlike others, Holy House Group shared only
                            verified properties. Saved a lot of time and
                            effort. Very satisfied with their service.
                        </p>

                    </div>

                </div>


                <!-- Client 3 -->
                <div class="overflow-hidden rounded-md border border-slate-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    data-aos="fade-up" data-aos-delay="200">

                    <div class="border-b border-slate-200 px-5 py-4">

                        <div class="flex items-center justify-between">

                            <h3 class="text-base font-bold text-slate-800">
                                Amit Singh
                            </h3>

                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                G+
                            </span>

                        </div>

                        <div class="mt-1 flex gap-0.5 text-orange-400">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star text-sm"></i>
                            @endfor
                        </div>

                    </div>

                    <div class="px-5 py-4">

                        <p class="text-sm leading-6 text-slate-700">
                            Good experience for property sale.
                            Sold my property at a fair market price
                            without any hassle. Timely updates and clear
                            communication throughout the process.
                        </p>

                    </div>

                </div>


                <!-- Client 4 -->
                <div class="overflow-hidden rounded-md border border-slate-200 bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                    data-aos="fade-up" data-aos-delay="300">

                    <div class="border-b border-slate-200 px-5 py-4">

                        <div class="flex items-center justify-between">

                            <h3 class="text-base font-bold text-slate-800">
                                Neha Verma
                            </h3>

                            <span
                                class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                G+
                            </span>

                        </div>

                        <div class="mt-1 flex gap-0.5 text-orange-400">
                            @for ($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star text-sm"></i>
                            @endfor
                        </div>

                    </div>

                    <div class="px-5 py-4">

                        <p class="text-sm leading-6 text-slate-700">
                            Trusted real estate partner. We purchased
                            our first home through Holy House Group.
                            They explained everything clearly and handled
                            all documentation properly. Highly reliable team.
                        </p>

                    </div>

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
