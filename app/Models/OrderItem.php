<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_id',
        'item_name',
        'model_name',
        'quantity_purchased',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class)->orderBy('order_time', 'desc');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id', 'item_id');
    }

    public function variantItems()
    {
        return $this->hasMany(VariantItems::class, 'item_id', 'id');
    }
}
