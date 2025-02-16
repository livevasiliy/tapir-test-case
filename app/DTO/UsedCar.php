<?php

declare(strict_types=1);

namespace App\DTO;

use JsonSerializable;
use SimpleXMLElement;

class UsedCar implements JsonSerializable
{
    public function __construct(
        public string $brand,
        public string $model,
        public string $vin,
        public int $price,
        public int $year,
        public int $mileage
    ) {
    }

    public static function fromXml(SimpleXMLElement $usedCarData): UsedCar
    {
        return new self(
            (string)$usedCarData->brand,
            (string)$usedCarData->model,
            (string)$usedCarData->vin,
            (int)$usedCarData->price,
            (int)$usedCarData->year,
            (int)$usedCarData->mileage
        );
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
