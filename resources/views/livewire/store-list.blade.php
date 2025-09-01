<div class="p-6 bg-white dark:bg-gray-800 rounded shadow">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Your Store List</h1>

    <div>
        <div wire:poll.10s>
        @forelse ($stores as $index => $store)
        <div class="max-h-[77.4vh] overflow-y-scroll">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                <tr>
                    <th scope="col" class="px-6 py-3 w-28 border-l-2">
                        <span >No</span>
                    </th>
                    <th scope="col" class="px-6 py-3 w-28">
                        <span >Platform</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Store Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                </tr>
            </thead>
        <tbody>
        @foreach ($stores as $store)
        @php
            $logos = [
                'Shopee' => 'Marketplace-logo/shopee.png',
                'Tokopedia' => 'Marketplace-logo/tokopedia.png',
                'Tiktokshop' => 'Marketplace-logo/tts.png'
            ];
            $logo = $logos[$store->platform] ?? null;
        @endphp
                    {{-- @php $rowspan = $store->store_name->count(); @endphp --}}
                <tr class="border-2">
                    {{-- Kolom gambar & nama produk selalu tampil --}}
                    <td class="px-6 py-3">
                        {{ $index + 1 }}
                    </td>
                    <td class="px-6 py-3">
                @if($logo)
                        <img src="{{ asset($logo) }}" class="rounded-lg" alt="{{ $store->platform }}" width="100">
                @else
                        <span class="text-gray-500">Unknown</span>
                @endif                    </td>
                   {{-- Kolom quantity per produk --}}
                    <td class="px-6 py-3">
                        {{ $store->store_name ?? 'unknown' }}
                    </td>
<td class="px-6 py-3 flex items-center gap-2">
    @php
        $isActive = $store->shop_expired_at && \Carbon\Carbon::parse($store->shop_expired_at)->isFuture();
    @endphp

    @if($isActive)
        {{-- Icon Active --}}
        <span class="material-symbols-rounded text-green-500">check_circle</span>
        <span>Active</span>
    @else
        {{-- Icon Inactive --}}
        <span class="material-symbols-rounded text-red-500">cancel</span>
        <span>Inactive</span>

        {{-- Tombol Authorize --}}
        <a href="{{ route('shopee.connect', $store->id) }}" 
           class="ml-3 px-3 py-1 bg-black text-white rounded hover:bg-blue-700">
            Authorize Now
        </a>
    @endif
</td>
                </tr>
        @endforeach
        </tbody>
        </table>
        </div>
            <div class="mt-4 flex justify-end">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>Add Stores</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('shopee.connect')" wire:navigate>
                            {{ __('Shopee') }}
                        </x-dropdown-link>

                    </x-slot>
                </x-dropdown>
            </div>
        @empty
        <p class="text-gray-500">No stores found.</p>
        <a href="{{ route('shopee.connect') }}" class="btn btn-primary">
            Hubungkan Toko Shopee
        </a>
        @endforelse
        </div>
    </div>
</div>