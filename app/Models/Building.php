<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;

// class Building extends Model
// {
//     protected $fillable = [
//         'name',
//         'code',
//         'floors',
//         'status',
//         'address',
//         'description',
//         'created_by',
//         'updated_by',
//     ];

//     /**
//      * Rooms
//      */
//     public function rooms(): HasMany
//     {
//         return $this->hasMany(Room::class);
//     }

//     /**
//      * Created By
//      */
//     public function creator(): BelongsTo
//     {
//         return $this->belongsTo(User::class, 'created_by');
//     }

//     /**
//      * Updated By
//      */
//     public function updater(): BelongsTo
//     {
//         return $this->belongsTo(User::class, 'updated_by');
//     }
// }


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Building extends Model
{
    protected $fillable = [
        'name',
        'code',
        'status',
        'address',
        'description',
        'created_by',
        'updated_by',
    ];

    /**
     * Floors
     */
    public function floors(): HasMany
    {
        return $this->hasMany(BuildingFloor::class)
            ->orderBy('sort_order');
    }

    /**
     * Rooms
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    /**
     * Created By
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Updated By
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}