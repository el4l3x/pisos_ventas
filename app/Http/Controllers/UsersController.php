<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Piso_venta;
use App\Sincronizacion;
use App\Vaciar_caja;
use DB;

class UsersController extends Controller
{
    //
    public function get_id()//ID PISO DE VENTA
    {

    	$usuario = Auth::user()->piso_venta->id;

    	return response()->json($usuario);
    }

    public function get_piso_venta()
    {
    	$usuario = Auth::user()->piso_venta->id;

    	$piso_venta = Piso_venta::where('user_id', $usuario)->first();

    	$sincronizacion = Sincronizacion::where('piso_venta_id', $usuario)->orderBy('id', 'desc')->first();

        $caja = Vaciar_caja::where('piso_venta_id', $usuario)->orderBy('id', 'desc')->first();

    	return response()->json(['piso_venta' => $piso_venta, 'sincronizacion' => $sincronizacion, 'caja' => $caja]);
    }

    public function vaciar_caja()
    {   
        try{

            DB::beginTransaction();

            $usuario = Auth::user()->piso_venta->id;

            $piso_venta = Piso_venta::findOrFail($usuario);

            $vaciar_caja = new Vaciar_caja();
            $vaciar_caja->piso_venta_id = $usuario;
            $vaciar_caja->monto = $piso_venta->dinero;
            $vaciar_caja->save();
            $vaciar_caja->id_extra = $vaciar_caja->id;
            $vaciar_caja->save();

            $piso_venta->dinero = 0;
            $piso_venta->save();

            DB::commit();

        }catch(Exception $e){

            DB::rollback();
            return response()->json($e);
        }

        $caja = Vaciar_caja::where('piso_venta_id', $usuario)->orderBy('id', 'desc')->first();

        return response()->json(['piso_venta' => $piso_venta, 'caja' => $caja]);
    }

}
