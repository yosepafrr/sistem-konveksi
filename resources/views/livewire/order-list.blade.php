<div class="p-6 bg-white dark:bg-gray-800 rounded shadow">
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Order List</h1>
    {{-- The best athlete wants his opponent at his best. --}}
                        <div>
                            <div class="flex flex-wrap gap-5 items-center">
                                {{-- <h1 class="mr-6">Store Filter</h1> --}}
                                @foreach ($stores as $store)
                                    <button 
                                        wire:click="toggleStoreFilter({{ $store->id }})"
                                        class="py-1 border-b-2 pb-2
                                            {{ in_array($store->id, $selectedStores) 
                                                ? ' text-[#00798f] border-b-[#0194af]' 
                                                : ' text-gray-700 border-none hover:text-[#00798f]' }}">
                                        {{ $store->store_name }}
                                    </button>
                                @endforeach
                            </div>
                            <hr class="mb-4"/>
                        </div>
                        <div class="flex flex-wrap gap-2 mb-4 items-center">
                            @php
                                $statuses = [
                                    'READY_TO_SHIP' => 'Perlu Diproses',
                                    'PROCESSED' => 'Diproses',
                                    'SHIPPED' => 'Dikirim',
                                    'COMPLETED' => 'Selesai',
                                ];
                            @endphp
                            <h1 class="mr-3">Order Status</h1>
                            @foreach ($statuses as $status => $label)
                                <button 
                                    wire:click="toggleStatusFilter('{{ $status }}')"
                                    class="px-4 py-1 rounded-full border 
                                        {{ in_array($status, $selectedStatuses) 
                                            ? ' text-[#00798f] border-[#0194af]' 
                                            : 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-gray-200' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                                <button 
                                    wire:click="$set('selectedStatuses', [])"
                                    class="px-4 py-1 rounded-full border border-red-500 bg-gray-50 text-red-500 hover:bg-gray-200">
                                    Reset Filter
                                </button>
                        </div>
                        <table class="w-full text-sm text-left my-4 text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                <tr>
                                    <th class="px-6 py-3 w-[630px]">Product</th>
                                    <th class="px-6 py-3">QTY</th>
                                    <th class="px-6 py-3">Order Status</th>
                                    <th class="px-6 py-3">Selling Price</th>
                                    <th class="px-6 py-3">Escrow</th>
                                </tr>
                            </thead>
                        </table>
            @forelse ($stores as $store)
                        @php
                            $logos = [
                                'Shopee' => 'Marketplace-logo/shopee.png',
                                'Tokopedia' => 'Marketplace-logo/tokopedia.png',
                                'Tiktokshop' => 'Marketplace-logo/tts.png'
                            ];
                            $logo = $logos[$store->platform] ?? null;
                        @endphp
                        
            <div wire:poll.5s class="mb-10">
                @php
                    // Ambil order berdasarkan toko ini
                    $storeOrders = $orders->where('store_id', $store->id);
                @endphp

                @if ($storeOrders->isEmpty())
                    <p class="text-gray-500">No orders found for this store.</p>
                    <a href="{{ route('shopee.orders', ['store_id' => $store->id]) }}" class="btn btn-primary">
                        Get Orders
                    </a>
                @else
                    <div class="max-h-[77.4vh] overflow-y-scroll  rounded-sm">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-400 sticky top-0  z-10">
                                <tr class="bg-gray-200 sticky bottom-0">
                                    <td colspan="5" class="px-6 py-3">Store: <strong class="uppercase">{{ $store->store_name ?? 'Unknown Store' }} ({{ $store->platform ?? 'Unknown Platform' }})</strong></td>
                                    {{-- <td colspan="4" class="px-6 py-3">
                                            
                                    </td> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($storeOrders as $order)
                                    @php $rowspan = $order->orderItems->count(); @endphp
                                    @php
                                        $statusMap = [
                                            'READY_TO_SHIP' => 'Perlu Diproses',
                                            'PROCESSED' => 'Diproses',
                                            'SHIPPED' => 'Dikirim',
                                            'COMPLETED' => 'Selesai',
                                            'CANCELLED' => 'Dibatalkan',
                                        ];
                                        $status = $statusMap[$order->order_status] ?? $order->order_status;
                                    @endphp
                                    <tr class="bg-transparent">
                                        <td colspan="5" class="py-3"></td>
                                    </tr>

                                    <tr class="bg-gray-100 sticky border-2 border-gray-300 rounded-t-md bottom-0 my-3 z-0">
                                        <td colspan="5" class="px-6 py-3">
                                            No. Pesanan: <strong>{{ $order->order_sn ?? 'unknown' }}</strong>
                                        </td>
                                    </tr>
                                    <tbody class="border-2 border-gray-300 rounded-lg">
                                    @foreach ($order->orderItems as $index => $orderItem )
                                        <tr>
                                            <td class="px-6 py-2 flex max-w-[320px] gap-5">
                                                <img src="{{ $orderItem->item->image ?? '' }}" 
                                                     alt="{{ $orderItem->item_name ?? 'unknown' }}" 
                                                     class="w-16 h-16 border rounded-md">
                                                @php
                                                    $variantCount = $orderItem->item->variantItems->count();
                                                @endphp
                                                @if ($variantCount > 0)
                                                    {{-- <td class="px-6 py-3"> --}}
                                                        <div>
                                                            <span class="truncate-btn inline-block max-w-[500px] font-semibold truncate hover:cursor-pointer">{{ $orderItem->item_name }}</span><br>
                                                            Variasi: <strong>{{ $orderItem->model_name }}</strong><br>
                                                            {{-- Quantity Purchased: <strong>{{ $orderItem->quantity_purchased }}</strong> --}}
                                                            {{-- <strong>{{ 'Rp ' . number_format($orderItem->item->variantItems->first()->price ?? 0, 0, ',', '.') }}</strong> --}}
                                                        </div>
                                                    {{-- </td> --}}
                                                @else
                                                    {{-- <td class="px-6 py-3"> --}}
                                                        <div>
                                                            <span class="truncate-btn inline-block max-w-[500px] font-semibold truncate hover:cursor-pointer">{{ $orderItem->item_name }}</span><br>
                                                            Variasi: <strong>-</strong><br>
                                                            {{-- <strong>Quantity Purchased: {{ $orderItem->quantity_purchased }}</strong> --}}
                                                            {{-- <strong>{{ 'Rp ' . number_format($orderItem->item->price ?? 0, 0, ',', '.') }}</strong> --}}
                                                        </div>
                                                    {{-- </td> --}}
                                                @endif

                                            </td>
                                            <td class="px-6 py-3 align-top">x{{ $orderItem->quantity_purchased ?? 'unknown' }}</td>

                                            @if ($index === 0)
                                                <td class="px-6 py-3 align-top" rowspan="{{ $rowspan }}">
                                                    {{ $status ?? 'unknown' }}
                                                </td>
                                                <td class="px-6 py-3 align-top" rowspan="{{ $rowspan }}">
                                                    {{ 'Rp ' . number_format($order->order_selling_price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-3 align-top" rowspan="{{ $rowspan }}">
                                                    {{ 'Rp ' . number_format($order->escrow_amount, 0, ',', '.') }}
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @php
                                    $totalSelling = $storeOrders->sum('order_selling_price');
                                    $totalEscrow = $storeOrders->sum('escrow_amount');
                                @endphp
                                <tr class="bg-gray-100 font-bold sticky bottom-0">
                                    <td colspan="2" class="px-6 py-2 border-l-2">
                                        <a href="{{ route('shopee.orders', ['store_id' => $store->id]) }}" class="btn btn-primary underline">
                                            Update Orders
                                        </a>
                                    </td>
                                    <td class="px-6 py-2">Total:</td>
                                    <td class="px-6 py-2">
                                        {{ 'Rp ' . number_format($totalSelling, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-2">
                                        {{ 'Rp ' . number_format($totalEscrow, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="mt-5">
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500">No stores found.</p>
            <a href="{{ route('shopee.connect') }}" class="btn btn-primary">Authorize Now</a>
        @endforelse
        <script>
            document.querySelectorAll('.truncate-btn').forEach(el => {
            el.addEventListener('click', () => {
                el.classList.toggle('truncate');
                if (el.classList.contains('truncate')) {
                el.style.whiteSpace = 'nowrap';
                el.style.overflow = 'hidden';
                el.style.textOverflow = 'ellipsis';
                } else {
                el.style.whiteSpace = 'normal';
                el.style.overflow = 'visible';
                el.style.textOverflow = 'unset';
                }
            });
            });
        </script>
</div>
