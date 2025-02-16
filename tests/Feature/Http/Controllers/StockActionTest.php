<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Brand;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed some test data
        $brand = Brand::create(['name' => 'Toyota']);
        $model = VehicleModel::create(['name' => 'Camry', 'brand_id' => $brand->id]);
        Vehicle::factory()->create([
            'vehicle_model_id' => $model->id,
            'vin' => 'JH4KA7660MC012345',
            'price' => 800000,
            'year' => 2010,
            'mileage' => 50000,
        ]);
        Vehicle::factory()->create([
            'vehicle_model_id' => $model->id,
            'vin' => '1HGCM82633A123456',
            'price' => 1200000,
            'year' => 2015,
            'mileage' => 80000,
        ]);
    }

    public function test_stock_filtering_by_price(): void
    {
        $response = $this->getJson('/api/stock?price_from=900000');

        // Assert that only vehicles with price >= 900000 are returned
        $response->assertStatus(200)
            ->assertJsonCount(1, 'data') // Only one vehicle matches the filter
            ->assertJsonFragment([
                'vin' => '1HGCM82633A123456',
                'price' => 1200000,
            ]);
    }

    public function test_stock_pagination(): void
    {
        $response = $this->getJson('/api/stock?per_page=1');

        // Assert pagination metadata
        $response->assertStatus(200)
            ->assertJsonPath('pagination.per_page', 1)
            ->assertJsonPath('pagination.total', 2)
            ->assertJsonPath('pagination.current_page', 1)
            ->assertJsonPath('pagination.last_page', 2);
    }
}
