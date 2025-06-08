<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'description',
        'is_active',
    ];

    protected $table = 'brands';

    protected $primaryKey = 'id';

    public $timestamps = false;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
