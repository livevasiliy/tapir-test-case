<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class VehicleModelFactory extends Factory
{
    protected $model = VehicleModel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'brand_id' => Brand::factory(),
        ];
    }
}
