<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Piso_venta;
use App\Venta;
use App\Despacho;
use Carbon\Carbon;
use App\Inventario_piso_venta;
use App\Vaciar_caja;

class PisoVentasController extends Controller
{
    //
    public function index()
    {
    	return view('admin.piso_ventas');
    }

    public function get_piso_ventas(){
    	$piso_ventas = Piso_venta::all();

    	return response()->json($piso_ventas);
    }

    public function resumen()// funcion carga el resumen de ventas compras despachos de la vista home
    {
        $usuario = Auth::user()->piso_venta->id;
    	$date = Carbon::now();
    	$mes = $date->month;

    	$ventas = Venta::where('piso_venta_id', $usuario)->where('type', 1)->whereMonth('created_at', $mes)->count();
    	$compras = Venta::where('piso_venta_id', $usuario)->where('type', 2)->whereMonth('created_at', $mes)->count();
    	$despachos = Despacho::where('piso_venta_id', $usuario)->where('type', 1)->whereMonth('created_at', $mes)->count();
    	$retiros = Despacho::where('piso_venta_id', $usuario)->where('type', 2)->whereMonth('created_at', $mes)->count();

    	return response()->json([
    							'ventas' => $ventas,
    							'compras' => $compras,
    							'despachos' => $despachos,
    							'retiros' => $retiros
    							]);
    }

    public function ventas_compras(Request $request)
    {
        $usuario = Auth::user()->piso_venta->id;

    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {

    		$fecha_i = new Carbon($request->fecha_i);
    		$fecha_f = new Carbon($request->fecha_f);

    		$ventas = Venta::with('detalle')->where('piso_venta_id', $usuario)->whereDate('created_at','>=', $fecha_i)->whereDate('created_at','<=', $fecha_f)->orderBy('id', 'desc')->paginate(1);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	$ventas = Venta::with('detalle')->where('piso_venta_id', $usuario)->whereMonth('created_at', $mes)->orderBy('id', 'desc')->paginate(1);
	    }

    	return response()->json($ventas);
    }

    public function despachos_retiros($id, Request $request)
    {
    	if ($request->fecha_i != 0 && $request->fecha_f != 0) {

    		$fecha_i = new Carbon($request->fecha_i);
    		$fecha_f = new Carbon($request->fecha_f);

    		$despachos = Despacho::with('productos')->where('piso_venta_id', $id)->whereDate('created_at','>=', $fecha_i)->whereDate('created_at','<=', $fecha_f)->orderBy('id', 'desc')->paginate(10);
    	}else{

	    	$date = Carbon::now();
	    	$mes = $date->month;

	    	$despachos = Despacho::with('productos')->where('piso_venta_id', $id)->whereMonth('created_at', $mes)->orderBy('id', 'desc')->paginate(10);
    	}
    	return response()->json($despachos);
    }

    public function productos_piso_venta($id)
    {

    	$productos = Inventario_piso_venta::with('inventario.precio')->where('piso_venta_id', $id)->orderBy('cantidad', 'desc')->paginate(10);

    	return response()->json($productos);
    }

    public function ultima_vaciada_caja_local($id)//WEB Y LOCAL
    {
        $caja = Vaciar_caja::where('id_extra', '>' ,$id)->get();

        return response()->json($caja);
    }
}
