<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');
//INVENTARIO
Route::get('/inventario', 'InventarioController@index')->name('inventario.index');
//VENTAS
Route::get('/ventas', 'VentasController@index')->name('ventas.index');
Route::get('/ventas/create', 'VentasController@create')->name('ventas.create');
Route::get('/ventas/create-compra', 'VentasController@create_compra')->name('ventas.create.compra');
//DESPACHOS
Route::get('/despachos', 'DespachosController@index')->name('despachos.index');
//COMPRAS
Route::get('/compras', 'ComprasController@index')->name('compras.index');
//DESPACHOS ALMACEN
Route::get('/despachos-almacen', 'DespachosController@index_almacen')->name('despachos.almacen.index');
Route::get('/despachos/create', 'DespachosController@create')->name('despachos.create');
Route::post('/despachos-almacen', 'DespachosController@store')->name('despachos.store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


//RUTA DE PRUEBAS
Route::get('/prueba', 'InventarioController@prueba');


//RUTAS APIS
Route::prefix('api')->group(function(){
	//USUARIO
	Route::get('/get-id', 'UsersController@get_id');
	Route::get('/get-piso-venta', 'UsersController@get_piso_venta');
	Route::post('/vaciar-caja', 'UsersController@vaciar_caja');	
	//INVENTARIO
	Route::get('/get-inventario', 'InventarioController@get_inventario');
	Route::get('/ultimo-inventory', 'InventarioController@ultimo_inventory');
	Route::get('/get-inventory/{id}', 'InventarioController@get_inventory');//WEB
	Route::post('/registrar-inventory', 'InventarioController@store_inventory');
	Route::get('/get-precios-inventory', 'InventarioController@get_precios_inventory');//WEB
	Route::post('/actualizar-precios-inventory', 'InventarioController@actualizar_precios_inventory');

	//actualizar inventory_id
	Route::get('/get-inventories-id', 'InventarioController@get_inventory_id');
	Route::post('/actualizar-inventory-id', 'InventarioController@actualizar_inventory_id');

	//DESPACHOS
	Route::get('/get-despachos', 'DespachosController@get_despachos');
	Route::post('/confirmar-despacho', 'DespachosController@confirmar_despacho');
	Route::post('/negar-despacho', 'DespachosController@negar_despacho');
	Route::get('/get-despachos-sin-confirmacion/{piso_venta_id}', 'DespachosController@get_despachos_sin_confirmacion');
	Route::post('/get-despachos-confirmados', 'DespachosController@get_despachos_confirmados');
	Route::post('/actualizar-confirmados', 'DespachosController@actualizar_confirmaciones');

	Route::post('/get-despachos-web', 'DespachosController@get_despachos_web');
	Route::get('/ultimo-despacho', 'DespachosController@ultimo_despacho');
	Route::post('/registrar-despachos-piso-venta', 'DespachosController@registrar_despacho_piso_venta');

	//DESPACHOS ALMACEN
	Route::get('/despachos-datos-create', 'DespachosController@get_datos_create');
	Route::post('/despachos', 'DespachosController@store');
	Route::get('/get-despachos-almacen', 'DespachosController@get_despachos_almacen');
	Route::post('/despachos-retiro', 'DespachosController@store_retiro');
	Route::get('/inventario-piso-venta/{id}', 'DespachosController@get_datos_inventario_piso_venta');

	//VENTAS
	Route::get('/get-ventas', 'VentasController@get_ventas');
	Route::get('/ventas-datos-create', 'VentasController@get_datos_create');
	Route::post('/ventas', 'VentasController@store');
	Route::post('/ventas-comprar', 'VentasController@store_compra');
	Route::put('/anular/{id}', 'VentasController@anular');
	//SINCRONIZACION
	Route::get('/ultima-sincronizacion/{id}', 'SincronizacionController@ultimo');
	Route::post('/sincronizacion', 'SincronizacionController@store')->name('sincronizacion.store');
	//VENTA REFRESCAR
	Route::get('/get-piso-venta-id', 'VentasController@get_piso_venta_id');
	Route::get('/ultima-venta/{piso_venta}', 'VentasController@ultima_venta');//WEB
	Route::get('/ventas-sin-registrar/{piso_venta}/{id}', 'VentasController@ventas_sin_registrar');
	Route::post('/registrar-ventas', 'VentasController@registrar_ventas');//WEB

	Route::get('/get-ventas-anuladas', 'VentasController@get_ventas_anuladas');
	Route::post('/actualizar-anulados', 'VentasController@actualizar_anulados');//WEB
	Route::post('/actualizar-anulados-local', 'VentasController@actualizar_anulados_local');

	//RESUMEN
	Route::get('/get-piso-ventas', 'PisoVentasController@get_piso_ventas');
	Route::get('/resumen', 'PisoVentasController@resumen');
	Route::get('/ventas-compras', 'PisoVentasController@ventas_compras');
	Route::get('/despachos-retiros/{id}', 'PisoVentasController@despachos_retiros');
	Route::get('/productos-piso-venta/{id}', 'PisoVentasController@productos_piso_venta');

	//CAJA REFRESCAR
	Route::get('ultima-vaciada-caja-local/{id}', 'PisoVentasController@ultima_vaciada_caja_local');
});