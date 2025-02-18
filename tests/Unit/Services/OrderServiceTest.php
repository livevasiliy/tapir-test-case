<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Jobs\SendOrderToCrmJob;
use App\Models\Order;
use App\Models\Vehicle;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

#[CoversClass(\App\Services\OrderService::class)]
#[CoversClass(\App\Mail\NewOrderMail::class)]
#[CoversClass(\App\Jobs\SendOrderToCrmJob::class)]
#[CoversClass(\App\Mail\FailSentOrderToCrmMail::class)]
#[Group('unit')]
class OrderServiceTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();
        Queue::fake([
            SendOrderToCrmJob::class,
        ]);
    }

    public function test_create_order_and_dispatches_job(): void
    {
        // Arrange
        $vehicle = Vehicle::factory()->create();
        $phone = '+79999999999';

        $orderService = new OrderService;

        // Act
        $order = $orderService->createOrder($phone, $vehicle->id);

        // Assert
        $this->assertInstanceOf(Order::class, $order);
        $this->assertEquals($phone, $order->phone);
        $this->assertEquals($vehicle->id, $order->vehicle_id);

        // Assert that the order was marked as not sent
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'vehicle_id' => $vehicle->id,
            'phone' => $phone,
            'is_sent' => 0,
        ]);

        // Assert that the job was dispatched
        Queue::assertPushed(SendOrderToCrmJob::class, function ($job) use ($order) {
            return $job->order->is($order);
        });

        // Simulate the job being handled
        $job = new SendOrderToCrmJob($order);
        $job->handle();

        // Assert that the order was marked as sent after job
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'vehicle_id' => $vehicle->id,
            'phone' => $phone,
            'is_sent' => 1,
        ]);
    }
}
