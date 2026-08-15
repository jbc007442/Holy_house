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
        'state',
        'c_form',
        'is_primary',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the booking that owns this guest.
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
    /**
     * Audit 
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Audit 
     */

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}