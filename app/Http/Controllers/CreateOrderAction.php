<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CreateOrderAction
{
    public function __invoke(CreateOrderRequest $request, OrderService $service): JsonResponse
    {
        try {
            $order = $service->createOrder(
                $request->validated('phone'),
                $request->validated('vehicle_id'),
            );

            return new JsonResponse([
                'message' => sprintf('Order %s successfully created.', $order->id)
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            return new JsonResponse([
                'error' => 'Internal server error'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
