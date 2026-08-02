<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'item_id',
        'type',
        'quantity',
        'reference',
        'remarks',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the inventory item.
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
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