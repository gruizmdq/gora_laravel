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

class DeliveryRoutesController extends Controller
{
    const LOG_LABEL = "[ROUTING APP]";

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index (Request $request) {
        $request->user()->authorizeRoles('delivery');
        $id_zone = Route::current()->parameter('id_zone');

        $orders = Ship_Order::where([
                                    ['status', '<', 4],
                                    ['is_assigned', 1], 
                                    ['zone', $id_zone]
                                    ])
                            ->orderBy('route_order')
                            ->get();

        $zones = Zone::get();
            
        return view('delivery.routes', compact('zones', 'orders'));
    }
}
