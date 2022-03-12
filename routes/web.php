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
 
    return view('welcome');
});

/*
Route::get('/menu', function(){
    return view('menu')->middleware('auth');
}); */

Route::get('/menu','menuController@menu')->name('menu')->middleware('auth');
Route::get('/menu_out','menuController@logout')->name('menu.out');

Route::get('/estadia','estadiaController@index')->name('estadia.index')->middleware('auth');
Route::post('/estadia', 'estadiaController@store')->name('estadia.store')->middleware('auth');
Route::get('/estadia/create', 'estadiaController@create')->name('estadia.create')->middleware('auth');
Route::get('/estadia/editar/{estadia}', 'estadiaController@edit')->name('estadia.edit')->middleware('auth');
Route::put('/estadia/{estadia}', 'estadiaController@update')->name('estadia.update')->middleware('auth');
Route::get('/estadia/anular/{estadia}', 'estadiaController@destroy')->name('estadia.destroy')->middleware('auth');
Route::post('/estadia_tarifa', 'estadiaController@tarifa')->name('estadia.tarifa')->middleware('auth');
Route::post('/estadia_persona', 'estadiaController@persona')->name('estadia.persona')->middleware('auth');
Route::post('/estadia_reserva', 'estadiaController@reserva')->name('estadia.reserva')->middleware('auth');

Route::get('/reserva','reservaController@index')->name('reserva.index')->middleware('auth');
Route::post('/reserva', 'reservaController@store')->name('reserva.store')->middleware('auth');
Route::get('/reserva/create', 'reservaController@create')->name('reserva.create')->middleware('auth');
Route::get('/reserva/editar/{reserva}', 'reservaController@edit')->name('reserva.edit')->middleware('auth');
Route::put('/reserva/{reserva}', 'reservaController@update')->name('reserva.update')->middleware('auth');
Route::get('/reserva/anular/{reserva}', 'reservaController@destroy')->name('reserva.destroy')->middleware('auth');

Route::get('/factura','facturaController@index')->name('factura.index')->middleware('auth');
Route::post('/factura', 'facturaController@store')->name('factura.store')->middleware('auth');
Route::get('/factura/create', 'facturaController@create')->name('factura.create')->middleware('auth');
Route::get('/factura/editar/{factura}/{timbrado}', 'facturaController@edit')->name('factura.edit')->middleware('auth');
Route::put('/factura/{factura}', 'facturaController@update')->name('factura.update')->middleware('auth');
Route::get('/factura/anular/{factura}/{timbrado}', 'facturaController@destroy')->name('factura.destroy')->middleware('auth');
Route::post('/factura_estadia', 'facturaController@estadia')->name('factura.estadia')->middleware('auth');

Route::get('/apertura_cierre','aperturaController@index')->name('apertura.index')->middleware('auth');
Route::post('/apertura_cierre', 'aperturaController@store')->name('apertura.store')->middleware('auth');
Route::get('/apertura_cierre/create', 'aperturaController@create')->name('apertura.create')->middleware('auth');
Route::get('/apertura_cierre/editar/{apertura}', 'aperturaController@edit')->name('apertura.edit')->middleware('auth');
Route::put('/apertura_cierre/{apertura}', 'aperturaController@update')->name('apertura.update')->middleware('auth');
Route::get('/apertura_cierre/anular/{apertura}', 'aperturaController@destroy')->name('apertura.destroy')->middleware('auth');
Route::post('/apertura_estimado', 'aperturaController@estimado')->name('apertura.estimado')->middleware('auth');

Route::get('/cobros','cobrosController@index')->name('cobros.index')->middleware('auth');
Route::post('/cobros', 'cobrosController@store')->name('cobros.store')->middleware('auth');
Route::get('/cobros/create', 'cobrosController@create')->name('cobros.create')->middleware('auth');
Route::get('/cobros/editar/{cobros}', 'cobrosController@edit')->name('cobros.edit')->middleware('auth');
Route::put('/cobros/{cobros}', 'cobrosController@update')->name('cobros.update')->middleware('auth');
Route::get('/cobros/anular/{cobros}', 'cobrosController@destroy')->name('cobros.destroy')->middleware('auth');

Route::get('/arqueo','arqueoController@index')->name('arqueo.index')->middleware('auth');
Route::post('/arqueo', 'arqueoController@store')->name('arqueo.store')->middleware('auth');
Route::get('/arqueo/create', 'arqueoController@create')->name('arqueo.create')->middleware('auth');
Route::get('/arqueo/editar/{cobros}', 'arqueoController@edit')->name('arqueo.edit')->middleware('auth');
Route::put('/arqueo/{cobros}', 'arqueoController@update')->name('arqueo.update')->middleware('auth');
Route::get('/arqueo/anular/{cobros}', 'arqueoController@destroy')->name('arqueo.destroy')->middleware('auth');
Route::post('/arqueo_estimado', 'arqueoController@estimado')->name('arqueo.estimado')->middleware('auth');

Route::get('/nota_de_credito','nota_de_creditoController@index')->name('nota_de_credito.index')->middleware('auth');
Route::post('/nota_de_credito', 'nota_de_creditoController@store')->name('nota_de_credito.store')->middleware('auth');
Route::get('/nota_de_credito/create', 'nota_de_creditoController@create')->name('nota_de_credito.create')->middleware('auth');
Route::get('/nota_de_credito/editar/{credito}', 'arqueoController@edit')->name('nota_de_credito.edit')->middleware('auth');
Route::put('/nota_de_credito/{credito}', 'nota_de_creditoController@update')->name('nota_de_credito.update')->middleware('auth');
Route::get('/nota_de_credito/anular/{credito}/{timbrado}', 'nota_de_creditoController@destroy')->name('nota_de_credito.destroy')->middleware('auth');
Route::post('/n_credito_factura', 'nota_de_creditoController@factura')->name('n_credito.factura')->middleware('auth');

Route::get('/nota_de_debito','nota_de_debitoController@index')->name('nota_de_debito.index')->middleware('auth');
Route::post('/nota_de_debito', 'nota_de_debitoController@store')->name('nota_de_debito.store')->middleware('auth');
Route::get('/nota_de_debito/create', 'nota_de_debitoController@create')->name('nota_de_debito.create')->middleware('auth');
Route::get('/nota_de_debito/editar/{credito}', 'nota_de_debitoController@edit')->name('nota_de_debito.edit')->middleware('auth');
Route::put('/nota_de_debito/{credito}', 'nota_de_debitoController@update')->name('nota_de_debito.update')->middleware('auth');
Route::get('/nota_de_debito/anular/{credito}/{timbrado}', 'nota_de_debitoController@destroy')->name('nota_de_debito.destroy')->middleware('auth');
Route::post('/n_debito_factura', 'nota_de_debitoController@factura')->name('n_debito.factura')->middleware('auth');

