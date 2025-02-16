<?php

namespace App\Http\Resources;

use App\Models\Brand;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VehicleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $vehicleModel = VehicleModel::find($this->vehicle_model_id);
        $brand = Brand::find($vehicleModel->brand_id);

        return [
            'id' => $this->id,
            'brand' => $brand->name,
            'model' => $vehicleModel->name,
            'vin' => $this->vin,
            'price' => $this->price,
            'year' => $this->year,
            'mileage' => $this->mileage,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
