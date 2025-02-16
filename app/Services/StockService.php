<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class StockService
{
    private array $availableScopes = [
        'brand' => 'ofBrand',
        'model' => 'ofModel',
        'vin' => 'ofVin',
        'price_from' => 'ofPriceFrom',
        'price_to' => 'ofPriceTo',
        'year_from' => 'ofYearFrom',
        'year_to' => 'ofYearTo',
        'mileage_from' => 'ofMileageFrom',
        'mileage_to' => 'ofMileageTo',
    ];

    public function getAll(array $filters, int $limit): LengthAwarePaginator
    {
        $query = Vehicle::query();

        // Dynamically apply scopes
        foreach ($filters as $key => $value) {
            $this->applyFilter($query, $key, $value);
        }

        return $query->paginate($limit);
    }

    private function applyFilter(Builder $query, string $key, string|int|bool|null $value): void
    {
        if (array_key_exists($key, $this->availableScopes)) {
            $scopeMethod = $this->availableScopes[$key];

            $query->$scopeMethod($value);
        }
    }
}
