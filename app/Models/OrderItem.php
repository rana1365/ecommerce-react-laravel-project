<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    // This method used to attach image(means all info of a product) in items
    public function product (): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
