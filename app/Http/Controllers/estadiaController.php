<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class estadiaController extends Controller
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
        $estadia = DB::table('estadia')
            ->select(
                'estadia.*',
                'op.descripcion as operador',
                'tc.descripcion as tipo_cliente',
                'te.descripcion as tipo_estadia',
                'pe.nombre',
                'pe.apellido',
                'cl.ruc'
            )
            ->where('estado', '=', 'A')
            ->leftJoin('operador_turistico as op', 'estadia.id_operador', '=', 'op.id')
            ->leftJoin('tipo_cliente as tc', 'estadia.tipo_cliente_id', '=', 'tc.id')
            ->leftJoin('tipo_estadia as te', 'estadia.tipo_estadia_id', '=', 'te.id')
            ->leftJoin('clientes as cl', 'estadia.clientes_id', '=', 'cl.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('cl.persona_pais', '=', 'pe.pais_id');
                $join->on('cl.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        // dd($estadia);

        return view('estadia.index', ['estadias' => $estadia]);
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
        $tipo_estadia = DB::table('tipo_estadia')->get();
        $habitacion = DB::table('habitaciones')
            ->select('id', 'descripcion')->get();
        //dd($operador, $tipo_cliente, $tipo_estadia);

        return view('estadia.create', [
            'operadores' => $operador,
            'tipo_clientes' => $tipo_cliente,
            'tipo_estadias' => $tipo_estadia,
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
            $id = DB::table('estadia')->insertGetId(
                [
                    'id_operador' => request()->operador, 'comentarios' => request()->comentarios,
                    'tipo_cliente_id' => request()->tipo_cliente,
                    'Tipo_estadia_id' => request()->tipo_estadia, 'fecha' => request()->fecha,
                    'clientes_id' => request()->cliente
                ]
            );
            //dump($request->all());

            if (request()->reserva !== null) {
                DB::table('estadia_reserva')->insert(
                    ['reservas_id' => request()->reserva, 'estadia_id' => $id,]
                );
                DB::table('reservas')
                    ->where('id', request()->reserva)
                    ->update(
                        [
                            'estado' => "I"
                        ]
                    );
            }

            foreach ($request->tarifa as $key => $value) {
                $data[] = [
                    'estadia_id' => $id,
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
                    'id_estadia' => $id,
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

            $input = $request->only(['persona_ciudad', 'persona_documento', 'habitacion_huesped', 'persona_pais']);

            foreach ($input["persona_ciudad"] as $key => $value) {
                //$data2[] = [
                // 'persona_ciudad_id' => $input["persona_ciudad"][$key],
                // 'persona_nro_documento' =>$input["persona_documento"][$key],
                //];
                $id_huesped = DB::table('huespedes')->insertGetId(
                    [
                        'persona_pais' => $input["persona_pais"][$key],
                        'persona_nro_documento' => $input["persona_documento"][$key],
                        'estadia_id' => $id
                    ]
                );

                DB::table('estadia_huespedes')->insert(
                    [
                        'id_estadia' => $id,
                        'huespedes_id' => $id_huesped,
                        'habitacion_id' => $input["habitacion_huesped"][$key]
                    ]
                );
            }
        } catch (\Exception $e) {
            //request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            //  return redirect()->route('personas.index');
        }

        return redirect()->route('estadia.index');
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
            DB::table('estadia')
                ->where('id', $id)
                ->update(
                    [
                        'estado' => "I"
                    ]
                );

            $reserva_id = DB::table('estadia_reserva')
                ->select('reservas_id')
                ->where('estadia_id', $id)
                ->get();

            if (isset($reserva_id[0]->reservas_id)) {
                DB::table('reservas')
                    ->where('id', $reserva_id[0]->reservas_id)
                    ->update(
                        [
                            'estado' => "A"
                        ]
                    );
            }
        } catch (\Exception $e) {
            //request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            //  return redirect()->route('personas.index');
        }
        return redirect()->route('estadia.index');
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
            ->select('nombre', 'apellido', 'pais_id')
            ->where([
                ['ciudad_id', '=', $request->id[0]],
                ['nro_documento', '=', $request->id[1]],
            ])->get();
        //dd($tarifa);

        //return view('estadia.create', ['tarifas' => $tarifa]);

        return ['personas' => $persona];
        //return $request;
    }
    public function reserva(Request $request)
    {
        $reserva = DB::table('reservas')
            ->select(
                'reservas.*'
            )
            ->where('id', '=', $request->id)
            ->get();

            $reserva_tarifas = DB::table('reserva_tarifas')
                ->select(
                    'reserva_tarifas.*'
                )
                ->where('id_reserva', '=', $request->id)
                ->get();

            foreach ($reserva_tarifas as $key => $value) {
                $art_cod[] = (int) ($reserva_tarifas[$key]->id_tarifa);
            }

            $tarifa = DB::table('tarifas')
            ->select('tn.descripcion', 'tarifas.id', 'tarifas.habitacion_id')
            ->whereIn('tarifas.id', $art_cod)
            ->leftJoin('tarifas_nombres as tn', 'tarifas.tarifas_nombres_id', '=', 'tn.id')
            ->get();

            $reserva_habitaciones = DB::table('reserva_habitaciones')
            ->select(
                'reserva_habitaciones.*',
                'h.descripcion'
            )
            ->where('id_reserva', '=', $request->id)
            ->leftJoin('habitaciones as h', 'reserva_habitaciones.id_habitaciones', '=', 'h.id')
            ->get();

            $persona = DB::table('reserva_personas')
            ->select('pe.*','reserva_personas.habitacion_id', 'h.descripcion')
            ->where('reservas_id', '=', $request->id)
            ->leftJoin('habitaciones as h', 'reserva_personas.habitacion_id', '=', 'h.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('reserva_personas.persona_pais', '=', 'pe.pais_id');
                $join->on('reserva_personas.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        return [
            'reservas' => $reserva,
            'tarifas' => $tarifa,
            'reserva_habitaciones' => $reserva_habitaciones,
            'personas' => $persona
        ];
        //return $request;
    }
}
