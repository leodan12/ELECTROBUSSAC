<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $fillable = [
        'nombre',
        'status',
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'category_id','id');
    }
}
