<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
    ];

    protected $table = 'product_images';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
