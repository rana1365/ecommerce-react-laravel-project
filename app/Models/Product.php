<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $appends = ['image_url'];

    public function getImageUrlAttribute ()
    {
        if ( empty($this->image) ) {
            return '';
        }
        return asset("/uploads/products/small/{$this->image}");
    }

    public function product_images (): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function product_sizes (): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }

}
