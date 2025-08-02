@props([
    'model' => 'null',
    'id' => 'password',
    'name' => 'password',
])

<div x-data="{ show:false }" class="flex mt-1">
    <!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
    <input 
    :type="show ? 'text' : 'password'" 
    id="{{ $id }}"
    name="{{ $name }}"
    wire:model="{{ $model }}"
    {{ $attributes->merge(['class' => 'mt-0 rounded-none rounded-s-lg bg-gray-50 border border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) }} />

        <span @click="show = !show"
        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 hover:bg-gray-350 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600 cursor-pointer">
        <span class="material-symbols-rounded text-gray-600" x-text="show ? 'visibility_off' : 'visibility'"></span>
    </span>
</div>