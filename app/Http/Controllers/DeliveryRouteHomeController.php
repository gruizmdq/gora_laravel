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

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {   
        //Auth::id()
        $request->user()->authorizeRoles('delivery');
        $ship_orders = Ship_Order::where('status', '<', 4)
                                    ->orderBy('ship__orders.id', 'asc')
                                    ->limit(10)
                                    ->get();

        $ship_orders_done = Ship_Order::where('status', 4)
                                    ->orderBy('id', 'desc')
                                    ->limit(20)
                                    ->get();

        $zones = Zone::get();
        $status = Status::get();
        return view('delivery.home', compact('ship_orders', 'ship_orders_done', 'zones', 'status'));
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

    public function save_ship_order(Request $request) {

        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request received: ".$request->getContent()); 
        
        $name = $request->input('name');
        $product = $request->input('product');
        $street = $request->input('street');
        $code = $request->input('code');
        $number = $request->input('number');
        $phone = $request->input('phone');;
        $price = $request->input('price');
        $description = $request->input('description');

        if (!$this->code_is_valid($code)) {
            //LANZAR EXCEPCION VER QUE ONDA WACHING
            Log::info(self::LOG_LABEL." ERROR. There is not a valid street with this code: $code $street)");
            return back()->with('error', 'Error. Hubo un problema al guardar la venta :(. La dirección ingresada parece no ser válida.');
        }

        Log::info(self::LOG_LABEL." Code validation success.");

        Log::info(self::LOG_LABEL." Start getting coordenates from address..."); 
        $coords = $this->get_street_coords($code, $number);

        if (!$this->coords_are_valid($coords)) {
            //LANZAR EXCEPCION
            Log::error(self::LOG_LABEL." ERROR. Coords from {$code} {$street} {$number} are invalid.");
            return back()->with('error', 'Error. Hubo un problema al guardar la venta :(. La dirección ingresada parece no ser válida.');
        }

        Log::info(self::LOG_LABEL." Getting neighborhood from address..."); 
        $neighborhood = $this->get_neighborhood($coords['lat'], $coords['lng']);
        if (!$neighborhood) {
             //LANZAR EXCEPCION. Podria ser indicador de que es invalida la direccion
            Log::error(self::LOG_LABEL." WARN. There is no neighborhood for {$street} {$number}");
            return back()->with('error', 'Error. Hubo un problema al guardar la venta :(. Verificar si la dirección es correcta.');
        }


        $zone = Neighborhood::where('code', $neighborhood)->value('zone');
        Log::info(self::LOG_LABEL." ZONE  $zone"); 

        
        try {
            $ship_order = new Ship_Order();
            $ship_order->id_user = Auth::id();
            $ship_order->product = $product;
            $ship_order->name = $name;
            $ship_order->street = $street;
            $ship_order->number = $number;
            $ship_order->code = $code;
            $ship_order->lat = $coords['lat'];
            $ship_order->lng = $coords['lng'];
            $ship_order->phone = $phone;
            $ship_order->price = $price;
            $ship_order->description = $description;
            $ship_order->status = 0;
            $ship_order->is_assigned = 0;
            $ship_order->zone = $zone;
            $ship_order->neighborhood = $neighborhood;
            $ship_order->save();
            Log::info(self::LOG_LABEL." Ship order save success.");
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL."ERROR. Save ship order failed: $e");
            return back()->with('error', 'Error. Hubo un problema al guardar la venta :(');
        }
        
        Log::info(self::LOG_LABEL." Process ended.");
        Log::info("************************************");

        if ($zone == 0)
            return back()->with('warn', 'La venta se cargó correctamente, pero no tiene ninguna zona asignada. Verificar si la dirección es correcta o asignar la zona manualmente.');
        return back()->with('success', '¡Venta cargada correctamente!');
        
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


    public function update_address(Request $request) {

        $request->user()->authorizeRoles('delivery');

        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request to set update order received: ".$request->getContent()); 
    
        $inputs = json_decode($request->getContent(), true);
        $id_order = $inputs['id'];
        $street = $request->has('street_name') ? $inputs['street_name'] : null;
        $code = $request->has('street_code') ? $inputs['street_code'] : null;
        $number = $request->has('number') ? $inputs['number'] : null;

        try{
            $order = Ship_Order::findOrFail($id_order);
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Order (id = $id_order) no existe");
            return response()->json('Hubo un error al buscar la orden en la base de datos.');
        }
        
        if ($code != null ) {
            if (!$this->code_is_valid($code)) {
                //LANZAR EXCEPCION VER QUE ONDA WACHING
                Log::info(self::LOG_LABEL." ERROR. There is not a valid street with this code: $code $street)");
                return response()->json('Error. Hubo un problema al guardar la venta :(. La dirección ingresada parece no ser válida.');
            }
            Log::info(self::LOG_LABEL." Code validation success.");
        }

        if ($number == null)
            $number = $order->number;
        if ($street == null)
            $street = $order->street;
        if ($code == null)
            $code = $order->code;

        Log::info(self::LOG_LABEL." Start getting coordenates from address..."); 
        $coords = $this->get_street_coords($code, $number);

        if (!$this->coords_are_valid($coords)) {
            //LANZAR EXCEPCION
            Log::error(self::LOG_LABEL." ERROR. Coords from {$code} {$street} {$number} are invalid.");
            return response()->json('Error. Hubo un problema al guardar la venta :(. La dirección ingresada parece no ser válida.');
        }

        Log::info(self::LOG_LABEL." Getting neighborhood from address..."); 
        $neighborhood = $this->get_neighborhood($coords['lat'], $coords['lng']);
        if (!$neighborhood) {
             //LANZAR EXCEPCION. Podria ser indicador de que es invalida la direccion
            Log::error(self::LOG_LABEL." WARN. There is no neighborhood for {$street} {$number}");
            return response()->json('Error. Hubo un problema al guardar la venta :(. Verificar si la dirección es correcta.');
        }


        $zone = Neighborhood::where('code', $neighborhood)->value('zone');
        Log::info(self::LOG_LABEL." ZONE  $zone"); 


        try {
            $order = Ship_Order::findOrFail($id_order);
            $order->street = $street;
            $order->number = $number;
            $order->code = $code;
            $order->lat = $coords['lat'];
            $order->lng = $coords['lng'];
            $order->is_assigned = 0;
            $order->zone = $zone;
            $order->neighborhood = $neighborhood;
            $order->save();

            Log::info(self::LOG_LABEL." Order (id = $order->id) updated."); 
        }
        catch (Exception $e) {
            Log::error(self::LOG_LABEL." ERROR. Order (id = $id_order) could not be updated");
            return response()->json('No se pudo actualizar.');
        }
        
        Log::info(self::LOG_LABEL." Process ended.");
        Log::info("************************************");

        if ($zone == 0)
            return ['msg' => 'La venta se cargó correctamente, pero no tiene ninguna zona asignada. Verificar si la dirección es correcta o asignar la zona manualmente.', 'order' => $order];
        
        return ['msg' => '¡Dirección actualizada correctamente!', 'order' => $order];
        
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
