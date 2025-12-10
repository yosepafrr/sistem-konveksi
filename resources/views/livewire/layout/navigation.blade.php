<?php

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Log;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        Log::info('>>> Logout berhasil dipanggil');
        $logout();
        $this->redirect('/', navigate: true);
    }
}; 
?>

<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = (window.pageYOffset > 10)"
     :class="scrolled ? 'bg-white/90 backdrop-blur-md shadow-sm border-gray-200' : 'bg-white border-transparent'"
     class="sticky top-0 z-50 w-full transition-all duration-300 border-b">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <div class="flex items-center gap-8">
                <div class="hidden space-x-1 sm:flex">
                    <a href="{{ route('dashboard') }}" wire:navigate 
                       class="relative group px-3 py-2 text-sm font-medium transition-colors duration-200
                       {{ request()->routeIs('dashboard') ? 'text-[#304674]' : 'text-gray-500 hover:text-[#304674]' }}">
                        Dashboard
                        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-[#304674] transition-transform duration-300 ease-out origin-left
                            {{ request()->routeIs('dashboard') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}">
                        </span>
                    </a>

                    <a href="{{ route('profile') }}" wire:navigate 
                       class="relative group px-3 py-2 text-sm font-medium transition-colors duration-200
                       {{ request()->routeIs('profile') ? 'text-[#304674]' : 'text-gray-500 hover:text-[#304674]' }}">
                        Profile
                        <span class="absolute inset-x-0 bottom-0 h-0.5 bg-[#304674] transition-transform duration-300 ease-out origin-left
                            {{ request()->routeIs('profile') ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-100' }}">
                        </span>
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                
                <button class="hidden sm:block p-2 text-gray-400 hover:text-[#304674] transition relative">
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                </button>

                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center gap-3 pl-3 pr-2 py-1.5 border border-gray-200 rounded-full hover:shadow-md hover:border-[#304674]/30 transition duration-200 bg-gray-50 group">
                                <div class="text-sm font-semibold text-gray-700 group-hover:text-[#304674] transition"
                                     x-data="{{ json_encode(['name' => auth()->user()->name]) }}" 
                                     x-text="name"
                                     x-on:profile-updated.window="name = $event.detail.name">
                                </div>
                                <div class="h-8 w-8 rounded-full bg-[#304674] text-white flex items-center justify-center text-xs font-bold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50/50">
                                <p class="text-xs text-gray-500">Signed in as</p>
                                <p class="text-sm font-bold text-[#304674] truncate">{{ auth()->user()->email }}</p>
                            </div>

                            <x-dropdown-link :href="route('profile')" wire:navigate class="hover:text-[#304674]">
                                {{ __('Profile Settings') }}
                            </x-dropdown-link>

                            <button wire:click="logout" class="w-full text-start">
                                <x-dropdown-link class="text-red-500 hover:text-red-700 hover:bg-red-50">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </button>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-[#304674] hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100 bg-white shadow-lg">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate 
                class="rounded-lg {{ request()->routeIs('dashboard') ? 'bg-[#304674]/10 text-[#304674] border-l-0' : 'border-l-0' }}">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
             <x-responsive-nav-link :href="route('profile')" :active="request()->routeIs('profile')" wire:navigate
                class="rounded-lg {{ request()->routeIs('profile') ? 'bg-[#304674]/10 text-[#304674] border-l-0' : 'border-l-0' }}">
                {{ __('Profile') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-4 border-t border-gray-100 px-4 bg-gray-50">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-10 w-10 rounded-full bg-[#304674] flex items-center justify-center text-white font-bold text-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-base text-gray-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div>
                </div>
            </div>

            <div class="space-y-1">
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link class="rounded-lg text-red-600 hover:bg-red-50 border-l-0">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>