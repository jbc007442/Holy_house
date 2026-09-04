<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name') . ' Dashboard')</title>

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Toast CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">

    <!-- Country Dropdown -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/country-select-js@2.1.0/build/css/countrySelect.min.css">

    <!--data table-->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #d4d4d8;
            border-radius: 9999px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }
    </style>

    @stack('styles')

</head>

<body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- Mobile Overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-sm lg:hidden"></div>

        <!-- Sidebar -->
        @include('dashboard.layout.sidebar')

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden lg:ml-64">

            <!-- Header -->
            @include('dashboard.layout.header')

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto">

                <div class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">

                    @yield('content')

                </div>

            </main>

        </div>

    </div>

    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/country-select-js@2.1.0/build/js/countrySelect.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

    <script>
        window.notyf = new Notyf({
            duration: 3000,
            dismissible: true,
            position: {
                x: 'center',
                y: 'top'
            }
        });

        function showSaving() {
            window.notyf.open({
                type: 'info',
                message: 'Saving...'
            });
        }

        @if (session('success'))
            window.notyf.success(@json(session('success')));
        @endif

        @if (session('error'))
            window.notyf.error(@json(session('error')));
        @endif

        // Automatically show "Saving..." on every form submit
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    showSaving();
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const sidebar = document.getElementById("sidebar");
            const overlay = document.getElementById("sidebar-overlay");
            const openBtn = document.getElementById("sidebar-toggle");
            const closeBtn = document.getElementById("close-sidebar");

            function openSidebar() {

                sidebar.classList.remove("-translate-x-full");

                overlay.classList.remove("hidden");

                document.body.classList.add("overflow-hidden");

            }

            function closeSidebar() {

                sidebar.classList.add("-translate-x-full");

                overlay.classList.add("hidden");

                document.body.classList.remove("overflow-hidden");

            }

            openBtn?.addEventListener("click", openSidebar);

            closeBtn?.addEventListener("click", closeSidebar);

            overlay?.addEventListener("click", closeSidebar);

            document.addEventListener("keydown", function(e) {

                if (e.key === "Escape") {

                    closeSidebar();

                }

            });

            window.addEventListener("resize", function() {

                if (window.innerWidth >= 1024) {

                    sidebar.classList.remove("-translate-x-full");

                    overlay.classList.add("hidden");

                    document.body.classList.remove("overflow-hidden");

                } else {

                    sidebar.classList.add("-translate-x-full");

                }

            });

        });
    </script>
    <script>
        $(document).ready(function() {

            $('#myTable').DataTable({

                responsive: true,

                pageLength: 10,

                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],

                ordering: true,

                searching: true,

                info: true,

                autoWidth: false,

                language: {

                    search: "Search:",

                    lengthMenu: "Show _MENU_ entries",

                    info: "Showing _START_ to _END_ of _TOTAL_ entries",

                    zeroRecords: "No records found",

                    paginate: {

                        previous: "Previous",

                        next: "Next"

                    }

                }

            });

        });
    </script>

    <!-- App JS -->
    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')

</body>

</html>
