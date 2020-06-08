<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Order;
use App\Menu;
use App\Empresa;
use App\User;
use DateTime;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {   
        $request->user()->authorizeRoles(['admin', 'delivery', 'seller']);
        
        if ($request->user()->hasRole('delivery'))
            return redirect('delivery');

        if ($request->user()->hasRole('seller'))
            return redirect('seller');
        
        if ($request->user()->hasRole('admin'))
            return redirect('admin');

    }

    private function index_user(){
        $next_week_forms = [];
        //TODO: Change this value
        //DOMINGO = 0
        if (date("w") >= 0){
            $next_week_forms = $this->get_orders_forms('+');
        }
        //TODO: fijarse que onda esta manera de pasar parámetros
        $actual_week_forms = $this->get_orders_forms('', -1);
        return view('home', ['next_week_forms' => $next_week_forms, 'actual_week_forms' => $actual_week_forms]);
    }

    public function view_order($id){
        #Get or fail
        $order = get_or_fail();
        $order->menu = Menu::where('id', $order->id_menu)->first();
        
        return view('order_detail', compact('order'));
    }

    public function add_order(Request $request){
        $inputs = $request->input();

        #TODO. No sé por que no pude crear un filter con lamda func. Malaso.
        foreach($this->filter_inputs($inputs) 
                as $key => $value){

            $date = $key;
            $id_menu = $value;
            $comments = $inputs[$key.'-obs'];
            
            $order = Order::where([
                ['date', $date],
                ['id_user',1]
            ])->first();

            if (empty($order)){
                $order = new Order;
                #TODO ver si es necesaria esta reundancia.
                $order->name = Empresa::where('id', auth()->user()->id_empresa)->pluck('name')[0]." - ".auth()->user()->name;
                $order->id_user = auth()->user()->id;
            } 

            $order->date = $date;
            $order->comments = $comments;
            $order->id_menu = $id_menu;

            $order->save();

          }
        return back()->with('message', 'Orders enviadas correctamente!');
    }

    private function filter_inputs($inputs){
        $retorno = [];
        foreach ($inputs as $k => $v){
            if ($k != '_token' && !strpos($k, 'obs'))
                $retorno[$k]=$v;
        };
        return $retorno;
    }

    private function get_orders_forms($operation, $val = 1){

        $next_week_forms = [];
        $days_to_monday = ((7 - date("w"))*$val+1) % 7;
        $date = (new DateTime("NOW"))->modify($operation.$days_to_monday." days");

        #TO compare and block if order it is old.
        $today = (new DateTime("NOW"))->format('Y-m-d');

        for ($i=0; $i<5; $i++){
            
            $menus = Menu::select('id', 'name')
                    ->where('date', $date->format('Y-m-d'))
                    ->orWhere('is_forever', 1)
                    ->get();
                    
            $order = Order::where([
                    ['date', $date->format('Y-m-d')],
                    #TODO: validate user.
                    ['id_user',Auth::id()]
                ])->first();

            if (empty($order)){
                $order = new Order;
                $order->date = $date->format('Y-m-d');
            }
            if ($order->date <= $today)
                $order->status = 'delivered';
                
            $next_week_forms[] = compact('order', 'menus');
            $date->modify("+1 days");
        }
        return $next_week_forms;
    }
}
