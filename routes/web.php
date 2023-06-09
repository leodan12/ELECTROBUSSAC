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



//Auth::routes();
Auth::routes(["register" => false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::prefix('admin')->middleware(['auth'])->group(function () {

        Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'inicio']);

        //Rutas de los Datos
        Route::controller(App\Http\Controllers\Admin\DatoController::class)->group(function () { 
            Route::get('/dato/vertasacambio', 'vertasacambio');
            Route::get('/dato/actualizartasacambio/{tasacambio}/{fecha}/{id}', 'actualizartasacambio');
        });
        //Rutas de las Categorias
        Route::controller(App\Http\Controllers\Admin\CategoryController::class)->group(function () {
            Route::get('/category', 'index')->name("categorias.index");
            Route::get('/category/create', 'create');
            Route::post('/category', 'store');
            Route::get('/category/{category}/edit', 'edit');
            Route::put('/category/{category}', 'update');
            Route::get('/category/{category_id}/delete', 'destroy');
            Route::get('/category/showcategoryrestore', 'showcategoryrestore');
            Route::get('/category/restaurar/{idregistro}', 'restaurar');
        });
        //Rutas de las empresas
        Route::controller(App\Http\Controllers\Admin\CompanyController::class)->group(function () {
            Route::get('/company', 'index')->name('empresas.index');
            Route::get('/company/create', 'create');
            Route::post('/company', 'store');
            Route::get('/company/{company}/edit', 'edit');
            Route::put('/company/{company}', 'update');
            Route::get('/company/show/{id}', 'show'); //ver   
            Route::get('/company/{company_id}/delete', 'destroy');
            Route::get('/company/showrestore', 'showrestore');
            Route::get('/company/restaurar/{idregistro}', 'restaurar');
        });
        //Ruta de los clientes
        Route::controller(App\Http\Controllers\Admin\ClienteController::class)->group(function () {
            Route::get('/cliente', 'index')->name('cliente.index');
            Route::get('/cliente/create', 'create');
            Route::post('/cliente', 'store');
            Route::get('/cliente/{cliente}/edit', 'edit');
            Route::put('/cliente/{cliente}', 'update');
            Route::get('/cliente/show/{id}', 'show'); //ver
            Route::get('/cliente/{product_id}/delete', 'destroy');
            Route::get('/cliente/showrestore', 'showrestore');
            Route::get('/cliente/restaurar/{idregistro}', 'restaurar');
        });
        //Rutas de los Productos
        Route::controller(App\Http\Controllers\Admin\ProductController::class)->group(function () {
            Route::get('/products', 'index')->name('producto.index');
            Route::get('/products/create', 'create');
            Route::post('/products', 'store');
            Route::get('/products/{product}/edit', 'edit');
            Route::put('/products/{product}', 'update');
            Route::get('/products/{product_id}/delete', 'destroy');
            Route::get('/products/show/{id}', 'show'); //ver  
            Route::get('/products/showrestore', 'showrestore');
            Route::get('/products/restaurar/{idregistro}', 'restaurar');
        });
        //Rutas de los Kits
        Route::controller(App\Http\Controllers\Admin\DetallekitController::class)->group(function () {
            Route::get('/kits', 'index')->name('kit.index');
            Route::get('/kits/create', 'create');
            Route::post('/kits', 'store');
            Route::get('/kits/{kit_id}/edit', 'edit');
            Route::put('/kits/{kit_id}', 'update');
            Route::get('/kits/{kit_id}/delete', 'destroy');
            Route::get('/kits/show/{kit_id}', 'show'); //ver   
            Route::get('/deletedetallekit/{id}', 'destroydetallekit');
            Route::get('/kits/showrestore', 'showrestore');
            Route::get('/kits/restaurar/{idregistro}', 'restaurar');
        });
        //Ruta de los Usuarios
        Route::controller(App\Http\Controllers\Admin\UserController::class)->group(function () {
            Route::get('/users', 'index')->name('usuario.index');
            Route::get('/users/create', 'create');
            Route::post('/users', 'store');
            Route::get('/users/{user_id}/edit', 'edit');
            Route::put('/users/{user_id}', 'update');
            Route::get('/users/{user_id}/delete', 'destroy');
        });
        //Ruta del inventario
        Route::controller(App\Http\Controllers\Admin\InventarioController::class)->group(function () {
            Route::get('/inventario', 'index')->name('inventario.index');
            Route::get('/inventario2', 'index2')->name('inventario2.index');
            Route::get('/inventorystock', 'index3');
            Route::get('/inventario/create', 'create');
            Route::post('/inventario', 'store');
            Route::get('/inventario/{inventario_id}/edit', 'edit');
            Route::put('/inventario/{inventario_id}', 'update');
            Route::get('/inventario/{inventario_id}/delete', 'destroy');
            Route::get('/deletedetalleinventario/{id}', 'destroydetalleinventario');
            Route::get('/inventario/show/{id}', 'show'); //ver  
            Route::get('/inventario/showkits', 'showkits'); //ver  
            Route::get('/inventario/showrestore', 'showrestore');
            Route::get('/inventario/restaurar/{idregistro}', 'restaurar');
            Route::get('/inventario/showsinstock', 'showsinstock');
            Route::get('/inventario/nroeliminados', 'nroeliminados');
            Route::get('/inventario/numerosinstock', 'numerosinstock');
        });
        //Ruta de la venta
        Route::controller(App\Http\Controllers\Admin\VentaController::class)->group(function () {
            Route::get('/venta', 'index')->name('venta.index');
            Route::get('/venta2', 'index2')->name('venta2.index');
            Route::get('/venta/create', 'create');
            Route::post('/venta', 'store');
            Route::get('/venta/{venta_id}/edit', 'edit');
            Route::get('/venta/create2/{idcotizacion}', 'create2');
            Route::put('/venta/{venta_id}', 'update');
            Route::get('venta/{venta_id}/delete', 'destroy');
            Route::get('/deletedetalleventa/{id}',  'destroydetalleventa');
            Route::get('/venta/show/{id}', 'show'); //ver  
            Route::get('/venta/showcreditos', 'showcreditos'); //ver   creditos
            Route::get('/venta/comboempresacliente/{id}', 'comboempresacliente'); //para no seleccionar en una venta la misma empresa y cliente  
            Route::get('/venta/productosxempresa/{id}', 'productosxempresa'); //devuelve los productos con stock de una empresa
            Route::get('/venta/pagarfactura/{id}',  'pagarfactura');
            Route::get('/venta/generarfacturapdf/{id}',  'generarfacturapdf');
            Route::get('/venta/productosxkit/{id}', 'productosxkit'); //ver  
            Route::get('/venta/stockkitxempresa/{id}', 'stockkitxempresa'); //ver  
            Route::get('/venta/stockxprodxempresa/{idproducto}/{idempresa}', 'stockxprodxempresa'); //ver  
            Route::get('/venta/comboempresaclientevi/{id}', 'comboempresaclientevi');
            Route::get('/venta/facturadisponible/{empresa}/{factura}', 'facturadisponible');
            Route::get('/venta/misdetallesventa/{idventa}', 'misdetallesventa'); //ver  
            Route::get('/venta/stocktotalxkit/{id}', 'stocktotalxkit'); //ver  
            Route::get('/venta/sinnumero', 'sinnumero');
            Route::get('/venta/creditosxvencer', 'creditosxvencer');
            Route::get('/venta/precioespecial/{idcliente}/{idproducto}', 'precioespecial');
            Route::get('/venta/listaprecioscompra/{idproducto}/{idempresa}', 'listaprecioscompra');
        });
        //Ruta de ingresos
        Route::controller(App\Http\Controllers\Admin\IngresoController::class)->group(function () {
            Route::get('/ingreso', 'index')->name('ingreso.index');
            Route::get('/ingreso2', 'index2')->name('ingreso2.index');
            Route::get('/ingreso/create', 'create');
            Route::post('/ingreso', 'store');
            Route::get('/ingreso/{ingreso_id}/edit', 'edit');
            Route::put('/ingreso/{ingreso_id}', 'update');
            Route::get('ingreso/{ingreso_id}/delete', 'destroy');
            Route::get('/deletedetalleingreso/{id}', 'destroydetalleingreso');
            Route::get('/ingreso/show/{id}', 'show'); //ver  
            Route::get('/ingreso/showcreditos', 'showcreditos'); //ver   creditos
            Route::get('/ingreso/pagarfactura/{id}',  'pagarfactura');
            Route::get('/ingreso/sinnumero', 'sinnumero');
            Route::get('/ingreso/creditosxvencer', 'creditosxvencer');
        });
        //Ruta de la cotizacion
        Route::controller(App\Http\Controllers\Admin\CotizacionesController::class)->group(function () {
            Route::get('/cotizacion', 'index')->name('cotizacion.index');
            Route::get('/cotizacion/create', 'create');
            Route::post('/cotizacion', 'store');
            Route::get('/cotizacion/{cotizacion_id}/edit', 'edit');
            Route::put('/cotizacion/{cotizacion_id}', 'update');
            Route::get('cotizacion/{cotizacion_id}/delete', 'destroy');
            Route::get('/deletedetallecotizacion/{id}',  'destroydetallecotizacion');
            Route::get('/deletecondicion/{id}',  'destroycondicion');
            Route::get('/cotizacion/show/{id}', 'show'); //ver  
            Route::get('/cotizacion/showcondiciones/{id}', 'showcondiciones'); //ver  
            Route::get('/cotizacion/vendercotizacion/{id}',  'vendercotizacion');
            Route::get('/cotizacion/generarcotizacionpdf/{id}',  'generarcotizacionpdf');
        });
        //Ruta de los reportes
        Route::controller(App\Http\Controllers\Admin\ReportesController::class)->group(function () {
            Route::get('/reporte', 'index');
            Route::get('/reporte/obtenerbalance/{idempresa}', 'obtenerbalance');
            Route::get('/reporte/balancemensualinicio', 'balancemensual');
            Route::get('/reporte/obtenerdatosgrafico/{idempresa}', 'obtenerdatosgrafico');
            Route::get('/reporte/obtenerproductosmasv/{idempresa}/{traer}', 'obtenerproductosmasv');
            Route::get('/reporte/obtenerclientesmasc/{idempresa}/{tipo}/{traer}', 'obtenerclientesmasc');

            Route::get('/reporte/tabladatos', 'infoproductos');
            Route::get('/reporte/datosproductos/{fechainicio}/{fechafin}/{empresa}/{producto}', 'datosproductos');

            Route::get('/reporte/rotacionstock', 'rotacionstock');
            Route::get('/reporte/datosrotacionstock/{fechainicio}/{fechafin}/{empresa}/{producto}', 'datosrotacionstock');
            Route::get('/reporte/detallecompras/{fechainicio}/{fechafin}/{empresa}/{producto}', 'detallecompras');
            Route::get('/reporte/detalleventas/{fechainicio}/{fechafin}/{empresa}/{producto}', 'detalleventas');

            Route::get('/reporte/cobrovent', 'cobroventas');
            Route::get('/reporte/datoscobroventas/{fechainicio}/{fechafin}/{empresa}/{cliente}', 'datoscobroventas');
            Route::get('/reporte/pagocompras', 'pagocompras');
            Route::get('/reporte/datospagocompras/{fechainicio}/{fechafin}/{empresa}/{cliente}', 'datospagocompras');

            Route::get('/reporte/listaprecioscompra', 'listaprecioscompra');
            Route::get('/reporte/datoslistaprecioscompra/{fechainicio}/{fechafin}/{empresa}/{producto}', 'datoslistaprecioscompra');

        });
        //rutas de los roles
        Route::controller(App\Http\Controllers\Admin\RolController::class)->group(function () {
            Route::get('/rol', 'index')->name('rol.index');
            Route::get('/rol/create', 'create');
            Route::post('/rol', 'store');
            Route::get('/rol/{cliente}/edit', 'edit');
            Route::put('/rol/{cliente}', 'update');
            Route::get('/rol/{product_id}/delete', 'destroy');
        });
        //rutas de los historiales
        Route::controller(App\Http\Controllers\Admin\HistorialController::class)->group(function () {
            Route::get('/historial', 'index')->name('historial.index');
            Route::get('/historial/{historial_id}/delete', 'destroy');
            Route::get('/historial/limpiartabla', 'limpiartabla');
        });
        //Ruta de las ventas antiguas
        Route::controller(App\Http\Controllers\Admin\VentasantiguasController::class)->group(function () {
            Route::get('/ventasantiguas', 'index')->name('ventasantiguas.index');
            Route::get('ventasantiguas/{venta_id}/delete', 'destroy');
            Route::get('/ventasantiguas/show/{id}', 'show'); //ver  

        });
        //Rutas de la Lista de precios
        Route::controller(App\Http\Controllers\Admin\ListaprecioController::class)->group(function () {
            Route::get('/listaprecios', 'index')->name('listaprecio.index');
            Route::get('/listaprecios/create', 'create');
            Route::post('/listaprecios', 'store');
            Route::get('/listaprecios/clientesxproducto/{id}', 'clientesxproducto');
            Route::get('/listaprecios/{product}/edit', 'edit');
            Route::put('/listaprecios/{product}', 'update');
            Route::get('/listaprecios/{product_id}/delete', 'destroy');
            Route::get('/listaprecios/show/{id}', 'show'); //ver   
        });

        //-------------------rutas para los modelos de produccion -----------------------------
        //Rutas de los Modelos de los carros
        Route::controller(App\Http\Controllers\Admin\ModelocarroController::class)->group(function () {
            Route::get('/modelocarro', 'index')->name("modelocarros.index");
            Route::get('/modelocarro/create', 'create');
            Route::post('/modelocarro', 'store');
            Route::get('/modelocarro/{id_registro}/edit', 'edit');
            Route::put('/modelocarro/{id_registro}', 'update');
            Route::get('/modelocarro/{id_registro}/delete', 'destroy');
            Route::get('/modelocarro/showmodelocarrorestore', 'showmodelocarrorestore');
            Route::get('/modelocarro/restaurar/{idregistro}', 'restaurar');
        });
        //Rutas de los Modelos de los carros
        Route::controller(App\Http\Controllers\Admin\CarroceriaController::class)->group(function () {
            Route::get('/carroceria', 'index')->name("carrocerias.index");
            Route::get('/carroceria/create', 'create');
            Route::post('/carroceria', 'store');
            Route::get('/carroceria/{id_registro}/edit', 'edit');
            Route::put('/carroceria/{id_registro}', 'update');
            Route::get('/carroceria/{id_registro}/delete', 'destroy');
            Route::get('/carroceria/showcarroceria/{id_registro}', 'show');
            Route::get('/carroceria/showcarroceriarestore', 'showcarroceriarestore');
            Route::get('/carroceria/restaurar/{idregistro}', 'restaurar');
            Route::get('/carroceria/deletedetalle/{id_registro}', 'deletedetalle');
        });
    });
});
