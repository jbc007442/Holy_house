<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseHistory extends Model
{
    protected $fillable = [
        'item_id',
        'quantity',
        'total_amount',
        'purchase_date',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'quantity'      => 'decimal:2',
        'total_amount'  => 'decimal:2',
        'purchase_date' => 'date',
    ];

    /**
     * Purchased Item
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Unit Purchase Price (Calculated)
     */
    public function getUnitPriceAttribute(): float
    {
        if ($this->quantity <= 0) {
            return 0;
        }

        return round($this->total_amount / $this->quantity, 2);
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