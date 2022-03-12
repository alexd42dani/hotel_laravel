<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class spa_saunaController extends Controller
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
        $spa_sauna = DB::table('spa_sauna')
            ->select(
                'spa_sauna.*'
            )
            ->get();

        // dd($estadia);

        return view('spa_sauna.index', ['variables' => $spa_sauna]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view(
            'spa_sauna.create'
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
        DB::table('spa_sauna')->insert(
            [
                'nombre' => request()->descripcion,
                'precio' => request()->precio
            ]
        );

        return redirect()->route('spa_sauna.index');
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

        $spa_sauna = DB::table('spa_sauna')
            ->where('id', '=', $id)
            ->get();

        //dd($reservas);
        return view('spa_sauna.update', [
            'variables' => $spa_sauna
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
        DB::table('spa_sauna')
            ->where('id', request()->codigo)
            ->update(
                [
                    'nombre' => request()->descripcion,
                    'precio' => request()->precio
                ]
            );


        return redirect()->route('spa_sauna.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('spa_sauna')->where('id', '=', $id)->delete();

        return redirect()->route('spa_sauna.index');
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
