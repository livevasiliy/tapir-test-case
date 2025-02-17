<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'phone',
        'vehicle_id',
        'is_sent'
    ];

    protected $with = [
        'vehicle'
    ];

    protected $casts = [
        'is_sent' => 'boolean'
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}
