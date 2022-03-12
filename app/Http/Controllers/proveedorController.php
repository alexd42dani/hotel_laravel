<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class proveedorController extends Controller
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
        $proveedor = DB::table('proveedor')
            ->select(
                'proveedor.*',
                'ci.descripcion as ciudad'
            )
            ->leftJoin('ciudad as ci', 'proveedor.ciudad_id', '=', 'ci.id')
            ->get();

        // dd($estadia);

        return view('proveedor.index', ['proveedores' => $proveedor]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ciudad = DB::table('ciudad')->get();

        return view('proveedor.create', [
            'ciudades' => $ciudad        ]);
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
          DB::table('proveedor')->insert(
            [
                'ruc' => request()->ruc, 'nombre' => request()->nombre,
                'direccion' => request()->direccion, 'correo' => request()->correo,
                'telefono' => request()->telefono, 'web_page' => request()->web,
                'ciudad_id' => request()->ciudad
            ]
        );
       
        return redirect()->route('proveedor.index');
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
        $ciudad = DB::table('ciudad')->get();

        $proveedores = DB::table('proveedor')
            ->where('ruc', '=', $id)
            ->get();

        //dd($reservas);
        return view('proveedor.update', [
            'ciudades' => $ciudad,
            'proveedores' => $proveedores
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
        DB::table('proveedor')
            ->where('ruc', request()->ruc)
            ->update(
                [
                     'nombre' => request()->nombre,
                'direccion' => request()->direccion, 'correo' => request()->correo,
                'telefono' => request()->telefono, 'web_page' => request()->web,
                'ciudad_id' => request()->ciudad
                ]
            );
      

        return redirect()->route('proveedor.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('proveedor')->where('ruc', '=', $id)->delete();

            return redirect()->route('proveedor.index');
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
