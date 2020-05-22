<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Order;
use App\Menu;
use App\Http\Controllers\UserController;
use DateTime;

class CompanyAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $request->user()->authorizeRoles(['company_admin']);
        $orders = $this->get_orders($request->user()->id_empresa);
        return view('company_admin.home', compact('orders'));
    }

    public function add_user(Request $request)
    {
        $request->user()->authorizeRoles(['company_admin']);

        if (!$request->isMethod('post'))
            return view('company_admin.add_user');

        $inputs = $request->input();
        $inputs['id_empresa'] = $request->user()->id_empresa;
        $inputs['is_admin'] = false;

        UserController::create($inputs);
        
        return back()->with('message', 'Usuario agregado correctamente!');

    }

    public function show_users(Request $request, $id=null)
    {
        $request->user()->authorizeRoles(['company_admin']);

        if ($id != null){
            $user = User::where([
                                ['id_empresa', $request->user()->id_empresa],
                                ['id', $id]
                    ])->first();
            return view('company_admin.show_users_single', compact('user'));           
        }
        $users = User::where('id_empresa', $request->user()->id_empresa)->get();
        
        return view('company_admin.show_users', compact('users'));

    }

    
    private function get_orders($id_empresa, $from = null, $to = 5)
    {
        $users = User::where('id_empresa', $id_empresa)->get();
        if ($from == null)
            $from = (new DateTime("NOW"))->modify(- date('w')." days");
        $orders = [];
        foreach ($users as $user) {
            $date = clone $from;
            for ($i=0; $i<5; $i++){
                $order = Order::where([
                                    ['orders.date', $date->modify("1 days")->format('Y-m-d')],
                                    ['orders.id_user', $user->id]
                                ])
                            ->first();

                if ($order == null){
                    $order = new Order();
                    $order->id_user = $user->id;
                }
                $order->user = $user->name;
                if ($order->id == null)
                        $orders[$date->format('Y-m-d')]['not_done'][] = $order;
                else
                    $orders[$date->format('Y-m-d')]['done'][] = $order;
            }
        }

        
        return $orders;
    }

}
