<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Store;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Services\ShopeeService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ShopeeController extends Controller
{
    public function redirectToShopee()
    {
        $partnerId = config('shopee.partner_id');
        $partnerKey = config('shopee.partner_key');
        $timestamp = time();
        $path = '/api/v2/shop/auth_partner';




        // HARUS pakai path, bukan redirectUrl di base_string
        $baseString = $partnerId . $path . $timestamp;
        $sign = hash_hmac('sha256', $baseString, $partnerKey);

        $redirectUrl = 'https://b2b1b53ce536.ngrok-free.app/shopee/callback'; // pastikan ini terdaftar di Shopee developer dashboard
        $url = "https://openplatform.sandbox.test-stable.shopee.sg{$path}"
            . "?partner_id={$partnerId}"
            . "&timestamp={$timestamp}"
            . "&sign={$sign}"
            . "&redirect=" . urlencode($redirectUrl);

        return redirect($url);
    }

    public function handleShopeeCallback(Request $request, ShopeeService $shopee)
    {
        $partnerId = config('shopee.partner_id');
        $partnerKey = config('shopee.partner_key');
        $timestamp = time();
        $path = '/api/v2/auth/token/get';

        $code = $request->query('code');
        $shopId = $shopData['shop_id_list'][0] ?? null;

        Log::info('Shopee Callback', ['code' => $code, 'shop_id' => $shopId]);

        $baseString = $partnerId . $path . $timestamp;
        $sign = hash_hmac('sha256', $baseString, $partnerKey);

        $url = "https://openplatform.sandbox.test-stable.shopee.sg{$path}"
            . "?partner_id={$partnerId}"
            . "&timestamp={$timestamp}"
            . "&sign={$sign}";

        $body = [
            'code' => $code,
            'shop_id' => (int)$shopId,
        ];

        Log::info('Shopee - Sending Request:', ['url' => $url, 'body' => $body]);

        $response = Http::withBody(json_encode($body), 'application/json')
            ->post($url);

        $result = $response->json();

        Log::info('Shopee - Response:', $result);

        // Simpan data token jika berhasil
        $shopData = $result;

        if ($shopData && !empty($shopData['shop_id_list'])) {
            $shopId = $shopData['shop_id_list'][0];
            Log::info('Shopee - Saving Store Data', ['shop_id' => $shopId, 'data' => $shopData]);

            $accessToken = $result['access_token'];
            $refreshToken = $result['refresh_token'];
            $tokenExpiredAt = Carbon::createFromTimestamp($result['expire_in'] + $timestamp)
                ->setTimezone('Asia/Jakarta');


            $store = new Store();
            $store->shopee_shop_id = $shopId;
            $store->access_token = $accessToken;

            $shopInfo = $shopee->getShopProfile($store);
            Log::info('Shopee - Shop Info:', $shopInfo);

            $store =  Auth::user()->stores()->updateOrCreate(
                ['shopee_shop_id' => $shopId],
                [
                    'platform'              => 'Shopee',
                    'store_name'            => $shopInfo['shop_name'] ?? 'Toko Shopee',
                    'shop_expired_at'       => isset($shopInfo['expire_time'])
                        ? Carbon::createFromTimestamp($shopInfo['expire_time'])
                        : now()->addYear(),
                    'access_token'          => $accessToken,
                    'refresh_token'         => $refreshToken,
                    'token_expired_at'      => $tokenExpiredAt,
                ]
            );

            // Log hasil penyimpanan
            Log::info('Data store berhasil diupdate/ditambahkan', [
                'store_id' => $store->id,
                'data'     => $store->toArray()
            ]);



            $itemList = $shopee->getItemList($store);
            Log::info('Item List Response:', ['item_list' => $itemList]);
            if (empty($itemList)) {
                Log::warning('Toko belum punya produk atau item list kosong');
                return;
            }


            $itemIds = collect($itemList)->pluck('item_id')->take(20)->toArray();
            Log::info('Item IDs to fetch:', ['item_id_list' => $itemIds]);

            $itemDetails = $shopee->getItemBaseInfo($store, $itemIds);

            Log::info('Raw data item detail', ['data' => $itemDetails]);


            if (empty($itemDetails)) {
                Log::warning('Item base info kosong untuk item_id_list', $itemIds);
            } else {
                foreach ($itemDetails as $item) {
                    try {
                        Item::updateOrCreate(
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
                        Log::info("Produk {$item['item_id']} berhasil disimpan");
                    } catch (\Throwable $e) {
                        Log::error('Gagal simpan produk Shopee', [
                            'error' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                            'data' => $item
                        ]);
                    }
                }
            }
            return redirect()->route('profit.tracker')->with('success', 'Toko Shopee berhasil terhubung.');

            // return response()->json([
            //     'message' => 'Gagal menghubungkan toko',
            //     'debug' => [
            //         'url'           => $url,
            //         'sign'          => $sign,
            //         'base_string'   => $baseString,
            //         'response'      => $result,
            //         'item_list' => $itemList,
            //         'item_details' => $itemDetails
            //     ]
            // ], 400);
        }
    }

    public function updateProducts(Request $request, ShopeeService $shopee)
    {
        $store = Auth::user()->stores()->where('platform', 'Shopee')->first();
        $accessToken = $shopee->ensureValidToken($store);

        if (!$store) {
            Log::warning('Tidak ada toko Shopee yang terhubung.');
            return response()->json(['error' => 'No Shopee store linked'], 404);
        }

        $itemList = $shopee->getItemList($store);
        $itemIds = collect($itemList['items'] ?? $itemList)->pluck('item_id')->take(20)->toArray();

        if (empty($itemIds)) {
            Log::warning('Item list kosong.');
            return;
        }

        $itemDetails = $shopee->getItemBaseInfo($store, $itemIds);
        if (empty($itemDetails)) {
            Log::warning('Item base info kosong.', ['item_id_list' => $itemIds]);
            return;
        }

        foreach ($itemDetails as $item) {
            try {
                Item::updateOrCreate(
                    [
                        'item_id'  => $item['item_id'],
                        'store_id' => $store->id,
                    ],
                    [
                        'item_name'  => $item['item_name'] ?? 'Unknown',
                        'image'      => $item['promotion_image']['image_url_list'][0]
                            ?? $item['images'][0]
                            ?? null,
                        'price'      => $item['price_info'][0]['current_price']
                            ?? $item['price']
                            ?? 0,
                        'item_sku'   => $item['item_sku'] ?? null,
                        'item_status' => $item['item_status'] ?? null,
                        'stock'      => $item['stock_info_v2']['summary_info']['total_available_stock'] ?? 0,
                        'category'   => $item['category_id'] ?? null,
                    ]
                );
                Log::info("Produk {$item['item_id']} berhasil disimpan");
            } catch (\Throwable $e) {
                Log::error('Gagal simpan produk Shopee', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'data' => $item
                ]);
            }
        }

        return redirect(route('product.list'));
    }

    // AMBIL PESANAN SELAMA 3 BULAN KEBELAKANG
    public function getShopeeOrders(Request $request, ShopeeService $shopee)
    {
        $store = Auth::user()->stores()->where('platform', 'Shopee')->first();

        if (!$store) {
            return response()->json(['error' => 'Shopee store not found.'], 404);
        }

        $accessToken = $shopee->ensureValidToken($store);

        $now = Carbon::now('UTC');
        $threeMonthsAgo = $now->copy()->subMonths(3)->startOfDay(); // 3 bulan lalu
        $intervalDays = 15;

        $allOrders = [];

        while ($threeMonthsAgo < $now) {
            $startTime = $threeMonthsAgo->copy();
            $endTime = $threeMonthsAgo->copy()->addDays($intervalDays);

            if ($endTime > $now) {
                $endTime = $now;
            }

            $orders = $shopee->getOrderList(
                $accessToken,
                (string) $store->shopee_shop_id,
                $startTime->timestamp,
                $endTime->timestamp
            );

            if (isset($orders['response']['order_list'])) {
                $orderSnList = [];

                // Simpan order_sn dan insert awal ke DB
                foreach ($orders['response']['order_list'] as $order) {
                    $orderSnList[] = $order['order_sn'];

                    \App\Models\Order::updateOrCreate(
                        ['order_sn' => $order['order_sn']],
                        [
                            'booking_sn'    => $order['booking_sn'] ?? null,
                            'store_id'      => $store->id,
                            'item_id'       => 0,
                            'created_at'    => now(),
                            'updated_at'    => now(),
                        ]
                    );
                }

                // Ambil detail pesanan (maks 50 order_sn sekaligus)
                $chunks = array_chunk($orderSnList, 50);

                foreach ($chunks as $chunk) {
                    $detailsResponse = $shopee->getOrderDetails($store, $chunk);

                    if (isset($detailsResponse['response']['order_list'])) {
                        foreach ($detailsResponse['response']['order_list'] as $detail) {
                            $escrowResponse = $shopee->getEscrowDetail($store, $detail['order_sn']);
                            $escrow = $escrowResponse['response'] ?? [];

                            $itemQuery = Item::query();

                            $itemConditions = [];

                            // Update kembali dengan detail pesanan
                            $orderModel = \App\Models\Order::updateOrCreate(
                                ['order_sn' => $detail['order_sn']],
                                [
                                    'order_status'      => $detail['order_status'] ?? null,
                                    'order_time'        => isset($detail['create_time']) ? Carbon::createFromTimestamp($detail['create_time']) : now(),
                                    'cod'               => $detail['cod'] ?? null,
                                    'ship_by_date'      => isset($detail['ship_by_date']) ? Carbon::createFromTimestamp($detail['ship_by_date']) : now(),
                                    'message_to_seller' => $detail['message_to_seller'] ?? null,
                                    'updated_at'        => isset($detail['updated_at']) ? Carbon::createFromTimestamp($detail['updated_at']) : Carbon::now()->timezone('Asia/Jakarta'),

                                    // escrow fields
                                    'order_selling_price' => $escrow['order_income']['order_selling_price'] ?? null,
                                    'escrow_amount' => $escrow['order_income']['escrow_amount'] ?? null,
                                    'escrow_amount_after_adjustment' => $escrow['order_income']['escrow_amount_after_adjusment'] ?? null,
                                ]
                            );

                            if (!empty($escrow['order_income']['items'])) {
                                foreach ($escrow['order_income']['items'] as $escrowItem) {
                                    \App\Models\OrderItem::UpdateOrCreate(
                                        [
                                            'order_id' => $orderModel->id,
                                            'item_id' => $escrowItem['item_id']
                                        ],
                                        [
                                            'item_name' => $escrowItem['item_name'] ?? null,
                                            'quantity_purchased' => $escrowItem['quantity_purchased'] ?? 0,
                                            'price' => $escrowItem['item_price'] ?? 0,
                                        ]
                                    );
                                }
                            }
                        }
                    }
                }

                $allOrders = array_merge($allOrders, $orders['response']['order_list']);
            }

            $threeMonthsAgo->addDays($intervalDays);
            // sleep(1); // hindari rate limit
        }

        return redirect(route('profit.tracker'));

        // dd($allOrders);

        // return response()->json([
        //     'total_orders'      => count($allOrders),
        //     'orders'            => $allOrders,
        //     'order_detail'      => $detail,
        //     'escrow'            => $escrow,
        // ]);
    }
}
