<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StockRequest;
use App\Http\Resources\VehicleCollection;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;

class StockAction
{
    public function __invoke(StockRequest $request, StockService $stockService): JsonResponse
    {
        return new JsonResponse(
            new VehicleCollection($stockService->getAll(
                $request->validated(),
                $request->integer('per_page', 10)
            )
            ));
    }
}
