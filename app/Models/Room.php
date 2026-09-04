<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'building_id',
        'room_number',
        'floor',
        'capacity',
        'base_price',
        'status',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Get the building that owns the room.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get all bookings for this room.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }


    /**
     * Get housekeepingMessages.
     */
    public function housekeepingMessages()
    {
        return $this->hasMany(HousekeepingMessage::class);
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