<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class facturaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$estadia = estadia::all();
        //return view('estadia.index', ['estadias' => $estadia]);
        $factura = DB::table('factura')
            ->select(
                'factura.*',
                'pe.nombre',
                'pe.apellido',
                'cl.ruc'
            )
            ->where('estado', '=', 'A')
            ->leftJoin('clientes as cl', 'factura.clientes_id', '=', 'cl.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('cl.persona_pais', '=', 'pe.pais_id');
                $join->on('cl.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        // dd($factura);

        return view('factura.index', ['facturas' => $factura]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*$operador = DB::table('operador_turistico')->get();
        $tipo_cliente = DB::table('tipo_cliente')->get();
        $tipo_estadia = DB::table('tipo_estadia')->get();
        $habitacion = DB::table('habitaciones')
            ->select('id', 'descripcion')->get();
        //dd($operador, $tipo_cliente, $tipo_estadia);

        return view('estadia.create', [
            'operadores' => $operador,
            'tipo_clientes' => $tipo_cliente,
            'tipo_estadias' => $tipo_estadia,
            'habitaciones' => $habitacion
        ]);*/
        try {
            $id = Auth::id();
            $user_numero = DB::table('user_numero')
                ->select(
                    'ca.numero',
                    'user_numero.factura_numero_id',
                    'fn.timbrado'
                )
                ->where('user_id', '=', $id)
                ->where([
                    ['user_id', '=', $id],
                    ['tipo', '=', 'Factura'],
                ])
                ->leftJoin('caja as ca', 'user_numero.caja_id', '=', 'ca.id')
                ->leftJoin('factura_numero as fn', 'user_numero.factura_numero_id', '=', 'fn.id')
                ->get();
            $numero = DB::table('factura_numero')
                ->where('id', '=', $user_numero[0]->factura_numero_id)
                ->get();
            $timbrado = DB::table('timbrado')
                ->select(
                    'timbrado.nro',
                    'timbrado.sucursal_id'
                )
                ->where('id', '=', $numero[0]->timbrado)
                ->get();
            $sucursal = DB::table('sucursal')
                ->where('id', '=', $timbrado[0]->sucursal_id)
                ->get();
            $nro3 = ((int) $numero[0]->nro_actual + 1);
            $nro3 = str_pad($nro3, 7, "0", STR_PAD_LEFT);
            $nro = $sucursal[0]->numero . "-" . $user_numero[0]->numero . "-" . $nro3;
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
             return redirect()->route('factura.index');
        }
        return view('factura.create', [
            'numero' => $nro,
            'timbrado' => $timbrado
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
        //dump(request()->all());dd("asdf");
        //dump(str_replace('.', '', request()->total));
        try {
            $id = Auth::id();
            $user_numero = DB::table('user_numero')
                ->select(
                    'ca.numero',
                    'user_numero.factura_numero_id',
                    'fn.timbrado'
                )
                ->where('user_id', '=', $id)
                ->leftJoin('caja as ca', 'user_numero.caja_id', '=', 'ca.id')
                ->leftJoin('factura_numero as fn', 'user_numero.factura_numero_id', '=', 'fn.id')
                ->get();
            $numero = DB::table('factura_numero')
                ->where('id', '=', $user_numero[0]->factura_numero_id)
                ->get();
            $timbrado = DB::table('timbrado')
                ->select(
                    'timbrado.nro',
                    'timbrado.sucursal_id'
                )
                ->where('id', '=', $numero[0]->timbrado)
                ->get();
            $sucursal = DB::table('sucursal')
                ->where('id', '=', $timbrado[0]->sucursal_id)
                ->get();
            $nro3 = ((int) $numero[0]->nro_actual + 1);
            $nro3 = str_pad($nro3, 7, "0", STR_PAD_LEFT);
            $nro = $sucursal[0]->numero . "-" . $user_numero[0]->numero . "-" . $nro3;

            DB::table('factura_numero')->where('id', $numero[0]->id)
                ->update(['nro_actual' => $nro3]);

            //dump($numero[0]);
            //dump($numero[0]->nro1."-".$numero[0]->nro2);
            $id = DB::table('factura')->insert(
                [
                    'numero' => $nro,
                    'fecha' => request()->fecha, 'estado' => "A", 'total' => str_replace('.', '', request()->total),
                    'condicion' => request()->condicion, 'timbrado' => $timbrado[0]->nro,
                    'clientes_id' => request()->cliente, 'estadia_id' => request()->estadia
                ]
            );

            $input = $request->only([
                'descripcion_detalle', 'precio_detalle', 'cantidad_detalle',
                'iva_detalle', 'tarifa_detalle','iva_descri','iva_porcentaje'
            ]);
            
            $iva5 = 0;
            $iva10 = 0;

            foreach ($input["descripcion_detalle"] as $key => $value) {
                $data2[] = [
                    'factura_numero' => $nro,
                    'factura_timbrado' => $timbrado[0]->nro,
                    'descripcion' => str_replace('_', ' ', $input["descripcion_detalle"][$key]),
                    'precio' => $input["precio_detalle"][$key],
                    'cantidad' => $input["cantidad_detalle"][$key],
                    'iva_id' => $input["iva_detalle"][$key],
                    'tarifa_id' => $input["tarifa_detalle"][$key]
                ];
                if($input['iva_descri'][$key]=="5%"){
                    $iva5+= ($input["precio_detalle"][$key] * $input["cantidad_detalle"][$key]) + ((($input["precio_detalle"][$key] * $input["cantidad_detalle"][$key]) )/$input["iva_porcentaje"][$key]);
                }else if($input['iva_descri'][$key]=="10%"){
                    $iva10+= ($input["precio_detalle"][$key] * $input["cantidad_detalle"][$key]) + (($input["precio_detalle"][$key] * $input["cantidad_detalle"][$key]) /$input["iva_porcentaje"][$key]);
                }
            } 

            $id = DB::table('libro_ventas')->insert(
                [
                    'factura_numero' => $nro,
                    'fecha' => request()->fecha,
                    'factura_timbrado' => $timbrado[0]->nro, 'iva_5' => $iva5,
                    'iva_10' => $iva10
                ]
            );

            DB::table('factura_detalle')->insert($data2);

            $condi_id = DB::table('condicion')
                ->insertGetId([
                    'cantidad_cuotas' => $request->cancuo,
                    'factura_numero' => $nro,
                    'factura_timbrado' => $timbrado[0]->nro,
                    'plazo' => $request->plazo
                ]);
            //a= activo p=pagado
            $cancuo = (int) $request->cancuo;
            if ($cancuo == 0) $cancuo = 1;
            $fecha = $request->fecha;
            $monto = (int) (((int) str_replace('.', '', request()->total)) / $cancuo);
            for ($i = 0; $i < $cancuo; $i++) {

                $fecha = date('Y-m-d', strtotime($fecha . ' + ' . $request->plazo . ' days'));

                DB::table('cuentas_a_cobrar')
                    ->insert([
                        'nro_cuota' => $i + 1,
                        'estado' => 'A',
                        'fecha_a_pagar' => $fecha,
                        'monto' => $monto,
                        'condicion_id' => $condi_id
                    ]);
            }

            DB::table('estadia')->where('id', $request->estadia)
                ->update(['estado' => 'P']);

            return redirect()->intended('http://localhost:8080/reportes/Imprimir_fac.jsp?desde='.$nro.'&hasta='.$timbrado[0]->nro.'&nombre_reporte=factura_1');

        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }

       // return redirect()->route('factura.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function show(estadia $estadia)
    {
        dd($estadia);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dump($id);
        $operador = DB::table('operador_turistico')->get();
        $tipo_cliente = DB::table('tipo_cliente')->get();
        $tipo_estadia = DB::table('tipo_estadia')->get();
        $habitacion = DB::table('habitaciones')
            ->select('id', 'descripcion')->get();

        $habitaciones = DB::table('estadia_habitaciones')
            ->select('estadia_habitaciones.*', 'ha.descripcion')
            ->leftJoin('habitaciones as ha', 'estadia_habitaciones.id_habitacion', '=', 'ha.id')
            ->where('id_estadia', '=', $id)
            ->get();

        $tarifas = DB::table('estadia_tarifas')
            ->select('estadia_tarifas.tarifa_id', 'tn.descripcion', 'ta.habitacion_id')
            ->leftJoin('tarifas as ta', 'estadia_tarifas.tarifa_id', '=', 'ta.id')
            ->leftJoin('tarifas_nombres as tn', 'ta.tarifas_nombres_id', '=', 'tn.id')
            ->where('estadia_id', '=', $id)
            ->get();
        //}}dd($habitaciones);

        $huespedes = DB::table('estadia_huespedes')
            ->select(
                'estadia_huespedes.habitacion_id',
                'ha.descripcion',
                'pe.ciudad_id',
                'pe.nro_documento',
                'pe.nombre',
                'pe.apellido'
            )
            ->where('estadia_id', '=', $id)
            ->leftJoin('habitaciones as ha', 'estadia_huespedes.habitacion_id', '=', 'ha.id')
            ->leftJoin('huespedes as hu', 'estadia_huespedes.huespedes_id', '=', 'hu.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('hu.persona_ciudad_id', '=', 'pe.ciudad_id');
                $join->on('hu.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        $estadias = DB::table('estadia')
            ->where('id', '=', $id)
            ->get();

        $reservas = DB::table('estadia_reserva')
            ->where('estadia_id', '=', $id)
            ->get();

        //dd($reservas);
        return view('estadia.update', [
            'operadores' => $operador,
            'tipo_clientes' => $tipo_cliente,
            'tipo_estadias' => $tipo_estadia,
            'habitaciones' => $habitacion,
            'estadia_habitaciones' => $habitaciones,
            'tarifas' => $tarifas,
            'huespedes' => $huespedes,
            'estad' => $estadias,
            'reserv' => $reservas
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //dump($request->all());
        DB::table('estadia')
            ->where('id', request()->codigo)
            ->update(
                [
                    'id_operador' => request()->operador, 'comentarios' => request()->comentarios,
                    'tipo_cliente_id' => request()->tipo_cliente, 'usuario_id' => 1,
                    'Tipo_estadia_id' => request()->tipo_estadia, 'fecha' => request()->fecha,
                    'clientes_id' => request()->cliente
                ]
            );
        //dump($request->all());

        if (request()->reserva !== null) {

            $reservas = DB::table('estadia_reserva')
                ->where('estadia_id', '=', request()->codigo)
                ->get();
            // dump($reservas);
            if (isset($reservas[0])) {
                DB::table('estadia_reserva')->where('estadia_id', request()->codigo)
                    ->update(
                        ['reservas_id' => request()->reserva]
                    );
            } else {
                DB::table('estadia_reserva')
                    ->insert(
                        [
                            'reservas_id' => request()->reserva, 'estadia_id' => request()->codigo
                        ]
                    );
            }
        } else {
            DB::table('estadia_reserva')->where('estadia_id', request()->codigo)->delete();
        }

        DB::table('estadia_tarifas')->where('estadia_id', request()->codigo)->delete();
        DB::table('estadia_habitaciones')->where('id_estadia', request()->codigo)->delete();
        DB::table('estadia_huespedes')->where('id_estadia', request()->codigo)->delete();
        DB::table('huespedes')->where('estadia_id', request()->codigo)->delete();

        foreach ($request->tarifa as $key => $value) {
            $data[] = [
                'estadia_id' => request()->codigo,
                'tarifa_id' => $value[0],
            ];
        }
        //dump($data);
        DB::table('estadia_tarifas')->insert($data);
        // dump($request->all()["f_entrada"]);
        $input = $request->only(['habitacion', 'f_entrada', 'f_salida', 'h_entrada', 'h_salida']);
        //dump($input);
        //dump($input["habitacion"][1]);
        foreach ($input["habitacion"] as $key => $value) {
            $data1[] = [
                'id_estadia' => request()->codigo,
                'id_habitacion' => $value,
                'entrada' => $input["f_entrada"][$key],
                'salida' => $input["f_salida"][$key],
                'hora_entrada' => $input["h_entrada"][$key],
                'hora_salida' => $input["h_salida"][$key]
            ];
            //dump($key);
            //dump($value);
            //dump($input["f_entrada"][$key]);
        }

        /* $data = array(
           array('id_estadia'=> [1,1], 'id_habitacion'=>  $input["habitacion"], 'entrada'=> $input["f_entrada"]),
           // array('id_estadia'=>["1","1"], 'id_habitacion'=> ["1","1"]),
        );*/

        //dump($data);

        DB::table('estadia_habitaciones')->insert($data1);

        $input = $request->only(['persona_ciudad', 'persona_documento', 'habitacion_huesped']);

        foreach ($input["persona_ciudad"] as $key => $value) {
            //$data2[] = [
            // 'persona_ciudad_id' => $input["persona_ciudad"][$key],
            // 'persona_nro_documento' =>$input["persona_documento"][$key],
            //];
            $id_huesped = DB::table('huespedes')->insertGetId(
                [
                    'persona_ciudad_id' => $input["persona_ciudad"][$key],
                    'persona_nro_documento' => $input["persona_documento"][$key],
                    'estadia_id' =>  request()->codigo
                ]
            );

            DB::table('estadia_huespedes')->insert(
                [
                    'id_estadia' =>  request()->codigo,
                    'huespedes_id' => $id_huesped,
                    'habitacion_id' => $input["habitacion_huesped"][$key]
                ]
            );
        }

        return redirect()->route('estadia.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            //dump($id);
            DB::table('factura')
                ->where('numero', $id)
                ->update(
                    [
                        'estado' => "I"
                    ]
                );
            $condicion = DB::table('condicion')
                ->select(
                    'condicion.id'
                )
                ->where('factura_numero', '=', $id)
                ->get();
            DB::table('cuentas_a_cobrar')
                ->where('condicion_id', $condicion[0]->id)
                ->update(
                    [
                        'estado' => "I"
                    ]
                );
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }
        return redirect()->route('factura.index');
    }

    public function estadia(Request $request)
    {
        try {
            $estadia = DB::table('estadia')
                ->select('estadia.id', 'es.entrada', 'es.salida', 'ha.descripcion', 'estadia.clientes_id')
                ->where('estadia.id', '=', $request->id)
                ->leftJoin('estadia_habitaciones as es', 'estadia.id', '=', 'es.id_estadia')
                ->leftJoin('habitaciones as ha', 'es.id_habitacion', '=', 'ha.id')
                ->get();

            $estadia1 = DB::table('estadia_tarifas')
                ->select(
                    DB::raw('DATEDIFF(eh.salida, eh.entrada) as dias'),
                    DB::raw('COUNT(ehs.huespedes_id) as huespedes_can'),
                    'estadia_tarifas.estadia_id as id_estadia',
                    'eh.id_habitacion as id_habitacion',
                    'ha.descripcion as habitacion',
                    'eh.entrada',
                    'eh.salida',
                    'ta.id as tarifa id',
                    'tn.descripcion as tarifa',
                    'ta.precio',
                    'ta.id as id_tarifa'
                )
                ->where([
                    ['estadia_tarifas.estadia_id', '=', $request->id],
                    ['eh.id_estadia', '=', $request->id],
                    ['ehs.id_estadia', '=', $request->id]
                ])
                ->leftJoin('tarifas as ta', 'estadia_tarifas.tarifa_id', '=', 'ta.id')
                ->leftJoin('tarifas_nombres as tn', 'tn.id', '=', 'ta.tarifas_nombres_id')
                ->leftJoin('estadia_habitaciones as eh', 'ta.habitacion_id', '=', 'eh.id_habitacion')
                ->leftJoin('habitaciones as ha', 'eh.id_habitacion', '=', 'ha.id')
                ->leftJoin('estadia_huespedes as ehs', 'eh.id_habitacion', '=', 'ehs.habitacion_id')
                ->groupBy('estadia_tarifas.tarifa_id')
                ->get();

            $iva = DB::table('iva')
                ->select('iva.id', 'iva.porcentaje', 'iva.descripcion')
                ->where('descripcion', '=', '10%')
                ->get();

            $tarifa = DB::table('estadia')
                ->select('estadia.id as estadia_id', 'ta.id', 'tn.descripcion', 'ta.precio')
                ->where('estadia.id', '=', $request->id)
                ->leftJoin('estadia_tarifas as et', 'estadia.id', '=', 'et.estadia_id')
                ->leftJoin('tarifas as ta', 'et.tarifa_id', '=', 'ta.id')
                ->leftJoin('tarifas_nombres as tn', 'tn.id', '=', 'ta.tarifas_nombres_id')
                ->get();

            $tarifa1 = DB::table('estadia_tarifas')
                ->select(
                    DB::raw('DATEDIFF(eh.salida, eh.entrada) as dias'),
                    DB::raw('COUNT(ehs.huespedes_id) as huespedes_can'),
                    'eh.id_habitacion',
                    'ta.id as id_tarifa',
                    'tn.descripcion',
                    'ta.precio',
                    'ta.descuento_personas',
                    'ta.cantidad_personas'
                )
                ->where([
                    ['estadia_tarifas.estadia_id', '=', $request->id],
                    ['eh.id_estadia', '=', $request->id],
                    ['ehs.id_estadia', '=', $request->id]
                ])
                ->leftJoin('tarifas as ta', 'estadia_tarifas.tarifa_id', '=', 'ta.id')
                ->leftJoin('tarifas_nombres as tn', 'tn.id', '=', 'ta.tarifas_nombres_id')
                ->leftJoin('estadia_habitaciones as eh', 'ta.habitacion_id', '=', 'eh.id_habitacion')
                ->leftJoin('estadia_huespedes as ehs', 'eh.id_habitacion', '=', 'ehs.habitacion_id')
                ->groupBy('estadia_tarifas.tarifa_id')
                ->get();

            $consumicion = DB::table('s_consumicion')
                ->select(
                    'cd.cantidad',
                    'cd.promocion',
                    'pr.producto',
                    'pr.precio',
                    'ha.descripcion as habitacion',
                    'iv.descripcion as descripcion_iva',
                    'iv.porcentaje as porcentaje_iva',
                    'iv.id as id_iva',
                    'iv.descripcion as descri_iva',
                    'ta.id as tarifa_id'
                )
                ->where([
                    ['s_consumicion.estadia_id', '=', $request->id],
                    ['ta.habitacion_id', '=', DB::raw('cd.habitacion_id')]
                ])
                ->leftJoin('consumicion_detalle as cd', 's_consumicion.id', '=', 'cd.s_consumicion_id')
                ->leftJoin('productos as pr', 'cd.producto_id', '=', 'pr.id')
                ->leftJoin('habitaciones as ha', 'cd.habitacion_id', '=', 'ha.id')
                ->leftJoin('iva as iv', 'pr.id_iva', '=', 'iv.id')
                ->leftJoin('estadia_tarifas as et', 's_consumicion.estadia_id', '=', 'et.estadia_id')
                ->leftJoin('tarifas as ta', 'et.tarifa_id', '=', 'ta.id')
                ->get();

            $traslado = DB::table('s_traslado')
                ->select(
                    'td.promocion',
                    'tr.descripcion',
                    'tr.precio',
                    'ha.descripcion as habitacion',
                    'iv.descripcion as descripcion_iva',
                    'iv.porcentaje as porcentaje_iva',
                    'iv.id as id_iva',
                    'iv.descripcion as descri_iva',
                    'ta.id as tarifa_id'
                )
                ->where([
                    ['s_traslado.estadia_id', '=', $request->id],
                    ['ta.habitacion_id', '=', DB::raw('td.habitacion_id')]
                ])
                ->leftJoin('traslado_detalle as td', 's_traslado.id', '=', 'td.s_traslado_id')
                ->leftJoin('traslado as tr', 'td.traslado_id', '=', 'tr.id')
                ->leftJoin('habitaciones as ha', 'td.habitacion_id', '=', 'ha.id')
                ->leftJoin('iva as iv', 'tr.id_iva', '=', 'iv.id')
                ->leftJoin('estadia_tarifas as et', 's_traslado.estadia_id', '=', 'et.estadia_id')
                ->leftJoin('tarifas as ta', 'et.tarifa_id', '=', 'ta.id')
                ->get();
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            // request()->session()->flash('error_', 'Error en base de datos');
            return ['error' => $e->getMessage()];
        }
        /*  $servicios = DB::table('estadia')
            ->select(
                'estadia.id as estadia_id',
                'es.id as esta_servi_id',
                'sn.nombre as servicio',
                'se.total as precio_servi'
            )
            ->where('estadia.id', '=', $request->id)
            ->leftJoin('estadia_servicios as es', 'estadia.id', '=', 'es.estadia_id')
            ->leftJoin('servicios as se', 'es.servicios_id', '=', 'se.id')
            ->leftJoin('servicios_nombres as sn', 'se.nombre_id', '=', 'sn.id')
            ->get();*/
        //dd($tarifa);

        //return view('estadia.create', ['tarifas' => $tarifa]);

        return [
            'estadias' => $estadia,
            'estadia1' => $estadia1,
            'tarifa' => $tarifa,
            'tarifa1' => $tarifa1,
            'iva_tarifa' => $iva,
            'consumicion' => $consumicion,
            'traslado' => $traslado
            //'servicios' => $servicios
        ];
        //return $request;
    }

    public function persona(Request $request)
    {

        $persona = DB::table('persona')
            ->select('nombre', 'apellido')
            ->where([
                ['ciudad_id', '=', $request->id[0]],
                ['nro_documento', '=', $request->id[1]],
            ])->get();
        //dd($tarifa);

        //return view('estadia.create', ['tarifas' => $tarifa]);

        return ['personas' => $persona];
        //return $request;
    }
}
