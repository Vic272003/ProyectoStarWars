<?php

namespace Tests\Feature;

use App\Models\Starship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class StarShips extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/api/starship');

        $response->assertStatus(200);
    }
    /**
     * Intento de devolver las naves con error
     */
    public function test_example2(): void
    {
        $response = $this->get('/api/adios');

        $response->assertStatus(404);
    }
    /**
     * Insertar Nave True
     */
    public function test_example4(): void
    {
        $starship = Starship::create([

            'id' => '200',
            'name' => 'Starship Test',
            'model' => 'Test Model',
            'manufacturer' => 'Test Manufacturer',
            'cost_in_credits' => 1000,
            'length' => 10,
            'max_atmosphering_speed' => 100,
            'crew' => 10,
            'passengers' => 5,
            'cargo_capacity' => 500,
            'consumables' => '1 week',
            'hyperdrive_rating' => 1.0,
            'MGLT' => 100,
            'starship_class' => 'Test Class',
            'created' => '2023-05-10T00:00:00Z',
            'edited' => '2023-05-10T00:00:00Z',
            'url' => 'http://example.com/starship',
        ]);

        $this->assertNotNull($starship);
        $this->assertEquals('Starship Test', $starship->name);
    }
    /**
     * Insertar Nave False porque le falta un atributo
     */
    public function test_example5(): void
    {
        //Lo hacemos porque al no tener atributo id, nos salta esta excepción
        $this->expectException(\Illuminate\Database\QueryException::class);
        $starship = Starship::create([

            'name' => 'Starship Test',
            'model' => 'Test Model',
            'manufacturer' => 'Test Manufacturer',
            'cost_in_credits' => 1000,
            'length' => 10,
            'max_atmosphering_speed' => 100,
            'passengers' => 5,
            'cargo_capacity' => 500,
            'consumables' => '1 week',
            'hyperdrive_rating' => 1.0,
            'MGLT' => 100,
            'starship_class' => 'Test Class',
            'created' => '2023-05-10T00:00:00Z',
            'edited' => '2023-05-10T00:00:00Z',
            'url' => 'http://example.com/starship',
        ]);
    }
    /**
     * Actualiza unos campos dándole un id correcto
     */
    public function test_example6(): void
    {
        $response = $this->json('POST', '/api/updateStarship/75', [
            'name' => 'Gato',
            'model' => 'Updated Starship Model',
            'cost_in_credits' => '2000',
            'max_atmosphering_speed' => '200',
        ]);
        // Verificar que la solicitud HTTP fue exitosa
        $response->assertStatus(200);
    }
    /**
     * Actualiza unos campos dándole un id correcto FALSE
     */
    public function test_example7(): void
    {
        $response = $this->json('POST', '/api/updateStarship/1000', [
            'name' => 'Updated Starship Name',
            'model' => 'Updated Starship Model',
            'cost_in_credits' => '2000',
            'max_atmosphering_speed' => '200',
        ]);
        // Verificar que la solicitud HTTP fue exitosa
        $response->assertStatus(404);
    }
    /**
     * Borra un registro TRUE
     */
    public function test_example8(): void
    {
        $response = $this->delete('/api/deleteStarship/75');

        $response->assertStatus(204);
        $this->assertDatabaseMissing('starships', ['id' => 200]);
    }
    /**
     * Borra un registro FALSE
     */
    public function test_example9(): void
    {
        $response = $this->delete('/api/deleteStarship/1000');

        $response->assertStatus(404);
    }
}
