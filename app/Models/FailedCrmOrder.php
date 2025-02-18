<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Orchid\Screen\AsSource;

class FailedCrmOrder extends Model
{
    use AsSource;

    protected $table = 'failed_crm_orders';

    protected $fillable = [
        'order_id',
        'message',
    ];

    protected $with = [
        'order',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
