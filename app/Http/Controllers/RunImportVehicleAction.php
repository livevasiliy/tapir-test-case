<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunImportVehicleAction
{
    public function __invoke(): JsonResponse
    {
        try {
            Artisan::call('app:import-vehicle');

            return new JsonResponse(null, Response::HTTP_OK);
        } catch (Exception $exception) {
            Log::error($exception->getMessage(), $exception->getTrace());
            return new JsonResponse([
                'error' => 'Internal server error',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
