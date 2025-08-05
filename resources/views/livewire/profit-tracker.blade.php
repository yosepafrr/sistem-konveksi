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
                <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-700">
                            <th class="px-4 py-2 text-left">Order ID</th>
                            <th class="px-4 py-2 text-left">Customer</th>
                            <th class="px-4 py-2 text-left">Total Amount</th>
                            <th class="px-4 py-2 text-left">Profit</th>
                            </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr class="border-b dark:border-gray-600">
                                            <td class=" px-4 py-2">{{ $order->id }}</td>
                                <td class="px-4 py-2">{{ $order->customer_name }}</td>
                                        <td class="px-4 py-2">{{ $order->total_amount }}</td>
                                <td class="px-4 py-2">{{ $order->profit }}</td>
                            </tr>
                           @endforeach
                    </tbody>
                </table>
            @endif
        @empty
            <p class="text-gray-500">No stores found.</p>
                <a href="{{ route('shopee.connect') }}" class="btn btn-primary">
            Hubungkan Toko Shopee
            </a>
        @endforelse
    </div>
</div>