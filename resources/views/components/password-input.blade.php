@props([
    'model' => 'null',
    'id' => 'password',
    'name' => 'password',
])

<div x-data="{ show:false }" class="relative mt-1">
    <!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
    <input 
    :type="show ? 'text' : 'password'" 
    id="{{ $id }}"
    name="{{ $name }}"
    wire:model="{{ $model }}"
    class="w-full pr-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    autocomplete="current-password"
    />

    <div class="absolute p-5 bg-slate-200 rounded-r-lg hover:bg-slate-300 inset-y-0 right-0 flex items-center  border-2text-sm leading-5 cursor-pointer text-gray-600 transition duration-200 ease-in-out"
        @click="show = !show">
        <span class=" material-symbols-rounded" x-text="show ? 'visibility_off' : 'visibility'"></span>
    </div>
</div>