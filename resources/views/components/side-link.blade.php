@props([
    'href' => '#',
    'icon' => '',
    'label' => '',
    'active' => false,
    'hasSubmenu' => false,
])

@php
    $baseClass = 'flex items-center justify-between p-2  rounded transition ';
    // $activeClass = $active ? 'border-r-2 border-indigo-500 font-bold bg-gray-200' : 'hover:bg-gray-200 text-gray-700';
@endphp

<div class="mt-1">
    <a href="{{ $hasSubmenu ? '#' : $href }}" 
        @if ($hasSubmenu) 
            @click.prevent="toggleMenu('{{ $label }}')" 
        @endif
        :class="sidebarCollapsed
            ? '{{ $active ? 'bg-indigo-900 text-indigo-200 hover:bg-indigo-950' : 'hover:bg-gray-200 text-gray-700' }}'
            : '{{ $active ? 'border-r-2 border-indigo-500 font-bold bg-gray-200' : 'hover:bg-gray-200 text-gray-700' }}'"
        class="{{ $baseClass }}"      
        >  
        <div class="flex items-center space-x-3 font-bold">
            <span class=" material-symbols-rounded">{{ $icon }}</span>
            <span x-show="!sidebarCollapsed" class="transition-all duration-200">{{ $label }}</span>
        </div>

        @if($hasSubmenu)
            <span x-show="!sidebarCollapsed" class="material-symbols-rounded transition-transform duration-200"
            :class="openSubmenus['{{ $label }}'] ? 'rotate-90' : ''">chevron_right</span>
        @endif
    </a>

@if ($hasSubmenu)
    <div 
        x-show="openSubmenus['{{ $label }}']" 
        x-collapse 
        class="relative space-y-1 mt-1"
    >
        <!-- Garis vertikal -->
        <div 
            class="absolute left-5 top-2 bottom-2 border-l border-gray-400 transition-all duration-300"
            aria-hidden="true">
        </div>

        <!-- Submenu -->
        <div 
            class="pl-10 transition-all duration-300"
            :class="sidebarCollapsed ? 'pl-24' : 'pl-10'">
            {{ $slot }}
        </div>
    </div>
@endif
</div>