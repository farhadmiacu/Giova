<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'status'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($brand) {
            $brand->slug = Str::slug($brand->name);
        });

        static::updating(function ($brand) {
            $brand->slug = Str::slug($brand->name);
        });

        static::deleting(function ($brand) {
            if ($brand->slug === 'unbranded') {
                throw new \Exception('Cannot delete the default Unbranded brand.');
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
