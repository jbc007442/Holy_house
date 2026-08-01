<header class="sticky top-0 z-40 bg-white border-b border-zinc-200 h-16 flex items-center justify-between px-4 lg:px-6">

    <!-- Left -->
    <div class="flex items-center gap-2 lg:gap-4">

        <!-- Mobile Menu -->
        <button id="sidebar-toggle"
            class="lg:hidden w-10 h-10 rounded-xl border border-zinc-200 hover:bg-zinc-100 flex items-center justify-center">
            <i class="fa-solid fa-bars"></i>
        </button>

        <div>
            <h1 class="text-xl font-semibold text-zinc-800">
                @yield('title', 'Dashboard')
            </h1>
        </div>

    </div>

    <!-- Right -->
    <div class="flex items-center gap-2 lg:gap-4">

        <!-- User -->
        <div class="relative">

            <button id="profileBtn" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-zinc-100">

                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=f59e0b&color=fff"
                    class="w-10 h-10 rounded-full" alt="{{ auth()->user()->name }}">

                <div class="hidden md:block text-left">

                    <p class="text-sm font-semibold text-zinc-800">

                        {{ auth()->user()->name }}

                    </p>

                    <p class="text-xs text-zinc-500">

                        {{ auth()->user()->email }}

                    </p>

                </div>

                <i class="fa-solid fa-chevron-down text-xs text-zinc-500"></i>

            </button>

            <!-- Dropdown -->
            <div id="profileMenu"
                class="hidden absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-zinc-200 overflow-hidden">

                <a href="{{ route("dashboard.profile") }}" class="flex items-center gap-3 px-4 py-3 hover:bg-zinc-50">
                    <i class="fa-regular fa-user w-5"></i>
                    Profile
                </a>

                <hr>

                <a href="#" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 text-red-600">
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    Logout
                </a>

            </div>

        </div>

    </div>

</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');

        if (toggle && sidebar) {
            toggle.addEventListener('click', function() {
                sidebar.classList.toggle('-translate-x-full');
            });
        }

        // Profile Dropdown
        const profileBtn = document.getElementById('profileBtn');
        const profileMenu = document.getElementById('profileMenu');

        if (profileBtn && profileMenu) {

            profileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                profileMenu.classList.toggle('hidden');
            });

            document.addEventListener('click', function() {
                profileMenu.classList.add('hidden');
            });

            profileMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });

        }

    });
</script>
