<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class articulosController extends Controller
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
        $articulo = DB::table('articulo')
            ->select(
                'articulo.*',
                'un.descripcion as unidad',
                'ca.descripcion as categoria'
            )
            ->leftJoin('unidad as un', 'articulo.unidad_id', '=', 'un.id')
            ->leftJoin('categoria as ca', 'articulo.categoria_id', '=', 'ca.id')
            ->get();

        // dd($estadia);

        return view('articulos.index', ['articulos' => $articulo]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoria = DB::table('categoria')->get();
        $unidad = DB::table('unidad')->get();

        return view('articulos.create', [
            'categorias' => $categoria,
            'unidades' => $unidad
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
         $id= DB::table('articulo')->insertGetId(
            [
                'nombre' => request()->nombre, 'descripcion' => request()->descripcion,
                'stock_minimo' => request()->stockmm, 'stock_maximo' => request()->stockmx,
                'unidad_id' => request()->unidad, 'categoria_id' => request()->categoria
            ]
        );

        DB::table('inventario')->insert(
            [
                'articulo_codigo' => $id,'stock'=>0,'precio'=>0
            ]
        );
       
        return redirect()->route('articulos.index');
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
        $articulos = DB::table('articulo')
            ->where('codigo', '=', $id)
            ->get();

        $categoria = DB::table('categoria')->get();
        $unidad = DB::table('unidad')->get();

        return view('articulos.update', [
            'categorias' => $categoria,
            'articulos' => $articulos,
            'unidades' => $unidad
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
        DB::table('articulo')
            ->where('codigo', request()->codigo)
            ->update(
                [
                    'nombre' => request()->nombre, 'descripcion' => request()->descripcion,
                'stock_minimo' => request()->stockmm, 'stock_maximo' => request()->stockmx,
                'unidad_id' => request()->unidad, 'categoria_id' => request()->categoria
                ]
            );
      

        return redirect()->route('articulos.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('inventario')->where('articulo_codigo', '=', $id)->delete();
        DB::table('articulo')->where('codigo', '=', $id)->delete();

            return redirect()->route('articulos.index');
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
