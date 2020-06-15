<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Street;
use App\Ship_Order;
use App\Neighborhood;
use App\Zone;
use App\Status;
use Route;
use Redirect;
use Exception;
use Log;


class AdminHomeController extends Controller
{   
    const LOG_LABEL = "[ROUTING APP]";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {

        $request->user()->authorizeRoles('admin');

        $date = Route::current()->parameter('date');

        $orders = Ship_Order::whereDate('ship__orders.created_at', date('Y-m-d',time()-$date*86400))
                            ->leftJoin('zones', 'ship__orders.zone', '=', 'zones.id')
                            ->join('statuses', 'ship__orders.status', '=', 'statuses.id')
                            ->join('users', 'ship__orders.id_user', '=', 'users.id')
                            ->select('users.name as user_name', 'ship__orders.*', 'zones.name as zone_name', 'statuses.name as status_name', 'statuses.color as status_color')
                            ->orderBy('ship__orders.id', 'desc')
                            //->offset($offset)
                            ->get();
        $profit = 0;
        foreach ($orders as $order)
            $profit += $order->price;
        $zones = Zone::get();
        $status = Status::get();
        return view('admin.home', compact('orders','zones','status', 'profit'));
    }

    public function delete_order(Request $request) {

        $request->user()->authorizeRoles('admin');

        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request to delete order received: ".$request->getContent()); 
    
        $inputs = json_decode($request->getContent(), true);
        $id = $inputs['id'];

        try {
            $order = Ship_Order::findOrFail($id);
            $order->delete();
            Log::info(self::LOG_LABEL." Order (id = $order->id) deleted."); 
            return ['msg' => '¡Se borró la venta correctamente!', 'status' => 'success'];
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Order (id = $id) could not be deleted");
            return ['msg' => 'Hubo un error al intentar borrar la venta', 'status' => 'error'];
        }
        
    }
}
