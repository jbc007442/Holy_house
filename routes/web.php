<?php

use App\Http\Controllers\Dashboard\Property\BuildingController;
use App\Http\Controllers\Dashboard\Property\RoomController;
use App\Http\Controllers\Dashboard\Bookings\BookingController;
use App\Http\Controllers\Dashboard\DashboardController;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginHistoryController;
use App\Http\Controllers\Dashboard\Accounts\InvoiceController;
use App\Http\Controllers\Dashboard\Users\UserController;

use App\Http\Controllers\Dashboard\Inventory\ItemController;
use App\Http\Controllers\Dashboard\Inventory\PurchaseHistoryController;
use App\Http\Controllers\Dashboard\Inventory\StockMovementController;

use App\Http\Controllers\Dashboard\Reports\ReportController;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Website
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('website.base'));

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::controller(AuthController::class)->group(function () {

    // Login
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('login.post');

    // Register
    Route::get('/register', 'register')->name('register');
    Route::post('/register', 'registerStore')->name('register.store');

    // Logout
    Route::post('/logout', 'logout')->name('logout');

    // Forgot Password
    Route::get('/forgot-password', 'forgotPassword')->name('forgot-password');
    Route::post('/forgot-password', 'sendResetLink')->name('forgot-password.post');

    // Reset Password
    Route::get('/reset-password/{token}', 'resetPassword')->name('password.reset');
    Route::post('/reset-password', 'updatePassword')->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('dashboard')
    ->name('dashboard.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/data', [DashboardController::class, 'data'])->name('data');
        Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');

        /*
        |--------------------------------------------------------------------------
        | Property
        |--------------------------------------------------------------------------
        */

        // Buildings

        Route::get('/buildings', [BuildingController::class, 'index'])->name('property.buildings');
        Route::get('/buildings/create', [BuildingController::class, 'create'])->name('property.buildings.create');
        Route::post('/buildings', [BuildingController::class, 'store'])->name('property.buildings.store');
        Route::get('/buildings/{building}', [BuildingController::class, 'show'])->name('property.buildings.show');
        Route::get('/buildings/{building}/edit', [BuildingController::class, 'edit'])->name('property.buildings.edit');
        Route::put('/buildings/{building}', [BuildingController::class, 'update'])->name('property.buildings.update');
        Route::delete('/buildings/{building}', [BuildingController::class, 'destroy'])->name('property.buildings.destroy');
        Route::get('/building-floors/{building}', [BuildingController::class, 'getFloors'])->name('property.buildings.get-floors');

        // Rooms

        Route::get('/rooms', [RoomController::class, 'index'])->name('property.rooms');
        Route::get('/rooms/create', [RoomController::class, 'create'])->name('property.rooms.create');
        Route::post('/rooms', [RoomController::class, 'store'])->name('property.rooms.store');
        Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('property.rooms.show');
        Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('property.rooms.edit');
        Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('property.rooms.update');
        Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('property.rooms.destroy');
        Route::get('/room-status', [RoomController::class, 'roomStatus'])->name('property.room-status');
        Route::patch('/rooms/{room}/change-status', [RoomController::class, 'changeStatus'])->name('rooms.change-status');
        Route::get('/buildings/{building}/floors', [RoomController::class, 'getFloors'])->name('property.buildings.floors');

        // =====================================
        // Bookings
        // =====================================

        Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{id}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
        Route::put('/bookings/{id}', [BookingController::class, 'update'])->name('bookings.update');
        Route::patch('/bookings/{id}/checkout', [BookingController::class, 'checkout'])->name('bookings.checkout');
        Route::delete('/bookings/{id}', [BookingController::class, 'destroy'])->name('bookings.destroy');
        Route::get('/current-stays', [BookingController::class, 'currentStays'])->name('bookings.current-stays');
        Route::get('/current-stays/ajax', [BookingController::class, 'ajaxCurrentStays'])->name('bookings.current-stays.ajax');
        Route::get('/booking-history', [BookingController::class, 'history'])->name('bookings.history');
        Route::get('/bookings/rooms/{building}', [BookingController::class, 'getRooms'])->name('bookings.rooms');
        Route::get('/bookings/{booking}/services', [BookingController::class, 'services'])->name('bookings.services');
        Route::post('/bookings/{booking}/services', [BookingController::class, 'storeService'])->name('bookings.services.store');
        Route::delete('/bookings/services/{service}', [BookingController::class, 'deleteService'])->name('bookings.services.delete');
        Route::get('/bookings/history/ajax', [BookingController::class, 'ajaxHistory'])->name('bookings.history.ajax');
        Route::patch('/bookings/services/{service}', [BookingController::class, 'updateService'])->name('bookings.services.update');
        Route::patch('/bookings/{booking}/invoice', [BookingController::class, 'updateInvoiceDetails'])->name('bookings.invoice.update');
        Route::get('/bookings/{booking}/details', [BookingController::class, 'details'])->name('bookings.details');

        /*
        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */

        // Items

        Route::get('/items', [ItemController::class, 'index'])->name('inventory.items');
        Route::get('/items/create', [ItemController::class, 'create'])->name('inventory.items.create');
        Route::post('/items', [ItemController::class, 'store'])->name('inventory.items.store');
        Route::get('/items/{item}', [ItemController::class, 'show'])->name('inventory.items.show');
        Route::get('/items/{item}/edit', [ItemController::class, 'edit'])->name('inventory.items.edit');
        Route::put('/items/{item}', [ItemController::class, 'update'])->name('inventory.items.update');
        Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('inventory.items.destroy');



        /*
        |--------------------------------------------------------------------------
        | PurchaseHistory;
        |--------------------------------------------------------------------------
        */
        Route::get('/inventory/items/{item}/manage', [PurchaseHistoryController::class, 'manage'])->name('inventory.items.manage');
        Route::post('/inventory/items/{item}/purchase', [PurchaseHistoryController::class, 'store'])->name('inventory.items.purchase');



        // Stock Movement


        Route::get('/stock-movement', [StockMovementController::class, 'index'])->name('inventory.stock-movement');
        Route::get('/stock-movement/create', [StockMovementController::class, 'create'])->name('inventory.stock-movement.create');
        Route::post('/stock-movement', [StockMovementController::class, 'store'])->name('inventory.stock-movement.store');
        Route::get('/stock-movement/{stockMovement}', [StockMovementController::class, 'show'])->name('inventory.stock-movement.show');
        Route::get('/stock-movement/{stockMovement}/edit', [StockMovementController::class, 'edit'])->name('inventory.stock-movement.edit');
        Route::put('/stock-movement/{stockMovement}', [StockMovementController::class, 'update'])->name('inventory.stock-movement.update');
        Route::delete('/stock-movement/{stockMovement}', [StockMovementController::class, 'destroy'])->name('inventory.stock-movement.destroy');
        //newly added routes for stock movement
        Route::get('/stock-movement/buildings/{building}/rooms', [StockMovementController::class, 'getStockRooms'])->name('inventory.stock-movement.rooms');
        Route::get('/stock-report', [StockMovementController::class, 'stockReport'])->name('inventory.stock-report');
        Route::get('/stock-per-item/{item}', [StockMovementController::class, 'stockPerItem'])->name('inventory.stock-per-item');
        Route::get('/stock-per-item/{item}/data', [StockMovementController::class, 'stockPerItemData'])->name('inventory.stock-per-item.data');


        /*
        |--------------------------------------------------------------------------
        | Accounts
        |--------------------------------------------------------------------------
        */

        Route::get('/invoices', [InvoiceController::class, 'index'])->name('accounts.invoices');
        Route::get('/invoices/ajax', [InvoiceController::class, 'ajaxInvoices'])->name('accounts.invoices.ajax');
        Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('accounts.invoices.show');

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::get('/occupancy-report', [ReportController::class, 'occupancy'])->name('reports.occupancy');
        Route::get('/revenue-report', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('/guest-report', [ReportController::class, 'guests'])->name('reports.guests');
        Route::get('/daily-collection', [ReportController::class, 'dailyCollection'])->name('reports.daily-collection');
        Route::get('/inventory-report', [ReportController::class, 'inventory'])->name('reports.inventory');

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('/login-history', [LoginHistoryController::class, 'index'])->name('login-history.index');


        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */

        Route::view('/system-settings', 'dashboard.settings.system-settings')->name('settings.system-settings');
    });