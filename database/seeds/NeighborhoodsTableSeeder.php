<?php

use Illuminate\Database\Seeder;
use App\Neighborhood;

class NeighborhoodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    const ENDPOINT = "http://gis.mardelplata.gob.ar/opendata/ws.php?";

    public function run()
    {

        $neighborhoods = $this->get_neighborhoods();
        foreach ($neighborhoods as $n) {
            $record = new Neighborhood();
            $record->code = $n['codigo'];
            $record->name = $n['descripcion'];
            $record->lat = $n['latitud'];
            $record->lng = $n['longitud'];
            $record->zone = 0;
            $record->save();
            \Log::info('Save: '.$record);
        }

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
       
        $resp = curl_exec($curl);
        curl_close($curl);

        return $resp;
    }

    private function get_neighborhoods() {

        $parameters = [
            'method' => 'rest',
            'endpoint' => 'barrios',
            'token' => 'wwfe345gQ3ed5T67g4Dase45F6fer'
        ];
        
        $response = json_decode($this->create_request($parameters), true);

        return $response;
    }
}
