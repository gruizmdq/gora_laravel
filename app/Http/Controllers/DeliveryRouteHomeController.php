<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Street;
use App\Ship_Order;
use App\Neighborhood;
use App\Zone;
use Route;
use Redirect;
use Exception;
use Log;

class DeliveryRouteHomeController extends Controller
{
    const ENDPOINT = "http://gis.mardelplata.gob.ar/opendata/ws.php?";
    const POPULATION_SIZE = 500;
    const MAX_ITERATIONS = 500;
    const LOG_LABEL = "[ROUTING APP]";

    var $street_codes = [];
    var $data = [];
    var $distance_matrix = [];

    //Order represent initial order of solution. NO USA LA INICIAL LOCATION 
    var $order = [];
    var $population = [];
    var $fitness = [];

    var $current_best_order = [];
    var $best_order_ever = [];
    var $record_distance_ever = PHP_FLOAT_MAX;

    public function index(Request $request)
    {   
        //$request->user()->authorizeRoles(['admin', 'user', 'company_admin']);
        //Auth::id()
        $ship_orders = Ship_Order::where([
                                        ['id_user', 1],
                                        ['status', 0]
                                    ])
                                    ->join('zones', 'ship__orders.zone', '=', 'zones.id')
                                    ->select('ship__orders.*', 'zones.name as zone_name')
                                    ->orderBy('ship__orders.id', 'asc')
                                    ->limit(10)
                                    ->get();

        $ship_orders_done = Ship_Order::where([
                                        ['id_user', 1],
                                        ['status', 1]
                                    ])->orderBy('id', 'desc')
                                    ->limit(20)
                                    ->get();

        return view('delivery.home', compact('ship_orders', 'ship_orders_done'));
    }

    public function list_orders(Request $request) {
        $id_zone = Route::current()->parameter('id_zone');
        if ($id_zone == null)
            $id_zone = 0;

        //TODO CHECK USER!

        $query = Ship_Order::where([
                        ['id_user', 1],
                        ['status', 0]
                    ]);

        if ($id_zone > 0) 
            $query->where('zone', $id_zone);
        
        $zone_name = "Todos";
        $zone = Zone::find($id_zone);
        if ($zone != null)
            $zone_name = $zone->name;
        
        $zones = Zone::get();
        $ship_orders[$zone_name] = $query->orderBy('route_order', 'asc')->get();

        return view('delivery.list_orders', compact('ship_orders', 'zones', 'id_zone'));
    }

    public function set_zone_order(Request $request)
    {  
        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request to set order zone received: ".$request->getContent()); 
    
        $inputs = json_decode($request->getContent(), true);
        $id_order = $inputs['id'];
        $id_zone = $inputs['id_zone'];

        try {
            $order = Ship_Order::findOrFail($id_order);
            $order->zone = $id_zone;
            $order->save();
            Log::info(self::LOG_LABEL." Order (id = $order->id) updated."); 
            return response()->json('¡Se cambió la zona correctamente!', 200);
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Order (id = $id_order) could not be updated");
            return; 
        }
        
    }

    public function delete_ship_order(Request $request) {
        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request to delete order received: ".$request->getContent()); 
    
        $inputs = json_decode($request->getContent(), true);
        $id = $inputs['id'];

        try {
            $ship_order = Ship_Order::findOrFail($id);
            Log::info(self::LOG_LABEL." Ship order found. ".json_encode($ship_order)); 
            $ship_order->delete();
            Log::info(self::LOG_LABEL." Ship order (id = $id) deleted: "); 
            return response()->json($id, 200);
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Ship order (id = $id) could not be deleted");
            return; 
        }
        
        Log::error(self::LOG_LABEL." ERROR. There is not order with id = $id. Could not be deleted"); 
        return;
    
    }

    public function set_order_done(Request $request) {
        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request to complete order received: ".$request->getContent()); 
    
        $inputs = json_decode($request->getContent(), true);
        $id = $inputs['id'];

        try {
            $ship_order = Ship_Order::findOrFail($id);
            Log::info(self::LOG_LABEL." Ship order found. ".json_encode($ship_order)); 
            $ship_order->status = 1;
            $ship_order->save();
            Log::info(self::LOG_LABEL." Ship order (id = $id) completed: "); 
            return response()->json($id, 200);
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Ship order (id = $id) could not be completed");
            return; 
        }
        
        Log::error(self::LOG_LABEL." ERROR. There is not order with id = $id. Could not be completed"); 
        return;
    
    }

