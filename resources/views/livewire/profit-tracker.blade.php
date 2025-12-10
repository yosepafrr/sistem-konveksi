
<div class="p-6 bg-white dark:bg-gray-800 rounded shadow">
    <div wire:poll.5s>
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Profit Tracker</h1>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-5 mb-10">
                    @foreach ($stores as $store)
                        @php
                            $bgColor = [
                                'Shopee' => '#ee4d2d',
                                'Tokopedia' => '#03ac0e',
                                'Tiktokshop' => '#141615'
                            ];
                            $bg = $bgColor[$store->platform] ?? '#black';
                        @endphp
                    <div class="h-40 bg-[#f5f7fb] shadow-md rounded-xl flex items-center justify-center gap-3">
                        <div>
                            <span style="background-color: {{ $bg }}" class="material-symbols-rounded text-5xl text-white rounded-full p-3">store</span>
                        </div>
                        <div class="justify-center flex flex-col">
                        <div class="text-gray-500 text-sm capitalize flex gap-1">Store: <span class="font-extrabold uppercase truncate-btn inline-block max-w-52 truncate hover:cursor-pointer">{{ $store->store_name }}</span></div>
                        <div class="text-sm capitalize text-[#344767]">Total Escrow: <span class="font-extrabold text-2xl">{{ 'Rp ' . number_format($storeEscrowTotal[$store->id] ?? 0, 0, ',', '.') }}</span></div>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="h-40 bg-[#f5f7fb] shadow-md rounded-xl flex items-center justify-center gap-3">
                        <div>
                            <span class="material-symbols-rounded text-5xl text-white bg-green-700 rounded-full p-3">functions</span>
                        </div>
                        <div class="justify-center flex flex-col">
                        <div class="text-gray-500 text-sm uppercase">Stores Escrow Sum</div>
                        <div class="font-extrabold text-2xl text-[#344767]">{{ 'Rp ' . number_format($totalEscrowAmount, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="h-40 bg-[#f5f7fb] shadow-md rounded-xl flex items-center justify-center gap-3">
                        <div>
                            <span class="material-symbols-rounded text-5xl text-white bg-blue-500 rounded-full p-3">request_quote</span>
                        </div>
                        <div class="justify-center flex flex-col">
                        <div class="text-gray-500 text-sm uppercase">Ads spent</div>
                        <div class="font-extrabold text-2xl text-[#344767]">{{ 'Rp ' . number_format(1000500, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <livewire:order-list /> --}}
</div>
