<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;

class NewCar implements JsonSerializable
{
    public function __construct(
        public string $brand,
        public string $model,
        public string $vin,
        public int $price
    ) {}

    public static function fromArray(array $newCarData): NewCar
    {
        return new self(
            $newCarData['brand'],
            $newCarData['model'],
            $newCarData['vin'],
            $newCarData['price']
        );
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
