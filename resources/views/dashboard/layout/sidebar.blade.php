<aside id="sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-zinc-200
    transform -translate-x-full transition-transform duration-300 ease-in-out
    lg:translate-x-0 lg:shadow-none flex flex-col">

    <div class="lg:hidden flex justify-end p-3 border-b border-zinc-200">

        <button id="close-sidebar" class="w-10 h-10 rounded-xl hover:bg-zinc-100 flex items-center justify-center">

            <i class="fa-solid fa-xmark text-xl"></i>

        </button>

    </div>



    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-zinc-200 bg-zinc-50">

        <a href="{{ url('/') }}"
            class="flex items-center border border-4 justify-center transition-transform duration-300 hover:scale-105">

            <img src="{{ asset('images/logo.png') }}" alt="The Hostel House" class="h-10 w-auto object-contain">

        </a>

    </div>

    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-2 custom-scrollbar">

        <!-- Dashboard -->
        <a href="{{ route('dashboard.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium
    {{ request()->routeIs('dashboard.index')
        ? 'bg-amber-50 text-amber-700 border border-amber-200'
        : 'hover:bg-zinc-100' }}">
            <i class="fa-solid fa-house w-5"></i>
            Dashboard
        </a>

        <!-- ================= Property ================= -->

        @php
            $propertyOpen = request()->routeIs('dashboard.property.*');
        @endphp

        <div>

            <button
                class="dropdown-btn w-full flex justify-between items-center px-3 py-2.5 rounded-xl text-sm font-medium {{ $propertyOpen ? 'bg-zinc-100' : 'hover:bg-zinc-100' }}">

                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-building w-5 text-blue-600"></i>
                    <span>Property</span>
                </div>

                <i
                    class="fa-solid fa-chevron-down text-xs transition-transform {{ $propertyOpen ? 'rotate-180' : '' }}"></i>

            </button>

            <div class="dropdown-content {{ $propertyOpen ? '' : 'hidden' }} ml-4 mt-2 border-l pl-4 space-y-1">

                <a href="{{ route('dashboard.property.buildings') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.property.buildings*') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-building w-4 mr-2"></i>
                    Buildings
                </a>

                <a href="{{ route('dashboard.property.rooms') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.property.rooms') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-door-open w-4 mr-2"></i>
                    Rooms
                </a>

                <a href="{{ route('dashboard.property.room-status') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.property.room-status') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-bed w-4 mr-2"></i>
                    Room Status
                </a>

            </div>

        </div>

        <!-- ================= Bookings ================= -->

        @php
            $bookingsOpen = request()->routeIs('dashboard.bookings.*');
        @endphp

        <div>

            <button
                class="dropdown-btn w-full flex justify-between items-center px-3 py-2.5 rounded-xl text-sm font-medium {{ $bookingsOpen ? 'bg-zinc-100' : 'hover:bg-zinc-100' }}">

                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-calendar-days w-5 text-green-600"></i>
                    <span>Bookings</span>
                </div>

                <i
                    class="fa-solid fa-chevron-down text-xs transition-transform {{ $bookingsOpen ? 'rotate-180' : '' }}"></i>

            </button>

            <div class="dropdown-content {{ $bookingsOpen ? '' : 'hidden' }} ml-4 mt-2 border-l pl-4 space-y-1">

                <a href="{{ route('dashboard.bookings.create') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.bookings.create') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-plus w-4 mr-2"></i>
                    New Booking
                </a>

                <a href="{{ route('dashboard.bookings.current-stays') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.bookings.current-stays') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-bed w-4 mr-2"></i>
                    Current Stays
                </a>

                <a href="{{ route('dashboard.bookings.history') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.bookings.history') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left w-4 mr-2"></i>
                    Booking History
                </a>

            </div>

        </div>

        <!-- ================= Inventory ================= -->

        @php
            $inventoryOpen = request()->routeIs('dashboard.inventory.*');
        @endphp

        <div>

            <button
                class="dropdown-btn w-full flex justify-between items-center px-3 py-2.5 rounded-xl text-sm font-medium {{ $inventoryOpen ? 'bg-zinc-100' : 'hover:bg-zinc-100' }}">

                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-boxes-stacked w-5 text-violet-600"></i>
                    <span>Inventory</span>
                </div>

                <i
                    class="fa-solid fa-chevron-down text-xs transition-transform {{ $inventoryOpen ? 'rotate-180' : '' }}"></i>

            </button>

            <div class="dropdown-content {{ $inventoryOpen ? '' : 'hidden' }} ml-4 mt-2 border-l pl-4 space-y-1">

                <a href="{{ route('dashboard.inventory.items') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.inventory.items') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-box-open w-4 mr-2"></i>
                    Items
                </a>

                <a href="{{ route('dashboard.inventory.stock-movement') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.inventory.stock-movement') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-arrow-right-arrow-left w-4 mr-2"></i>
                    Stock Movement
                </a>

                <a href="{{ route('dashboard.inventory.stock-report') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.inventory.stock-report') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-chart-column w-4 mr-2"></i>
                    Stock Report
                </a>

            </div>

        </div>

        <!-- ================= Billing ================= -->

        @php
            $accountsOpen = request()->routeIs('dashboard.accounts.*');
        @endphp

        <div>

            <button
                class="dropdown-btn w-full flex justify-between items-center px-3 py-2.5 rounded-xl text-sm font-medium {{ $accountsOpen ? 'bg-zinc-100' : 'hover:bg-zinc-100' }}">

                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-wallet w-5 text-emerald-600"></i>
                    <span>Accounts</span>
                </div>

                <i
                    class="fa-solid fa-chevron-down text-xs transition-transform {{ $accountsOpen ? 'rotate-180' : '' }}"></i>

            </button>

            <div class="dropdown-content {{ $accountsOpen ? '' : 'hidden' }} ml-4 mt-2 border-l pl-4 space-y-1">

                <a href="{{ route('dashboard.accounts.invoices') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.accounts.invoices') ? 'text-amber-600 font-semibold' : '' }}">
                    <i class="fa-solid fa-file-invoice w-4 mr-2"></i>
                    Invoices
                </a>

            </div>

        </div>

        <!-- ================= Users ================= -->

        @php
            $usersOpen = request()->routeIs('dashboard.users.*');
        @endphp

        <div>

            <button
                class="dropdown-btn w-full flex justify-between items-center px-3 py-2.5 rounded-xl text-sm font-medium {{ $usersOpen ? 'bg-zinc-100' : 'hover:bg-zinc-100' }}">

                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-user-shield w-5 text-cyan-600"></i>
                    <span>Users</span>
                </div>

                <i class="fa-solid fa-chevron-down text-xs transition-transform {{ $usersOpen ? 'rotate-180' : '' }}">
                </i>

            </button>

            <div class="dropdown-content {{ $usersOpen ? '' : 'hidden' }} ml-4 mt-2 border-l pl-4 space-y-1">

                <!-- Add User -->
                <a href="{{ route('dashboard.users.create') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.users.create') ? 'text-amber-600 font-semibold' : '' }}">

                    <i class="fa-solid fa-user-plus w-4 mr-2"></i>
                    Add User

                </a>

                <!-- Manage Users -->
                <a href="{{ route('dashboard.users.index') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.users.index') || request()->routeIs('dashboard.users.edit') || request()->routeIs('dashboard.users.show') ? 'text-amber-600 font-semibold' : '' }}">

                    <i class="fa-solid fa-users w-4 mr-2"></i>
                    Manage Users

                </a>

                <!-- Login History -->
                <a href="{{ route('dashboard.login-history.index') }}"
                    class="sidebar-link {{ request()->routeIs('dashboard.login-history.*') ? 'text-amber-600 font-semibold' : '' }}">

                    <i class="fa-solid fa-clock-rotate-left w-4 mr-2"></i>
                    Login History

                </a>

            </div>

        </div>


    </div>

    <!-- Footer -->

    <div class="border-t border-zinc-200 p-4 bg-zinc-50">

        <form action="{{ route('logout') }}" method="POST">
            @csrf

            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl border border-zinc-200 bg-white hover:bg-red-50 hover:border-red-200 hover:text-red-600 transition-all text-sm font-medium">

                <i class="fa-solid fa-right-from-bracket"></i>

                Logout

            </button>
        </form>

    </div>

</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const buttons = document.querySelectorAll('.dropdown-btn');

        buttons.forEach(button => {

            button.addEventListener('click', function() {

                const content = this.nextElementSibling;
                const icon = this.querySelector('.fa-chevron-down');

                // Close all other dropdowns
                document.querySelectorAll('.dropdown-content').forEach(item => {
                    if (item !== content) {
                        item.classList.add('hidden');
                        const otherIcon = item.previousElementSibling.querySelector(
                            '.fa-chevron-down');
                        otherIcon?.classList.remove('rotate-180');
                    }
                });

                // Toggle current dropdown
                content.classList.toggle('hidden');
                icon.classList.toggle('rotate-180');

            });

        });

    });
</script>
