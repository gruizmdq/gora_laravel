<?php

use Illuminate\Database\Seeder;
use App\Street;

class StreetTableSeeder extends Seeder
{
    const ENDPOINT = "http://gis.mardelplata.gob.ar/opendata/ws.php?";

    public function run()
    {

        $streets = $this->get_street_code();
        foreach ($streets as $street) {
            $record = new Street();
            $record->code = $street['codigo'];
            $record->name = $street['descripcion'];
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

    private function get_street_code() {

        $parameters = [
            'method' => 'rest',
            'endpoint' => 'callejero_mgp',
            'token' => 'wwfe345gQ3ed5T67g4Dase45F6fer',
            'nombre_calle' => ""
        ];
        
        $response = json_decode($this->create_request($parameters), true);

        return $response;
    }
}
