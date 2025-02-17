<?php

declare(strict_types=1);

return [
    'import' => [
        'new_car_url' => 'https://tapir.ws/files/new_cars.json',
        'used_car_url' => 'https://tapir.ws/files/used_cars.xml',
    ],
    'crm_url' => 'https://crm.tapir.ws/api/crm',
    'failure_order_email' => env('TAPIR_FAILURE_ORDER_EMAIL', 'admin@admin.com'),
];
