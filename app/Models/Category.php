<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'status'];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::deleting(function ($category) {
            if ($category->slug === 'uncategorized') {
                throw new \Exception('Cannot delete the default Uncategorized category.');
            }
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
