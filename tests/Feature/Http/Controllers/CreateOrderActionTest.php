<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Models\Order;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateOrderActionTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_successfully_create_an_order()
    {
        // Arrange
        $order = Order::factory()->make([
            'phone' => '+79999999999'
        ]);

        // Act
        $response = $this->postJson('/api/order', [
            'phone' => $order->phone,
            'vehicle_id' => $order->vehicle_id,
        ]);

        // Assert
        $response->assertCreated();
        $this->assertDatabaseHas('orders', [
            'phone' => $order->phone,
            'vehicle_id' => $order->vehicle_id,
        ]);
    }

    public function test_failed_create_an_order()
    {
        // Arrange
        $phone = $this->faker->word;
        $vehicle = Vehicle::factory()->create();

        // Act
        $response = $this->postJson('/api/order', [
            'phone' => $phone,
            'vehicle_id' => $vehicle->id,
        ]);

        // Assert
        $response->assertStatus(422);
        $this->assertSame([
            'message' => 'The phone field must be a valid number.',
            'errors' => [
                'phone' => ['The phone field must be a valid number.'],
            ]
        ], $response->json());
        $this->assertDatabaseMissing('orders', [
            'phone' => $phone,
            'vehicle_id' => $vehicle->id,
        ]);
    }
}
