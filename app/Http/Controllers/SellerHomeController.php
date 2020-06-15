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

class SellerHomeController extends Controller
{
    const LOG_LABEL = "[ROUTING APP]";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {   

        $request->user()->authorizeRoles('seller');

        $orders = Ship_Order::where('id_user', Auth::id())
                                    ->leftJoin('zones', 'ship__orders.zone', '=', 'zones.id')
                                    ->join('statuses', 'ship__orders.status', '=', 'statuses.id')
                                    ->select('ship__orders.*', 'zones.name as zone_name', 'statuses.name as status_name', 'statuses.color as status_color')
                                    ->orderBy('ship__orders.id', 'desc')
                                    ->limit(20)
                                    ->get();
                                    
        
        return view('seller.home', compact('orders'));
    }
}
