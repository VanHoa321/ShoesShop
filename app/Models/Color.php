<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
    ];

    protected $table = 'colors';

    protected $primaryKey = 'id';

    public $timestamps = false;
}
