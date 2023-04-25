<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use App\Models\Pilot;
use Illuminate\Http\Request;

// function sacarId($cadena)
// {
//     $id = explode('/', $cadena);
//     return $id[sizeof($id) - 2];
// }
class controllerPilot extends Controller
{


    /**
     * Hacemos una función que llama a la api y recogemos los pilotos 
     */
    public function createPilot($page = 1)
    {
        //Hacemos la llamada a la api
        $client  =  new  Client([
            // El URI base se usa con solicitudes relativas 
            'base_uri'  =>  'https://swapi.dev/api/',
            // Puede configurar cualquier número de opciones de solicitud predeterminadas. 
        ]);
        $response  =  $client->request('GET',  'people', ['query' => ['page' => $page]]);

        $pilots = json_decode($response->getBody()->getContents());

        //Recorremos todos los pilotos
        foreach ($pilots->results as $pilotData) {
            $pilot = new Pilot();
            $pilot->id = sacarId($pilotData->url);
            $pilot->name = $pilotData->name;
            $pilot->height = $pilotData->height;
            $pilot->mass = $pilotData->mass;
            $pilot->hair_color = $pilotData->hair_color;
            $pilot->skin_color = $pilotData->skin_color;
            $pilot->eye_color = $pilotData->eye_color;
            $pilot->birth_year = $pilotData->birth_year;
            $pilot->gender = $pilotData->gender;
            $pilot->homeworld = $pilotData->homeworld;
            $pilot->created = $pilotData->created;
            $pilot->edited = $pilotData->edited;
            $pilot->url = $pilotData->url;
            $pilot->save();
        }

        // segundo bucle para crear y guardar las relaciones en la tabla intermedia
        foreach ($pilots->results as $pilotData) {
            $pilot = Pilot::find(sacarId($pilotData->url));
            
            $shipIds = array_map(function ($shipUrl) {
                return sacarId($shipUrl);
            }, $pilotData->starships);
            $pilot->ships()->sync($shipIds);
        }
        //Esto lo hacemos para pasar de página en la API
        if ($pilots->next) {
            $nextPage = explode('=', $pilots->next)[1];
            $this->createPilot($nextPage);
        }
    }
    
    /**
     * Obtenemos todos los pilotos
     */
    public function getPilot()
    {
        return response()->json(Pilot::all(), 200);
    }

    /**
     * Obtenemos un piloto por su id
     */
    public function getPilotId($id)
    {
        $pilot = Pilot::find($id);
        if (is_null($pilot)) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        return response()->json($pilot::find($id), 200);
    }
    /**
     * Obtenemos el piloto por su nombre (No lo uso)
     */
    public function showByName($name)
{
    $pilot = Pilot::where('name', $name)->first();
    return response()->json($pilot);
}

    /**
     * Insertamos un piloto
     */
    public function insertPilot(Request $request)
    {
        $pilot = Pilot::create($request->all());
        return response($pilot, 201);
    }
    /**
     * Actualizamos un piloto
     */
    public function updatePilot(Request $request)
    {
        $pilot = Pilot::find($request->id);
        if (is_null($pilot)) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        $pilot->update($request->all());
        return response($pilot, 200);
    }
    /**
     * Eliminamos un piloto
     */
    public function deletePilot($id)
    {
        $pilot = Pilot::find($id);
        if (is_null($pilot)) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        $pilot->ships()->detach();
        $pilot->delete();
        return response()->json(['message' => 'Registro eliminado'], 204);
    }
}
