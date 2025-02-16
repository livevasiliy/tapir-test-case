<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\NewCar;
use App\DTO\UsedCar;
use App\Models\Brand;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;

class ImportVehicleService
{
    /**
     * @throws Exception
     */
    public function process(): void
    {
        $this->importNewCars();
        $this->importUsedCars();
    }

    /**
     * @throws Exception
     */
    private function importNewCars(): void
    {
        try {
            $response = Http::get(config('import.import.new_car_url'));
            if (! $response->successful()) {
                throw new Exception('Failed to fetch new cars data.');
            }

            $newCars = $response->json();
            foreach ($newCars as $newCarData) {
                $newCar = NewCar::fromArray($newCarData);
                $this->createVehicle($newCar);
            }
        } catch (Exception $e) {
            logs()->error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    private function importUsedCars(): void
    {
        try {
            $response = Http::get(config('import.import.used_car_url'));
            if (! $response->successful()) {
                throw new Exception('Failed to fetch used cars data.');
            }

            $xml = simplexml_load_string($response->body());
            if (! isset($xml->vehicle)) {
                return; // No vehicles to process
            }

            foreach ($xml->vehicle as $usedCarData) {
                $usedCar = UsedCar::fromXml($usedCarData);
                $this->createVehicle($usedCar);
            }
        } catch (Exception $e) {
            logs()->error($e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    private function createVehicle(object $car): void
    {
        $brand = $this->ensureBrandExists($car->brand);
        $vehicleModel = $this->ensureVehicleModelExists($brand, $car->model);

        try {
            Vehicle::create([
                'vehicle_model_id' => $vehicleModel->id,
                'vin' => $car->vin,
                'price' => $car->price,
                'year' => $car->year ?? null,
                'mileage' => $car->mileage ?? null,
            ]);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                throw new Exception('Duplicate entry found while creating vehicle with VIN: ' . $car->vin);
            }
            throw $e;
        } catch (Exception $e) {
            logs()->error($e->getMessage());
            throw $e;
        }
    }

    private function ensureBrandExists(string $brandName): Brand
    {
        return Brand::firstOrCreate(['name' => $brandName]);
    }

    private function ensureVehicleModelExists(Brand $brand, string $modelName): VehicleModel
    {
        return VehicleModel::firstOrCreate([
            'name' => $modelName,
            'brand_id' => $brand->id,
        ]);
    }
}