Route::get('/requisicion','requisicionController@index')->name('requisicion.index')->middleware('auth');
Route::post('/requisicion', 'requisicionController@store')->name('requisicion.store')->middleware('auth');
Route::get('/requisicion/create', 'requisicionController@create')->name('requisicion.create')->middleware('auth');
Route::get('/requisicion/editar/{requisicion}', 'requisicionController@edit')->name('requisicion.edit')->middleware('auth');
Route::put('/requisicion/{requisicion}', 'requisicionController@update')->name('requisicion.update')->middleware('auth');
Route::get('/requisicion/anular/{requisicion}', 'requisicionController@destroy')->name('requisicion.destroy')->middleware('auth');
Route::post('/requisicion_articulo', 'requisicionController@articulo')->name('requisicion.articulo')->middleware('auth');

Route::get('/presupuesto','presupuestoController@index')->name('presupuesto.index')->middleware('auth');
Route::post('/presupuesto', 'presupuestoController@store')->name('presupuesto.store')->middleware('auth');
Route::get('/presupuesto/create', 'presupuestoController@create')->name('presupuesto.create')->middleware('auth');
Route::get('/presupuesto/editar/{requisicion}', 'presupuestoController@edit')->name('presupuesto.edit')->middleware('auth');
Route::put('/presupuesto/{requisicion}', 'presupuestoController@update')->name('presupuesto.update')->middleware('auth');
Route::get('/presupuesto/anular/{requisicion}', 'presupuestoController@destroy')->name('presupuesto.destroy')->middleware('auth');

Route::get('/factura_numero','factura_numeroController@index')->name('factura_numero.index')->middleware('auth');
Route::post('/factura_numero', 'factura_numeroController@store')->name('factura_numero.store')->middleware('auth');
Route::get('/factura_numero/create', 'factura_numeroController@create')->name('factura_numero.create')->middleware('auth');
Route::get('/factura_numero/anular/{requisicion}', 'factura_numeroController@destroy')->name('factura_numero.destroy')->middleware('auth');

Route::get('/orden','ordenController@index')->name('orden.index')->middleware('auth');
Route::post('/orden', 'ordenController@store')->name('orden.store')->middleware('auth');
Route::get('/orden/create', 'ordenController@create')->name('orden.create')->middleware('auth');
Route::get('/orden/editar/{orden}', 'ordenController@edit')->name('orden.edit')->middleware('auth');
Route::put('/orden/{orden}', 'ordenController@update')->name('orden.update')->middleware('auth');
Route::get('/orden/anular/{numero}', 'ordenController@destroy')->name('orden.destroy')->middleware('auth');
Route::post('/orden_presupuesto', 'ordenController@presupuesto')->name('orden.presupuesto')->middleware('auth');

Route::get('/factura_compra','factura_compraController@index')->name('factura_compra.index')->middleware('auth');
Route::post('/factura_compra', 'factura_compraController@store')->name('factura_compra.store')->middleware('auth');
Route::get('/factura_compra/create', 'factura_compraController@create')->name('factura_compra.create')->middleware('auth');
Route::get('/factura_compra/editar/{factura}', 'factura_compraController@edit')->name('factura_compra.edit')->middleware('auth');
Route::put('/factura_compra/{factura}', 'factura_compraController@update')->name('factura_compra.update')->middleware('auth');
Route::get('/factura_compra/anular/{factura}', 'factura_compraController@destroy')->name('factura_compra.destroy')->middleware('auth');
Route::post('/factura_orden', 'factura_compraController@orden')->name('factura.orden')->middleware('auth');

Route::get('/remision','remisionController@index')->name('remision.index')->middleware('auth');
Route::post('/remision', 'remisionController@store')->name('remision.store')->middleware('auth');
Route::get('/remision/create', 'remisionController@create')->name('remision.create')->middleware('auth');
Route::get('/remision/editar/{requisicion}', 'remisionController@edit')->name('remision.edit')->middleware('auth');
Route::put('/remision/{requisicion}', 'remisionController@update')->name('remision.update')->middleware('auth');
Route::get('/remision/anular/{requisicion}', 'remisionController@destroy')->name('remision.destroy')->middleware('auth');
Route::post('/remision_factura', 'remisionController@factura')->name('remision.factura')->middleware('auth');

Route::get('/entrada','entradaController@index')->name('entrada.index')->middleware('auth');
Route::post('/entrada', 'entradaController@store')->name('entrada.store')->middleware('auth');
Route::get('/entrada/create', 'entradaController@create')->name('entrada.create')->middleware('auth');
Route::get('/entrada/editar/{entrada}', 'entradaController@edit')->name('entrada.edit')->middleware('auth');
Route::put('/entrada/{entrada}', 'entradaController@update')->name('entrada.update')->middleware('auth');
Route::get('/entrada/anular/numero/{numero}', 'entradaController@destroy')->name('entrada.destroy')->middleware('auth');
Route::post('/entrada_articulo', 'entradaController@articulo')->name('entrada.articulo')->middleware('auth');

Route::get('/salida','salidaController@index')->name('salida.index')->middleware('auth');
Route::post('/salida', 'salidaController@store')->name('salida.store')->middleware('auth');
Route::get('/salida/create', 'salidaController@create')->name('salida.create')->middleware('auth');
Route::get('/salida/editar/{salida}', 'salidaController@edit')->name('salida.edit')->middleware('auth');
Route::put('/salida/{salida}', 'salidaController@update')->name('salida.update')->middleware('auth');
Route::get('/salida/anular/{salida}', 'salidaController@destroy')->name('salida.destroy')->middleware('auth');
Route::post('/salida_articulo', 'salidaController@articulo')->name('salida.articulo')->middleware('auth');
Route::post('/salida_requisicion', 'salidaController@requisicion')->name('salida.requisicion')->middleware('auth');

Route::get('/nota_credito_c','nota_credito_cController@index')->name('nota_credito_c.index')->middleware('auth');
Route::post('/nota_credito_c', 'nota_credito_cController@store')->name('nota_credito_c.store')->middleware('auth');
Route::get('/nota_credito_c/create', 'nota_credito_cController@create')->name('nota_credito_c.create')->middleware('auth');
Route::get('/nota_credito_c/editar/{salida}', 'nota_credito_cController@edit')->name('nota_credito_c.edit')->middleware('auth');
Route::put('/nota_credito_c/{salida}', 'nota_credito_cController@update')->name('nota_credito_c.update')->middleware('auth');
Route::get('/nota_credito_c/anular/{salida}', 'nota_credito_cController@destroy')->name('nota_credito_c.destroy')->middleware('auth');
Route::post('/nota_credito_c_factura', 'nota_credito_cController@rfactura')->name('nota_credito_c.requisicion')->middleware('auth');

