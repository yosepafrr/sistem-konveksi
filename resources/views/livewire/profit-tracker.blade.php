<div class="p-6 bg-white dark:bg-gray-800 rounded shadow">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Profit Tracker</h1>

    {{-- <div wire:loading>
        <p class="text-gray-500">Loading data...</p>
    </div> --}}


    {{-- <div wire:loading.remove> --}}
    <div>
        <div wire:poll.10s>
        @forelse ($stores as $store)
        @if (empty($orders))
        <p class="text-gray-500">No orders found.</p>
        <a href="{{ route('shopee.orders') }}" class="btn btn-primary">
            Ambil Data Pesanan
        </a>
        @else
        <div class="max-h-[77.4vh] overflow-y-scroll">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                <tr>
                    <th scope="col" class="px-6 py-3 w-28 border-l-2">
                        <span class="sr-only">Image</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Nama Produk
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Order QTY
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Order ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Order Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Harga Jual
                    </th>
                    <th scope="col" class="px-6 py-3 border-r-2">
                        Pendapatan Akhir
                    </th>
                </tr>
            </thead>
<tbody>
        @foreach ($orders as $order)
            @php $rowspan = $order->orderItems->count(); @endphp
            @foreach ($order->orderItems as $index => $orderItem)
                <tr class="{{ $index === $rowspan - 1 ? 'border-b dark:border-gray-700 border-gray-300 py-0' : '' }}">
                    {{-- Kolom gambar & nama produk selalu tampil --}}
                    <td class="px-6 py-3 border-l-2">
                        <img src="{{ $orderItem->item->image ?? 'unknown' }}" alt="{{ $orderItem->item_name ?? 'unknown' }}" class="w-14 h-14 rounded-md">
                    </td>
                    <td class="px-6 py-3">
                        {{ $orderItem->item_name ?? 'unknown' }}
                    </td>
                   {{-- Kolom quantity per produk --}}
                    <td class="px-6 py-3">
                        {{ $orderItem->quantity_purchased ?? 'unknown' }}
                    </td>


                    {{-- Kolom order hanya tampil di baris pertama saja --}}
                    @if ($index === 0)
                        <td class="px-6 py-3" rowspan="{{ $rowspan }}">
                            {{ $order->order_sn ?? 'unknown' }}
                        </td>
                        <td class="px-6 py-3" rowspan="{{ $rowspan }}">
                            {{ $order->order_status ?? 'unknown' }}
                        </td>
                        <td class="px-6 py-3" rowspan="{{ $rowspan }}">
                            {{ 'Rp ' . number_format($order->order_selling_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 border-r-2" rowspan="{{ $rowspan }}">
                            {{ 'Rp ' . number_format($order->escrow_amount, 0, ',', '.') }}
                        </td>
                    @endif

                </tr>
            @endforeach
        @endforeach
    </tbody>
                <tfoot>
                <tr class=" bg-gray-100 border-b font-bold text-dark dark:border-gray-700 border-gray-200  sticky bottom-0">
                    <td colspan="5" class="px-6 py-2 border-l-2">Total:</td>
                    <td class="px-6 py-2">
                        {{ 'Rp ' . number_format($totalOrderSellingPrice, 0, ',', '.') }}
                    </td>
                    <td class="px-6 py-2 border-r-2">
                        {{ 'Rp ' . number_format($totalEscrowAmount, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
        <div class="mt-5">
            <a href=" {{ route('shopee.orders') }}" class="btn btn-primary mt-10">
                Update Orders
            </a>
        </div>

        @endif
        @empty
        <p class="text-gray-500">No stores found.</p>
        <a href="{{ route('shopee.connect') }}" class="btn btn-primary">
            Authorize Now
        </a>
        @endforelse
        </div>
    </div>
</div>