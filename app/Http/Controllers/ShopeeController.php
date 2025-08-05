<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Store;
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

        $redirectUrl = 'https://ac5e7a3666d8.ngrok-free.app/shopee/callback'; // pastikan ini terdaftar di Shopee developer dashboard
        $url = "https://openplatform.sandbox.test-stable.shopee.sg{$path}"
            . "?partner_id={$partnerId}"
            . "&timestamp={$timestamp}"
            . "&sign={$sign}"
            . "&redirect=" . urlencode($redirectUrl);

        return redirect($url);
    }

    public function handleShopeeCallback(Request $request)
    {
        $partnerId = config('shopee.partner_id');
        $partnerKey = config('shopee.partner_key');
        $timestamp = time();
        $path = '/api/v2/auth/token/get';

        $code = $request->query('code');
        $shopId = $data['shop_id_list'][0] ?? null;

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
        $data = $result;

        if ($data && !empty($data['shop_id_list'])) {
            $shopId = $data['shop_id_list'][0];
            Log::info('Shopee - Saving Store Data', ['shop_id' => $shopId, 'data' => $data]);

            Auth::user()->stores()->updateOrCreate(
                ['shopee_shop_id' => $shopId],
                [
                    'platform' => 'Shopee',
                    'store_name' => $data['shop_name'] ?? 'Toko Shopee',
                    'access_token' => $data['access_token'],
                    'refresh_token' => $data['refresh_token'],
                    'token_expired_at' => Carbon::createFromTimestamp($data['expire_in'] + $timestamp)
                        ->setTimezone('Asia/Jakarta'),
                ]
            );

            return redirect()->route('profit.tracker')->with('success', 'Toko Shopee berhasil terhubung.');
        }

        return response()->json([
            'message' => 'Gagal menghubungkan toko',
            'debug' => [
                'url' => $url,
                'sign' => $sign,
                'base_string' => $baseString,
                'response' => $result
            ]
        ], 400);
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
                            'booking_sn' => $order['booking_sn'] ?? null,
                            'store_id' => $store->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                // Ambil detail pesanan (maks 50 order_sn sekaligus)
                $chunks = array_chunk($orderSnList, 50);

                foreach ($chunks as $chunk) {
                    $detailsResponse = $shopee->getOrderDetails($store, $chunk);

                    if (isset($detailsResponse['response']['order_list'])) {
                        foreach ($detailsResponse['response']['order_list'] as $detail) {
                            // Update kembali dengan detail pesanan
                            \App\Models\Order::where('order_sn', $detail['order_sn'])
                                ->update([
                                    'order_status'    => $detail['order_status'] ?? null,
                                    'order_time'      => isset($detail['create_time']) ? Carbon::createFromTimestamp($detail['create_time']) : now(),
                                    'updated_at'      => Carbon::now()->timezone('Asia/Jakarta'),
                                ]);
                        }
                    }
                }

                $allOrders = array_merge($allOrders, $orders['response']['order_list']);
            }

            $threeMonthsAgo->addDays($intervalDays);
            sleep(1); // hindari rate limit
        }


        return response()->json([
            'total_orders' => count($allOrders),
            'orders' => $allOrders,
        ]);
    }
}
