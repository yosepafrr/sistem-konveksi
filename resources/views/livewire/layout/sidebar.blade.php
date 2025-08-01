<aside class="w-80 mr-3 ml-1 pt-5">
    {{-- The Master doesn't talk, he acts. --}}
    <div class="w-full" x-data="{ 
        openSubmenus: JSON.parse(localStorage.getItem('sidebarState')) || {},

        toggleMenu(menu) {
            this.openSubmenus[menu] = !this.openSubmenus[menu];
            localStorage.setItem('sidebarState', JSON.stringify(this.openSubmenus));
        }
    }">
        {{-- HEADER SIDEBAR --}}
        <div class="flex items-center space-x-2 gap-1 justify-between">
            <div class="flex items-center space-x-2 mb-5 gap-1">
                <x-application-logo class="items-center" />
                <div>
                    <h1 class="text-md font-semibold text-gray-800 dark:text-white">Sistem Informasi</h1>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Ramdani Konveksi</p>
                </div>
            </div>
            <a href="#" class="mb-5 py-1 px-2 hover:bg-gray-200 rounded-lg ">
                <span class="material-symbols-rounded text-2xl text-gray-600">dock_to_left</span>
            </a>
        </div>
        <hr class="mb-4">

        {{-- ORDER --}}
        <x-side-link label="Order" icon="inventory_2" href="'#'" :active="request()->routeIs('orders.*')"
            :hasSubmenu=true>
            <x-side-link :active="request()->routeIs('dashboard')" label="List Order" icon="list"
                href="{{ route('dashboard') }}" />
            <x-side-link :active="request()->routeIs('profile')" label="Create Order" icon="add"
                href="{{ route('profile') }}" />
        </x-side-link>

        {{-- FINANCE --}}
        <x-side-link label="Finance" icon="finance_mode" href="'#'" :active="request()->routeIs('orders.*')"
            :hasSubmenu=true>
            <x-side-link :active="request()->routeIs('dashboard')" label="List Order" icon="list"
                href="{{ route('dashboard') }}" />
            <x-side-link :active="request()->routeIs('profile')" label="Create Order" icon="add"
                href="{{ route('profile') }}" />
        </x-side-link>
    </div>
</aside>