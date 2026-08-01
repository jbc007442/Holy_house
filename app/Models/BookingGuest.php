<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingGuest extends Model
{
    protected $fillable = [
        'booking_id',
        'guest_name',
        'mobile',
        'id_type',
        'id_number',
        'nationality',
        'is_primary',
    ];

    /**
     * Get the booking that owns this guest.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}