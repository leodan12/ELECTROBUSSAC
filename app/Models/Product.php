<?php

namespace App\Models;

use App\Models\Inventario;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    
    protected $table = 'products';
   
    protected $fillable = [
        'category_id',
        'nombre',
        'codigo',
        'unidad',
        'und',
        'maximo',
        'minimo',
        'moneda',
        'NoIGV',
        'SiIGV',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id','id');
    }

    public function inventarios()
    {
        return $this->hasOne(Inventario::class,'product_id','id');
    }

    public function detalleventa()
    {
        return $this->hasMany(Detalleventa::class,'product_id','id');
    }

}
