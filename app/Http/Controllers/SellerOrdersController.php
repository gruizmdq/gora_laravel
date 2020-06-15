<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Ship_Order;
use App\Status;
use Route;
use Redirect;
use Exception;
use Log;

class SellerOrdersController extends Controller
{
    const LOG_LABEL = "[ROUTING APP]";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list_orders(Request $request) {   

        $request->user()->authorizeRoles('seller');
        $offset = Route::current()->parameter('offset') ? Route::current()->parameter('offset') : 0;
        
        $statuses = Status::get();
        $total_orders = Ship_Order::where('id_user', Auth::id())->count();
        $orders = Ship_Order::where('id_user', Auth::id())
                                    ->leftJoin('zones', 'ship__orders.zone', '=', 'zones.id')
                                    ->join('statuses', 'ship__orders.status', '=', 'statuses.id')
                                    ->select('ship__orders.*', 'zones.name as zone_name', 'statuses.name as status_name', 'statuses.color as status_color')
                                    ->orderBy('ship__orders.id', 'desc')
                                    //->offset($offset)
                                    ->limit(100)
                                    ->get();
                                    
        
        return view('seller.list_orders', compact('orders', 'statuses', 'total_orders'));
    }
}