Route::get('/nota_debito_c','nota_debito_cController@index')->name('nota_debito_c.index')->middleware('auth');
Route::post('/nota_debito_c', 'nota_debito_cController@store')->name('nota_debito_c.store')->middleware('auth');
Route::get('/nota_debito_c/create', 'nota_debito_cController@create')->name('nota_debito_c.create')->middleware('auth');
Route::get('/nota_debito_c/editar/{salida}', 'nota_debito_cController@edit')->name('nota_debito_c.edit')->middleware('auth');
Route::put('/nota_debito_c/{salida}', 'nota_debito_cController@update')->name('nota_debito_c.update')->middleware('auth');
Route::get('/nota_debito_c/anular/{salida}', 'nota_debito_cController@destroy')->name('nota_debito_c.destroy')->middleware('auth');
Route::post('/nota_debito_c_factura', 'nota_debito_cController@rfactura')->name('nota_debito_c.requisicion')->middleware('auth');

Route::get('/servicios_spa','servicios_spaController@index')->name('servicios_spa.index')->middleware('auth');
Route::post('/servicios_spa', 'servicios_spaController@store')->name('servicios_spa.store')->middleware('auth');
Route::get('/servicios_spa/create', 'servicios_spaController@create')->name('servicios_spa.create')->middleware('auth');
Route::get('/servicios_spa/editar/{servicios}', 'servicios_spaController@edit')->name('servicios_spa.edit')->middleware('auth');
Route::put('/servicios_spa/{servicios}', 'servicios_spaController@update')->name('servicios_spa.update')->middleware('auth');
Route::get('/servicios_spa/anular/{servicios}', 'servicios_spaController@destroy')->name('servicios_spa.destroy')->middleware('auth');
Route::post('/servicios_huesped', 'servicios_spaController@huesped')->name('servicios_spa.huesped')->middleware('auth');
Route::post('/servicios_sauna', 'servicios_spaController@sauna')->name('servicios_spa.sauna')->middleware('auth');

Route::get('/servicios_spa_sauna','servicios_spa_saunaController@index')->name('servicios_spa_sauna.index')->middleware('auth');
Route::post('/servicios_spa_sauna', 'servicios_spa_saunaController@store')->name('servicios_spa_sauna.store')->middleware('auth');
Route::get('/servicios_spa_sauna/create', 'servicios_spa_saunaController@create')->name('servicios_spa_sauna.create')->middleware('auth');
Route::get('/servicios_spa_sauna/editar/{servicios}', 'servicios_spa_saunaController@edit')->name('servicios_spa_sauna.edit')->middleware('auth');
Route::put('/servicios_spa_sauna/{servicios}', 'servicios_spa_saunaController@update')->name('servicios_spa_sauna.update')->middleware('auth');
Route::get('/servicios_spa_sauna/anular/{servicios}', 'servicios_spa_saunaController@destroy')->name('servicios_spa_sauna.destroy')->middleware('auth');
Route::post('/servicios_spa_sauna_spa_sauna', 'servicios_spa_saunaController@spa_sauna')->name('servicios_spa_sauna.spa_sauna')->middleware('auth');
Route::get('/spa_sauna_realizado/{id}', 'servicios_spa_saunaController@realizado')->name('servicios_spa_sauna.realizado')->middleware('auth');

Route::get('/servicios_consumicion','servicios_consumicionController@index')->name('servicios_consumicion.index')->middleware('auth');
Route::post('/servicios_consumicion', 'servicios_consumicionController@store')->name('servicios_consumicion.store')->middleware('auth');
Route::get('/servicios_consumicion/create', 'servicios_consumicionController@create')->name('servicios_consumicion.create')->middleware('auth');
Route::get('/servicios_consumicion/editar/{servicios}', 'servicios_consumicionController@edit')->name('servicios_consumicion.edit')->middleware('auth');
Route::put('/servicios_consumicion/{servicios}', 'servicios_consumicionController@update')->name('servicios_consumicion.update')->middleware('auth');
Route::get('/servicios_consumicion/anular/{servicios}', 'servicios_consumicionController@destroy')->name('servicios_consumicion.destroy')->middleware('auth');
Route::post('/servicios_producto', 'servicios_consumicionController@producto')->name('servicios_consumicion.producto')->middleware('auth');
Route::get('/consumicion_realizado/{id}', 'servicios_consumicionController@realizado')->name('consumicion.realizado')->middleware('auth');

Route::get('/servicios_lavanderia','servicios_lavanderiaController@index')->name('servicios_lavanderia.index')->middleware('auth');
Route::post('/servicios_lavanderia', 'servicios_lavanderiaController@store')->name('servicios_lavanderia.store')->middleware('auth');
Route::get('/servicios_lavanderia/create', 'servicios_lavanderiaController@create')->name('servicios_lavanderia.create')->middleware('auth');
Route::get('/servicios_lavanderia/editar/{servicios}', 'servicios_lavanderiaController@edit')->name('servicios_lavanderia.edit')->middleware('auth');
Route::put('/servicios_lavanderia/{servicios}', 'servicios_lavanderiaController@update')->name('servicios_lavanderia.update')->middleware('auth');
Route::get('/servicios_lavanderia/anular/{servicios}', 'servicios_lavanderiaController@destroy')->name('servicios_lavanderia.destroy')->middleware('auth');
Route::post('/servicio_lavanderia', 'servicios_lavanderiaController@lavanderia')->name('servicios_lavanderia.lavanderia')->middleware('auth');
Route::get('/lavanderia_realizado/{id}', 'servicios_lavanderiaController@realizado')->name('lavanderia.realizado')->middleware('auth');

Route::get('/servicios_traslado','servicios_trasladoController@index')->name('servicios_traslado.index')->middleware('auth');
Route::post('/servicios_traslado', 'servicios_trasladoController@store')->name('servicios_traslado.store')->middleware('auth');
Route::get('/servicios_traslado/create', 'servicios_trasladoController@create')->name('servicios_traslado.create')->middleware('auth');
Route::get('/servicios_traslado/editar/{servicios}', 'servicios_trasladoController@edit')->name('servicios_traslado.edit')->middleware('auth');
Route::put('/servicios_traslado/{servicios}', 'servicios_trasladoController@update')->name('servicios_traslado.update')->middleware('auth');
Route::get('/servicios_traslado/anular/{servicios}', 'servicios_trasladoController@destroy')->name('servicios_traslado.destroy')->middleware('auth');
Route::post('/servicios_traslados', 'servicios_trasladoController@producto')->name('servicios_traslado.producto')->middleware('auth');
Route::get('/traslado_realizado/{id}', 'servicios_trasladoController@realizado')->name('traslado.realizado')->middleware('auth');

