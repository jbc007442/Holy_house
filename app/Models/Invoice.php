<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'booking_id',
        'grand_total',
        'created_by',
        'updated_by',
    ];

    /**
     * Booking
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