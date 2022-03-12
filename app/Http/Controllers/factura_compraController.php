<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class factura_compraController extends Controller
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
        //estado i=inactivo a=activo
        $factura_compra = DB::table('factura_compra')
            ->select(
                'factura_compra.*',
                'pr.ruc',
                'pr.nombre'
            )
            ->where('factura_compra.estado', '=', 'A')
            ->leftJoin('orden_de_compra as od', 'factura_compra.orden_de_compra_numero', '=', 'od.numero')
            ->leftJoin('proveedor as pr', 'od.proveedor_ruc', '=', 'pr.ruc')
            ->get();

        //  dd($nota_credito);

        return view('factura_compra.index', ['factura_compras' => $factura_compra]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $iva = DB::table('iva')->get();

        return view('factura_compra.create', [
            'ivas' => $iva,
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
        // dump(request()->all());
        //c=cobrado a=anulado
        try {
            $id_fac = DB::table('factura_compra')->insertGetId(
                [
                    'numero' => request()->codigo, 'fecha' => request()->fecha,
                    'estado' => "A",
                    'orden_de_compra_numero' => request()->orden_de_compra, 'condicion' => request()->condicion,
                    'can_cuo' => request()->cancuo, 'plazo' => request()->plazo
                ]
            );

            $input = $request->only([
                'articulo_detalle', 'precio_detalle',
                'cantidad_detalle', 'iva_detalle', 'iva_descri'
            ]);
            //dump($input);
            //dump($input["habitacion"][1]);

            $iva5 = 0;
            $iva10 = 0;

            if (isset($input["articulo_detalle"])) {
                foreach ($input["articulo_detalle"] as $key => $value) {

                    $iva = DB::table('iva')
                        ->select('iva.id')
                        ->where('porcentaje', '=', $input["iva_detalle"][$key])
                        ->get();

                    $data1[] = [
                        'factura_compra_id' => $id_fac,
                        'articulo_codigo' => $input["articulo_detalle"][$key],
                        'precio' => $input["precio_detalle"][$key],
                        'cantidad' => $input["cantidad_detalle"][$key],
                        'iva_id' => $iva[0]->id
                    ];

                    if($input['iva_descri'][$key]=="5%"){
                        $iva5+= ($input["precio_detalle"][$key] * $input["cantidad_detalle"][$key]) + ((($input["precio_detalle"][$key] * $input["cantidad_detalle"][$key]) )/$input["iva_detalle"][$key]);
                    }else if($input['iva_descri'][$key]=="10%"){
                        $iva10+= ($input["precio_detalle"][$key] * $input["cantidad_detalle"][$key]) + (($input["precio_detalle"][$key] * $input["cantidad_detalle"][$key]) /$input["iva_detalle"][$key]);
                    }
                }


                DB::table('factura_c_detalle')->insert($data1);
            }

            $id = DB::table('libro_compras')->insert(
                [
                    'factura_compra_id' => $id_fac,
                    'fecha' => request()->fecha,
                    'numero_factura' => request()->codigo, 'iva_5' => $iva5,
                    'iva_10' => $iva10
                ]
            );

            $cancuo = (int) $request->cancuo;
            if ($cancuo == 0) $cancuo = 1;
            $fecha = $request->fecha;
            $monto = (int) (((int) str_replace('.', '', request()->monto)) / $cancuo);
            for ($i = 0; $i < $cancuo; $i++) {

                $fecha = date('Y-m-d', strtotime($fecha . ' + ' . $request->plazo . ' days'));

                DB::table('cuentas_a_pagar')
                    ->insert([
                        'numero_cuotas' => $i + 1,
                        'estado' => 'A',
                        'fecha_a_pagar' => $fecha,
                        'monto' => $monto,
                        'factura_compra_id' => $id_fac,
                    ]);
            }

            DB::table('orden_de_compra')
                ->where('numero', $request->orden_de_compra)
                ->update(
                    [
                        'estado' => "I"
                    ]
                );
        } catch (\Exception $e) {
            //request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            //return redirect()->route('personas.index');
        }

        return redirect()->route('factura_compra.index');
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
            //i=inactivo a=activo 
            DB::table('factura_compra')
                ->where('id', $id)
                ->update(
                    [
                        'estado' => "I"
                    ]
                );
            DB::table('cuentas_a_pagar')
                ->where('factura_compra_id', $id)
                ->update(
                    [
                        'estado' => "I"
                    ]
                );

            $orden = DB::table('factura_compra')
                ->select('orden_de_compra_numero')
                ->where('id', '=', $id)
                ->get();

            DB::table('orden_de_compra')
                ->where([
                    ['numero', '=', $orden[0]->orden_de_compra_numero]
                ])
                ->update(
                    [
                        'estado' => "A"
                    ]
                );
        } catch (\Exception $e) {
            //request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            //return redirect()->route('personas.index');
        }
        return redirect()->route('factura_compra.index');
    }

    public function tarifa(Request $request)
    {

        $tarifa = DB::table('tarifas')
            ->select('tn.descripcion', 'tarifas.id', 'tarifas.habitacion_id')
            ->where('tarifas.id', '=', $request->id)
            ->leftJoin('tarifas_nombres as tn', 'tarifas.tarifas_nombres_id', '=', 'tn.id')
            ->get();
        //dd($tarifa);

        //return view('estadia.create', ['tarifas' => $tarifa]);

        return ['tarifas' => $tarifa];
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
    public function orden(Request $request)
    {
        $orden = DB::table('orden_de_compra')
            ->select(
                'condicion'
            )
            ->where('numero', '=', $request->id)
            ->get();

        $orden_detalle = DB::table('orden_detalle')
            ->select(
                'ar.codigo',
                'ar.nombre',
                'orden_detalle.cantidad',
                'orden_detalle.precio',
                'iv.descripcion',
                'iv.porcentaje'
            )
            ->where('orden_de_compra_numero', '=', $request->id)
            ->leftJoin('articulo as ar', 'orden_detalle.articulo_codigo', '=', 'ar.codigo')
            ->leftJoin('iva as iv', 'orden_detalle.iva_id', '=', 'iv.id')
            ->get();

        return [
            'orden' => $orden,
            'articulos' => $orden_detalle,
        ];
        //return $request;
    }
}
