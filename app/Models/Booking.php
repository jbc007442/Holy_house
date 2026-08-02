<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Booking extends Model
{
    protected $fillable = [
        'booking_no',
        'room_id',
        'check_in',
        'check_out',
        'guest_count',
        'room_rent',
        'chargeable_amount',
        'complimentary_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'payment_status',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'room_rent' => 'decimal:2',
        'chargeable_amount' => 'decimal:2',
        'complimentary_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
    ];

    /**
     * Room
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Guests
     */
    public function guests(): HasMany
    {
        return $this->hasMany(BookingGuest::class);
    }

    /**
     * Services
     */
    public function services(): HasMany
    {
        return $this->hasMany(BookingService::class);
    }

    /**
     * Payments
     */
    public function payments(): HasMany
    {
        return $this->hasMany(BookingPayment::class);
    }

    /**
     *  Audit
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}