<?php

namespace App\Http\Controllers;

use App\estadia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class menuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function menu()
    {
        //$estadia = estadia::all();
        //return view('estadia.index', ['estadias' => $estadia]);
       /* $inventario = DB::table('inventario')
            ->select(
                'inventario.*',
                'ar.nombre as nombre',
                'ar.descripcion as descripcion',
                'ca.descripcion as categoria'
            )
            ->leftJoin('articulo as ar', 'inventario.articulo_codigo', '=', 'ar.codigo')
            ->leftJoin('categoria as ca', 'ar.categoria_id', '=', 'ca.id')
            ->get();*/

        // dd($estadia);

        //return view('ajuste.index', ['inventarios' => $inventario]);

        $user = Auth::user();
        //dump($user->name);
        $id = Auth::id();
        //dump($id);
        

        return view('menu',['nivel'=>$user->nivel, 'usuario'=>$user->name]);
    }

    public function logout(){
        Auth::logout();
        return view('auth.login');
    }

}