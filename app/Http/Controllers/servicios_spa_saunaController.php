<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class servicios_spa_saunaController extends Controller
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
        $servicios = DB::table('s_spa_sauna')
            ->select(
                's_spa_sauna.*',
                'pe.nombre',
                'pe.apellido',
                'cl.ruc'
            )
            ->where('s_spa_sauna.estado', '=', 'A')
            ->leftJoin('estadia as es', 's_spa_sauna.estadia_id', '=', 'es.id')
            ->leftJoin('clientes as cl', 'es.clientes_id', '=', 'cl.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('cl.persona_pais', '=', 'pe.pais_id');
                $join->on('cl.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        //  dd($nota_credito);

        return view('servicios_spa_sauna.index', ['servicios' => $servicios]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $spa_sauna = DB::table('spa_sauna')->get();
        $habitacion = DB::table('habitaciones')
            ->select('id', 'descripcion')->get();

        return view('servicios_spa_sauna.create', [
            'spa_sauna' => $spa_sauna,
            'habitacion' => $habitacion,
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
//dd(request()->all());
        try {
            // dump(request()->all());
            //c=cobrado a=anulado
            $id = DB::table('s_spa_sauna')->insertGetId(
                [
                    'estadia_id' => request()->estadia, 'descripcion' => request()->descripcion,
                    'fecha' => request()->fecha, 'estado' => "A",
                    'realizado' => request()->realizado,
                ]
            );

            $input = $request->only([
                'spa_sauna_detalle', 'cantidad_detalle', 'habitacion_detalle', 'promocion_detalle', 'huespedes_detalle'
            ]);
            //dump($input);
            //dump($input["habitacion"][1]);
            if (isset($input["spa_sauna_detalle"])) {
                foreach ($input["spa_sauna_detalle"] as $key => $value) {

                    $data1[] = [
                        's_spa_sauna_id' => $id,
                        'spa_sauna_id' => $input["spa_sauna_detalle"][$key],
                        'cantidad' => $input["cantidad_detalle"][$key],
                        'habitacion_id' => $input["habitacion_detalle"][$key],
                        'huesped_id' => $input["huespedes_detalle"][$key],
                        'promocion' => $input["promocion_detalle"][$key]
                    ];
                }


                DB::table('spa_sauna_detalle')->insert($data1);
            }
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }


        return redirect()->route('servicios_spa_sauna.index');
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
        //i=inactivo a=activo 
        try {

            DB::table('spa_sauna_detalle')->where('s_spa_sauna_id', '=', $id)->delete();
            DB::table('s_spa_sauna')->where('id', '=', $id)->delete();
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }

        return redirect()->route('servicios_spa_sauna.index');
    }

    public function realizado($id)
    {
        try {
            //dump($id);
            //i=inactivo a=activo 

            DB::table('s_spa_sauna')
                ->where('id', '=', $id)->update(
                    [
                        'realizado' => "Si"
                    ]
                );
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }

        return redirect()->route('servicios_spa_sauna.index');
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

    public function huesped(Request $request)
    {
        $huespedes = DB::table('huespedes')
            ->select(
                'huespedes.id',
                'pe.nombre',
                'pe.apellido'
            )
            ->where('huespedes.id', '=', $request->id)
            ->leftJoin('persona as pe', function ($join) {
                $join->on('huespedes.persona_ciudad_id', '=', 'pe.ciudad_id');
                $join->on('huespedes.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();

        return ['huespedes' => $huespedes];
        //return $request;
    }

    public function spa_sauna(Request $request)
    {
        $spa_sauna = DB::table('spa_sauna')
            ->select(
                'spa_sauna.*'
            )
            ->where('id', '=', $request->id[0])
            ->get();

        $habitaciones = DB::table('habitaciones')
            ->select(
                'habitaciones.id',
                'habitaciones.descripcion'
            )
            ->where('id', '=', $request->id[1])
            ->get();

        $huespedes = DB::table('huespedes')
            ->select(
                'huespedes.id',
                'huespedes.estadia_id',
                'pe.nombre',
                'pe.apellido'
            )
            ->where('huespedes.id', '=', $request->id[2])
            ->leftJoin('persona as pe', function ($join) {
                $join->on('huespedes.persona_pais', '=', 'pe.pais_id');
                $join->on('huespedes.persona_nro_documento', '=', 'pe.nro_documento');
            })->get();

        $tarifa_id = DB::table('estadia_tarifas')
            ->select(
                'ta.id'
            )
            ->where([
                ['estadia_tarifas.estadia_id', '=', $huespedes[0]->estadia_id],
                ['ta.habitacion_id', '=', $request->id[1]]
            ])
            ->leftJoin('tarifas as ta', 'estadia_tarifas.tarifa_id', '=', 'ta.id')
            ->get();
    
        $promocion = DB::table('promocion')
            ->select(
                'promocion.porcentaje'
            )
            ->where([
                ['tarifas_id', '=', $tarifa_id[0]->id],
                ['servicio', '=', "Spa_sauna"]
            ])->get();

        return [
            'spa_sauna' => $spa_sauna, 'habitaciones' => $habitaciones,
            'huespedes' => $huespedes, 'promocion' => $promocion
        ];
        //return $request;
    }
}