Route::get('/servicios_turismo','servicios_turismoController@index')->name('servicios_turismo.index')->middleware('auth');
Route::post('/servicios_turismo', 'servicios_turismoController@store')->name('servicios_turismo.store')->middleware('auth');
Route::get('/servicios_turismo/create', 'servicios_turismoController@create')->name('servicios_turismo.create')->middleware('auth');
Route::get('/servicios_turismo/editar/{servicios}', 'servicios_turismoController@edit')->name('servicios_turismo.edit')->middleware('auth');
Route::put('/servicios_turismo/{servicios}', 'servicios_turismoController@update')->name('servicios_turismo.update')->middleware('auth');
Route::get('/servicios_turismo/anular/{servicios}', 'servicios_turismoController@destroy')->name('servicios_turismo.destroy')->middleware('auth');
Route::post('/servicio_turismo', 'servicios_turismoController@turismo')->name('servicios_turismo.turismo')->middleware('auth');
Route::get('/turismo_realizado/{id}', 'servicios_turismoController@realizado')->name('turismo.realizado')->middleware('auth');

Route::get('/tarifa','tarifaController@index')->name('tarifa.index')->middleware('auth');
Route::post('/tarifa', 'tarifaController@store')->name('tarifa.store')->middleware('auth');
Route::get('/tarifa/create', 'tarifaController@create')->name('tarifa.create')->middleware('auth');
Route::get('/tarifa/editar/{tarifa}', 'tarifaController@edit')->name('tarifa.edit')->middleware('auth');
Route::put('/tarifa/{tarifa}', 'tarifaController@update')->name('tarifa.update')->middleware('auth');
Route::get('/tarifa/anular/{tarifa}', 'tarifaController@destroy')->name('tarifa.destroy')->middleware('auth');

Route::get('/habitaciones','habitacionesController@index')->name('habitaciones.index')->middleware('auth');
Route::post('/habitaciones', 'habitacionesController@store')->name('habitaciones.store')->middleware('auth');
Route::get('/habitaciones/create', 'habitacionesController@create')->name('habitaciones.create')->middleware('auth');
Route::get('/habitaciones/editar/{habitacion}', 'habitacionesController@edit')->name('habitaciones.edit')->middleware('auth');
Route::put('/habitaciones/{habitacion}', 'habitacionesController@update')->name('habitaciones.update')->middleware('auth');
Route::get('/habitaciones/anular/{habitacion}', 'habitacionesController@destroy')->name('habitaciones.destroy')->middleware('auth');
Route::post('/habitaciones_cama', 'habitacionesController@cama')->name('habitaciones.cama')->middleware('auth');

Route::get('/ajuste','ajusteController@index')->name('ajuste.index')->middleware('auth');
Route::post('/ajuste', 'ajusteController@store')->name('ajuste.store')->middleware('auth');
Route::get('/ajuste/create/{ajuste}', 'ajusteController@create')->name('ajuste.create')->middleware('auth');

Route::get('/recaudaciones','recaudacionesController@index')->name('recaudaciones.index')->middleware('auth');
Route::post('/recaudaciones', 'recaudacionesController@store')->name('recaudaciones.store')->middleware('auth');
Route::get('/recaudaciones/create', 'recaudacionesController@create')->name('recaudaciones.create')->middleware('auth');
Route::get('/recaudaciones/editar/{recaudaciones}', 'recaudacionesController@edit')->name('recaudaciones.edit')->middleware('auth');
Route::put('/recaudaciones/{recaudaciones}', 'recaudacionesController@update')->name('recaudaciones.update')->middleware('auth');
Route::get('/recaudaciones/anular/{recaudaciones}', 'recaudacionesController@destroy')->name('recaudaciones.destroy')->middleware('auth');

//Referenciales
Route::get('/personas','personasController@index')->name('personas.index')->middleware('auth');
Route::post('/personas', 'personasController@store')->name('personas.store')->middleware('auth');
Route::get('/personas/create', 'personasController@create')->name('personas.create')->middleware('auth');
Route::get('/personas/editar/{data1}/{data2}', 'personasController@edit')->name('personas.edit')->middleware('auth');
Route::put('/personas/{habitacion}', 'personasController@update')->name('personas.update')->middleware('auth');
Route::get('/personas/eliminar/{data1}/{data2}', 'personasController@destroy')->name('personas.destroy')->middleware('auth');

Route::get('/articulos','articulosController@index')->name('articulos.index')->middleware('auth');
Route::post('/articulos', 'articulosController@store')->name('articulos.store')->middleware('auth');
Route::get('/articulos/create', 'articulosController@create')->name('articulos.create')->middleware('auth');
Route::get('/articulos/editar/{articulos}', 'articulosController@edit')->name('articulos.edit')->middleware('auth');
Route::put('/articulos/{articulos}', 'articulosController@update')->name('articulos.update')->middleware('auth');
Route::get('/articulos/eliminar/{articulos}', 'articulosController@destroy')->name('articulos.destroy')->middleware('auth');

Route::get('/proveedor','proveedorController@index')->name('proveedor.index')->middleware('auth');
Route::post('/proveedor', 'proveedorController@store')->name('proveedor.store')->middleware('auth');
Route::get('/proveedor/create', 'proveedorController@create')->name('proveedor.create')->middleware('auth');
Route::get('/proveedor/editar/{proveedor}', 'proveedorController@edit')->name('proveedor.edit')->middleware('auth');
Route::put('/proveedor/{proveedor}', 'proveedorController@update')->name('proveedor.update')->middleware('auth');
Route::get('/proveedor/eliminar/{proveedor}', 'proveedorController@destroy')->name('proveedor.destroy')->middleware('auth');

Route::get('/area','areaController@index')->name('area.index')->middleware('auth');
Route::post('/area', 'areaController@store')->name('area.store')->middleware('auth');
Route::get('/area/create', 'areaController@create')->name('area.create')->middleware('auth');
Route::get('/area/editar/{area}', 'areaController@edit')->name('area.edit')->middleware('auth');
Route::put('/area/{area}', 'areaController@update')->name('area.update')->middleware('auth');
Route::get('/area/eliminar/{area}', 'areaController@destroy')->name('area.destroy')->middleware('auth');

Route::get('/area','areaController@index')->name('area.index')->middleware('auth');
Route::post('/area', 'areaController@store')->name('area.store')->middleware('auth');
Route::get('/area/create', 'areaController@create')->name('area.create')->middleware('auth');
Route::get('/area/editar/{area}', 'areaController@edit')->name('area.edit')->middleware('auth');
Route::put('/area/{area}', 'areaController@update')->name('area.update')->middleware('auth');
Route::get('/area/eliminar/{area}', 'areaController@destroy')->name('area.destroy')->middleware('auth');

