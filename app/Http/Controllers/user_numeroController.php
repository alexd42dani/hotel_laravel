<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class user_numeroController extends Controller
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
        $user_numero = DB::table('user_numero')
            ->select(
                'user_numero.*',
                'usr.name',
                'usr.nivel',
                'usr.email',
                'ca.numero as numero_caja',
                'tm.nro as timbrado',
                'pe.nombre',
                'pe.apellido',
                'emp.persona_nro_documento as documento'
            )
            ->leftJoin('caja as ca', 'user_numero.caja_id', '=', 'ca.id')
            ->leftJoin('users as usr', 'user_numero.user_id', '=', 'usr.id')
            ->leftJoin('empleado as emp', 'usr.empleado_id', '=', 'emp.id')
            ->leftJoin('persona as pe', function ($join) {
                $join->on('emp.persona_ciudad_id', '=', 'pe.ciudad_id');
                $join->on('emp.persona_nro_documento', '=', 'pe.nro_documento');
            })
            ->leftJoin('factura_numero as fn', 'user_numero.factura_numero_id', '=', 'fn.id')
            ->leftJoin('timbrado as tm', 'fn.timbrado', '=', 'tm.id')
            ->get();


        // dd($estadia);

        return view('user_numero.index', ['variables' => $user_numero]);
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
        $cargo = DB::table('caja')->get();
        return view(
            'user_numero.create',
            [
                'caja' => $cargo,
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
        try {
            // dump(request()->all());
            DB::table('user_numero')->insert(
                [
                    'user_id' => request()->user,
                    'factura_numero_id' => request()->factura_numero,
                    'caja_id' => request()->caja, 'tipo' => request()->tipo
                ]
            );
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }

        return redirect()->route('user_numero.index');
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
        try {
            DB::table('user_numero')->where('user_id', '=', $id)->delete();
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }

        return redirect()->route('user_numero.index');
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
