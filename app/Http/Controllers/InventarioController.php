<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventario;
use App\Inventario_piso_venta;
use App\Piso_venta;
use App\Venta;
use App\Detalle_venta;
use App\Despacho;
use Illuminate\Support\Facades\Auth;
use App\Inventory;
use App\Despacho_detalle;
use App\Product;
use App\Precio;
use DB;

class InventarioController extends Controller
{
    //
    public function index()
    {

    	return view('inventario.index');
    }

    public function get_inventario(Request $request)
    {
        $usuario = Auth::user()->piso_venta->id;

        //$inventario = Inventario_piso_venta::with(['inventario' => function($inventario){
        //    $inventario->where('name', 'quo');
        //}])->where('piso_venta_id', $usuario)->whereHas('inventario')->get();

        $inventario  = Inventario_piso_venta::with('inventario.precio')->where('piso_venta_id', $usuario)->whereHas('inventario', function($q)use($request){
           // $q->where('name', 'quo');
            if ($request->search != null) {
                
                $q->where('name', 'like', '%'.$request->search.'%');
            }

        })->orderBy('cantidad', 'desc')->paginate(1);
        return response()->json($inventario);
    }

    public function ultimo_inventory()
    {

        $inventory = Inventory::select('id')->orderBy('id', 'desc')->first();

        return response()->json($inventory->id);
    }

    public function get_inventory($id)//WEB
    {

        $inventory = Inventory::with('product')->where('id', '>', $id)->get();

        return response()->json($inventory);
    }

    public function store_inventory(Request $request)
    {
        try{

            DB::beginTransaction();
            foreach ($request->productos as $producto) {
            
                $inventory = new Inventory();
                $inventory->product_name = $producto['product_name'];
                $inventory->description = $producto['description'];
                $inventory->quantity = $producto['quantity'];
                $inventory->unit_type = $producto['unit_type'];
                $inventory->unit_type_menor = $producto['unit_type_menor'];
                $inventory->qty_per_unit = $producto['qty_per_unit'];
                $inventory->status = $producto['status'];
                $inventory->total_qty_prod = $producto['total_qty_prod'];
                $inventory->save();
                if ($producto['product'] != null) {
                
                    //REGISTRAMOS LOS PRECIOS PRODUCT
                    $product = new Product();
                    $product->cost = $producto['product']['cost'];
                    $product->iva_percent = $producto['product']['iva_percent'];
                    $product->retail_margin_gain = $producto['product']['retail_margin_gain'];
                    $product->retail_total_price = $producto['product']['retail_total_price'];
                    $product->retail_iva_amount = $producto['product']['retail_iva_amount'];
                    $product->image = $producto['product']['image'];
                    $product->wholesale_margin_gain = $producto['product']['wholesale_margin_gain'];
                    $product->wholesale_packet_price = $producto['product']['wholesale_packet_price'];
                    $product->wholesale_total_individual_price = $producto['product']['wholesale_total_individual_price'];
                    $product->wholesale_total_packet_price = $producto['product']['wholesale_total_packet_price'];
                    $product->wholesale_iva_amount = $producto['product']['wholesale_iva_amount'];
                    $product->oferta = $producto['product']['oferta'];
                    $product->inventory_id = $producto['product']['inventory_id'];
                    $product->save();
                }
            }
            DB::commit();

            return response()->json(true);
            
        }catch(Exception $e){

            DB::rollback();
            return response()->json($e);
        }
        
        
    }

    public function get_precios_inventory()//WEB
    {
        $inventory = Inventory::with('product')->where('status', 1)->whereHas('product', function($q){ $q->where('id', '!=', null);})->get();

        return response()->json($inventory);
    }

    public function actualizar_precios_inventory(Request $request)
    {
        try{

            DB::beginTransaction();

            foreach ($request->productos as $producto) {
                
                $inventario = Inventario::select('id')->where('inventory_id', $producto['id'])->orderBy('id', 'desc')->first();

                if ($inventario['id'] != null) {
                    $precio = Precio::where('inventario_id', $inventario['id'])->orderBy('id', 'desc')->first();
                    $precio->costo = $producto['product']['cost'];
                    $precio->iva_porc = $producto['product']['iva_percent'];
                    $precio->iva_menor = $producto['product']['retail_iva_amount'];
                    $precio->sub_total_menor = $producto['product']['retail_total_price'] - $producto['product']['retail_iva_amount'];
                    $precio->total_menor = $producto['product']['retail_total_price'];
                    $precio->iva_mayor = $producto['product']['wholesale_iva_amount'] * $producto['qty_per_unit'];
                    $precio->sub_total_mayor = $producto['product']['wholesale_packet_price'];
                    $precio->total_mayor = $precio->sub_total_mayor + $precio->iva_mayor;
                    $precio->oferta = $producto['product']['oferta'];
                    $precio->save();
                }
                
            }

            foreach ($request->precios as $producto) {
                
                $inventario = Inventario::select('id')->where('inventory_id', null)->where('id_extra', $producto['id_extra'])->orderBy('id', 'desc')->first();

                if ($inventario['id'] != null) {
                    $precio = Precio::where('inventario_id', $inventario['id'])->orderBy('id', 'desc')->first();
                    $precio->costo = $producto['precio']['costo'];
                    $precio->iva_porc = $producto['precio']['iva_porc'];
                    $precio->iva_menor = $producto['precio']['iva_menor'];
                    $precio->sub_total_menor = $producto['precio']['sub_total_menor'];
                    $precio->total_menor = $producto['precio']['total_menor'];
                    $precio->save();
                }
                
            }

            DB::commit();

            return response()->json(true);
            
        }catch(Exception $e){

            DB::rollback();
            return response()->json($e);
        }
    }

    public function get_inventory_id()
    {
        $inventario = Inventario::where('inventory_id', null)->get();

        return response()->json($inventario);

    }

    public function actualizar_inventory_id(Request $request)
    {

        foreach ($request->inventario as $valor) {
            
            $inventario = Inventario::where('id_extra', $valor['id_extra'])->orderBy('id', 'desc')->first();
            $inventario->inventory_id = $valor['inventory_id'];
            $inventario->save();
        }

        return response()->json(true);
    }

    public function prueba()
    {

        $inventario = Inventario::with('inventory')->get();
    	
    	return $inventario;
    }

}
