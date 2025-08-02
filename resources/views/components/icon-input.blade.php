@props(['disabled' => false,
    'icon' => ''
    ])

<div class="flex mt-1">
    <span
        class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border border-e-0 border-gray-300 rounded-s-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
        <span class="material-symbols-rounded text-gray-600">{{ $icon }}</span>
    </span>
    <input @disabled($disabled) type="text" {{ $attributes->merge(['class' => 'mt-0 rounded-none rounded-e-lg bg-gray-50 border border-gray-300 text-gray-900 focus:border-indigo-500 focus:ring-indigo-500 block flex-1 min-w-0 w-full text-sm p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500']) }} style="margin-top: 0 !important;">
</div>