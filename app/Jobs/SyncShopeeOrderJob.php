<?php

namespace App\Jobs;

use App\Models\Store;
use App\Models\Order;
use App\Services\ShopeeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SyncShopeeOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
        // tidak perlu parameter
    }

    public function handle(): void
    {
        $stores = Store::where('platform', 'Shopee')->get();

        foreach ($stores as $store) {
            $shopee = new ShopeeService();
            $accessToken = $shopee->ensureValidToken($store);
            $now = Carbon::now('UTC');
            $oneMinutesAgo = $now->copy()->subMinutes(1); // Ambil pesanan dari 1 menit terakhir

            $orders = $shopee->getOrderList(
                $accessToken,
                (string) $store->shopee_shop_id,
                $oneMinutesAgo->timestamp,
                $now->timestamp
            ); // Ambil semua order terkini

            foreach ($orders['response']['order_list'] as $order) {
                $orderSn = $order['order_sn'];
                $orderDetail = $shopee->getOrderDetails($store, $orderSn);
                $escrowResponse = $shopee->getEscrowDetail($store, $orderSn);
                $escrow = $escrowResponse['response'] ?? [];

                Order::updateOrCreate(
                    ['order_sn' => $orderSn],
                    [
                        'order_status' => $orderDetail['order_status'] ?? null,
                        'order_time'   => isset($orderDetail['create_time']) ? Carbon::createFromTimestamp($orderDetail['create_time']) : now(),
                        'cod'          => $orderDetail['cod'] ?? null,
                        'ship_by_date' => isset($orderDetail['ship_by_date']) ? Carbon::createFromTimestamp($orderDetail['ship_by_date']) : now(),
                        'message_to_seller' => $orderDetail['message_to_seller'] ?? null,

                        // escrow fields
                        'order_selling_price' => $escrow['order_income']['order_selling_price'] ?? null,
                        'escrow_amount' => $escrow['order_income']['escrow_amount'] ?? null,
                        'escrow_amount_after_adjustment' => $escrow['order_income']['escrow_amount_after_adjusment'] ?? null,
                        'quantity_purchased' => $escrow['order_income']['items'][0]['quantity_purchased'] ?? null,
                        'item_id' => $escrow['order_income']['items'][0]['item_id'] ?? 0,
                    ]
                );
            }
        }
    }
}
