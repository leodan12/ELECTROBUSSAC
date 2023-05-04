<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleingreso extends Model
{
    use HasFactory;
    protected $table = 'detalleingresos';
    protected $fillable = [
        'product_id',
        'cantidad',
        'preciounitario',
        'preciounitariomo',
        'servicio',
        'preciofinal',
    ];
}
