<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class nota_de_creditoController extends Controller
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
        $nota_credito = DB::table('nota_credito')
            ->select(
                'nota_credito.*',
                'pe.nombre',
                'pe.apellido',
                'cl.ruc'
            )
            ->where('nota_credito.estado', '=', 'A')
            ->leftJoin('factura as fa', 'nota_credito.factura_numero', '=', 'fa.numero')
            ->leftJoin('clientes as cl', 'fa.clientes_id', '=', 'cl.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('cl.persona_pais', '=', 'pe.pais_id');
                $join->on('cl.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        //  dd($nota_credito);

        return view('nota_de_credito.index', ['notas_creditos' => $nota_credito]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        try {
            $id = Auth::id();
            $user_numero = DB::table('user_numero')
                ->select(
                    'ca.numero',
                    'user_numero.factura_numero_id',
                    'fn.timbrado'
                )
                ->where([
                    ['user_id', '=', $id],
                    ['tipo', '=', 'Nota_credito'],
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
            $iva = DB::table('iva')->get();
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            //return view('nota_de_credito.index');
            return redirect()->route('nota_de_credito.index');
        }
        return view('nota_de_credito.create', [
            'numero' => $nro,
            'timbrado' => $timbrado,
            'ivas' => $iva
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

        try {
            // dump(request()->all());
            //c=cobrado a=anulado

            $c_a_pagar = DB::table('cuentas_a_cobrar')
                ->select('cuentas_a_cobrar.*')
                ->where([
                    ['cuentas_a_cobrar.estado', '=', "A"],
                    ['co.factura_numero', '=', request()->factura],
                    ['co.factura_timbrado', '=', request()->timbradof],
                ])
                ->leftJoin('condicion as co', 'cuentas_a_cobrar.condicion_id', '=', 'co.id')
                ->get();
            $monto_c = 0;
            $can = 0;
            foreach ($c_a_pagar as $key => $value) {
                $monto_c += (int)$c_a_pagar[$key]->monto;
                $can += 1;
            }
            //dump($monto_c);
            $monto = ((int) str_replace('.', '', request()->importe));
            //dump($monto);
            if ($monto_c >= $monto) {
                //dump("modificar");
                DB::table('cuentas_a_cobrar')
                    ->where([
                        ['co.factura_numero', '=', request()->factura],
                        ['co.factura_timbrado', '=', request()->timbradof],
                        ['estado', '=', "A"],
                    ])
                    ->leftJoin('condicion as co', 'cuentas_a_cobrar.condicion_id', '=', 'co.id')
                    ->update(
                        [
                            'monto' => (($monto_c - $monto) / $can)
                        ]
                    );
            } else {
                // dump("error");
                request()->session()->flash('error_', 'Monto mayor al total de cuentas a cobrar');
                return redirect()->route('nota_de_credito.index');
            }


            DB::table('nota_credito')->insert(
                [
                    'numero' => request()->numero, 'factura_numero' => request()->factura,
                    'fecha' => request()->fecha, 'estado' => "A",
                    'concepto' => request()->concepto, 'importe' => ((int) str_replace('.', '', request()->importe)),
                    'timbrado' => request()->timbrado, 'factura_timbrado' => request()->timbradof
                ]
            );

            $input = $request->only([
                'descripcion_detalle', 'precio_detalle',
                'cantidad_detalle', 'iva_detalle'
            ]);
            //dump($input);
            //dump($input["habitacion"][1]);
            if (isset($input["descripcion_detalle"])) {
                foreach ($input["descripcion_detalle"] as $key => $value) {

                    $iva = DB::table('iva')
                        ->select('iva.id')
                        ->where('porcentaje', '=', $input["iva_detalle"][$key])
                        ->get();

                    $data1[] = [
                        'nota_credito_numero' => request()->numero,
                        'nota_credito_timbrado' => request()->timbrado,
                        'descripcion' => str_replace('_', ' ', $input["descripcion_detalle"][$key]),
                        'precio' => $input["precio_detalle"][$key],
                        'cantidad' => $input["cantidad_detalle"][$key],
                        'iva_id' => $iva[0]->id
                    ];
                }

                DB::table('nota_credito_detalle')->insert($data1);
            }
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }


        return redirect()->route('nota_de_credito.index');
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
    public function destroy($nro, $timbrado)
    {

        try {
            //dump($id);
            //i=inactivo a=activo 

            $n_credito_c = DB::table('nota_credito')
                ->select('nota_credito.*')
                ->where([
                    ['numero', '=', $nro],
                    ['timbrado', '=', $timbrado],
                ])
                ->get();

            $can = DB::table('cuentas_a_cobrar')
                ->select('cuentas_a_cobrar.*')
                ->where([
                    ['cuentas_a_cobrar.estado', '=', "A"],
                    ['co.factura_numero', '=', $n_credito_c[0]->factura_numero],
                    ['co.factura_timbrado', '=', $n_credito_c[0]->factura_timbrado],
                ])
                ->leftJoin('condicion as co', 'cuentas_a_cobrar.condicion_id', '=', 'co.id')
                ->count();

            DB::table('cuentas_a_cobrar')
                ->where([
                    ['cuentas_a_cobrar.estado', '=', "A"],
                    ['co.factura_numero', '=', $n_credito_c[0]->factura_numero],
                    ['co.factura_timbrado', '=', $n_credito_c[0]->factura_timbrado],
                ])
                ->leftJoin('condicion as co', 'cuentas_a_cobrar.condicion_id', '=', 'co.id')
                ->update(
                    [
                        'monto' => DB::raw('monto+' . (((int)$n_credito_c[0]->importe) / $can))
                    ]
                );

                DB::table('nota_credito_detalle')
                ->where([
                    ['nota_credito_numero', '=', $nro],
                    ['nota_credito_timbrado', '=', $timbrado],
                ])
                ->delete();

            DB::table('nota_credito')
                ->where([
                    ['numero', '=', $nro],
                    ['timbrado', '=', $timbrado],
                ])
                ->delete();
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }

        return redirect()->route('nota_de_credito.index');
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

    public function factura(Request $request)
    {
        $factura_detalle = DB::table('factura_detalle')
            ->select(
                'factura_detalle.*',
                'iv.porcentaje'
            )
            ->where([
                ['factura_numero', '=', $request->id[0]],
                ['factura_timbrado', '=', $request->id[1]],
            ])
            ->leftJoin('iva as iv', 'factura_detalle.iva_id', '=', 'iv.id')
            ->get();

        return [
            'factura' => $factura_detalle,
        ];
        //return $request;
    }
}
