<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Order_Individual;
use App\Order_Individual_Item;
use App\Menu;
use App\Empresa;
use App\User;
use App\Delivery;

class GoraAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    #HOME ROUTE
    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        return view('admin.home');
    }

    #ADD ORDER ROUTE
    public function add_order(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);

        #GET
        if (!$request->isMethod('post')){
            $deliverys = Delivery::distinct()->get();
            $companies = Empresa::distinct()->get();
            return view('admin.orders.add_order', compact('deliverys', 'companies'));
        }

        #POST
        //TODO CHECK TOKEN!
        $inputs = $request->input();

        try{
            $order = new Order_Individual();
            $order->name = $inputs['name'];
            if ($inputs['delivery'] != null)
                $order->delivery = $inputs['delivery'];
            $order->address = $inputs['address'];
            $order->shift = $inputs['shift'];
            $order->date = $inputs['date'];
            $order->comments = $inputs['comments'];
            $order->save();

            foreach($inputs as $input){
                if (substr($input, 0, 3) === 'qty'){
                    $item = new Order_Individual_Item();
                    $item->id_order = $order->id;
                    $item->qty = $input;
                    //TODO change this id menu. Ahora agarra 1, pero deberia agarrar el id de cada b,s,t, etc
                    $item->id_menu = 1;
                }
            }
        }
        
        catch (Exception $e){
            return back()->with('message', $e);
        }
        return back()->with('message', 'Orden agregado correctamente!');

    }

    #ADD ORDER ROUTE_COMPANY!!
    public function add_order_company(Request $request)
    {
        $request->user()->authorizeRoles(['admin']);
        //TODO CHECK TOKEN!
        $inputs = $request->input();
        
        try{
            $order = new Order();
            $order->name = $inputs['name'];
            if ($inputs['id_delivery'] != null)
                $order->id_delivery = $inputs['id_delivery'];
            $order->id_user = $inputs['id_user'];
            $order->id_menu = $inputs['id_menu'];
            $order->date = $inputs['date'];
            $order->comments = $inputs['comments'];
            $order->save();
        }
        
        catch (Exception $e){
            return back()->with('message', $e);
        }
        return back()->with('message', 'Orden agregado correctamente!');

    }

}
