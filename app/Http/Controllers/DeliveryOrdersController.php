<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Ship_Order;
use App\Zone;
use App\Status;
use Route;
use Redirect;
use Exception;
use Log;

class DeliveryOrdersController extends Controller
{
    
    const LOG_LABEL = "[ROUTING APP]";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function set_status(Request $request) {
        $request->user()->authorizeRoles(['delivery','admin']);

        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request to set order status received: ".$request->getContent()); 
    
        $inputs = json_decode($request->getContent(), true);
        $id_order = $inputs['id'];
        $id_status = $inputs['id_status'];

        try {
            $order = Ship_Order::findOrFail($id_order);
            $order->status = $id_status;
            $order->save();
            Log::info(self::LOG_LABEL." Order (id = $order->id) updated."); 
            return response()->json('¡Se cambió el estado correctamente!', 200);
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Order (id = $id_order) could not be updated");
            return; 
        }
    }

    public function list_orders(Request $request) {

        $request->user()->authorizeRoles('delivery');

        $id_zone = Route::current()->parameter('id_zone');

        $query_assigned_route = Ship_Order::where('status', '<', 4);
        if ($id_zone != null) 
            $query_assigned_route->where('zone', $id_zone);
        
        $query_unassigned_route = clone $query_assigned_route;

        $query_assigned_route->where('is_assigned', 1);
        $orders_unassigned = $query_unassigned_route->where('is_assigned', 0)
                                                    ->join('users', 'ship__orders.id_user', '=', 'users.id')
                                                    ->select('ship__orders.*', 'users.name as user_name')
                                                    ->get();

        
        $status = Status::get();
        $zones = Zone::get();
        $ship_orders = $query_assigned_route->orderBy('route_order', 'asc')
                                                        ->join('users', 'ship__orders.id_user', '=', 'users.id')
                                                        ->select('ship__orders.*', 'users.name as user_name')
                                                        ->get();

        return view('delivery.list_orders', compact('ship_orders', 'zones', 'id_zone', 'status', 'orders_unassigned'));
    }

    public function complete_order(Request $request) {
        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request to complete order received: ".$request->getContent()); 
    
        $inputs = json_decode($request->getContent(), true);
        $id = $inputs['id'];

        try {
            $ship_order = Ship_Order::findOrFail($id);
            Log::info(self::LOG_LABEL." Ship order found. ".json_encode($ship_order)); 
            $ship_order->status = 4;
            $ship_order->save();
            Log::info(self::LOG_LABEL." Ship order (id = $id) completed: "); 
            return ['msg' => '¡La orden se completó correctamente!', 'status' => 'success', 'code' => 200 ];
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Ship order (id = $id) could not be completed");
            //TODO CHANGE CODE RESPONSE
            return ['msg' => 'La orden no se pudo completar', 'status' => 'error', 'code' => 200 ];
        }
        
        Log::error(self::LOG_LABEL." ERROR. There is not order with id = $id. Could not be completed"); 
        //TODO ERROR CODE
        return ['msg' => 'Hubo un problema al buscar la orden la base de datos', 'status' => 'error', 'code' => 200 ];

    
    }
}
