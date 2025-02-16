<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    public function definition(): array
    {
        return [
            'brand_id' => Brand::factory(),
            'vin' => $this->faker->ean8(),
            'price' => $this->faker->randomNumber(),
            'year' => $this->faker->year(),
            'mileage' => $this->faker->randomNumber(),
        ];
    }
}
