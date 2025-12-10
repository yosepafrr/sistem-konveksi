<?php

namespace App\Jobs;

use App\Models\Item;
use App\Models\Store;
use Illuminate\Support\Arr;
use App\Models\VariantItems;
use App\Events\ProductCreated;
use App\Services\ShopeeService;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncShopeeProductJob implements ShouldQueue
{
    public $tries = 3;
    public $timeout = 120;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        // Log::info('SyncShopeeProductJob on Constructor initialized', ['time' => now()]);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info('SyncShopeeProductJob on handle initialized', ['time' => now()]);
        $stores = Store::where('platform', 'Shopee')->get();
        $shopee = new ShopeeService();

        foreach ($stores as $store) {
            try {
                $itemList = $shopee->getItemList($store);
                if (isset($itemList['error'])) {
                    Log::error("Gagal ambil item list dari Shopee API", [
                        'store_id' => $store->id,
                        'error' => $itemList['error'],
                        'message' => $itemList['message'] ?? null,
                        'response' => $itemList
                    ]);
                    continue;
                }
                Log::info("Item list Response untuk store {$store->id}", ['data' => $itemList]);
                // ...lanjutkan proses...
            } catch (\Throwable $e) {
                Log::error('Exception saat ambil item list Shopee', [
                    'store_id' => $store->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
                continue;
            }
            $itemIds = collect($itemList)->pluck('item_id')->take(20)->toArray();
            Log::info('Item IDs to fetch:', ['item_id_list' => $itemIds]);

            $itemDetails = $shopee->getItemBaseInfo($store, $itemIds);
            $itemVariants = $shopee->getItemsVariant($store, $itemIds);

            Log::info('Raw data item detail', ['data' => $itemDetails]);


            if (empty($itemDetails)) {
                Log::warning("Item base info kosong untuk store {$store->id}", $itemIds);
                continue;
            }

            foreach ($itemDetails as $item) {
                try {
                    $product = Item::updateOrCreate(
                        [
                            'item_id' => $item['item_id'],
                            'store_id' => $store->id,
                        ],
                        [
                            'item_name'  => $item['item_name'] ?? 'Unknown',
                            'image'      => $item['promotion_image']['image_url_list'][0] ?? null,
                            'price'      => $item['price_info'][0]['current_price'] ?? 0,
                            'item_sku'   => $item['item_sku'] ?? null,
                            'item_status' => $item['item_status'] ?? null,
                            'stock'      => $item['stock_info_v2']['summary_info']['total_available_stock'] ?? 0,
                            'category'   => $item['category_id'] ?? null,
                        ]
                    );

                    if (!empty($itemVariants[$item['item_id']]['model'])) {
                        foreach ($itemVariants[$item['item_id']]['model'] as $model) {
                            try {
                                Log::info("Otw simpan variant", [
                                    'item_id'  => $item['item_id'],
                                    'model_id' => Arr::get($model, 'model_id'),
                                ]);

                                $variantSaved = VariantItems::updateOrCreate(
                                    [
                                        'item_id'  => $product->id, // id dari tabel products
                                        'model_id' => Arr::get($model, 'model_id'),
                                    ],
                                    [
                                        'model_name' => Arr::get($model, 'model_name'),
                                        'model_sku'  => Arr::get($model, 'model_sku'),
                                        'stock'      => Arr::get($model, 'stock_info_v2.summary_info.total_available_stock', 0),
                                        'price'      => Arr::get($model, 'price_info.0.current_price', 0),
                                        'status'     => Arr::get($model, 'model_status'),
                                    ]
                                );

                                Log::info("Variant {$model['model_id']} untuk item {$item['item_id']} berhasil disimpan ke DB", [
                                    'db_id' => $variantSaved->id,
                                ]);
                            } catch (\Throwable $e) {
                                Log::error("Gagal simpan variant ke DB", [
                                    'item_id'  => $item['item_id'],
                                    'model_id' => $model['model_id'] ?? null,
                                    'error'    => $e->getMessage(),
                                    'trace'    => $e->getTraceAsString(),
                                    'data'     => $model
                                ]);
                            }
                        }
                    }


                    Log::info("Produk {$item['item_id']} berhasil disimpan");
                    event(new ProductCreated($product));
                } catch (\Throwable $e) {
                    Log::error('Gagal simpan produk Shopee', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                        'data' => $item
                    ]);
                }
            }
        }
    }
}
