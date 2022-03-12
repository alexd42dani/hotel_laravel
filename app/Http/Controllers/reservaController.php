<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class reservaController extends Controller
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
        $reserva = DB::table('reservas')
            ->select(
                'reservas.*',
                'op.descripcion as operador',
                'tc.descripcion as tipo_cliente',
                'te.descripcion as tipo_reserva',
                'pe.nombre',
                'pe.apellido',
                'cl.ruc'
            )
            ->where('estado', '=', 'A')
            ->leftJoin('operador_turistico as op', 'reservas.id_operador', '=', 'op.id')
            ->leftJoin('tipo_cliente as tc', 'reservas.tipo_cliente_id', '=', 'tc.id')
            ->leftJoin('tipo_reserva as te', 'reservas.tipo_reserva_id', '=', 'te.id')
            ->leftJoin('clientes as cl', 'reservas.clientes_id', '=', 'cl.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('cl.persona_pais', '=', 'pe.pais_id');
                $join->on('cl.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        // dd($estadia);

        return view('reserva.index', ['reservas' => $reserva]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $operador = DB::table('operador_turistico')->get();
        $tipo_cliente = DB::table('tipo_cliente')->get();
        $tipo_reserva = DB::table('tipo_reserva')->get();
        $habitacion = DB::table('habitaciones')
            ->select('id', 'descripcion')->get();
        //dd($operador, $tipo_cliente, $tipo_estadia);

        return view('reserva.create', [
            'operadores' => $operador,
            'tipo_clientes' => $tipo_cliente,
            'tipo_reservas' => $tipo_reserva,
            'habitaciones' => $habitacion
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
        try {
            $id = DB::table('reservas')->insertGetId(
                [
                    'id_operador' => request()->operador, 'comentarios' => request()->comentarios,
                    'tipo_cliente_id' => request()->tipo_cliente,
                    'tipo_reserva_id' => request()->tipo_reserva, 'fecha' => request()->fecha,
                    'clientes_id' => request()->cliente
                ]
            );
            //dump($request->all());


            foreach ($request->tarifa as $key => $value) {
                $data[] = [
                    'id_reserva' => $id,
                    'id_tarifa' => $value[0],
                ];
            }
            //dump($data);
            DB::table('reserva_tarifas')->insert($data);
            // dump($request->all()["f_entrada"]);
            $input = $request->only(['habitacion', 'f_entrada', 'f_salida', 'h_entrada', 'h_salida']);
            //dump($input);
            //dump($input["habitacion"][1]);
            foreach ($input["habitacion"] as $key => $value) {
                $data1[] = [
                    'id_reserva' => $id,
                    'id_habitaciones' => $value,
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

            DB::table('reserva_habitaciones')->insert($data1);

            $input = $request->only(['persona_ciudad', 'persona_documento', 'habitacion_huesped', 'persona_pais']);

            foreach ($input["persona_ciudad"] as $key => $value) {
                //$data2[] = [
                // 'persona_ciudad_id' => $input["persona_ciudad"][$key],
                // 'persona_nro_documento' =>$input["persona_documento"][$key],
                //];

                DB::table('reserva_personas')->insert(
                    [
                        'reservas_id' => $id,
                        'persona_pais' => $input["persona_pais"][$key],
                        'persona_nro_documento' => $input["persona_documento"][$key],
                        'habitacion_id' => $input["habitacion_huesped"][$key]
                    ]
                );
            }
        } catch (\Exception $e) {
            //request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            //  return redirect()->route('personas.index');
        }

        return redirect()->route('reserva.index');
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
        $tipo_estadia = DB::table('tipo_reserva')->get();
        $habitacion = DB::table('habitaciones')
            ->select('id', 'descripcion')->get();

        $habitaciones = DB::table('reserva_habitaciones')
            ->select('reserva_habitaciones.*', 'ha.descripcion')
            ->leftJoin('habitaciones as ha', 'reserva_habitaciones.id_habitaciones', '=', 'ha.id')
            ->where('id_reserva', '=', $id)
            ->get();

        $tarifas = DB::table('reserva_tarifas')
            ->select('reserva_tarifas.id_tarifa', 'tn.descripcion', 'ta.habitacion_id')
            ->leftJoin('tarifas as ta', 'reserva_tarifas.tarifa_id', '=', 'ta.id')
            ->leftJoin('tarifas_nombres as tn', 'ta.tarifas_nombres_id', '=', 'tn.id')
            ->where('id_reserva', '=', $id)
            ->get();
        //}}dd($habitaciones);

        $huespedes = DB::table('reserva_personas')
            ->select(
                'reserva_personas.habitacion_id',
                'ha.descripcion',
                'pe.ciudad_id',
                'pe.nro_documento',
                'pe.nombre',
                'pe.apellido'
            )
            ->where('estadia_id', '=', $id)
            ->leftJoin('habitaciones as ha', 'reserva_personas.habitacion_id', '=', 'ha.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('reserva_personas.persona_ciudad_id', '=', 'pe.ciudad_id');
                $join->on('reserva_personas.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        $estadias = DB::table('reservas')
            ->where('id', '=', $id)
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
            'estad' => $estadias
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
        try {
            DB::table('reservas')
                ->where('id', $id)
                ->update(
                    [
                        'estado' => "I"
                    ]
                );
        } catch (\Exception $e) {
            //request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            //  return redirect()->route('personas.index');
        }
        return redirect()->route('reserva.index');
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
