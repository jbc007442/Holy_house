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
    <link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
      integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer" />
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

                    <a href="#"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        Home

                    </a>

                    <a href="#"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        Rooms

                    </a>

                    <a href="#"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        Facilities

                    </a>

                    <a href="#"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        Gallery

                    </a>

                    <a href="#"
                        class="relative hover:text-blue-600 transition after:absolute after:left-0 after:-bottom-1 after:h-0.5 after:w-0 after:bg-blue-600 hover:after:w-full after:transition-all">

                        Contact

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

    <!-- Hero -->
    <section class="bg-white py-20">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-2 gap-16 items-center">

                <!-- Left Content -->

                <div data-aos="fade-right">

                    <span
                        class="inline-flex items-center rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

                        Welcome to The Hostel House

                    </span>

                    <h1 class="mt-6 text-5xl lg:text-6xl font-bold leading-tight text-slate-900">

                        Comfortable Living

                        <span class="block text-blue-600">

                            Starts Here.

                        </span>

                    </h1>

                    <p class="mt-6 text-lg leading-8 text-slate-600">

                        Experience premium hostel accommodation with modern rooms,
                        secure access, high-speed Wi-Fi, housekeeping and a peaceful
                        environment for students and working professionals.

                    </p>


                </div>

                <!-- Right Slider -->

                <div data-aos="fade-left">

                    <div class="overflow-hidden rounded-3xl shadow-2xl">

                        <div class="swiper heroSwiper">

                            <div class="swiper-wrapper">

                                <div class="swiper-slide">

                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcToSUgq0A9SwPjSgqwzqFWgG21vchRRnlqdXBon4TAtDA&s=10"
                                        class="h-[500px] w-full object-cover">

                                </div>

                                <div class="swiper-slide">

                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdVUI5Dxuwq19qd_LvY_ZEjnLh6ht1tJ6yOo_Rmw_VfA&s=10"
                                        class="h-[500px] w-full object-cover">

                                </div>

                                <div class="swiper-slide">

                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTOmDZObyJLtOMh_QXMCAFz8381_HuCb3W1TeEhjRFo1Q&s=10"
                                        class="h-[500px] w-full object-cover">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <section class="py-24 bg-slate-50">

        <div class="max-w-7xl mx-auto px-6">

            <div class="grid lg:grid-cols-2 gap-20 items-center">

                <!-- Images -->

                <div class="relative" data-aos="fade-right">

                    <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=900"
                        class="rounded-3xl shadow-2xl w-full h-[520px] object-cover">

                    <div class="absolute -bottom-8 -right-8 bg-white rounded-2xl shadow-xl p-6">

                        <h3 class="text-4xl font-bold text-blue-600">

                            10+

                        </h3>

                        <p class="mt-2 text-slate-600">

                            Years of Hospitality

                        </p>

                    </div>

                </div>

                <!-- Content -->

                <div data-aos="fade-left">

                    <span class="inline-flex rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

                        About The Hostel House

                    </span>

                    <h2 class="mt-6 text-4xl lg:text-5xl font-bold text-slate-900 leading-tight">

                        More Than Just
                        <span class="text-blue-600">

                            A Hostel

                        </span>

                    </h2>

                    <p class="mt-6 text-lg leading-8 text-slate-600">

                        The Hostel House offers a modern, safe and comfortable
                        living environment designed for students and working
                        professionals. Our thoughtfully designed spaces combine
                        affordability with premium amenities, creating a place
                        where you can study, work and relax with peace of mind.

                    </p>

                    <div class="mt-10 grid grid-cols-2 gap-6">

                        <div class="flex gap-4">

                            <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">

                                <i class="fa-solid fa-wifi text-blue-600"></i>

                            </div>

                            <div>

                                <h4 class="font-semibold">

                                    High-Speed Wi-Fi

                                </h4>

                                <p class="text-sm text-slate-500">

                                    Fast internet throughout the hostel.

                                </p>

                            </div>

                        </div>

                        <div class="flex gap-4">

                            <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">

                                <i class="fa-solid fa-shield-halved text-green-600"></i>

                            </div>

                            <div>

                                <h4 class="font-semibold">

                                    24×7 Security

                                </h4>

                                <p class="text-sm text-slate-500">

                                    CCTV and secure access control.

                                </p>

                            </div>

                        </div>

                        <div class="flex gap-4">

                            <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center">

                                <i class="fa-solid fa-broom text-amber-600"></i>

                            </div>

                            <div>

                                <h4 class="font-semibold">

                                    Housekeeping

                                </h4>

                                <p class="text-sm text-slate-500">

                                    Daily cleaning and maintenance.

                                </p>

                            </div>

                        </div>

                        <div class="flex gap-4">

                            <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center">

                                <i class="fa-solid fa-mug-hot text-purple-600"></i>

                            </div>

                            <div>

                                <h4 class="font-semibold">

                                    Healthy Meals

                                </h4>

                                <p class="text-sm text-slate-500">

                                    Hygienic breakfast, lunch and dinner.

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    <!-- Testimonials -->

    <section class="py-24 bg-white">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->

            <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">

                <span class="inline-flex rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

                    Testimonials

                </span>

                <h2 class="mt-6 text-4xl lg:text-5xl font-bold text-slate-900">

                    What Our Residents Say

                </h2>

                <p class="mt-5 text-lg text-slate-600">

                    Trusted by students and working professionals for a comfortable,
                    secure and affordable living experience.

                </p>

            </div>

            <!-- Cards -->

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Card -->

                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm hover:-translate-y-2 hover:shadow-xl transition duration-300"
                    data-aos="fade-up">

                    <div class="flex text-amber-400 text-lg">

                        ★★★★★

                    </div>

                    <p class="mt-6 text-slate-600 leading-8">

                        Staying at The Hostel House has been an amazing experience.
                        Clean rooms, friendly staff and excellent security give me
                        complete peace of mind.

                    </p>

                    <div class="mt-8 flex items-center gap-4">

                        <img src="https://i.pravatar.cc/100?img=12" class="h-14 w-14 rounded-full">

                        <div>

                            <h4 class="font-semibold text-slate-900">

                                Rahul Sharma

                            </h4>

                            <p class="text-sm text-slate-500">

                                Engineering Student

                            </p>

                        </div>

                    </div>

                </div>

                <!-- Card -->

                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm hover:-translate-y-2 hover:shadow-xl transition duration-300"
                    data-aos="fade-up" data-aos-delay="150">

                    <div class="flex text-amber-400 text-lg">

                        ★★★★★

                    </div>

                    <p class="mt-6 text-slate-600 leading-8">

                        The rooms are spacious and the Wi-Fi is excellent for remote
                        work. Housekeeping is regular and the food quality is very
                        good.

                    </p>

                    <div class="mt-8 flex items-center gap-4">

                        <img src="https://i.pravatar.cc/100?img=32" class="h-14 w-14 rounded-full">

                        <div>

                            <h4 class="font-semibold text-slate-900">

                                Priya Verma

                            </h4>

                            <p class="text-sm text-slate-500">

                                Software Engineer

                            </p>

                        </div>

                    </div>

                </div>

                <!-- Card -->

                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm hover:-translate-y-2 hover:shadow-xl transition duration-300"
                    data-aos="fade-up" data-aos-delay="300">

                    <div class="flex text-amber-400 text-lg">

                        ★★★★★

                    </div>

                    <p class="mt-6 text-slate-600 leading-8">

                        I appreciate the peaceful environment and modern facilities.
                        Everything is well maintained and the management is always
                        helpful.

                    </p>

                    <div class="mt-8 flex items-center gap-4">

                        <img src="https://i.pravatar.cc/100?img=15" class="h-14 w-14 rounded-full">

                        <div>

                            <h4 class="font-semibold text-slate-900">

                                Aman Gupta

                            </h4>

                            <p class="text-sm text-slate-500">

                                MBA Student

                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- Why Choose Us -->
    <section class="py-24 bg-slate-50">

        <div class="max-w-7xl mx-auto px-6">

            <!-- Heading -->

            <div class="max-w-3xl mx-auto text-center mb-16" data-aos="fade-up">

                <span
                    class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">

                    <i class="fa-solid fa-shield-heart"></i>

                    Why Choose Us

                </span>

                <h2 class="mt-6 text-4xl lg:text-5xl font-bold text-slate-900">

                    Experience Premium Hostel Living

                </h2>

                <p class="mt-5 text-lg text-slate-600">

                    We combine comfort, safety and convenience to provide a
                    modern living experience for students and professionals.

                </p>

            </div>

            <!-- Accordion -->

            <div class="max-w-4xl mx-auto space-y-4">

                <!-- Item -->

                <details
                    class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">

                    <summary class="flex items-center justify-between cursor-pointer list-none">

                        <div class="flex items-center gap-4">

                            <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">

                                <i class="fa-solid fa-bed text-blue-600 text-lg"></i>

                            </div>

                            <span class="text-lg font-semibold">

                                Spacious & Comfortable Rooms

                            </span>

                        </div>

                        <i class="fa-solid fa-plus text-slate-500 transition group-open:rotate-45"></i>

                    </summary>

                    <p class="mt-5 text-slate-600 leading-8">

                        Fully furnished rooms with comfortable beds, wardrobes,
                        study tables and proper ventilation for a peaceful stay.

                    </p>

                </details>

                <!-- Item -->

                <details
                    class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">

                    <summary class="flex items-center justify-between cursor-pointer list-none">

                        <div class="flex items-center gap-4">

                            <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">

                                <i class="fa-solid fa-shield-halved text-green-600 text-lg"></i>

                            </div>

                            <span class="text-lg font-semibold">

                                24×7 Security

                            </span>

                        </div>

                        <i class="fa-solid fa-plus text-slate-500 transition group-open:rotate-45"></i>

                    </summary>

                    <p class="mt-5 text-slate-600 leading-8">

                        CCTV surveillance, biometric access and trained staff ensure
                        complete safety around the clock.

                    </p>

                </details>

                <!-- Item -->

                <details
                    class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">

                    <summary class="flex items-center justify-between cursor-pointer list-none">

                        <div class="flex items-center gap-4">

                            <div class="h-12 w-12 rounded-xl bg-purple-100 flex items-center justify-center">

                                <i class="fa-solid fa-wifi text-purple-600 text-lg"></i>

                            </div>

                            <span class="text-lg font-semibold">

                                High-Speed Wi-Fi

                            </span>

                        </div>

                        <i class="fa-solid fa-plus text-slate-500 transition group-open:rotate-45"></i>

                    </summary>

                    <p class="mt-5 text-slate-600 leading-8">

                        High-speed internet throughout the property for online
                        classes, remote work and entertainment.

                    </p>

                </details>

                <!-- Item -->

                <details
                    class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">

                    <summary class="flex items-center justify-between cursor-pointer list-none">

                        <div class="flex items-center gap-4">

                            <div class="h-12 w-12 rounded-xl bg-amber-100 flex items-center justify-center">

                                <i class="fa-solid fa-utensils text-amber-600 text-lg"></i>

                            </div>

                            <span class="text-lg font-semibold">

                                Hygienic Food

                            </span>

                        </div>

                        <i class="fa-solid fa-plus text-slate-500 transition group-open:rotate-45"></i>

                    </summary>

                    <p class="mt-5 text-slate-600 leading-8">

                        Fresh, nutritious meals prepared daily with high hygiene
                        standards for a healthy lifestyle.

                    </p>

                </details>

                <!-- Item -->

                <details
                    class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition">

                    <summary class="flex items-center justify-between cursor-pointer list-none">

                        <div class="flex items-center gap-4">

                            <div class="h-12 w-12 rounded-xl bg-rose-100 flex items-center justify-center">

                                <i class="fa-solid fa-broom text-rose-600 text-lg"></i>

                            </div>

                            <span class="text-lg font-semibold">

                                Daily Housekeeping

                            </span>

                        </div>

                        <i class="fa-solid fa-plus text-slate-500 transition group-open:rotate-45"></i>

                    </summary>

                    <p class="mt-5 text-slate-600 leading-8">

                        Daily cleaning and regular maintenance keep every room
                        fresh, clean and comfortable.

                    </p>

                </details>

            </div>

        </div>

    </section>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-slate-500">
        © {{ date('Y') }} The Hostel House. All Rights Reserved.
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            AOS.init({
                duration: 800,
                once: true,
            });
        });

        const swiper = new Swiper(".heroSwiper", {
            loop: true,
            effect: "fade",
            speed: 1200,
            autoplay: {
                delay: 3500,
                disableOnInteraction: false,
            },
            allowTouchMove: false,
        });
    </script>
</body>

</html>
