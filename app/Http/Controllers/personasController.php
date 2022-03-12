<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class personasController extends Controller
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
        $persona = DB::table('persona')
            ->select(
                'persona.*',
                'ci.descripcion as ciudad',
                'td.descripcion as tipo_documento'
            )
            ->leftJoin('ciudad as ci', 'persona.ciudad_id', '=', 'ci.id')
            ->leftJoin('tipo_documento as td', 'persona.tipo_documento', '=', 'td.id')
            ->get();

        // dd($estadia);

        return view('personas.index', ['personas' => $persona]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ciudad = DB::table('ciudad')->get();
        $tipo_documento = DB::table('tipo_documento')->get();

        return view('personas.create', [
            'ciudades' => $ciudad,
            'tipo_documentos' => $tipo_documento
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
            $deptonro = DB::table('ciudad')
                ->select('idDepartamento')
                ->where('id', '=', request()->ciudad)
                ->get();

            $paisid = DB::table('departamento')
                ->select('pais_id')
                ->where('id', '=', $deptonro[0]->idDepartamento)
                ->get();

            DB::table('persona')->insert(
                [
                    'ciudad_id' => request()->ciudad, 'tipo_documento' => request()->tipo_documento,
                    'nombre' => request()->nombre, 'apellido' => request()->apellido,
                    'telefono' => request()->telefono, 'email' => request()->email,
                    'direccion' => request()->direccion, 'fecha_nacimiento' => request()->fecha_nacimiento,
                    'nro_documento' => request()->numero, 'pais_id' => $paisid[0]->pais_id
                ]
            );
        } catch (\Exception $e) {
            //request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_','Error en base de datos');
            //return redirect()->route('personas.index');
        }

        return redirect()->route('personas.index');
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
    public function edit($cedula,$pais_id)
    {
        $ciudad = DB::table('ciudad')->get();
        $tipo_documento = DB::table('tipo_documento')->get();

        $personas = DB::table('persona')
        ->where([
            ['nro_documento', '=', $cedula],
            ['pais_id', '=', $pais_id]
        ])->get();

        //dd($reservas);
        return view('personas.update', [
            'ciudades' => $ciudad,
            'tipo_documentos' => $tipo_documento,
            'personas' => $personas
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
        try {
            $deptonro = DB::table('ciudad')
                ->select('idDepartamento')
                ->where('id', '=', request()->ciudad)
                ->get();

            $paisid = DB::table('departamento')
                ->select('pais_id')
                ->where('id', '=', $deptonro[0]->idDepartamento)
                ->get();
            DB::table('persona')
                ->where('nro_documento', request()->numero)
                ->update(
                    [
                        'ciudad_id' => request()->ciudad, 'tipo_documento' => request()->tipo_documento,
                        'nombre' => request()->nombre, 'apellido' => request()->apellido,
                        'telefono' => request()->telefono, 'email' => request()->email,
                        'direccion' => request()->direccion, 'fecha_nacimiento' => request()->fecha_nacimiento,
                        'nro_documento' => request()->numero, 'pais_id' => $paisid[0]->pais_id
                    ]
                );
            } catch (\Exception $e) {
                //request()->session()->flash('error_', $e->getMessage());
                request()->session()->flash('error_','Error en base de datos');
                return redirect()->route('personas.index');
            }


        return redirect()->route('personas.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function destroy($cedula,$pais_id)
    {
        try {
            DB::table('persona')
                ->where([
                    ['nro_documento', '=', $cedula],
                    ['pais_id', '=', $pais_id]
                ])->delete();
            } catch (\Exception $e) {
                //request()->session()->flash('error_', $e->getMessage());
                request()->session()->flash('error_','Error en base de datos');
                //return redirect()->route('personas.index');
            }
        return redirect()->route('personas.index');
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
