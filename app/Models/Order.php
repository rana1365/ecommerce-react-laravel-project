<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    // This method will return all items of a user order
    public function items (): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // This method will modify the created_at date format
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:d M, Y',
        ];
    }


}
