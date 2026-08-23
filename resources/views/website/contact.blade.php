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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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

    <!-- Contact Information -->
    <section id="contact-info" class="bg-white py-12 sm:py-14">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <div class="grid gap-6 md:grid-cols-2">

                <!-- Phone -->
                <a href="tel:+919821120188"
                    class="group flex min-h-[190px] flex-col items-center justify-center border border-zinc-200 bg-white px-6 py-8 text-center shadow-[0_4px_16px_rgba(0,0,0,0.18)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.22)]"
                    data-aos="fade-right">

                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-md bg-orange-600 text-white transition-transform duration-300 group-hover:scale-105">
                        <i class="fa-solid fa-phone text-3xl"></i>
                    </div>

                    <h3 class="mt-7 text-2xl font-extrabold text-black">
                        Phone
                    </h3>

                    <p class="mt-5 text-base text-zinc-700">
                        +91-9821120188
                    </p>

                </a>


                <!-- Email -->
                <a href="mailto:holyhousegroup@gmail.com"
                    class="group flex min-h-[190px] flex-col items-center justify-center border border-zinc-200 bg-white px-6 py-8 text-center shadow-[0_4px_16px_rgba(0,0,0,0.18)] transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_8px_24px_rgba(0,0,0,0.22)]"
                    data-aos="fade-left">

                    <div
                        class="flex h-14 w-14 items-center justify-center rounded-md bg-orange-600 text-white transition-transform duration-300 group-hover:scale-105">
                        <i class="fa-regular fa-envelope text-3xl"></i>
                    </div>

                    <h3 class="mt-7 text-2xl font-extrabold text-black">
                        Email
                    </h3>

                    <p class="mt-5 text-base text-zinc-700">
                        holyhousegroup@gmail.com
                    </p>

                </a>

            </div>

        </div>

    </section>

    <!-- Contact / Enquiry Section -->
    <section id="contact" class="bg-white py-14 sm:py-16">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <!-- Heading -->
            <div class="text-center" data-aos="fade-up">

                <h2 class="text-3xl font-extrabold tracking-tight text-black sm:text-4xl">
                    Planning a Stay or Experience?
                </h2>

                <p class="mt-4 text-base text-zinc-500 sm:text-lg">
                    Get in touch with Holy House Group for a smooth and comfortable experience.
                </p>

            </div>


            <!-- Contact Grid -->
            <div class="mt-12 grid items-stretch gap-6 lg:grid-cols-2">


                <!-- Map -->
                <div class="h-[380px] overflow-hidden rounded-lg border border-zinc-200 shadow-sm sm:h-[420px]"
                    data-aos="fade-right">

                    <iframe src="https://www.google.com/maps?q=Sector%2052,%20Gurugram,%20Haryana&output=embed"
                        class="h-full w-full border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen></iframe>

                </div>


                <!-- Contact Form -->
                <div data-aos="fade-left">

                    <form action="#" method="POST" class="space-y-4">

                        @csrf

                        <!-- Name -->
                        <div>

                            <label for="contact_name" class="mb-1.5 block text-sm font-medium text-zinc-500">
                                Name <span class="text-red-500">*</span>
                            </label>

                            <input id="contact_name" name="name" type="text" placeholder="Name" required
                                class="h-12 w-full rounded-md border border-zinc-400 bg-white px-5 text-sm text-zinc-800 outline-none transition focus:border-orange-600 focus:ring-2 focus:ring-orange-600/10">

                        </div>


                        <!-- Number -->
                        <div>

                            <label for="contact_number" class="mb-1.5 block text-sm font-medium text-zinc-500">
                                Number <span class="text-red-500">*</span>
                            </label>

                            <input id="contact_number" name="number" type="tel" placeholder="Number" required
                                class="h-12 w-full rounded-md border border-zinc-400 bg-white px-5 text-sm text-zinc-800 outline-none transition focus:border-orange-600 focus:ring-2 focus:ring-orange-600/10">

                        </div>


                        <!-- Email -->
                        <div>

                            <label for="contact_email" class="mb-1.5 block text-sm font-medium text-zinc-500">
                                Email <span class="text-red-500">*</span>
                            </label>

                            <input id="contact_email" name="email" type="email" placeholder="Email" required
                                class="h-12 w-full rounded-md border border-zinc-400 bg-white px-5 text-sm text-zinc-800 outline-none transition focus:border-orange-600 focus:ring-2 focus:ring-orange-600/10">

                        </div>


                        <!-- Message -->
                        <div>

                            <label for="contact_message" class="mb-1.5 block text-sm font-medium text-zinc-500">
                                Message
                            </label>

                            <textarea id="contact_message" name="message" rows="4" placeholder="Message"
                                class="w-full resize-none rounded-md border border-zinc-400 bg-white px-5 py-3 text-sm text-zinc-800 outline-none transition focus:border-orange-600 focus:ring-2 focus:ring-orange-600/10"></textarea>

                        </div>


                        <!-- Submit -->
                        <button type="submit"
                            class="h-12 w-full rounded-md bg-orange-600 px-6 text-sm font-bold text-white shadow-md transition-all duration-300 hover:bg-orange-700 hover:shadow-lg">
                            Send
                        </button>

                    </form>

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
