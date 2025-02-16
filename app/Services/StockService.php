<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Vehicle;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Str;

class StockService
{
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
        $convertedKey = Str::camel($key);
        $scopeMethod = 'scope' . ucfirst($convertedKey);

        if (method_exists(Vehicle::class, $scopeMethod)) {
            $query->$convertedKey($value);
        }
    }
}
