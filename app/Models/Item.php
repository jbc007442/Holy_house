<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'item_name',
        'category',
        'unit',
        'purchase_price',
        'opening_stock',
        'minimum_stock',
        'status',
        'remarks',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'opening_stock'  => 'integer',
        'minimum_stock'  => 'integer',
        'status'         => 'boolean',
    ];

    public function bookingServices(): HasMany
    {
        return $this->hasMany(BookingService::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the current available stock.
     */
    public function getCurrentStockAttribute(): int
    {
        return (int) $this->opening_stock;
    }

    /**
     * Check if the item has sufficient stock.
     */
    public function hasStock(int $quantity): bool
    {
        return $this->opening_stock >= $quantity;
    }

    /**
     * Increase stock.
     */
    public function increaseStock(int $quantity): void
    {
        $this->increment('opening_stock', $quantity);
    }

    /**
     * Decrease stock.
     */
    public function decreaseStock(int $quantity): void
    {
        $this->decrement('opening_stock', $quantity);
    }

    /**
     * Check if stock is below minimum level.
     */
    public function isLowStock(): bool
    {
        return $this->opening_stock <= $this->minimum_stock;
    }

    /**
     * Purchase History 
     */
    public function purchaseHistories()
    {
        return $this->hasMany(PurchaseHistory::class);
    }
}