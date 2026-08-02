<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPayment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_type',
        'payment_method',
        'transaction_no',
        'remarks',
        'received_by',
        'paid_at',
        'created_by',
        'updated_by',
    ];


    /**
     * Carbon date object.
     */
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * Booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Staff/User who received the payment
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
    /**
     * Audit 
     */
    public function receivedBy()
    {
        return $this->belongsTo(User::class, 'received_by');
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