Route::get('/categoria','categoriaController@index')->name('categoria.index')->middleware('auth');
Route::post('/categoria', 'categoriaController@store')->name('categoria.store')->middleware('auth');
Route::get('/categoria/create', 'categoriaController@create')->name('categoria.create')->middleware('auth');
Route::get('/categoria/editar/{area}', 'categoriaController@edit')->name('categoria.edit')->middleware('auth');
Route::put('/categoria/{area}', 'categoriaController@update')->name('categoria.update')->middleware('auth');
Route::get('/categoria/eliminar/{area}', 'categoriaController@destroy')->name('categoria.destroy')->middleware('auth');

Route::get('/unidad','unidadController@index')->name('unidad.index')->middleware('auth');
Route::post('/unidad', 'unidadController@store')->name('unidad.store')->middleware('auth');
Route::get('/unidad/create', 'unidadController@create')->name('unidad.create')->middleware('auth');
Route::get('/unidad/editar/{area}', 'unidadController@edit')->name('unidad.edit')->middleware('auth');
Route::put('/unidad/{area}', 'unidadController@update')->name('unidad.update')->middleware('auth');
Route::get('/unidad/eliminar/{area}', 'unidadController@destroy')->name('unidad.destroy')->middleware('auth');

Route::get('/caja','cajaController@index')->name('caja.index')->middleware('auth');
Route::post('/caja', 'cajaController@store')->name('caja.store')->middleware('auth');
Route::get('/caja/create', 'cajaController@create')->name('caja.create')->middleware('auth');
Route::get('/caja/editar/{caja}', 'cajaController@edit')->name('caja.edit')->middleware('auth');
Route::put('/caja/{caja}', 'cajaController@update')->name('caja.update')->middleware('auth');
Route::get('/caja/eliminar/{caja}', 'cajaController@destroy')->name('caja.destroy')->middleware('auth');

Route::get('/entidad','entidadController@index')->name('entidad.index')->middleware('auth');
Route::post('/entidad', 'entidadController@store')->name('entidad.store')->middleware('auth');
Route::get('/entidad/create', 'entidadController@create')->name('entidad.create')->middleware('auth');
Route::get('/entidad/editar/{entidad}', 'entidadController@edit')->name('entidad.edit')->middleware('auth');
Route::put('/entidad/{entidad}', 'entidadController@update')->name('entidad.update')->middleware('auth');
Route::get('/entidad/eliminar/{entidad}', 'entidadController@destroy')->name('entidad.destroy')->middleware('auth');

Route::get('/iva','ivaController@index')->name('iva.index')->middleware('auth');
Route::post('/iva', 'ivaController@store')->name('iva.store')->middleware('auth');
Route::get('/iva/create', 'ivaController@create')->name('iva.create')->middleware('auth');
Route::get('/iva/editar/{iva}', 'ivaController@edit')->name('iva.edit')->middleware('auth');
Route::put('/iva/{iva}', 'ivaController@update')->name('iva.update')->middleware('auth');
Route::get('/iva/eliminar/{iva}', 'ivaController@destroy')->name('iva.destroy')->middleware('auth');

Route::get('/marca_tarjeta','marca_tarjetaController@index')->name('marca_tarjeta.index')->middleware('auth');
Route::post('/marca_tarjeta', 'marca_tarjetaController@store')->name('marca_tarjeta.store')->middleware('auth');
Route::get('/marca_tarjeta/create', 'marca_tarjetaController@create')->name('marca_tarjeta.create')->middleware('auth');
Route::get('/marca_tarjeta/editar/{iva}', 'marca_tarjetaController@edit')->name('marca_tarjeta.edit')->middleware('auth');
Route::put('/marca_tarjeta/{iva}', 'marca_tarjetaController@update')->name('marca_tarjeta.update')->middleware('auth');
Route::get('/marca_tarjeta/eliminar/{iva}', 'marca_tarjetaController@destroy')->name('marca_tarjeta.destroy')->middleware('auth');

Route::get('/procesadora','procesadoraController@index')->name('procesadora.index')->middleware('auth');
Route::post('/procesadora', 'procesadoraController@store')->name('procesadora.store')->middleware('auth');
Route::get('/procesadora/create', 'procesadoraController@create')->name('procesadora.create')->middleware('auth');
Route::get('/procesadora/editar/{iva}', 'procesadoraController@edit')->name('procesadora.edit')->middleware('auth');
Route::put('/procesadora/{iva}', 'procesadoraController@update')->name('procesadora.update')->middleware('auth');
Route::get('/procesadora/eliminar/{iva}', 'procesadoraController@destroy')->name('procesadora.destroy')->middleware('auth');

Route::get('/timbrado','timbradoController@index')->name('timbrado.index')->middleware('auth');
Route::post('/timbrado', 'timbradoController@store')->name('timbrado.store')->middleware('auth');
Route::get('/timbrado/create', 'timbradoController@create')->name('timbrado.create')->middleware('auth');
Route::get('/timbrado/editar/{timbrado}', 'timbradoController@edit')->name('timbrado.edit')->middleware('auth');
Route::put('/timbrado/{timbrado}', 'timbradoController@update')->name('timbrado.update')->middleware('auth');
Route::get('/timbrado/eliminar/{timbrado}', 'timbradoController@destroy')->name('timbrado.destroy')->middleware('auth');

Route::get('/tipo_tarjeta','tipo_tarjetaController@index')->name('tipo_tarjeta.index')->middleware('auth');
Route::post('/tipo_tarjeta', 'tipo_tarjetaController@store')->name('tipo_tarjeta.store')->middleware('auth');
Route::get('/tipo_tarjeta/create', 'tipo_tarjetaController@create')->name('tipo_tarjeta.create')->middleware('auth');
Route::get('/tipo_tarjeta/editar/{timbrado}', 'tipo_tarjetaController@edit')->name('tipo_tarjeta.edit')->middleware('auth');
Route::put('/tipo_tarjeta/{timbrado}', 'tipo_tarjetaController@update')->name('tipo_tarjeta.update')->middleware('auth');
Route::get('/tipo_tarjeta/eliminar/{timbrado}', 'tipo_tarjetaController@destroy')->name('tipo_tarjeta.destroy')->middleware('auth');

Route::get('/cama','camaController@index')->name('cama.index')->middleware('auth');
Route::post('/cama', 'camaController@store')->name('cama.store')->middleware('auth');
Route::get('/cama/create', 'camaController@create')->name('cama.create')->middleware('auth');
Route::get('/cama/editar/{timbrado}', 'camaController@edit')->name('cama.edit')->middleware('auth');
Route::put('/cama/{timbrado}', 'camaController@update')->name('cama.update')->middleware('auth');
Route::get('/cama/eliminar/{timbrado}', 'camaController@destroy')->name('cama.destroy')->middleware('auth');

