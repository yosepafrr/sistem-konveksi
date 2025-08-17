<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
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
}
