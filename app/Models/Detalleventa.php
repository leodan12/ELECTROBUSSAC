<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalleventa extends Model
{
    use HasFactory;
    protected $table = 'detalleventas';
    protected $fillable = [
        'product_id',
        'cantidad',
        'preciounitario',
        'preciounitariomo',
        'servicio',
        'preciofinal',
    ];
}
