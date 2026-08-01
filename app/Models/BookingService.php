<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingService extends Model
{
    protected $fillable = [
        'booking_id',
        'item_id',
        'service_name',
        'type',
        'quantity',
        'unit_price',
        'total_amount',
        'remarks',
    ];


    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];
    /**
     * Get the booking that owns this service.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the inventory item associated with this service.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}