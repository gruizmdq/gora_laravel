<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ship_Order;
use App\Zone;
use Log;


class DeliveryCreateRouteController extends Controller
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

    public function create_route(Request $request) {

        Log::info("************************************"); 
        Log::info(self::LOG_LABEL." New request to create route received: ".$request->getContent()); 
        
        $inputs = json_decode($request->getContent(), true);
        $id_zone = $inputs['id'];
        $start = $inputs['start'];
        $end = $inputs['end'];
        

        //Checkeo si la zona tiene una ruta calculandose
        $zone = Zone::find($id_zone);
        if ($zone->lock) {
            //TODO LANZAR EXCEPCION?
            Log::error(self::LOG_LABEL." ERROR: One process is calculating the route for the zone with id $id_zone $zone->name"); 
            return;
        }
        else{
            $zone->lock = 1;
            $zone->save();
        }

        //TODO DO. CONTROLL USER!
        //TODO fijarse cuando idzone = 0, hay que armar ruta con TODAS las zonas...
        $orders = Ship_Order::where([
                                    ['zone', $id_zone],
                                    ['status', 0]])
                                    ->get();
        
        //TODO Cambiar esta verga que lo hice de copy paste nomass
        $i = 0;
        foreach ($orders as $order) {
            $this->data[] = [
                'code' => $order->code,
                'nombre' => $order->street,
                'altura' => $order->number,
                'coords' => ['lat' => $order->lat, 'lng' => $order->lng]
            ];

            $this->order[$i] = $i;
            $i++;
        }
        Log::info(self::LOG_LABEL." Orders to calculate: ".sizeof($orders));

        Log::info(self::LOG_LABEL." Start getting coordenates from START address..."); 
        $coords = $this->get_street_coords($start['code'], $start['number']);

        if (!$this->coords_are_valid($coords)) {
            //TODO LANZAR EXCEPCION
            Log::error(self::LOG_LABEL." ERROR. Coords from {$start['code']} {$start['name']} {$start['number']} are invalid.");
            return; 
        }
        array_unshift($this->data, [
                                    'code' => $start['code'],
                                    'nombre' => $start['name'],
                                    'altura' => $start['number'],
                                    'coords' => $coords
                            ]);
        $this->order[sizeof($this->order)] = sizeof($this->order);
        
        Log::info(self::LOG_LABEL." Start getting coordenates from END address..."); 
        $coords = $this->get_street_coords($end['code'], $end['number']);

        if (!$this->coords_are_valid($coords)) {
            //TODO LANZAR EXCEPCION
            Log::error(self::LOG_LABEL." ERROR. Coords from {$end['code']} {$end['name']} {$end['number']} are invalid.");
            return; 
        }
        array_push($this->data, [
                                    'code' => $end['code'],
                                    'nombre' => $end['name'],
                                    'altura' => $end['number'],
                                    'coords' => $coords
                            ]);
        $this->order[sizeof($this->order)] = sizeof($this->order);
        
        Log::info(self::LOG_LABEL." Route calculation process starts..."); 
        
        $iterattion = 0;
        $this->population = $this->create_population(self::POPULATION_SIZE);
        while ($iterattion < self::MAX_ITERATIONS) {
            $this->fitness = $this->calculate_fitness();
            $this->normalize_fitness();
            $this->nextGeneration();
            $iterattion++;
        }

        $i = 0;
        //No tengo en cuenta el primero y el ultimo (end and start)
        foreach (array_slice($this->best_order_ever, 1, sizeof($this->best_order_ever)-2)  as $index){
            $orders[$index-1]->route_order = $i;
            $orders[$index-1]->save();
            $i++;
            Log::info(self::LOG_LABEL." {$this->data[$index]['nombre']} {$this->data[$index]['altura']}");
        }

        $zone->lock = 0;
        $zone->save();
        Log::info(self::LOG_LABEL." Route calculation success."); 
        return response()->json('¡La ruta se calculó satisfactoriamente!', 200);

    }
    
    private function calculate_distante($population) {
        $sum = 0;
        for ($i = 0; $i < sizeof($population) - 1; $i++) {
            $coords_A_index = $population[$i];
            $coords_A = $this->data[$coords_A_index]['coords'];
            $coords_B_index = $population[$i + 1];
            $coords_B = $this->data[$coords_B_index]['coords'];
            $d = $this->distance($coords_A['lat'], $coords_A['lng'], $coords_B['lat'], $coords_B['lng']);
            $sum += $d;
        }
        return $sum;
    }
    
    private function distance($x1, $y1, $x2, $y2) {
        return sqrt(($x1 - $x2)**2 + ($y1 - $y2)**2);
    }

    private function create_population($total) {
        $population = [];
        for ($i = 0; $i < $total ; $i++) {
            $order_cpy = $this->order;
            $initial_location = array_shift($order_cpy);
            $end_location = array_pop($order_cpy);
            $population[$i][0] = $initial_location;
            shuffle($order_cpy);
            $population[$i] = array_merge($population[$i], array_merge($order_cpy, array($end_location)));
        }
        return $population;
    }

    private function calculate_fitness() {
        $fitness = [];
        $current_record = PHP_FLOAT_MAX;
        for ($i = 0; $i < sizeof($this->population); $i++) {
            $d = $this->calculate_distante($this->population[$i]);
            if ($d < $this->record_distance_ever) {
                $this->record_distance_ever = $d;
                $this->best_order_ever = $this->population[$i];
            }
            if ($d < $current_record) {
                $current_record = $d;
                $this->current_best_order = $this->population[$i];
            }

    // This fitness function has been edited from the original video
    // to improve performance, as discussed in The Nature of Code 9.6 video,
    // available here: https://www.youtube.com/watch?v=HzaLIO9dLbA
            $fitness[$i] = 1 / (pow($d, 8) + 1);
        }
        return $fitness;
    }

    private function normalize_fitness() {
        $sum = 0;
        for ($i = 0; $i < sizeof($this->fitness); $i++) {
          $sum += $this->fitness[$i];
        }
        for ($i = 0; $i < sizeof($this->fitness); $i++) {
          $this->fitness[$i] = $this->fitness[$i] / $sum;
        }
    }

    private function nextGeneration() {
        $newPopulation = [];
        for ($i = 0; $i < sizeof($this->population); $i++) {
            $orderA = $this->pickOne($this->population, $this->fitness);
            $orderB = $this->pickOne($this->population, $this->fitness);
            $order = $this->crossOver($orderA, $orderB);
            $this->mutate($order, 0.01);
            $newPopulation[$i] = $order;
        }
        $this->population = $newPopulation;
    }

    private function pickOne($list, $prob) {
        $index = 0;
        $r = (float)rand() / (float)getrandmax();
        
        while ($r > 0) {
            $r = $r - $prob[$index];
            $index++;
        }
        $index--;
        return array_slice($list[$index], 0);
    }
      
    private function crossOver($orderA, $orderB) {
        $start = rand(1, sizeof($orderA)-2);
        $end = rand($start, sizeof($orderA)-2);
        $neworder = array_slice($orderA, $start, (1 + $end - $start));
        $neworder = array_merge(array(0), array_slice($orderA, $start, (1 + $end - $start)));
        // var left = totalCities - neworder.length;
        for ($i = 1; $i < sizeof($orderB)-1; $i++) {
            $value = $orderB[$i];
            if (!in_array($value, $neworder)) {
                $neworder[] = $value;
            }
        }
        $neworder[] = sizeof($neworder);
        return $neworder;
    }
      
    private function mutate($order, $mutation_rate) {
        for ($i = 0; $i < sizeof($order); $i++) {
          if ((float)rand() / (float)getrandmax() < $mutation_rate) {
            $indexA = rand(1, sizeof($order)-2);
            $indexB = ($indexA + 1) % sizeof($order);
            $this->swap($order, $indexA, $indexB);
          }
        }
    }

    private function swap($arr, $i, $j) {
        $temp = $arr[$i];
        $arr[$i] = $arr[$j];
        $arr[$j] = $temp;
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
