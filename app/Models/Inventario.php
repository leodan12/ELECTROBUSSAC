<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventarios';
    protected $fillable = [
        'product_id',
        'stockminimo',
        'stocktotal',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

}
