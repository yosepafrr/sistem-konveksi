<div class="p-6 bg-white dark:bg-gray-800 rounded shadow">
    <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">Product List</h1>

    <div wire:poll.10s>
        @forelse ($stores as $store)
            @php
                $storeProducts = $products->where('store_id', $store->id);
            @endphp
            <div class="mb-10">
                @if ($storeProducts->isEmpty())
                    <p class="text-gray-500">No products found for this store.</p>
                    <a href="{{ route('shopee.update-product', $store->id) }}" class="btn btn-primary">
                        Get Products
                    </a>
                @else
                    <div class="max-h-[77.4vh] overflow-y-scroll border rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400 sticky top-0">
                                <tr class="bg-gray-300 font-bold sticky bottom-0">
                                    <td colspan="2" class="px-6 py-2 border-l-2">Store Name:</td>
                                    <td colspan="6" class="px-6 py-2">
                                            {{ $store->store_name ?? 'Unknown Store' }} ({{ $store->platform ?? 'Unknown Platform' }})
                                    </td>
                                </tr>
                                <tr>
                                    <th class="px-6 py-3 w-28 border-l-2">Image</th>
                                    <th class="px-6 py-3">Product Name</th>
                                    <th class="px-6 py-3">SKU</th>
                                    <th class="px-6 py-3">Variants</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Stock</th>
                                    <th class="px-6 py-3">Price</th>
                                    <th class="px-6 py-3">HPP</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($storeProducts as $product)
                                @php
                                    $variantCount = $product->variantItems->count();
                                @endphp
                                @if ($variantCount > 0)
                                @foreach ($product->variantItems as $i => $variant)
                                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-600">
                                        @if ($i === 0)
                                            <td class="px-6 py-3 border-l-2 align-top" rowspan="{{ $variantCount }}">
                                                <img src="{{ $product->image ?? '' }}" alt="{{ $product->item_name ?? 'unknown' }}" class="w-14 h-14 rounded-md">
                                            </td>
                                            <td class="px-6 py-3 align-top max-w-52" rowspan="{{ $variantCount }}">{{ $product->item_name ?? 'unknown' }}</td>
                                            @if ($product->item_sku === '')
                                                <td class="px-6 py-3 align-top border-r-2" rowspan="{{ $variantCount }}">No SKU</td>
                                            @else
                                                <td class="px-6 py-3 align-top border-r-2" rowspan="{{ $variantCount }}">{{ $variant->model_sku ?? 'unknown' }}</td>
                                            @endif
                                        @endif
                                        <td class="px-6 py-3">{{ $variant->model_name ?? 'unknown' }}</td>
                                        <td class="px-6 py-3">{{ $variant->status ?? 'unknown' }}</td>
                                        <td class="px-6 py-3">{{ $variant->stock ?? 0 }}</td>
                                        <td class="px-6 py-3">
                                            {{ 'Rp ' . number_format($variant->price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-3">
                                            @if ($editingHppId === 'variant-' . $variant->id)
                                                <input type="text" wire:model.defer="newHppValue" class="border border-green-800 focus:border-green-900 focus:outline-none focus:ring-green-500 rounded px-2 py-1 w-28 placeholder-shown:italic" placeholder="Enter HPP">
                                                <button wire:click="updateHppVariant({{ $variant->id }})" class="btn btn-sm btn-primary ml-2 hover:underline">Save</button>
                                                <button wire:click="$set('editingHppId', null)" class="btn btn-sm btn-secondary ml-2 hover:underline">Cancel</button>
                                            @else
                                                {{ $variant->hpp !== null ? 'Rp ' . number_format($variant->hpp, 0, ',', '.') : '-' }}
                                                <button wire:click="showHppInput('variant', {{ $variant->id }}, {{ $variant->hpp ?? 0 }})" class="btn btn-sm btn-primary ml-2">
                                                    <span class="text-green-600 hover:underline">Edit</span>
                                                </button>
                                            @endif
                                        </td>                                        
                                    </tr>
                                @endforeach
                                @else
                                <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="px-6 py-3 border-l-2">
                                        <img src="{{ $product->image ?? '' }}" alt="{{ $product->item_name ?? 'unknown' }}" class="w-14 h-14 rounded-md">
                                    </td>
                                    <td id="truncateButton" class="px-6 py-3 max-w-52 truncate hover:cursor-pointer">{{ $product->item_name ?? 'unknown' }}</td>
                                    @if ($product->item_sku === '')
                                        <td class="px-6 py-3 border-r-2">No SKU</td>
                                    @else
                                        <td class="px-6 py-3 border-r-2">{{ $product->item_sku ?? 'unknown' }}</td>
                                    @endif
                                    <td class="px-6 py-3">Tidak ada varian</td>
                                    <td class="px-6 py-3">{{ $product->item_status ?? 'unknown' }}</td>
                                    <td class="px-6 py-3">{{ $product->stock ?? 0 }}</td>
                                    <td class="px-6 py-3">
                                        {{ 'Rp ' . number_format($product->price, 0, ',', '.') }}
                                    </td>
                                        <td class="px-6 py-3">
                                            @if ($editingHppId === 'item-' . $product->id)
                                                <input type="text" wire:model.defer="newHppValue" class="border border-green-800 focus:border-green-900 focus:outline-none focus:ring-green-500 rounded px-2 py-1 w-28 placeholder-shown:italic" placeholder="Enter HPP">
                                                <button wire:click="updateHppProduk({{ $product->id }})" class="btn btn-sm btn-primary ml-2 hover:underline">Save</button>
                                                <button wire:click="$set('editingHppId', null)" class="btn btn-sm btn-secondary ml-2 hover:underline">Cancel</button>
                                            @else
                                                {{ $product->hpp !== null ? 'Rp ' . number_format($product->hpp, 0, ',', '.') : '-' }}
                                                <button wire:click="showHppInput('item', {{ $product->id }}, {{ $product->hpp ?? 0 }})" class="btn btn-sm btn-primary ml-2">
                                                    <span class="text-green-600 hover:underline">Edit</span>
                                                </button>
                                            @endif
                                        </td>                                        
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="bg-gray-100 font-bold sticky bottom-0">
                                    <td colspan="8" class="px-6 py-2 text-end">
                                        <a href="{{ route('shopee.update-product', $store->id) }}" class="btn btn-primary underline">
                                            Update Product
                                        </a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500">No stores found.</p>
            <a href="{{ route('shopee.connect') }}" class="btn btn-primary">Authorize Now</a>
        @endforelse
    </div>
</div>

{{-- Truncate Button --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const truncateButton = document.getElementById('truncateButton');
        if (truncateButton) {
            truncateButton.addEventListener('click', function () {
                this.classList.toggle('truncate');
                if (this.classList.contains('truncate')) {
                    this.style.whiteSpace = 'nowrap';
                    this.style.overflow = 'hidden';
                    this.style.textOverflow = 'ellipsis';
                } else {
                    this.style.whiteSpace = 'normal';
                    this.style.overflow = 'visible';
                    this.style.textOverflow = 'unset';
                }
            });
        }
    });
</script>
{{-- Truncate Button --}}

