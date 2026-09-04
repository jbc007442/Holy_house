<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HousekeepingMessage extends Model
{
    protected $fillable = [
        'booking_id',
        'room_id',
        'message',
        'created_by',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}