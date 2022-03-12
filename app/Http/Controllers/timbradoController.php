<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class timbradoController extends Controller
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
        $timbrado = DB::table('timbrado')
            ->select(
                'timbrado.*',
                'su.nombre',
                'su.numero'
            )
            ->where('timbrado.estado', '=', 'A')
            ->leftJoin('sucursal as su','timbrado.sucursal_id','=','su.id')
            ->get();

        // dd($estadia);

        return view('timbrado.index', ['variables' => $timbrado]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view(
            'timbrado.create'
            /* ,[
            'ciudades' => $ciudad,
        ]*/
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
        DB::table('timbrado')->insert(
            [
                'nro' => request()->numero,
                'fecha_desde' => request()->fecha_desde,
                'fecha_fin' => request()->fecha_fin,
                'sucursal_id' => request()->sucursal,
                'estado' => "A"
            ]
        );

        return redirect()->route('timbrado.index');
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

        $timbrado = DB::table('timbrado')
            ->where('id', '=', $id)
            ->get();

        //dd($reservas);
        return view('timbrado.update', [
            'variables' => $timbrado
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
        DB::table('timbrado')
            ->where('id', request()->codigo)
            ->update(
                [
                    'nro' => request()->numero,
                    'fecha_desde' => request()->fecha_desde,
                    'fecha_fin' => request()->fecha_fin
                ]
            );


        return redirect()->route('timbrado.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('timbrado')
                ->where('id', $id)
                ->update(
                    [
                        'estado' => "I"
                    ]
                );

        return redirect()->route('timbrado.index');
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
