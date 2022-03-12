<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class arqueoController extends Controller
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
        $arqueo = DB::table('arqueo')
            ->select(
                'arqueo.*',
                'ca.descripcion as caja'
            )
            ->where([
                ['ap.caja_id', '=', $caja[0]->id],
                ['arqueo.estado', '=', "A"],
            ])
            ->leftJoin('apertura_cierre as ap', 'arqueo.apertura_cierre_id', '=', 'ap.id')
            ->leftJoin('caja as ca', 'ap.caja_id', '=', 'ca.id')
            ->get();

        // dd($factura);

        return view('arqueo.index', ['arqueos' => $arqueo]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*$operador = DB::table('operador_turistico')->get();
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
        ]);*/

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
                'apertura_cierre.id'
            )
            ->where([
                ['caja_id', '=', $caja[0]->id],
                ['estado', '=', "A"],
            ])
            ->leftJoin('caja as ca', 'apertura_cierre.caja_id', '=', 'ca.id')
            ->get();

        return view('arqueo.create', [
            'apertura' => $apertura
        ]);
        return view('arqueo.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //activo inactivo
        $id = DB::table('arqueo')->insert(
            [
                'monto_cheque' => request()->monto_cheque,
                'fecha' => request()->fecha,
                'apertura_cierre_id' => request()->apertura, 'estado' => "A",
                '100mil' => request()->cienmill, '50mil' => request()->cincuentamil,
                '20mil' => request()->veintemil, '10mil' => request()->diezmil,
                '1mil' => request()->mil, '500' => request()->quinientos,
                '100' => request()->cien, '50' => request()->cincuenta,
                '5mil' => request()->cincomil, '2mil' => request()->dosmil,
                'monto_efectivo' => str_replace('.', '', request()->m_efectivo)
            ]
        );

        return redirect()->route('arqueo.index');
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

        $arqueos = DB::table('arqueo')
            ->select('estadia_habitaciones.*', 'ha.descripcion')
            ->leftJoin('habitaciones as ha', 'estadia_habitaciones.id_habitacion', '=', 'ha.id')
            ->where('id_estadia', '=', $id)
            ->get();

        //dd($reservas);
        return view('estadia.update', [
            'arqueos' => $arqueos
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
        DB::table('arqueo')
            ->where('id', $id)
            ->update(
                [
                    'estado' => "I"
                ]
            );
        return redirect()->route('arqueo.index');
    }

    public function estimado(Request $request)
    {

        $cobroe = DB::table('cobros')
            ->select(DB::raw('(SUM(cf.`monto`) + ac.`saldo_inicial`)-SUM(cf.`vuelto`) AS saldo'))
            ->where([
                ['cobros.apertura_cierre_id', '=', $request->id],
                ['cobros.estado', '=', 'C'],
            ])
            ->leftJoin('cobro_efectivo as cf', 'cobros.id', '=', 'cf.cobrosid')
            ->leftJoin('apertura_cierre as ac', 'cobros.apertura_cierre_id', '=', 'ac.id')
            ->get();

        $cobroc = DB::table('cobros')
            ->select(DB::raw('SUM(cc.monto) as cheque'))
            ->where([
                ['cobros.apertura_cierre_id', '=', $request->id],
                ['cobros.estado', '=', 'C'],
            ])
            ->leftJoin('cobro_cheque as cc', 'cobros.id', '=', 'cc.cobrosid')
            ->get();


        //dd($tarifa);

        //return view('estadia.create', ['tarifas' => $tarifa]);

        return [
            'cobroe' => $cobroe,
            'cobroc' => $cobroc
        ];
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
