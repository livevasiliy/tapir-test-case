<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected $with = [
        'vehicleModel',
        'vehicleModel.brand',
    ];

    public function scopeOfBrand(Builder $query, string $brand): void
    {
        $query->whereHas('vehicleModel.brand', function (Builder $query) use ($brand) {
            $query->where('name', 'like', '%' . $brand . '%');
        });
    }

    public function scopeOfModel(Builder $query, string $model): void
    {
        $query->whereHas('vehicleModel', function (Builder $query) use ($model) {
            $query->where('name', 'like', '%' . $model . '%');
        });
    }

    public function scopeOfVin(Builder $query, string $vin): void
    {
        $query->where('vin', 'like', '%' . $vin . '%');
    }

    public function scopeOfPriceFrom(Builder $query, int $priceFrom): void
    {
        $query->where('price', '>=', $priceFrom);
    }

    public function scopeOfPriceTo(Builder $query, int $priceTo): void
    {
        $query->where('price', '<=', $priceTo);
    }

    public function scopeOfYearFrom(Builder $query, int $yearFrom): void
    {
        $query->where('year', '>=', $yearFrom);
    }

    public function scopeOfYearTo(Builder $query, int $yearTo): void
    {
        $query->where('year', '<=', $yearTo);
    }

    public function scopeOfMileageFrom(Builder $query, int $mileageFrom): void
    {
        $query->where('mileage', '>=', $mileageFrom);
    }

    public function scopeOfMileageTo(Builder $query, int $mileageTo): void
    {
        $query->where('mileage', '<=', $mileageTo);
    }

    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
