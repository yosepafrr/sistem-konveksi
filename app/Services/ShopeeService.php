<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Store;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ShopeeService
{
    protected $partnerId;
    protected $partnerKey;

    public function __construct()
    {
        $this->partnerId = config('services.shopee.partner_id');
        $this->partnerKey = config('services.shopee.partner_key');
        Log::info('ShopeeService constructed', [
            'partner_id' => $this->partnerId,
            'partner_key' => $this->partnerKey,
        ]);
    }

    public function ensureValidToken(Store $store)
    {
        if (Carbon::now('Asia/Jakarta')->gte($store->token_expired_at)) {
            $this->refreshAccessToken($store);
        }

        return $store->access_token;
    }

    public function refreshAccessToken(Store $store)
    {
        $path = '/api/v2/auth/access_token/get';
        $timestamp = time();

        $shopId = trim($store->shopee_shop_id);
        $refreshToken = trim($store->refresh_token);

        $baseString = $this->partnerId . $path . $timestamp . $refreshToken . $shopId;
        $sign = hash_hmac('sha256', $baseString, $this->partnerKey);

        $url = "https://openplatform.sandbox.test-stable.shopee.sg{$path}"
            . "?partner_id={$this->partnerId}"
            . "&timestamp={$timestamp}"
            . "&shop_id={$shopId}"
            . "&refresh_token={$refreshToken}"
            . "&sign={$sign}";

        Log::info('Shopee Access Token Refresh Request URL', ['url' => $url]);

        // Gunakan GET atau POST tergantung dari dokumentasi Shopee (biasanya POST tapi tanpa body)
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url);

        $json = $response->json();

        Log::info('Shopee - Refresh Access Token Response', [
            'response' => $json,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if (isset($json['response'])) {
            $data = $json['response'];

            $store->update([
                'access_token' => $data['access_token'],
                'refresh_token' => $data['refresh_token'],
                'token_expired_at' => Carbon::createFromTimestamp($data['expire_in'] + $timestamp)
                    ->setTimezone('Asia/Jakarta'),
            ]);

            return true;
        }

        Log::debug('Shopee base string', ['base_string' => $baseString]);
        Log::debug('Shopee partner key', ['partner_key' => $this->partnerKey]);
        Log::error('Shopee - Failed to refresh access token', ['response' => $response->body()]);
        return false;
    }


    protected function generateSign($path, $timestamp, $accessToken, $shopId)
    {
        $baseString = $this->partnerId . $path . $timestamp . $accessToken . $shopId;
        return hash_hmac('sha256', $baseString, $this->partnerKey);
    }

    public function getOrderList(string $accessToken, string $shopId, int $timeFrom, int $timeTo)
    {
        $timestamp = time();
        $path = '/api/v2/order/get_order_list';
        $sign = $this->generateSign($path, $timestamp, $accessToken, $shopId);

        $url = "https://openplatform.sandbox.test-stable.shopee.sg{$path}"
            . "?partner_id={$this->partnerId}"
            . "&timestamp={$timestamp}"
            . "&sign={$sign}"
            . "&access_token={$accessToken}"
            . "&shop_id={$shopId}"
            . "&time_range_field=create_time"
            . "&time_from={$timeFrom}"
            . "&time_to={$timeTo}"
            . "&page_size=100";

        Log::info('Shopee - Fetching Order List', ['url' => $url]);
        Log::info('Shopee - Params', [
            'partner_id' => $this->partnerId,
            'shop_id' => $shopId,
            'access_token' => $accessToken,
            'sign' => $sign,
            'url' => $url,
        ]);


        $response = Http::get($url);

        return $response->json();
    }

    public function getOrderDetails($store, $orderSnList)
    {
        $shop_id = $store->shopee_shop_id;
        $access_token = $store->access_token;
        $order_sn_list_json = json_encode($orderSnList);

        $path = "/api/v2/order/get_order_detail";
        $timestamp = time();

        $base_string = $this->partnerId . $path . $timestamp . $access_token . $shop_id;
        $sign = hash_hmac('sha256', $base_string, $this->partnerKey);

        $response = Http::get('https://openplatform.sandbox.test-stable.shopee.sg/api/v2/order/get_order_detail', [
            'partner_id' => $this->partnerId,
            'timestamp' => $timestamp,
            'sign' => $sign,
            'shop_id' => $shop_id,
            'access_token' => $access_token,
            'order_sn_list' => implode(',', $orderSnList), // JSON-encoded array!
        ]);

        return json_decode($response->getBody(), true);
    }
}
