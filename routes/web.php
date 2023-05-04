<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/', [App\Http\Controllers\HomeController::class, 'inicio'])->name('inicio');

Route::prefix('admin')->middleware(['auth','isAdmin'])->group(function (){

    Route::get('dashboard',[App\Http\Controllers\Admin\DashboardController::class, 'index']);

    //Rutas de las Categorias
    Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function(){
        Route::get('/category','index');
        Route::get('/category/create','create');
        Route::post('/category','store');
        Route::get('/category/{category}/edit','edit');
        Route::put('/category/{category}','update');
    }); 
    //Rutas de los proveedores
    Route::controller(App\Http\Controllers\Admin\CompanyController::class)->group(function(){
        Route::get('/company','index');
        Route::get('/company/create','create');
        Route::post('/company','store');
        Route::get('/company/{company}/edit','edit');
        Route::put('/company/{company}','update');
        Route::get('/company/show/{id}', 'show');//ver   
    }); 
    //Ruta de los clientes
    Route::controller(App\Http\Controllers\Admin\ClienteController::class)->group(function(){
        Route::get('/cliente','index');
        Route::get('/cliente/create','create');
        Route::post('/cliente','store');
        Route::get('/cliente/{cliente}/edit','edit');
        Route::put('/cliente/{cliente}','update');
        Route::get('/cliente/show/{id}', 'show');//ver
    });   
    //Rutas de los Productos
    Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function(){
        Route::get('/products','index');
        Route::get('/products/create','create');
        Route::post('/products','store');
        Route::get('/products/{product}/edit','edit');
        Route::put('/products/{product}','update');
        Route::get('products/{product_id}/delete','destroy');
        Route::get('/products/show/{id}', 'show');//ver   
    }); 
    //Ruta de los Usuarios
    Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function(){
        Route::get('/users','index');
        Route::get('/users/create','create');
        Route::post('/users','store');
        Route::get('/users/{user_id}/edit','edit');
        Route::put('/users/{user_id}','update');
        Route::get('users/{user_id}/delete','destroy');
    });
    //Ruta del inventario
    Route::controller(App\Http\Controllers\Admin\InventarioController::class)->group(function(){
        Route::get('/inventario','index');
        Route::get('/inventario/create','create');
        Route::post('/inventario','store');
        Route::get('/inventario/{inventario_id}/edit','edit');
        Route::put('/inventario/{inventario_id}','update');
        Route::get('inventario/{inventario_id}/delete','destroy');
        Route::get('/deletedetalleinventario/{id}','destroydetalleinventario');
        Route::get('/inventario/show/{id}', 'show');//ver  

    });

    //Ruta de la venta
    Route::controller(App\Http\Controllers\Admin\VentaController::class)->group(function(){
        Route::get('/venta','index');
        Route::get('/venta/create','create');
        Route::post('/venta','store');
        Route::get('/venta/{venta_id}/edit','edit');
        Route::put('/venta/{venta_id}','update');
        Route::get('venta/{venta_id}/delete','destroy');
        Route::get('/deletedetalleventa/{id}',  'destroydetalleventa');
        Route::get('/venta/show/{id}', 'show');//ver  
    });

    //Ruta de ingresos
    Route::controller(App\Http\Controllers\Admin\IngresoController::class)->group(function(){
        Route::get('/ingreso','index');
        Route::get('/ingreso/create','create');
        Route::post('/ingreso','store');
        Route::get('/ingreso/{ingreso_id}/edit','edit');
        Route::put('/ingreso/{ingreso_id}','update');
        Route::get('ingreso/{ingreso_id}/delete','destroy');
        Route::get('/deletedetalleingreso/{id}', [IngresoController::class, 'destroydetalleingreso']);
        Route::get('/ingreso/show/{id}', 'show');//ver  
    });
});
