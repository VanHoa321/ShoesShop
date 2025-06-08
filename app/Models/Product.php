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
        'group_code',
        'thumbnail',
        'brand_id',
        'color_id',
        'price',
        'discount',
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

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
