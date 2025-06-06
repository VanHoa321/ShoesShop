<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "level",
        "parent",
        "order",
        "icon",
        "route",
        "role_id",
        "is_active"
    ];
    
    protected $primaryKey = 'id';
    protected $table = 'menus';
    public $timestamps = false;

    public function parents()
    {
        return $this->belongsTo(Menu::class, 'parent');
    }
}
