<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log;

class DeliveryRoutesController extends Controller
{
    const LOG_LABEL = "[ROUTING APP]";

    public function index (Request $request) {
        return view('delivery.routes');
    }
}
