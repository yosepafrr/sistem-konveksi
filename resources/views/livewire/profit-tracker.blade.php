<div class="p-6 bg-white dark:bg-gray-800 rounded shadow">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Profit Tracker</h1>

    <div wire:loading>
        <p class="text-gray-500">Loading data...</p>
    </div>


    <div wire:loading.remove>
        @forelse ($stores as $store)
            @if (empty($orders))
                <p class="text-gray-500">No orders found.</p>
                <a href="{{ route('shopee.orders') }}" class="btn btn-primary">
                    Ambil Data Pesanan
                </a>
            @else
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-16 py-3">
                                <span class="sr-only">Image</span>
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Order Status
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Order QTY
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Harga Jual
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Harga Akhir
                            </th>
                        </tr>
                    </thead>
                    @foreach ($orders as $order)
                        <tbody>
                            <tr
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700 border-gray-200">
                                <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $order->id }}
                                </th>
                                <td class="px-6 py-3">
                                    {{ $order->order_status }}
                                </td>
                                <td class="px-6 py-3">
                                    {{ $order->quantity_purchased }}
                                </td>
                                <td class="px-6 py-3">
                                    {{ 'Rp ' . number_format($order->order_selling_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3">
                                    {{ 'Rp ' . number_format($order->escrow_amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    @endforeach
                    <tfoot>
                        <tr class=" bg-gray-100 border-b font-bold text-dark dark:border-gray-700 border-gray-200">
                            <td colspan="3" class="px-6 py-2">Total:</td>
                            <td class="px-6 py-2">
                                {{ 'Rp ' . number_format($totalOrderSellingPrice, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-2">
                                {{ 'Rp ' . number_format($totalEscrowAmount, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="mt-5">
                    <a href=" {{ route('shopee.orders') }}" class="btn btn-primary mt-10">
                        Update Data Pesanan
                    </a>
                </div>

            @endif
        @empty
            <p class="text-gray-500">No stores found.</p>
            <a href="{{ route('shopee.connect') }}" class="btn btn-primary">
                Hubungkan Toko Shopee
            </a>
        @endforelse
    </div>
</div>