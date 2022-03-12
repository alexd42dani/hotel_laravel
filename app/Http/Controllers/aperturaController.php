<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class aperturaController extends Controller
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
        $id = Auth::id();
        $caja = DB::table('user_numero')
            ->select(
                'ca.descripcion',
                'ca.id'
            )
            ->where('user_id', '=', $id)
            ->leftJoin('caja as ca', 'user_numero.caja_id', '=', 'ca.id')
            ->get();
        $apertura = DB::table('apertura_cierre')
            ->select(
                'apertura_cierre.*',
                'ca.descripcion as caja'
            )
            ->where([
                ['caja_id', '=', $caja[0]->id],
                ['estado', '=', "A"],
            ])
            ->leftJoin('caja as ca', 'apertura_cierre.caja_id', '=', 'ca.id')
            ->get();

        // dd($estadia);

        return view('apertura_cierre.index', ['aperturas' => $apertura]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $id = Auth::id();
        $caja = DB::table('user_numero')
            ->select(
                'ca.descripcion',
                'ca.id'
            )
            ->where('user_id', '=', $id)
            ->leftJoin('caja as ca', 'user_numero.caja_id', '=', 'ca.id')
            ->get();

        return view('apertura_cierre.create', [
            'cajas' => $caja
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
        try {
            // dump(request()->all());
            DB::table('apertura_cierre')->insert(
                [
                    'caja_id' => request()->caja, 'fecha_apertura' => request()->fechaa,
                    'hora_apertura' => request()->horaa, 'saldo_inicial' => request()->salini,
                    'estado' => "A"
                ]
            );
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }

        return redirect()->route('apertura.index');
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
        $id1 = Auth::id();
        $caja = DB::table('user_numero')
            ->select(
                'ca.descripcion',
                'ca.id'
            )
            ->where('user_id', '=', $id1)
            ->leftJoin('caja as ca', 'user_numero.caja_id', '=', 'ca.id')
            ->get();

        $apertura = DB::table('apertura_cierre')
            ->where('id', '=', $id)
            ->get();

        return view('apertura_cierre.update', [
            'cajas' => $caja,
            'aperturas' => $apertura,
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
        try {
            $cobroe = DB::table('cobros')
                ->select(DB::raw('SUM(cf.`monto`)-SUM(cf.`vuelto`) AS saldo'))
                ->where([
                    ['cobros.apertura_cierre_id', '=', $request->codigo],
                    ['cobros.estado', '=', 'C'],
                ])
                ->leftJoin('cobro_efectivo as cf', 'cobros.id', '=', 'cf.cobrosid')
                ->get();

            $cobroc = DB::table('cobros')
                ->select(DB::raw('SUM(cc.monto) as cheque'))
                ->where([
                    ['cobros.apertura_cierre_id', '=', $request->codigo],
                    ['cobros.estado', '=', 'C'],
                ])
                ->leftJoin('cobro_cheque as cc', 'cobros.id', '=', 'cc.cobrosid')
                ->get();

            DB::table('recaudaciones_a_depositar')
                ->insert(
                    [
                        'apertura_cierre_id' => request()->codigo, 'monto_efectivo' => $cobroe[0]->saldo,
                        'monto_cheque' => $cobroc[0]->cheque
                    ]
                );

            DB::table('apertura_cierre')->where('id', request()->codigo)
                ->update(
                    [
                        'caja_id' => request()->caja, 'fecha_apertura' => request()->fechaa,
                        'hora_apertura' => request()->horaa, 'saldo_inicial' => request()->salini,
                        'estado' => "I", 'fecha_cierre' => request()->fechac,
                        'hora_cierre' => request()->horac, 'saldo_final' => str_replace('.', '', request()->salfin)
                    ]
                );
        } catch (\Exception $e) {
            // request()->session()->flash('error_', $e->getMessage());
            request()->session()->flash('error_', 'Error en base de datos');
            // return redirect()->route('personas.index');
        }



        return redirect()->route('apertura.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\estadia  $estadia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }

    public function estimado(Request $request)
    {
        $cobroe = DB::table('cobros')
            ->select(DB::raw('SUM(cf.`monto`)-SUM(cf.`vuelto`) AS saldo'))
            ->where([
                ['cobros.apertura_cierre_id', '=', $request->id],
                ['cobros.estado', '=', 'C'],
            ])
            ->leftJoin('cobro_efectivo as cf', 'cobros.id', '=', 'cf.cobrosid')
            ->get();

        $cobroc = DB::table('cobros')
            ->select(DB::raw('SUM(cc.monto) as cheque'))
            ->where([
                ['cobros.apertura_cierre_id', '=', $request->id],
                ['cobros.estado', '=', 'C'],
            ])
            ->leftJoin('cobro_cheque as cc', 'cobros.id', '=', 'cc.cobrosid')
            ->get();

        $cobrot = DB::table('cobros')
            ->select(DB::raw('SUM(ct.monto) as tarjeta'))
            ->where([
                ['cobros.apertura_cierre_id', '=', $request->id],
                ['cobros.estado', '=', 'C'],
            ])
            ->leftJoin('cobro_tarjeta as ct', 'cobros.id', '=', 'ct.cobrosid')
            ->get();


        //dd($tarifa);

        //return view('estadia.create', ['tarifas' => $tarifa]);

        return [
            'cobroe' => $cobroe,
            'cobroc' => $cobroc,
            'cobrot' => $cobrot
        ];
        //return $request;
    }
}
