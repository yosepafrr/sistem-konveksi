<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'store_id',
        'order_sn',
        'booking_sn',
        'created_at',
        'updated_at',
        'order_status',
        'order_time',
        'updated_at',
    ];

    protected $casts = [
        'cod' => 'boolean',
        'ship_by_date' => 'datetime',
        'order_time' => 'datetime',
        'raw_data' => 'array',
        'total_amount' => 'float',
        'order_selling_price' => 'float',
        'escrow_amount_after_adjustment' => 'float',
    ];
}