    public function save_ship_order(Request $request) {

        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request received: ".$request->getContent()); 
        
        $name = $request->input('street');
        $code = $request->input('code');
        $number = $request->input('number');
        $phone = $request->input('phone');;
        $price = $request->input('price');
        $description = $request->input('description');

        if (!$this->code_is_valid($code)) {
            //LANZAR EXCEPCION VER QUE ONDA WACHING
            Log::info(self::LOG_LABEL." ERROR. There is not a valid street with this code: $code $name)");
            return;
        }
        Log::info(self::LOG_LABEL." Code validation success.");

        Log::info(self::LOG_LABEL." Start getting coordenates from address..."); 
        $coords = $this->get_street_coords($code, $number);

        if (!$this->coords_are_valid($coords)) {
            //LANZAR EXCEPCION
            Log::error(self::LOG_LABEL." ERROR. Coords from {$code} {$name} {$number} are invalid.");
            return; 
        }

        Log::info(self::LOG_LABEL." Getting neighborhood from address..."); 
        $neighborhood = $this->get_neighborhood($coords['lat'], $coords['lng']);
        if (!$neighborhood) {
             //LANZAR EXCEPCION. Podria ser indicador de que es invalida la direccion
             Log::error(self::LOG_LABEL." WARN. There is no neighborhood for {$name} {$number}");
        }


        $zone = Neighborhood::where('code', $neighborhood)->value('zone');

        Log::info(self::LOG_LABEL." ZONE  $zone"); 


        try {
            $ship_order = new Ship_Order();
            $ship_order->id_user = 1;
            $ship_order->street = $name;
            $ship_order->number = $number;
            $ship_order->code = $code;
            $ship_order->lat = $coords['lat'];
            $ship_order->lng = $coords['lng'];
            $ship_order->phone = $phone;
            $ship_order->price = $price;
            $ship_order->description = $description;
            $ship_order->status = 0;
            $ship_order->zone = $zone;
            $ship_order->neighborhood = $neighborhood;
            Log::info(self::LOG_LABEL." Ship order save success.");
            $ship_order->save();
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL."ERROR. Save ship order failed.");
            return;
        }
        
        Log::info(self::LOG_LABEL." Process ended.");
        Log::info("************************************");

        return Redirect::action('DeliveryRouteController@index');
        
    }

    public function update_ship_order(Request $request) {
        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." Update ship order request received: ".$request->getContent()); 

        $inputs = json_decode($request->getContent(), true);
        $id = $inputs['id'];
        $number = $inputs['number'];
        $price = (float) $inputs['price'];
        $phone = $inputs['phone'];

        try {

            $ship_order = Ship_Order::findOrFail($id);
            Log::info(self::LOG_LABEL." Ship order found. ".json_encode($ship_order)); 

            // neighborhood and coords change
            if ($ship_order->number != $number) {
                $coords = $this->get_street_coords($ship_order->code, $number);
                $ship_order->neighborhood = $this->get_neighborhood($coords['lat'], $coords['lng']);
                $ship_order->lat = $coords['lat'];
                $ship_order->lng = $coords['lng'];
                $ship_order->number = $number;
            }

            $ship_order->price = $price;
            $ship_order->phone = $phone;
            $ship_order->save();
            Log::info(self::LOG_LABEL." Ship order (id = $id) updated: ".json_encode($ship_order)); 
            return response()->json($ship_order, 200);
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Ship order (id = $id) could not be updated");
            return; 
        }
        
        Log::error(self::LOG_LABEL." ERROR. There is not order with id = $id. Could not be updated"); 
        return;
    }

    
    //TODO: best way is to use guzzle. But having problems to install it on server.
    private function create_request($parameters) {
        $curl = curl_init();
        $url = self::ENDPOINT;

        foreach($parameters as $key => $value) {
            $url = $url.$key.'='.$value.'&';
        }

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
        ]);
       
        Log::info(self::LOG_LABEL." Sending request to $url."); 

        $resp = curl_exec($curl);
        curl_close($curl);

        return $resp;
    }

    private function coords_are_valid($coords) {
        if ($coords['lat'] == 0 || $coords['lng'] == 0)
            return false;
        return true;        
    }
    private function code_is_valid($code) {
        Log::info(self::LOG_LABEL." Validating code: ".$code); 
        return Street::where('code', $code)->first();        
    }

    private function get_neighborhood($lat, $lng) {

        $parameters = [
            'method' => 'rest',
            'endpoint' => 'latlong_barrio',
            'token' => 'wwfe345gQ3ed5T67g4Dase45F6fer',
            'latitud' => $lat,
            'longitud' => $lng,
        ];

        $response = json_decode($this->create_request($parameters), true);

        return $response['codigo'];
    }

    private function get_street_coords($code, $number) {

        $parameters = [
            'method' => 'rest',
            'endpoint' => 'callealtura_coordenada',
            'token' => 'wwfe345gQ3ed5T67g4Dase45F6fer',
            'codigocalle' => $code,
            'altura' => $number,
        ];

        $response = json_decode($this->create_request($parameters), true);

        return $response;
    }
    
}
