<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    protected $fillable = [
        'store_id',
        'item_id',
        'item_name',
        'item_sku',
        'item_status',
        'stock',
        'price',
        'category',
        'image',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'item_id', 'item_id');
    }

    public function variantItems()
    {
        return $this->hasMany(VariantItems::class, 'item_id', 'id')->orderBy('model_name');
    }
}
