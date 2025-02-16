<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vehicle extends Model
{
    use HasFactory;

    protected $table = 'vehicles';

    protected $fillable = [
        'vehicle_model_id',
        'vin',
        'price',
        'year',
        'mileage',
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'integer',
    ];

    public function scopeBrand(Builder $query, string $brand): Builder
    {
        return $query->whereHas('vehicleModel.brand', function (Builder $query) use ($brand) {
            $query->where('name', 'like', '%' . $brand . '%');
        });
    }

    public function scopeModel(Builder $query, string $model): Builder
    {
        return $query->whereHas('vehicleModel', function (Builder $query) use ($model) {
            $query->where('name', 'like', '%' . $model . '%');
        });
    }

    public function scopeVin(Builder $query, string $vin): Builder
    {
        return $query->where('vin', 'like', '%' . $vin . '%');
    }

    public function scopePriceFrom(Builder $query, int $priceFrom): Builder
    {
        return $query->where('price', '>=', $priceFrom);
    }

    public function scopePriceTo(Builder $query, int $priceTo): Builder
    {
        return $query->where('price', '<=', $priceTo);
    }

    public function scopeYearFrom(Builder $query, int $yearFrom): Builder
    {
        return $query->where('year', '>=', $yearFrom);
    }

    public function scopeYearTo(Builder $query, int $yearTo): Builder
    {
        return $query->where('year', '<=', $yearTo);
    }

    public function scopeMileageFrom(Builder $query, int $mileageFrom): Builder
    {
        return $query->where('mileage', '>=', $mileageFrom);
    }

    public function scopeMileageTo(Builder $query, int $mileageTo): Builder
    {
        return $query->where('mileage', '<=', $mileageTo);
    }

    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }
}
