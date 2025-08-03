<aside x-data="{ 
        openSubmenus: JSON.parse(localStorage.getItem('sidebarState')) || {},
        sidebarCollapsed: JSON.parse(localStorage.getItem('sidebarCollapsed')) || false,

        toggleMenu(menu) {
            this.openSubmenus[menu] = !this.openSubmenus[menu];
            localStorage.setItem('sidebarState', JSON.stringify(this.openSubmenus));
        },
        toggleSidebar() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            localStorage.setItem('sidebarCollapsed', JSON.stringify(this.sidebarCollapsed));
        }
    }" class="mr-3 ml-1 pt-5"
    :class="sidebarCollapsed ? 'w-11 transition-all duration-300 ease-in-out overflow-hidden' : 'w-80 overflow-hidden'">
    {{-- HEADER SIDEBAR --}}

    <div class="flex items-center space-x-2 gap-1 justify-between">
        <div class="flex items-center -mr-3 space-x-2 mb-5 gap-1">
            <div x-show="!sidebarCollapsed" class="transition-all duration-300 ease-in-out overflow-hidden">
                <x-application-logo class="items-center" />
            </div>
            <div x-show="!sidebarCollapsed" class="transition-all duration-300 ease-in-out overflow-hidden">
                <h1 class="text-md font-semibold text-gray-800 dark:text-white">Sistem Informasi</h1>
                <p class="text-xs text-gray-500 dark:text-gray-400">Ramdani Konveksi</p>
            </div>
        </div>
        <a href="#" @click.prevent="toggleSidebar" :class="sidebarCollapsed ? 'mb-1.5' : 'mb-5'" class="mb-5 py-1 px-2 hover:bg-gray-200 rounded-lg ">
            <span class="material-symbols-rounded text-2xl text-gray-600">dock_to_left</span>
        </a>
    </div>
    <hr class="mb-4">



    {{-- ORDER --}}
    <x-side-link label="Dashboard" icon="space_dashboard" href="{{ route('dashboard') }}"
        :active="request()->routeIs('dashboard')" :hasSubmenu=false>
    </x-side-link>

    {{-- FINANCE --}}
    <x-side-link label="Finance" icon="finance_mode" href="'#'" :active="request()->routeIs('orders.*')"
        :hasSubmenu=true> <x-side-link :active="request()->routeIs('dashboard')" label="List Order" icon="list"
            href="{{ route('dashboard') }}" />
        <x-side-link :active="request()->routeIs('profile')" label="Create Order" icon="add"
            href="{{ route('profile') }}" />
    </x-side-link>

    {{-- ORDERS --}}
    <x-side-link label="Orders" icon="shopping_bag" href="'#'" :active="request()->routeIs('orders.*')"
        :hasSubmenu=true> <x-side-link :active="request()->routeIs('dashboard')" label="List Order" icon="list"
            href="{{ route('dashboard') }}" />
        <x-side-link :active="request()->routeIs('profile')" label="Create Order" icon="add"
            href="{{ route('profile') }}" />
        <x-side-link :active="request()->routeIs('profile')" label="Create Order" icon="add"
            href="{{ route('profile') }}" />
        <x-side-link :active="request()->routeIs('profile')" label="Create Order" icon="add"
            href="{{ route('profile') }}" />
        <x-side-link :active="request()->routeIs('profile')" label="Create Order" icon="add"
            href="{{ route('profile') }}" />
    </x-side-link>
</aside>