<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'thumbnail',
        'brand_id',
        'price',
        'summary',
        'description',
        'is_new',
        'is_bestseller',
        'is_sale',
        'status',
    ];

    protected $table = 'products';

    protected $primaryKey = 'id';

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }


    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function colorImages()
    {
        return $this->hasMany(ProductColorImage::class);
    }
}
