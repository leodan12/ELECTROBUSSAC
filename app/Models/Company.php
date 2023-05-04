<?php

namespace App\Models;

use App\Models\Detalleinventario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;
    protected $table = 'companies';
    protected $fillable = [
        'nombre',
        'ruc',
        'direccion',
        'telefono',
        'email',
        'tipo',
        'status',
    ];

    public function detalleinventario()
    {
        return $this->hasMany(Detalleinventario::class,'company_id','id');
    }

    public function ventas()
    {
        return $this->hasOne(Venta::class,'company_id','id');
    }
}
