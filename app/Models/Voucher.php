<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_value',
        'min_order_amount',
        'quantity',
        'start_date',
        'end_date',
        'status',
        'description'
    ];

    protected $primaryKey = 'id';
    protected $table = 'vouchers';
}