Route::get('/caracteristicas','caracteristicasController@index')->name('caracteristicas.index')->middleware('auth');
Route::post('/caracteristicas', 'caracteristicasController@store')->name('caracteristicas.store')->middleware('auth');
Route::get('/caracteristicas/create', 'caracteristicasController@create')->name('caracteristicas.create')->middleware('auth');
Route::get('/caracteristicas/editar/{timbrado}', 'caracteristicasController@edit')->name('caracteristicas.edit')->middleware('auth');
Route::put('/caracteristicas/{timbrado}', 'caracteristicasController@update')->name('caracteristicas.update')->middleware('auth');
Route::get('/caracteristicas/eliminar/{timbrado}', 'caracteristicasController@destroy')->name('caracteristicas.destroy')->middleware('auth');

Route::get('/cargo','cargoController@index')->name('cargo.index')->middleware('auth');
Route::post('/cargo', 'cargoController@store')->name('cargo.store')->middleware('auth');
Route::get('/cargo/create', 'cargoController@create')->name('cargo.create')->middleware('auth');
Route::get('/cargo/editar/{timbrado}', 'cargoController@edit')->name('cargo.edit')->middleware('auth');
Route::put('/cargo/{timbrado}', 'cargoController@update')->name('cargo.update')->middleware('auth');
Route::get('/cargo/eliminar/{timbrado}', 'cargoController@destroy')->name('cargo.destroy')->middleware('auth');

Route::get('/empleado','empleadoController@index')->name('empleado.index')->middleware('auth');
Route::post('/empleado', 'empleadoController@store')->name('empleado.store')->middleware('auth');
Route::get('/empleado/create', 'empleadoController@create')->name('empleado.create')->middleware('auth');
Route::get('/empleado/editar/{timbrado}', 'empleadoController@edit')->name('empleado.edit')->middleware('auth');
Route::put('/empleado/{timbrado}', 'empleadoController@update')->name('empleado.update')->middleware('auth');
Route::get('/empleado/eliminar/{timbrado}', 'empleadoController@destroy')->name('empleado.destroy')->middleware('auth');

Route::get('/usuarios','usuariosController@index')->name('usuarios.index')->middleware('auth');
Route::post('/usuarios', 'usuariosController@store')->name('usuarios.store')->middleware('auth');
Route::get('/usuarios/create', 'usuariosController@create')->name('usuarios.create')->middleware('auth');
Route::get('/usuarios/editar/{timbrado}', 'usuariosController@edit')->name('usuarios.edit')->middleware('auth');
Route::put('/usuarios/{timbrado}', 'usuariosController@update')->name('usuarios.update')->middleware('auth');
Route::get('/usuarios/eliminar/{timbrado}', 'usuariosController@destroy')->name('usuarios.destroy')->middleware('auth');

Route::get('/ciudad','ciudadController@index')->name('ciudad.index')->middleware('auth');
Route::post('/ciudad', 'ciudadController@store')->name('ciudad.store')->middleware('auth');
Route::get('/ciudad/create', 'ciudadController@create')->name('ciudad.create')->middleware('auth');
Route::get('/ciudad/editar/{timbrado}', 'ciudadController@edit')->name('ciudad.edit')->middleware('auth');
Route::put('/ciudad/{timbrado}', 'ciudadController@update')->name('ciudad.update')->middleware('auth');
Route::get('/ciudad/eliminar/{timbrado}', 'ciudadController@destroy')->name('ciudad.destroy')->middleware('auth');

Route::get('/departamento','departamentoController@index')->name('departamento.index')->middleware('auth');
Route::post('/departamento', 'departamentoController@store')->name('departamento.store')->middleware('auth');
Route::get('/departamento/create', 'departamentoController@create')->name('departamento.create')->middleware('auth');
Route::get('/departamento/editar/{timbrado}', 'departamentoController@edit')->name('departamento.edit')->middleware('auth');
Route::put('/departamento/{timbrado}', 'departamentoController@update')->name('departamento.update')->middleware('auth');
Route::get('/departamento/eliminar/{timbrado}', 'departamentoController@destroy')->name('departamento.destroy')->middleware('auth');

Route::get('/habitacion_estado','habitacion_estadoController@index')->name('habitacion_estado.index')->middleware('auth');
Route::post('/habitacion_estado', 'habitacion_estadoController@store')->name('habitacion_estado.store')->middleware('auth');
Route::get('/habitacion_estado/create', 'habitacion_estadoController@create')->name('habitacion_estado.create')->middleware('auth');
Route::get('/habitacion_estado/editar/{timbrado}', 'habitacion_estadoController@edit')->name('habitacion_estado.edit')->middleware('auth');
Route::put('/habitacion_estado/{timbrado}', 'habitacion_estadoController@update')->name('habitacion_estado.update')->middleware('auth');
Route::get('/habitacion_estado/eliminar/{timbrado}', 'habitacion_estadoController@destroy')->name('habitacion_estado.destroy')->middleware('auth');

Route::get('/habitacion_nombres','habitacion_nombresController@index')->name('habitacion_nombres.index')->middleware('auth');
Route::post('/habitacion_nombres', 'habitacion_nombresController@store')->name('habitacion_nombres.store')->middleware('auth');
Route::get('/habitacion_nombres/create', 'habitacion_nombresController@create')->name('habitacion_nombres.create')->middleware('auth');
Route::get('/habitacion_nombres/editar/{timbrado}', 'habitacion_nombresController@edit')->name('habitacion_nombres.edit')->middleware('auth');
Route::put('/habitacion_nombres/{timbrado}', 'habitacion_nombresController@update')->name('habitacion_nombres.update')->middleware('auth');
Route::get('/habitacion_nombres/eliminar/{timbrado}', 'habitacion_nombresController@destroy')->name('habitacion_nombres.destroy')->middleware('auth');

Route::get('/operador_turistico','operador_turisticoController@index')->name('operador_turistico.index')->middleware('auth');
Route::post('/operador_turistico', 'operador_turisticoController@store')->name('operador_turistico.store')->middleware('auth');
Route::get('/operador_turistico/create', 'operador_turisticoController@create')->name('operador_turistico.create')->middleware('auth');
Route::get('/operador_turistico/editar/{timbrado}', 'operador_turisticoController@edit')->name('operador_turistico.edit')->middleware('auth');
Route::put('/operador_turistico/{timbrado}', 'operador_turisticoController@update')->name('operador_turistico.update')->middleware('auth');
Route::get('/operador_turistico/eliminar/{timbrado}', 'operador_turisticoController@destroy')->name('operador_turistico.destroy')->middleware('auth');

Route::get('/pais','paisController@index')->name('pais.index')->middleware('auth');
Route::post('/pais', 'paisController@store')->name('pais.store')->middleware('auth');
Route::get('/pais/create', 'paisController@create')->name('pais.create')->middleware('auth');
Route::get('/pais/editar/{timbrado}', 'paisController@edit')->name('pais.edit')->middleware('auth');
Route::put('/pais/{timbrado}', 'paisController@update')->name('pais.update')->middleware('auth');
Route::get('/pais/eliminar/{timbrado}', 'paisController@destroy')->name('pais.destroy')->middleware('auth');

