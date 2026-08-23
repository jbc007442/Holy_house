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
    <!-- Plus Jakarta Sans Font for Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
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

    <!-- Hero Carousel -->
    <section class="hero-section relative h-[calc(100vh-80px)] min-h-[620px] overflow-hidden">

        <div class="swiper heroSwiper h-full w-full">

            <div class="swiper-wrapper">

                <!-- Slide 1 -->
                <div class="swiper-slide relative h-full">

                    <img src="{{ asset('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTmV8_s51ZjlMqIXX3iER-d-Q0-zcenGcbgtu9XPHinVw&s=10') }}"
                        alt="Holy House Group" class="absolute inset-0 h-full w-full object-cover">

                    <!-- Dark Overlay -->
                    <div class="absolute inset-0 bg-black/55"></div>

                    <!-- Content -->
                    <div class="relative z-10 flex h-full items-center justify-center px-6 text-center">

                        <div class="max-w-5xl text-white" data-aos="fade-up">

                            <h2
                                class="text-4xl font-extrabold leading-tight tracking-tight sm:text-5xl md:text-6xl lg:text-7xl">
                                Holy House Group brings you a curated
                                <span class="block">
                                    blend of unique experiences
                                </span>
                            </h2>

                            <p class="mx-auto mt-8 max-w-3xl text-lg font-medium text-white/95 sm:text-xl md:text-2xl">
                                Select the type of stay you are looking for
                            </p>

                            <a href="#contact"
                                class="mt-9 inline-flex items-center gap-3 rounded-xl bg-orange-600 px-8 py-4 text-lg font-bold text-white shadow-xl transition-all duration-300 hover:bg-orange-700 hover:-translate-y-1 hover:shadow-2xl">
                                Contact Us

                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                        </div>

                    </div>

                </div>


                <!-- Slide 2 -->
                <div class="swiper-slide relative h-full">

                    <img src="{{ asset('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRZyDsiqRFXzmTMtEjG5z9kc95cohnThq-cP_jK2-ilYQ&s=10') }}"
                        alt="Luxury Stay" class="absolute inset-0 h-full w-full object-cover">

                    <div class="absolute inset-0 bg-black/55"></div>

                    <div class="relative z-10 flex h-full items-center justify-center px-6 text-center">

                        <div class="max-w-5xl text-white">

                            <h2 class="text-4xl font-extrabold leading-tight sm:text-5xl md:text-6xl lg:text-7xl">
                                Stay. Experience.
                                <span class="block">
                                    Belong.
                                </span>
                            </h2>

                            <p class="mx-auto mt-8 max-w-3xl text-lg font-medium text-white/95 sm:text-xl md:text-2xl">
                                Discover thoughtfully designed spaces made for modern living.
                            </p>

                            <a href="#contact"
                                class="mt-9 inline-flex items-center gap-3 rounded-xl bg-orange-600 px-8 py-4 text-lg font-bold text-white shadow-xl transition-all duration-300 hover:bg-orange-700 hover:-translate-y-1">
                                Explore More

                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                        </div>

                    </div>

                </div>


                <!-- Slide 3 -->
                <div class="swiper-slide relative h-full">

                    <img src="{{ asset('https://content.jdmagicbox.com/v2/comp/delhi/l2/011pxx11.xx11.250228163925.b9l2/catalogue/v0nwcjmricl93tt-kuwus55svf.jpg') }}"
                        alt="Premium Accommodation" class="absolute inset-0 h-full w-full object-cover">

                    <div class="absolute inset-0 bg-black/55"></div>

                    <div class="relative z-10 flex h-full items-center justify-center px-6 text-center">

                        <div class="max-w-5xl text-white">

                            <h2 class="text-4xl font-extrabold leading-tight sm:text-5xl md:text-6xl lg:text-7xl">
                                A place to call
                                <span class="block">
                                    your own
                                </span>
                            </h2>

                            <p class="mx-auto mt-8 max-w-3xl text-lg font-medium text-white/95 sm:text-xl md:text-2xl">
                                Comfortable spaces, premium amenities and memorable experiences.
                            </p>

                            <a href="#contact"
                                class="mt-9 inline-flex items-center gap-3 rounded-xl bg-orange-600 px-8 py-4 text-lg font-bold text-white shadow-xl transition-all duration-300 hover:bg-orange-700 hover:-translate-y-1">
                                Contact Us

                                <i class="fa-solid fa-arrow-right"></i>
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- About Holy House Group -->
    <section id="about" class="bg-white py-12 sm:py-16">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <div class="grid items-center gap-8 lg:grid-cols-2 lg:gap-12">

                <!-- Image -->
                <div class="overflow-hidden rounded-xl" data-aos="fade-right">
                    <img src="https://holyhousegroup.com/wp-content/uploads/2026/01/ChatGPT-Image-Jan-6-2026-12_41_30-AM.png"
                        alt="Holy House Group"
                        class="h-[300px] w-full object-cover transition-transform duration-700 hover:scale-105 sm:h-[350px]">
                </div>


                <!-- Content -->
                <div data-aos="fade-left" data-aos-delay="100">

                    <h2 class="text-2xl font-extrabold uppercase tracking-tight text-black sm:text-3xl lg:text-4xl">
                        About Holy House Group
                    </h2>

                    <div class="mt-4 space-y-4 text-sm leading-6 text-zinc-600 sm:text-base sm:leading-7">

                        <p>
                            <strong class="font-bold text-zinc-700">
                                Holy House Group
                            </strong>
                            is a hospitality-focused brand offering carefully curated
                            hotels, service apartments, long-stay homes and private retreats.
                        </p>

                        <p>
                            We prioritise comfort, quality and seamless experiences through
                            thoughtful selection and professional management of every space.
                        </p>

                        <p>
                            Our aim is simple — to deliver dependable, comfortable and memorable
                            experiences every time.
                        </p>

                    </div>


                    <!-- Button -->
                    <div class="mt-6">

                        <a href="#contact"
                            class="group inline-flex items-center gap-2 rounded-br-2xl bg-orange-600 px-6 py-3 text-sm font-bold text-white shadow-md transition-all duration-300 hover:-translate-y-0.5 hover:bg-orange-700 hover:shadow-lg">
                            <i
                                class="fa-regular fa-circle-right text-base transition-transform duration-300 group-hover:translate-x-1"></i>

                            Know More
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

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

    <!-- Why Choose Us -->
    <section id="why-choose-us" class="bg-white py-14 sm:py-16">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <!-- Heading -->
            <div class="text-center" data-aos="fade-up">

                <h2 class="text-3xl font-extrabold tracking-tight text-black sm:text-4xl lg:text-5xl">
                    Why Choose Us
                </h2>

                <p class="mt-3 text-sm text-zinc-600 sm:text-base">
                    Trusted for Comfort. Chosen for Experience.
                </p>

                <div class="mx-auto mt-3 h-px w-36 bg-zinc-800"></div>

            </div>


            <!-- Statistics -->
            <div class="mt-8 grid grid-cols-1 gap-8 sm:grid-cols-2 sm:gap-16">

                <!-- Experience -->
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">

                    <div class="text-5xl font-extrabold leading-none text-black sm:text-6xl">
                        5+
                    </div>

                    <p class="mt-8 text-lg text-zinc-600 sm:text-xl">
                        Years of Hospitality Experience
                    </p>

                </div>


                <!-- Guests -->
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">

                    <div class="text-5xl font-extrabold leading-none text-black sm:text-6xl">
                        500+
                    </div>

                    <p class="mt-8 text-lg text-zinc-600 sm:text-xl">
                        Happy Guests Served
                    </p>

                </div>

            </div>


            <!-- Benefits -->
            <div class="mt-14 grid grid-cols-1 gap-5 text-center sm:grid-cols-2 lg:grid-cols-4 lg:gap-8"
                data-aos="fade-up" data-aos-delay="300">

                <!-- Benefit 1 -->
                <div class="flex items-center justify-center gap-2 text-sm font-medium text-zinc-800 sm:text-base">
                    <span class="text-base">✅</span>
                    <span>Thoughtfully Curated Spaces</span>
                </div>


                <!-- Benefit 2 -->
                <div class="flex items-center justify-center gap-2 text-sm font-medium text-zinc-800 sm:text-base">
                    <span class="text-base">✅</span>
                    <span>Professionally Managed</span>
                </div>


                <!-- Benefit 3 -->
                <div class="flex items-center justify-center gap-2 text-sm font-medium text-zinc-800 sm:text-base">
                    <span class="text-base">✅</span>
                    <span>Comfort You Can Rely On</span>
                </div>


                <!-- Benefit 4 -->
                <div class="flex items-center justify-center gap-2 text-sm font-medium text-zinc-800 sm:text-base">
                    <span class="text-base">✅</span>
                    <span>Seamless &amp; Hassle-Free Stay</span>
                </div>

            </div>

        </div>

    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="bg-white py-14 sm:py-16">

        <div class="mx-auto max-w-6xl px-5 lg:px-6">

            <div class="rounded-sm bg-zinc-100 px-4 py-10 sm:px-6 lg:px-8">

                <!-- Heading -->
                <div class="text-center" data-aos="fade-up">
                    <h2 class="text-3xl font-extrabold tracking-tight text-black sm:text-4xl">
                        Testimonials
                    </h2>
                </div>


                <!-- Testimonials Grid -->
                <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">


                    <!-- Testimonial 1 -->
                    <div class="overflow-hidden rounded-md border border-zinc-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                        data-aos="fade-up" data-aos-delay="0">

                        <!-- Header -->
                        <div class="border-b border-zinc-200 px-4 py-4">

                            <div class="flex items-center justify-between">

                                <h3 class="text-base font-bold text-zinc-800">
                                    Sandeep Kumar
                                </h3>

                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                    G+
                                </span>

                            </div>

                            <div class="mt-1 flex gap-0.5 text-orange-400">
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                            </div>

                        </div>

                        <!-- Review -->
                        <div class="px-4 py-3">

                            <p class="text-sm leading-6 text-slate-700">
                                Highly recommended for rent and purchase.
                                Professional approach, polite communication,
                                and complete support. A trustworthy name in
                                real estate services.
                            </p>

                        </div>

                    </div>


                    <!-- Testimonial 2 -->
                    <div class="overflow-hidden rounded-md border border-zinc-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                        data-aos="fade-up" data-aos-delay="100">

                        <div class="border-b border-zinc-200 px-4 py-4">

                            <div class="flex items-center justify-between">

                                <h3 class="text-base font-bold text-zinc-800">
                                    Pooja Mehta
                                </h3>

                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                    G+
                                </span>

                            </div>

                            <div class="mt-1 flex gap-0.5 text-orange-400">
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                            </div>

                        </div>

                        <div class="px-4 py-3">

                            <p class="text-sm leading-6 text-slate-700">
                                Quick response and genuine listings.
                                Unlike others, Holy House Group shared only
                                verified properties. Saved a lot of time and
                                effort. Very satisfied with their service.
                            </p>

                        </div>

                    </div>


                    <!-- Testimonial 3 -->
                    <div class="overflow-hidden rounded-md border border-zinc-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                        data-aos="fade-up" data-aos-delay="200">

                        <div class="border-b border-zinc-200 px-4 py-4">

                            <div class="flex items-center justify-between">

                                <h3 class="text-base font-bold text-zinc-800">
                                    Amit Singh
                                </h3>

                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                    G+
                                </span>

                            </div>

                            <div class="mt-1 flex gap-0.5 text-orange-400">
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                            </div>

                        </div>

                        <div class="px-4 py-3">

                            <p class="text-sm leading-6 text-slate-700">
                                Good experience for property sale. Sold my
                                property at a fair market price without any
                                hassle. Timely updates and clear communication
                                throughout the process.
                            </p>

                        </div>

                    </div>


                    <!-- Testimonial 4 -->
                    <div class="overflow-hidden rounded-md border border-zinc-200 bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-lg"
                        data-aos="fade-up" data-aos-delay="300">

                        <div class="border-b border-zinc-200 px-4 py-4">

                            <div class="flex items-center justify-between">

                                <h3 class="text-base font-bold text-zinc-800">
                                    Neha Verma
                                </h3>

                                <span
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">
                                    G+
                                </span>

                            </div>

                            <div class="mt-1 flex gap-0.5 text-orange-400">
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                                <i class="fa-solid fa-star text-sm"></i>
                            </div>

                        </div>

                        <div class="px-4 py-3">

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

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            AOS.init({
                duration: 900,
                once: true,
                offset: 50,
            });

            new Swiper(".heroSwiper", {
                loop: true,

                effect: "fade",

                fadeEffect: {
                    crossFade: true,
                },

                speed: 1200,

                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: false,
                },

                allowTouchMove: true,
            });

        });
    </script>
</body>

</html>
