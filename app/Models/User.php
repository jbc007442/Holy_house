<?php

namespace App\Models;

use App\Models\LoginHistory;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


#[Fillable([
    'name',
    'email',
    'role',
    'status',
    'password',
])]

#[Hidden([
    'password',
    'remember_token',
])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if the user is a normal user.
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if the user is inactive.
     */
    public function isInactive(): bool
    {
        return $this->status === 'inactive';
    }

    /**
     * Get all login history records for the user.
     */
    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * Audit 
     */
    public function createdBookingServices()
    {
        return $this->hasMany(BookingService::class, 'created_by');
    }

    /**
     * Audit 
     */
    public function updatedBookingServices()
    {
        return $this->hasMany(BookingService::class, 'updated_by');
    }

    /**
     * Audit 
     */
    public function createdBookings()
    {
        return $this->hasMany(Booking::class, 'created_by');
    }

    /**
     * Audit 
     */
    public function updatedBookings()
    {
        return $this->hasMany(Booking::class, 'updated_by');
    }

    /**
     * Audit 
     */
    public function createdItems()
    {
        return $this->hasMany(Item::class, 'created_by');
    }

    /**
     * Audit 
     */
    public function updatedItems()
    {
        return $this->hasMany(Item::class, 'updated_by');
    }

    /**
     * Audit 
     */
    public function createdBookingGuests()
    {
        return $this->hasMany(BookingGuest::class, 'created_by');
    }

    /**
     * Audit 
     */
    public function updatedBookingGuests()
    {
        return $this->hasMany(BookingGuest::class, 'updated_by');
    }

    /**
     * Audit 
     */
    public function createdRooms()
    {
        return $this->hasMany(Room::class, 'created_by');
    }

    /**
     * Audit 
     */
    public function updatedRooms()
    {
        return $this->hasMany(Room::class, 'updated_by');
    }

    /**
     * Audit 
     */
    public function createdBuildings()
    {
        return $this->hasMany(Building::class, 'created_by');
    }
   
    /**
     * Audit 
     */
    public function updatedBuildings()
    {
        return $this->hasMany(Building::class, 'updated_by');
    }


    /**
     * Audit 
     */
    public function createdStockMovements()
    {
        return $this->hasMany(StockMovement::class, 'created_by');
    }
    /**
     * Audit 
     */
    public function updatedStockMovements()
    {
        return $this->hasMany(StockMovement::class, 'updated_by');
    }

    /**
     * Audit 
     */
    public function receivedPayments()
    {
        return $this->hasMany(BookingPayment::class, 'received_by');
    }
    /**
     * Audit 
     */

    public function createdBookingPayments()
    {
        return $this->hasMany(BookingPayment::class, 'created_by');
    }

    /**
     * Audit 
     */
    public function updatedBookingPayments()
    {
        return $this->hasMany(BookingPayment::class, 'updated_by');
    }
    /**
     * Audit 
     */
    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    /**
     * Audit 
     */
    public function updatedInvoices()
    {
        return $this->hasMany(Invoice::class, 'updated_by');
    }

    /**
     * Audit 
     */
    public function createdPurchaseHistories()
    {
        return $this->hasMany(PurchaseHistory::class, 'created_by');
    }

    /**
     * Audit 
     */
    public function updatedPurchaseHistories()
    {
        return $this->hasMany(PurchaseHistory::class, 'updated_by');
    }

    /**
     * Audit
     */
    public function createdBuildingFloors()
    {
        return $this->hasMany(BuildingFloor::class, 'created_by');
    }

    /**
     * Audit
     */
    public function updatedBuildingFloors()
    {
        return $this->hasMany(BuildingFloor::class, 'updated_by');
    }
}