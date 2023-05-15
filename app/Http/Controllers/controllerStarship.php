<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Starship;
use Illuminate\Support\Facades\DB;

function sacarId($cadena)
{
    $id = explode('/', $cadena);
    return $id[sizeof($id) - 2];
}

class controllerStarship extends Controller
{
    /**
     * Creamos la función para crear todas las naves
     */
    public function createStarship($page = 1)
    {
        //Hacemos la llamada a la API
        $client  =  new  Client([
            // El URI base se usa con solicitudes relativas 
            'base_uri'  =>  'https://swapi.dev/api/',
            // Puede configurar cualquier número de opciones de solicitud predeterminadas. 
        ]);
        $response  =  $client->request('GET',  'starships', ['query' => ['page' => $page]]);
        $contadorPaginas = 1;
        $ships = json_decode($response->getBody()->getContents());

        //Recorremos todas las naves
        foreach ($ships->results as $shipData) {
            // Atributos de la nave
            $ship = new Starship();
            $ship->id = sacarId($shipData->url);
            $ship->name = $shipData->name;
            $ship->model = $shipData->model;
            $ship->manufacturer = $shipData->manufacturer;
            $ship->cost_in_credits = $shipData->cost_in_credits;
            $ship->length = $shipData->length;
            $ship->max_atmosphering_speed = $shipData->max_atmosphering_speed;
            $ship->crew = $shipData->crew;
            $ship->passengers = $shipData->passengers;
            $ship->cargo_capacity = $shipData->cargo_capacity;
            $ship->consumables = $shipData->consumables;
            $ship->hyperdrive_rating = $shipData->hyperdrive_rating;
            $ship->MGLT = $shipData->MGLT;
            $ship->starship_class = $shipData->starship_class;
            $ship->created = $shipData->created;
            $ship->edited = $shipData->edited;
            $ship->url = $shipData->url;
            $ship->save(); //La guardamos
        }

        //Esto lo hacemos para pasar de página en la API
        if ($ships->next) {
            $nextPage = explode('=', $ships->next)[1];
            $this->createStarship($nextPage);
        }
    }
    //CRUD
    /**
     * Cogemos todas las naves
     */
    public function getStarship()
    {
        // Devolvemos un json con las naves
        return response()->json(Starship::all(), 200);
    }
    /**
     * Cogemos una nave por su id
     */
    public function getStarhipId($id)
    {
        // Buscamos por su id
        $starship = Starship::find($id);
        // Si no encuentra la nave
        if (is_null($starship)) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        // Devolvemos un json
        return response()->json($starship::find($id), 200);
    }
    /**
     * Insertamos una nave
     */
    public function insertStarship(Request $request)
    {
        // Creamos la nave
        $starship = Starship::create($request->all());
        return response($starship, 201);
    }
    /**
     * Actualizamos una nave
     */
    public function updateStarship(Request $request)
    {
        // Buscamos la nave para ver si existe
        $starship = Starship::find($request->id);
        // Si es null error 404
        if (is_null($starship)) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        // Si recoge algo hacemos el update
        $starship->update($request->all());
        return response($starship, 200);
    }
    /**
     * Eliminamos una nave
     */
    public function deleteStarship($id)
    {
        // Buscamos la nave por su id
        $starship = Starship::find($id);
        // Si no se encuentra error 404
        if (is_null($starship)) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }
        // Si encuentra algo la borramos
        $starship->delete();
        return response()->json(['message' => 'Registro eliminado'], 204);
    }
    /**
     * Obtenemos los pilotos de una nave por su id
     */
    public function getPilotsPorId($id)
    {
        // Buscamos la nave por su id
        $starship = Starship::find($id);
        // Recogemos a los pilotos
        $pilots = $starship->pilots;
        return response()->json($pilots, 200);
    }
    /**
     * En el raiz llamamos a la función index y devuelve la vista starwars
     */
    public function index()
    {
        return view('starwars');
    }

    /**
     * Devuelve las naves con los pilotos asociados
     */
    public function getStarshipsWithPilots()
    {
        // Recogemos el atributo pilots en el modelo starships
        $starships = Starship::with('pilots')->get();

        return response()->json($starships);
    }
    /**
     * Añadimos un Piloto a una nave por su id
     */
    public function addPilotToStarship(Request $request, $id)
    {
        // Buscamos la nave
        $starship = Starship::find($id);

        // Si es null error 404
        if (is_null($starship)) {
            return response()->json(['message' => 'Nave no encontrada'], 404);
        }
        //Si no recogemos el id del piloto
        $pilotId = $request->input('pilot_id');
        // Si ya está asociado a la nave error 400
        if ($starship->pilots()->where('pilot_id', $pilotId)->exists()) {
            return response()->json(['message' => 'El piloto ya está asociado a la nave'], 400);
        }
        //Si no estaba asociado, lo asociamos
        $starship->pilots()->attach($pilotId);

        return response()->json(['message' => 'Piloto agregado a la nave'], 200);
    }
    /**
     * Borrar un piloto asociado a una nave
     */
    public function deletePilotFromStarship($starship, $pilot)
    {
        // Buscamos la nave por su nombre y recogemos la primera que nos encuentre
        $starships = Starship::where('name', $starship)->first();
        // Si es null error 404
        if (is_null($starships)) {
            return response()->json(['message' => 'Nave no encontrada'], 404);
        }

        //Si no buscamos el nombre del piloto
        $pilots = $starships->pilots()->where('name', $pilot)->first();
        // Si es null error 404
        if (is_null($pilots)) {
            return response()->json(['message' => 'Piloto no encontrado'], 404);
        }
        // Si no, lo borramos de la relación
        $starships->pilots()->detach($pilots->id);

        return response()->json(['message' => 'Piloto eliminado de la nave'], 200);
    }
}
