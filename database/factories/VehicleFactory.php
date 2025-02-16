<?php

namespace Database\Factories;

use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'vehicle_model_id' => VehicleModel::factory(),
            'vin' => $this->faker->ean8(),
            'price' => $this->faker->randomNumber(),
            'year' => $this->faker->year(),
            'mileage' => $this->faker->randomNumber(),
        ];
    }
}
