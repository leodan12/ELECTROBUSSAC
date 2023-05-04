<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleinventario extends Model
{
    use HasFactory;
    protected $table = 'detalleinventarios';
    protected $fillable = [
        'inventario_id',
        'company_id',
        'stockempresa',
        'status',
    ];

    
}
