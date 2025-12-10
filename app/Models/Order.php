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
        'order_status',
        'order_time',
        'cod',
        'ship_by_date',
        'message_to_seller',
        'raw_data',
        'order_selling_price',
        'escrow_amount',
        'escrow_amount_after_adjustment',
        'created_at',
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

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id')->orderBy('id', 'asc');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }
}
