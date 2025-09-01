<div class="p-6 bg-white dark:bg-gray-800 rounded shadow">
    {{-- Care about people's approval and you will be their prisoner. --}}
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Product List</h1>

    <div>
        <div wire:poll.10s>
        <div class="max-h-[77.4vh] overflow-y-scroll">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                <tr>
                    <th scope="col" class="px-6 py-3 w-28 border-l-2">
                        <span class="sr-only">Image</span>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Product Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        SKU
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Stock
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Price
                    </th>
                </tr>
            </thead>
<tbody>
        @foreach ($products as $index => $product)
                <tr>
                    {{-- Kolom gambar & nama produk selalu tampil --}}
                    <td class="px-6 py-3 border-l-2">
                        <img src="{{ $product->image ?? 'unknown' }}" alt="{{ $product->item_name ?? 'unknown' }}" class="w-14 h-14 rounded-md">
                    </td>
                    <td class="px-6 py-3">
                        {{ $product->item_name ?? 'unknown' }}
                    </td>
                   {{-- Kolom quantity per produk --}}
                    <td class="px-6 py-3">
                        {{ $product->item_sku ?? 'unknown' }}
                    </td>
                    <td class="px-6 py-3">
                        {{ $product->item_status ?? 'unknown' }}
                    </td>
                    <td class="px-6 py-3">
                        {{ $product->stock ?? 'unknown' }}
                    </td>
                    <td class="px-6 py-3">
                        {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                    </td>
                </tr>
        @endforeach
    </tbody>
                <tfoot>
                <tr class=" bg-gray-100 border-b font-bold text-dark dark:border-gray-700 border-gray-200  sticky bottom-0">
                    <td colspan="6" class="px-6 py-2 text-end">
                        <a href="{{ route('shopee.update-product') }}">Update Product</a>
                    </td>
                </tr>
            </tfoot>
        </table>
        </div>
        </div>
    </div>
</div>