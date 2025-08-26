<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\SyncShopeeOrderJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShopeeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->all();

        Log::info('Shopee Webhook Received', $payload);
        
        $orderSn = $payload['response']['order_sn'] ?? null;

        if ($orderSn) {
            SyncShopeeOrderJob::dispatch($orderSn);
        }

        return response()->json(['code' => 0, 'message' => 'success']);
    }
}
