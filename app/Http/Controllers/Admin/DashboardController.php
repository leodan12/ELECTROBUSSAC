<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Inventario;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $sinstock = $this->productossinstock();
        return view('admin.dashboard',compact('sinstock'));
    }

    public function productossinstock()
    {
        $numerosinstock=0;
        $prod  = DB::table('inventarios as i')
            ->where('i.status', '=', 0)
            ->select('i.id', 'i.product_id', 'i.stockminimo', 'i.stocktotal')
            ->get();
        for ($i = 0; $i < count($prod); $i++) {
            $p  = DB::table('inventarios as i')
                ->where('i.status', '=', 0)
                ->where('i.product_id', '=', $prod[$i]->product_id)
                ->select('i.id', 'i.product_id', 'i.stockminimo', 'i.stocktotal')
                ->first();
            if($p->stockminimo >= $prod[$i]->stocktotal ){
                $numerosinstock++;
            }
        }
        return $numerosinstock;
    }
}
