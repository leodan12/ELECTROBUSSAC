<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;
    protected $table = 'ventas';
    protected $fillable = [
        'company_id',
        'cliente_id',
        'moneda',
        'factura',
        'formapago',
        'observacion',
        'tasacambio',
        'costoventa',
        'fecha',
        'fechav',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class,'company_id','id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class,'cliente_id','id');
    }
    
}
