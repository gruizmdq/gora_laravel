<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Zone;
use App\Neighborhood;
use Redirect;

class DeliveryZonesController extends Controller
{
    const LOG_LABEL = "[ROUTING APP]";

    public function index(Request $request)
    {   
        $request->user()->authorizeRoles(['admin', 'delivery']);
        //Auth::id()
        $neighborhoods_unasigned = Neighborhood::where('zone', 0)->get();
        $neighborhoods_assigned = Neighborhood::where('zone','>', 0)->get();
        $zones = Zone::get();
        $neighborhoods = [];
        foreach ($zones as $zone) {
            $data = [];
            if (count($neighborhoods_assigned))
                foreach ($neighborhoods_assigned as $key => $value) {
                    if ($value->zone == $zone->id) {
                        $data[] = $value;
                        if (isset($neighborhoods_assigned[$key])) 
                            unset($neighborhoods_assigned[$key]);
                    }
                }
            $neighborhoods[$zone->name] = $data;
        }

        return view('delivery.zones', compact('neighborhoods_unasigned', 'zones', 'neighborhoods'));
    }

    public function set_neighborhood(Request $request) {
        \Log::info("************************************"); 
        \Log::info(self::LOG_LABEL." New request to add a neighborhood to a zone received: ".$request->getContent()); 
        
        $request->user()->authorizeRoles(['admin', 'delivery']);

        $inputs = json_decode($request->getContent(), true);
        $id_neighborhood = $inputs['id'];
        $id_zone = $inputs['id_zone'];

        try {
            $neighborhood = Neighborhood::findOrFail($id_neighborhood);
            $neighborhood->zone = $id_zone;
            $neighborhood->save();
            \Log::info(self::LOG_LABEL." Neighborhood (id = $neighborhood->id, $neighborhood->name) updated."); 
            return response()->json('¡El barrio se añadió a la zona correctamente!', 200);
        }
        catch (Exeption $e) {
            \Log::error(self::LOG_LABEL." ERROR. Neighborhood (id = $id_neighborhood) could not be updated");
            return; 
        }
        
        \Log::error(self::LOG_LABEL." ERROR. There is not Neighborhood with id = $id_neighborhood. Could not be updated"); 
        return;
    
    }


    public function add_zone(Request $request)
    {  
        
        \Log::info("************************************"); 
        \Log::info(self::LOG_LABEL." New request to add zone received: ".$request->getContent()); 
        
        $request->user()->authorizeRoles(['admin', 'delivery']);

        $name = $request->input('name');

        try {
            $zone = new Zone();
            $zone->name = $name;
            $zone->save();
            \Log::info(self::LOG_LABEL." Zone $name add success."); 
            return Redirect::action('DeliveryZonesController@index');
        }
        catch (Exeption $e) {
            \Log::info(self::LOG_LABEL." ERROR. Zone ($name) could not be deleted");
            return; 
        }
    }

}
