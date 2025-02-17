<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\SendOrderToCrmJob;
use App\Models\Order;
use Exception;

class OrderService
{
    /**
     * @throws Exception
     */
    public function createOrder(string $phone, int $vehicleId): Order
    {
        try {
            $order = Order::create([
                'phone' => $phone,
                'vehicle_id' => $vehicleId
            ]);

            SendOrderToCrmJob::dispatch($order);
            return $order;
        } catch (Exception $exception) {
            throw new $exception;
        }
    }
}
