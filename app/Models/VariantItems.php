<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantItems extends Model
{
        protected $fillable = [
        'item_id',
        'model_id',
        'model_name',
        'model_sku',
        'status',
        'stock',
        'price',
        'hpp',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
