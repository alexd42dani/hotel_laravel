<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class cobrosController extends Controller
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
        //estado c=cobrado a=anulado
        $cobros = DB::table('cobros')
            ->select(
                'cobros.*',
                'ca.descripcion as caja',
                'pe.nombre',
                'pe.apellido',
                'cl.ruc'
            )
            ->where('cobros.estado', '=', 'C')
            ->leftJoin('apertura_cierre as ac', 'cobros.apertura_cierre_id', '=', 'ac.id')
            ->leftJoin('caja as ca', 'ac.caja_id', '=', 'ca.id')
            ->leftJoin('cuentas_a_cobrar as cc', 'cobros.cuentas_a_cobrar_id', '=', 'cc.id')
            ->leftJoin('condicion as co', 'cc.condicion_id', '=', 'co.id')
            ->leftJoin('factura as fa', 'co.factura_numero', '=', 'fa.numero')
            ->leftJoin('clientes as cl', 'fa.clientes_id', '=', 'cl.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('cl.persona_pais', '=', 'pe.pais_id');
                $join->on('cl.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        // dd($estadia);

        return view('cobros.index', ['cobros' => $cobros]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $entidad = DB::table('entidad')->get();
        $marca_tarjeta = DB::table('marca_tarjeta')->get();
        $tipo_tarjeta = DB::table('tipo_tarjeta')->get();
        $procesadora = DB::table('procesadora')->get();

        return view('cobros.create', [
            'entidades' => $entidad,
            'marca_tarjetas' => $marca_tarjeta,
            'tipo_tarjetas' => $tipo_tarjeta,
            'procesadoras' => $procesadora
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
        $id = DB::table('cobros')->insertGetId(
            [
                'fecha' => request()->fecha, 'cuentas_a_cobrar_id' => request()->cuenta,
                'apertura_cierre_id' => request()->apertura,
                'estado' => "C"
            ]
        );

        DB::table('cobro_efectivo')->insert(
            [
                'monto' => str_replace('.', '', request()->efectivo), 'cobrosid' => $id,
                'vuelto' => request()->vuelto
            ]
        );

        $input = $request->only([
            'monto_tarjeta', 'ticket_tarjeta',
            'entidad_tarjeta', 'tipo_tarjeta', 'marca_tarjeta', 'serie_tarjeta', 'procesadora_tarjeta'
        ]);
        //dump($input);
        //dump($input["habitacion"][1]);
        if (isset($input["monto_tarjeta"])) {
            foreach ($input["monto_tarjeta"] as $key => $value) {
                $data1[] = [
                    'cobrosid' => $id,
                    'monto' => $input["monto_tarjeta"][$key],
                    'ticket' => $input["ticket_tarjeta"][$key],
                    'entidad_id' => $input["entidad_tarjeta"][$key],
                    'tipo_tarjeta_id' => $input["tipo_tarjeta"][$key],
                    'marca_tarjeta_id' => $input["marca_tarjeta"][$key],
                    'serie' => $input["serie_tarjeta"][$key],
                    'procesadora_id' => $input["procesadora_tarjeta"][$key]
                ];
            }

            /* $data = array(
           array('id_estadia'=> [1,1], 'id_habitacion'=>  $input["habitacion"], 'entrada'=> $input["f_entrada"]),
           // array('id_estadia'=>["1","1"], 'id_habitacion'=> ["1","1"]),
        );*/

            //dump($data);

            DB::table('cobro_tarjeta')->insert($data1);
        }
        $input = $request->only([
            'entidad_cheque', 'numero_cheque',
            'emision_cheque', 'vencimiento_cheque', 'monto', 'titular'
        ]);

        if (isset($input["entidad_cheque"])) {
            foreach ($input["entidad_cheque"] as $key => $value) {
                $data2[] = [
                    'cobrosid' => $id,
                    'identidad' => $input["entidad_cheque"][$key],
                    'numero' => $input["numero_cheque"][$key],
                    'emision' => $input["emision_cheque"][$key],
                    'vencimiento' => $input["vencimiento_cheque"][$key],
                    'monto' => $input["monto"][$key],
                    'titular' => $input["titular"][$key]
                ];
            }

            DB::table('cobro_cheque')->insert($data2);
        }
        DB::table('cuentas_a_cobrar')
            ->where('id', request()->cuenta)
            ->update(
                [
                    'estado' => "I"
                ]
            );

        return redirect()->route('cobros.index');
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
        //dump($id);
        //c=cobrado a=anulado COBROS
        DB::table('cobros')
            ->where('id', $id)
            ->update(
                [
                    'estado' => "A"
                ]
            );
        $cuentas = DB::table('cobros')
            ->select(
                'cobros.cuentas_a_cobrar_id'
            )
            ->where('cobros.id', '=', $id)
            ->get();
        //a=activo i=inactivo //CUENTAS
        DB::table('cuentas_a_cobrar')
            ->where('id', $cuentas[0]->cuentas_a_cobrar_id)
            ->update(
                [
                    'estado' => "A"
                ]
            );
        return redirect()->route('cobros.index');
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
}
