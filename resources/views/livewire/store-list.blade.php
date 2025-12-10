<div class="p-6 bg-white dark:bg-gray-800 rounded shadow">
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Your Store List</h1>

    <div wire:poll.10s>
        <div class="max-h-[77.4vh] overflow-y-scroll border rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                    <tr>
                        <th class="px-6 py-3 w-28 border-l-2">No</th>
                        <th class="px-6 py-3 w-28">Platform</th>
                        <th class="px-6 py-3">Store Name</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stores as $index => $store)
                        @php
                            $logos = [
                                'Shopee' => 'Marketplace-logo/shopee.png',
                                'Tokopedia' => 'Marketplace-logo/tokopedia.png',
                                'Tiktokshop' => 'Marketplace-logo/tts.png'
                            ];
                            $logo = $logos[$store->platform] ?? null;
                        @endphp
                        <tr class="border-2">
                            <td class="px-6 py-3">{{ $index + 1 }}</td>
                            <td class="px-6 py-3">
                                @if($logo)
                                    <img src="{{ asset($logo) }}" class="rounded-lg" alt="{{ $store->platform }}" width="100">
                                @else
                                    <span class="text-gray-500">Unknown</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">{{ $store->store_name ?? 'unknown' }}</td>
                            <td class="px-6 py-3 flex items-center gap-2">
                                @php
                                    $isActive = $store->shop_expired_at && \Carbon\Carbon::parse($store->shop_expired_at)->isFuture();
                                @endphp

                                @if($isActive)
                                    <span class="material-symbols-rounded text-green-500">check_circle</span>
                                    <span>Active</span>
                                @else
                                    <span class="material-symbols-rounded text-red-500">cancel</span>
                                    <span>Inactive</span>
                                    <a href="{{ route('shopee.connect', $store->id) }}"
                                       class="ml-3 px-3 py-1 bg-black text-white rounded hover:bg-blue-700">
                                        Authorize Now
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">
                                No stores found.
                                <a href="{{ route('shopee.connect') }}" class="btn btn-primary">
                                    Hubungkan Toko Shopee
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
