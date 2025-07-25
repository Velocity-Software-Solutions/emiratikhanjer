<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;
use App\Models\Category;

class Product extends Model
{
  protected $fillable = [
        'name', 'slug', 'sku', 'description', 'price', 'discount_price',
        'stock_quantity', 'condition', 'status', 'category_id'
    ];

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
{
    return $this->belongsTo(Category::class);
}

}
