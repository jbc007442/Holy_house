<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'booking_no',
        'room_id',
        'check_in',
        'check_out',
        'guest_count',

        // Charges
        'room_rent',
        'chargeable_amount',
        'complimentary_amount',
        'total_amount',
        'discount',

        // Payment
        'paid_amount',
        'balance_amount',
        'payment_status',

        // Status
        'status',

        // Invoice Details
        'rate_type',
        'bill_to',
        'bill_to_gstin',
        'hsn_code',

        // Remarks
        'remarks',
        'discount_remark',

        // Audit
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
        'discount' => 'decimal:2',
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


    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    /**
     * Audit
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Audit
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}