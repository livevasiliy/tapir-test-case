<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class RunImportVehicleActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_vehicle_action_success(): void
    {
        // Mock the Artisan command to simulate a successful execution
        Artisan::shouldReceive('call')
            ->with('app:import-vehicle')
            ->andReturn(0); // Success status code

        $response = $this->postJson('/api/import-vehicles');

        // Assert that the response has a 200 OK status code
        $response->assertStatus(200);
    }

    public function test_import_vehicle_action_failure(): void
    {
        // Mock the Artisan command to simulate a failure
        Artisan::shouldReceive('call')
            ->with('app:import-vehicle')
            ->andThrow(new Exception('Import failed'));

        $response = $this->postJson('/api/import-vehicles');

        // Assert that the response has a 500 Internal Server Error status code
        $response->assertStatus(500)
            ->assertJson(['error' => 'Internal server error']);
    }
}
