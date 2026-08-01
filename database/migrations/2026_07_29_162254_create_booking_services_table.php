<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_services', function (Blueprint $table) {
            $table->id();

            // Booking
            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            // Inventory Item (optional)
            $table->foreignId('item_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Chargeable / Complimentary
            $table->enum('type', [
                'chargeable',
                'complimentary',
            ]);

            // Service Name (for non-inventory services)
            $table->string('service_name')->nullable();

            // Quantity
            $table->unsignedInteger('quantity')->default(1);

            // Pricing
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);

            // Remarks
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_services');
    }
};