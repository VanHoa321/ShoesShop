<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCancel extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'cancel_reason',
        'cancel_date',
        'cancel_by',
    ];

    protected $primaryKey = 'id';

    protected $table = 'order_cancels';

    public $timestamps = false;
}
