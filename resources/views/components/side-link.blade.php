@props([
    'href' => '#',
    'icon' => '',
    'label' => '',
    'active' => false,
    'hasSubmenu' => false,
])

@php
    $baseClass = 'flex items-center justify-between p-2  rounded transition ';
    $activeClass = $active ? 'bg-gray-200 text-blue-700' : 'hover:bg-gray-100 text-gray-700';
@endphp

<div>
    <a href="{{ $hasSubmenu ? '#' : $href }}" 
        @if ($hasSubmenu) 
            @click.prevent="toggleMenu('{{ $label }}')" 
        @endif
        class="{{ $baseClass }} {{ $activeClass }}">
        
        <div class="flex items-center space-x-2">
            <span class=" material-symbols-rounded">{{ $icon }}</span>
            <span>{{ $label }}</span>
        </div>

        @if($hasSubmenu)
            <span class="material-symbols-rounded transition-transform duration-200"
            :class="openSubmenus['{{ $label }}'] ? 'rotate-90' : ''">chevron_right</span>
        @endif
    </a>

    @if ($hasSubmenu)
        <div x-show="openSubmenus['{{ $label }}']" 
        class="ml-6 space-y-1">
            {{ $slot }}
        </div>
    @endif
</div>