<?php

namespace App\Http\Controllers\Tpv;

use App\Http\Controllers\Controller;
use App\Models\Tpv\Caja;
use App\Models\Tpv\Category;
use App\Models\Tpv\Mesa;
use App\Models\Tpv\Order;
use App\Models\Tpv\OrderItem;
use App\Models\Tpv\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TpvController extends Controller
{

    public function distribucionMesas($salonId){
        $mesas = Mesa::where('salon_id',$salonId)->get();
        return view('tpv.index',compact('mesas','salonId'));
    }

    public function getMesas(Request $request){
        $mesas = Mesa::where('salon_id',$request->salon_id)->get();

        return response()->json($mesas);

    }

    public function updateMesa(Request $request){
        //dd($request->all());
        $mesa = Mesa::find($request->id);
        if (isset($mesa)){
            $mesa->posicion_x = $request->posicion_x;
            $mesa->posicion_y = $request->posicion_y;
            $mesa->save();
            return response()->json([
                'success' => true,
                'mensaje' =>'Posición actualizada']);
        }else{
            return response()->json([
                'success' => false,
                'mensaje' =>'Mesa no actualizada']);
        }

    }

    public function addMesa(Request $request){
        $nombre = $request->nombre;
        $salon_id =$request->salon_id;
        if (isset($nombre)){
            $mesa = new Mesa;
            $mesa->nombre = $nombre;
            $mesa->posicion_x = 10;
            $mesa->posicion_y = 10;
            $mesa->salon_id = $salon_id;
            $mesa->save();
            return response()->json([
                'success' => true,
                'mensaje' =>'Mesa creada ']);
        }else{
            return response()->json([
                'success' => false,
                'mensaje' =>'Mesa no creada']);
        }
    }

    public function mapa(){
        $mesas = Mesa::where('salon_id',Auth::user()->salon_id)->get();

        return view('tpv.mapa',compact('mesas'));
    }

    public function edit($id){

        $order = Order::find($id);
        $caja = Caja::where('cierre',null)->get()->first();
        // Obtener categorías activas
        $categories = Category::where('inactive', 0)->get();
        // Obtener productos activos
        $products = Product::where('inactive', 0)->get();

        return view('dashboards.dashboard_tpv', compact('categories', 'products','order','caja'));
    }

    public function mesa($id){
        $caja = Caja::where('cierre',null)->get()->first();

        $order = Order::where('mesa_id',$id)->where('status',1)->first();
         // Obtener categorías activas
         $categories = Category::where('inactive', 0)->get();
         // Obtener productos activos
         $products = Product::where('inactive', 0)->get();

        if (isset($order)){
            return view('dashboards.dashboard_tpv', compact('categories', 'products','order','caja'));

        }else{
            $today = Carbon::today();
            $nextNumber = Order::whereDate('created_at', $today)->max('numero') + 1;

            $order = Order::create([
                'user_id' => Auth::user()->id,
                'numero' => $nextNumber, // Número incremental
                'status' => 1, // Estado inicial
                'mesa_id' => $id, // Estado inicial
                'total' => 0 // Total inicial
            ]);
            return view('dashboards.dashboard_tpv', compact('categories', 'products','order','caja'));
        }
    }

    public function categorias(){
        return Category::with('products')->where('inactive', 0)->get();
    }

    public function productos($categoryId){
        return Product::where('category_id', $categoryId)->where('inactive', 0)->get();
    }

    public function orders(){
        return Order::with('items.product')->where('status', 'open')->get();

    }


    public function addItem(Request $request){
        $order = Order::find($request->order_id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        $item = OrderItem::create(
            [
                'order_id' => $order->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $request->price
            ]
        );

        $order->update(['total' => $order->items->sum(fn($i) => $i->quantity * $i->price)]);

        $items = $order->items()->with('product')->get();

        return response()->json(['order' => true, 'items' => $items], 200);
    }

    public function removeItem(Request $request){
        $item = OrderItem::find($request->item_id);

        if ($item) {
            $order = $item->order;
            $item->delete();
            $order->update(['total' => $order->items->sum(fn($i) => $i->quantity * $i->price)]);
        }

        $items = $order->items()->with('product')->get();

        return response()->json(['order' => true, 'items' => $items], 200);
    }

    public  function delete(Request $request){
        $order = Order::find($request->id);
        if(!$order){
            return response()->json(['status' => false,'mensaje' => 'Cuenta no encontrada'], 404);
        }
        $order->items()->delete();
        $order->delete();
        return response()->json(['status' => true , 'mensaje' => 'Cuenta Borrada con exito'], 200);
    }

    public function checkout(Request $request){
        try {
            $salon_id = Auth::user()->salon_id;
            $caja = Caja::where('salon_id',$salon_id)->where('cierre',null)->get()->first();
            $order = Order::find($request->order_id);

            if (!$order) {
            return response()->json(['error' => 'Invalid order'], 400);
            }
            if($order->caja_id != $caja->id){
                $order->update(['caja_id' => $caja->id]);
            }

            $order->update([
            'status' => '2',
            'total' => $order->items->sum(fn($i) => $i->quantity * $i->price)
            ]);


            return response()->json(['status' => true], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => false ,'error' => 'An error occurred during checkout', 'message' => $e->getMessage()], 500);
        }
    }

    public function getData(){
        $salonId = Auth::user()->salon_id;
        $mesas = Mesa::where('salon_id',$salonId)->get();
        $ordenes = Order::where('status', 1)->whereHas('items')->wherenull('mesa_id')->get();
        return response()->json([
            'mesas' => $mesas,
            'ordenes' => $ordenes
        ]);

    }

    public function deleteMesa($id){
        $mesa = Mesa::find($id);
        if (isset($mesa)){
            $mesa->delete();
            return response()->json([
                'success' => true,
                'mensaje' =>'Mesa eliminada']);
        }else{
            return response()->json([
                'success' => false,
                'mensaje' =>'Mesa no eliminada']);
        }
    }

    public function AbrirCaja(Request $request){
        $salonid = auth()->user()->salon_id;
        $caja = Caja::whereNull('cierre')->where('salon_id',$salonid)->get()->first();
        if (isset($caja)){
            return redirect()->back()->with('toast',[
                'icon' => 'error',
                'mensaje' =>'Ya hay una caja abierta'
            ]);
        }else{
            $request->validate([
                'apertura' => 'required'
            ],[
                'apertura.required' => 'Cantidad de apertura es obligatorio'
            ])
            ;
            $caja = new Caja;
            $caja->apertura = $request->apertura;
            $caja->salon_id = $salonid;
            $caja->save();
            return redirect()->back()->with('toast',[
                'icon' => 'success',
                'mensaje' =>'Caja abierta'
            ]);
        }
    }

    public function CerraCaja(Request $request){
        $salonid = auth()->user()->salon_id;
        $caja = Caja::whereNull('cierre')->where('salon_id',$salonid)->get()->first();
        if (isset($caja)){
            $request->validate([
                'cierre' => 'required',
                'cambio' => 'required'
            ],[
                'cierre.required' => 'Cantidad de cierre es obligatorio',
                'cambio.required' => 'Cambio dejado es obligatorio',
            ]);

            $previsto = $caja->apertura + $caja->orders()->sum('total');
            $caja->previsto = $previsto;
            $caja->diferencia =  $request->cierre - $caja->previsto;
            $caja->cierre = $request->cierre;
            $caja->cambio = $request->cambio;
            $caja->save();
            return redirect()->back()->with('toast',[
                'icon' => 'success',
                'mensaje' =>'Caja Cerrada'
            ]);
        }else{
            return redirect()->back()->with('toast',[
                'icon' => 'error',
                'mensaje' =>'No hay una caja abierta'
            ]);
        }
    }

    public function setNombre(Request $request){
        $order = Order::find($request->id);
        if (isset($order)){
            $order->nombre = $request->nombre;
            $order->save();
            return response()->json([
                'success' => true,
                'mensaje' =>'Nombre actualizado']);
        }else{
            return response()->json([
                'success' => false,
                'mensaje' =>'Nombre no actualizado']);
        }
    }

}
