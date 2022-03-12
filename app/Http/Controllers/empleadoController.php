<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class empleadoController extends Controller
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
        $empleado = DB::table('empleado')
            ->select(
                'empleado.*',
                'car.descripcion as cargo',
                'pe.nombre',
                'pe.apellido',
                'empleado.persona_nro_documento as documento'
            )
            ->leftJoin('cargo as car', 'empleado.cargo_id', '=', 'car.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('empleado.persona_ciudad_id', '=', 'pe.ciudad_id');
                $join->on('empleado.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->get();


        // dd($estadia);

        return view('empleado.index', ['variables' => $empleado]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* $cargo = DB::table('empleado')
            ->select(
                'empleado.*',
                'car.descripcion as cargo'
            )
            ->leftJoin('cargo as car', 'empleado.cargo_id', '=', 'car.id')
            ->get();*/
        $cargo = DB::table('cargo')->get();
        return view(
            'empleado.create',
            [
                'cargos' => $cargo,
            ]
        );
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
        DB::table('empleado')->insert(
            [
                'cargo_id' => request()->cargo,
                'codigo_empleado' => request()->codigo_empleado,
                'persona_ciudad_id' => request()->persona_ciudad,
                'persona_nro_documento' => request()->persona_documento
            ]
        );

        return redirect()->route('empleado.index');
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
        // $ciudad = DB::table('ciudad')->get();
        $cargo = DB::table('cargo')->get();

        $empleado = DB::table('empleado')
            ->where('id', '=', $id)
            ->get();

        //dd($reservas);
        return view('empleado.update', [
            'variables' => $empleado,
            'cargos' => $cargo
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
        DB::table('empleado')
            ->where('id', request()->codigo)
            ->update(
                [
                    'cargo_id' => request()->cargo,
                    'codigo_empleado' => request()->codigo_empleado,
                    'persona_ciudad_id' => request()->persona_ciudad,
                    'persona_nro_documento' => request()->persona_documento
                ]
            );


        return redirect()->route('empleado.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('empleado')->where('id', '=', $id)->delete();

        return redirect()->route('empleado.index');
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