Route::get('/spa_sauna','spa_saunaController@index')->name('spa_sauna.index')->middleware('auth');
Route::post('/spa_sauna', 'spa_saunaController@store')->name('spa_sauna.store')->middleware('auth');
Route::get('/spa_sauna/create', 'spa_saunaController@create')->name('spa_sauna.create')->middleware('auth');
Route::get('/spa_sauna/editar/{timbrado}', 'spa_saunaController@edit')->name('spa_sauna.edit')->middleware('auth');
Route::put('/spa_sauna/{timbrado}', 'spa_saunaController@update')->name('spa_sauna.update')->middleware('auth');
Route::get('/spa_sauna/eliminar/{timbrado}', 'spa_saunaController@destroy')->name('spa_sauna.destroy')->middleware('auth');

Route::get('/tarifas_nombres','tarifas_nombresController@index')->name('tarifas_nombres.index')->middleware('auth');
Route::post('/tarifas_nombres', 'tarifas_nombresController@store')->name('tarifas_nombres.store')->middleware('auth');
Route::get('/tarifas_nombres/create', 'tarifas_nombresController@create')->name('tarifas_nombres.create')->middleware('auth');
Route::get('/tarifas_nombres/editar/{timbrado}', 'tarifas_nombresController@edit')->name('tarifas_nombres.edit')->middleware('auth');
Route::put('/tarifas_nombres/{timbrado}', 'tarifas_nombresController@update')->name('tarifas_nombres.update')->middleware('auth');
Route::get('/tarifas_nombres/eliminar/{timbrado}', 'tarifas_nombresController@destroy')->name('tarifas_nombres.destroy')->middleware('auth');

Route::get('/temporadas','temporadasController@index')->name('temporadas.index')->middleware('auth');
Route::post('/temporadas', 'temporadasController@store')->name('temporadas.store')->middleware('auth');
Route::get('/temporadas/create', 'temporadasController@create')->name('temporadas.create')->middleware('auth');
Route::get('/temporadas/editar/{timbrado}', 'temporadasController@edit')->name('temporadas.edit')->middleware('auth');
Route::put('/temporadas/{timbrado}', 'temporadasController@update')->name('temporadas.update')->middleware('auth');
Route::get('/temporadas/eliminar/{timbrado}', 'temporadasController@destroy')->name('temporadas.destroy')->middleware('auth');

Route::get('/tipo_cliente','tipo_clienteController@index')->name('tipo_cliente.index')->middleware('auth');
Route::post('/tipo_cliente', 'tipo_clienteController@store')->name('tipo_cliente.store')->middleware('auth');
Route::get('/tipo_cliente/create', 'tipo_clienteController@create')->name('tipo_cliente.create')->middleware('auth');
Route::get('/tipo_cliente/editar/{timbrado}', 'tipo_clienteController@edit')->name('tipo_cliente.edit')->middleware('auth');
Route::put('/tipo_cliente/{timbrado}', 'tipo_clienteController@update')->name('tipo_cliente.update')->middleware('auth');
Route::get('/tipo_cliente/eliminar/{timbrado}', 'tipo_clienteController@destroy')->name('tipo_cliente.destroy')->middleware('auth');

Route::get('/tipo_documento','tipo_documentoController@index')->name('tipo_documento.index')->middleware('auth');
Route::post('/tipo_documento', 'tipo_documentoController@store')->name('tipo_documento.store')->middleware('auth');
Route::get('/tipo_documento/create', 'tipo_documentoController@create')->name('tipo_documento.create')->middleware('auth');
Route::get('/tipo_documento/editar/{timbrado}', 'tipo_documentoController@edit')->name('tipo_documento.edit')->middleware('auth');
Route::put('/tipo_documento/{timbrado}', 'tipo_documentoController@update')->name('tipo_documento.update')->middleware('auth');
Route::get('/tipo_documento/eliminar/{timbrado}', 'tipo_documentoController@destroy')->name('tipo_documento.destroy')->middleware('auth');


Route::get('/tipo_estadia','tipo_estadiaController@index')->name('tipo_estadia.index')->middleware('auth');
Route::post('/tipo_estadia', 'tipo_estadiaController@store')->name('tipo_estadia.store')->middleware('auth');
Route::get('/tipo_estadia/create', 'tipo_estadiaController@create')->name('tipo_estadia.create')->middleware('auth');
Route::get('/tipo_estadia/editar/{timbrado}', 'tipo_estadiaController@edit')->name('tipo_estadia.edit')->middleware('auth');
Route::put('/tipo_estadia/{timbrado}', 'tipo_estadiaController@update')->name('tipo_estadia.update')->middleware('auth');
Route::get('/tipo_estadia/eliminar/{timbrado}', 'tipo_estadiaController@destroy')->name('tipo_estadia.destroy')->middleware('auth');

Route::get('/tipos_habitacion','tipos_habitacionController@index')->name('tipos_habitacion.index')->middleware('auth');
Route::post('/tipos_habitacion', 'tipos_habitacionController@store')->name('tipos_habitacion.store')->middleware('auth');
Route::get('/tipos_habitacion/create', 'tipos_habitacionController@create')->name('tipos_habitacion.create')->middleware('auth');
Route::get('/tipos_habitacion/editar/{timbrado}', 'tipos_habitacionController@edit')->name('tipos_habitacion.edit')->middleware('auth');
Route::put('/tipos_habitacion/{timbrado}', 'tipos_habitacionController@update')->name('tipos_habitacion.update')->middleware('auth');
Route::get('/tipos_habitacion/eliminar/{timbrado}', 'tipos_habitacionController@destroy')->name('tipos_habitacion.destroy')->middleware('auth');

Route::get('/tipo_reserva','tipo_reservaController@index')->name('tipo_reserva.index')->middleware('auth');
Route::post('/tipo_reserva', 'tipo_reservaController@store')->name('tipo_reserva.store')->middleware('auth');
Route::get('/tipo_reserva/create', 'tipo_reservaController@create')->name('tipo_reserva.create')->middleware('auth');
Route::get('/tipo_reserva/editar/{timbrado}', 'tipo_reservaController@edit')->name('tipo_reserva.edit')->middleware('auth');
Route::put('/tipo_reserva/{timbrado}', 'tipo_reservaController@update')->name('tipo_reserva.update')->middleware('auth');
Route::get('/tipo_reserva/eliminar/{timbrado}', 'tipo_reservaController@destroy')->name('tipo_reserva.destroy')->middleware('auth');

