<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Models\LongDescriptionImage;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'brand_id',
        'code',
        'image',
        'short_description',
        'long_description',
        'regular_price',
        'selling_price',
        'stock',
        'status'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });

        static::updating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function longDescriptionImages()
    {
        return $this->hasMany(LongDescriptionImage::class);
    }
    public function productMultiImages()
    {
        return $this->hasMany(ProductMultiImage::class);
    }
}