Route::get('/turismo','turismoController@index')->name('turismo.index')->middleware('auth');
Route::post('/turismo', 'turismoController@store')->name('turismo.store')->middleware('auth');
Route::get('/turismo/create', 'turismoController@create')->name('turismo.create')->middleware('auth');
Route::get('/turismo/editar/{timbrado}', 'turismoController@edit')->name('turismo.edit')->middleware('auth');
Route::put('/turismo/{timbrado}', 'turismoController@update')->name('turismo.update')->middleware('auth');
Route::get('/turismo/eliminar/{timbrado}', 'turismoController@destroy')->name('turismo.destroy')->middleware('auth');

Route::get('/ubicaciones','ubicacionesController@index')->name('ubicaciones.index')->middleware('auth');
Route::post('/ubicaciones', 'ubicacionesController@store')->name('ubicaciones.store')->middleware('auth');
Route::get('/ubicaciones/create', 'ubicacionesController@create')->name('ubicaciones.create')->middleware('auth');
Route::get('/ubicaciones/editar/{timbrado}', 'ubicacionesController@edit')->name('ubicaciones.edit')->middleware('auth');
Route::put('/ubicaciones/{timbrado}', 'ubicacionesController@update')->name('ubicaciones.update')->middleware('auth');
Route::get('/ubicaciones/eliminar/{timbrado}', 'ubicacionesController@destroy')->name('ubicaciones.destroy')->middleware('auth');

Route::get('/productos','productosController@index')->name('productos.index')->middleware('auth');
Route::post('/productos', 'productosController@store')->name('productos.store')->middleware('auth');
Route::get('/productos/create', 'productosController@create')->name('productos.create')->middleware('auth');
Route::get('/productos/editar/{timbrado}', 'productosController@edit')->name('productos.edit')->middleware('auth');
Route::put('/productos/{timbrado}', 'productosController@update')->name('productos.update')->middleware('auth');
Route::get('/productos/eliminar/{timbrado}', 'productosController@destroy')->name('productos.destroy')->middleware('auth');

Route::get('/sucursal','sucursalController@index')->name('sucursal.index')->middleware('auth');
Route::post('/sucursal', 'sucursalController@store')->name('sucursal.store')->middleware('auth');
Route::get('/sucursal/create', 'sucursalController@create')->name('sucursal.create')->middleware('auth');
Route::get('/sucursal/eliminar/{timbrado}', 'sucursalController@destroy')->name('sucursal.destroy')->middleware('auth');

Route::get('/clientes','clientesController@index')->name('clientes.index')->middleware('auth');
Route::post('/clientes', 'clientesController@store')->name('clientes.store')->middleware('auth');
Route::get('/clientes/create', 'clientesController@create')->name('clientes.create')->middleware('auth');
Route::get('/clientes/editar/{timbrado}', 'clientesController@edit')->name('clientes.edit')->middleware('auth');
Route::put('/clientes/{timbrado}', 'clientesController@update')->name('clientes.update')->middleware('auth');
Route::get('/clientes/eliminar/{timbrado}', 'clientesController@destroy')->name('clientes.destroy')->middleware('auth');

Route::get('/user_numero','user_numeroController@index')->name('user_numero.index')->middleware('auth');
Route::post('/user_numero', 'user_numeroController@store')->name('user_numero.store')->middleware('auth');
Route::get('/user_numero/create', 'user_numeroController@create')->name('user_numero.create')->middleware('auth');
Route::get('/user_numero/eliminar/{timbrado}', 'user_numeroController@destroy')->name('user_numero.destroy')->middleware('auth');

//Buscadores
Route::get('/searcher/clientes', 'searcherController@clientes')->name('searcher.clientes')->middleware('auth');
Route::get('/searcher/reservas', 'searcherController@reservas')->name('searcher.reservas')->middleware('auth');
Route::get('/searcher/estadias', 'searcherController@estadias')->name('searcher.estadias')->middleware('auth');
Route::get('/searcher/tarifas', 'searcherController@tarifas')->name('searcher.tarifas')->middleware('auth');
Route::get('/searcher/personas', 'searcherController@personas')->name('searcher.personas')->middleware('auth');
Route::get('/searcher/cuentas_a_cobrar', 'searcherController@cuentas_a_cobrar')->name('searcher.cuentas_a_cobrar')->middleware('auth');
Route::get('/searcher/apertura_cierre', 'searcherController@apertura_cierre')->name('searcher.apertura_cierre')->middleware('auth');
Route::get('/searcher/factura', 'searcherController@factura')->name('searcher.factura')->middleware('auth');
Route::get('/searcher/factura_compra', 'searcherController@factura_compra')->name('searcher.factura_compra')->middleware('auth');
Route::get('/searcher/articulo', 'searcherController@articulo')->name('searcher.articulo')->middleware('auth');
Route::get('/searcher/proveedor', 'searcherController@proveedor')->name('searcher.proveedor')->middleware('auth');
Route::get('/searcher/requisicion', 'searcherController@requisicion')->name('searcher.requisicion')->middleware('auth');
Route::get('/searcher/presupuesto', 'searcherController@presupuesto')->name('searcher.presupuesto')->middleware('auth');
Route::get('/searcher/orden', 'searcherController@orden')->name('searcher.orden')->middleware('auth');
Route::get('/searcher/huesped', 'searcherController@huesped')->name('searcher.huesped')->middleware('auth');
Route::get('/searcher/huesped/{id}/{id1}', 'searcherController@huesped1')->name('searcher.huesped1')->middleware('auth');
Route::get('/searcher/spa', 'searcherController@spa')->name('searcher.spa')->middleware('auth');
Route::get('/searcher/lavanderia', 'searcherController@lavanderia')->name('searcher.lavanderia')->middleware('auth');
Route::get('/searcher/habitacion', 'searcherController@habitacion')->name('searcher.habitacion')->middleware('auth');
Route::get('/searcher/habitacion/{id}', 'searcherController@habitacion1')->name('searcher.habitacion1')->middleware('auth');
Route::get('/searcher/producto', 'searcherController@producto')->name('searcher.producto')->middleware('auth');
Route::get('/searcher/turismo', 'searcherController@turismo')->name('searcher.turismo')->middleware('auth');
Route::get('/searcher/cama', 'searcherController@cama')->name('searcher.cama')->middleware('auth');
Route::get('/searcher/empleado', 'searcherController@empleado')->name('searcher.empleado')->middleware('auth');
Route::get('/searcher/departamento', 'searcherController@departamento')->name('searcher.departamento')->middleware('auth');
Route::get('/searcher/pais', 'searcherController@pais')->name('searcher.pais')->middleware('auth');
Route::get('/searcher/timbrado', 'searcherController@timbrado')->name('searcher.timbrado')->middleware('auth');
Route::get('/searcher/user', 'searcherController@user')->name('searcher.user')->middleware('auth');
Route::get('/searcher/factura_numero', 'searcherController@factura_numero')->name('searcher.factura_numero')->middleware('auth');
Route::get('/searcher/sucursal', 'searcherController@sucursal')->name('searcher.sucursal')->middleware('auth');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
