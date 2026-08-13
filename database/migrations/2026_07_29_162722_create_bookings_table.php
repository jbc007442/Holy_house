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
        Schema::create('bookings', function (Blueprint $table) {

            $table->id();

            // Booking Information
            $table->string('booking_no')->unique();

            // Room
            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete();

            // Stay Details
            $table->date('check_in');
            $table->date('check_out')->nullable();

            // Guests
            $table->unsignedTinyInteger('guest_count')->default(1);

            // Room Charges
            $table->decimal('room_rent', 10, 2)->default(0);

            // Service Charges
            $table->decimal('chargeable_amount', 10, 2)->default(0);

            $table->decimal('complimentary_amount', 10, 2)->default(0);

            // Grand Total
            $table->decimal('total_amount', 10, 2)->default(0);

            // Payment Summary
            $table->decimal('paid_amount', 10, 2)->default(0);

            $table->decimal('balance_amount', 10, 2)->default(0);

            $table->enum('payment_status', [
                'pending',
                'partial',
                'paid',
                'refunded',
            ])->default('pending');

            // Booking Status
            $table->enum('status', [
                'reserved',
                'checked_in',
                'checked_out',
                'cancelled',
            ])->default('reserved');

            // Remarks
            $table->text('remarks')->nullable();

            // Invoice Details
            $table->enum('rate_type', [
                'EP',
                'CP',
                'MAP',
            ])->nullable();

            $table->string('bill_to')->nullable();

            $table->string('bill_to_gstin')->nullable();

            $table->string('hsn_code')->default('998552');

            $table->decimal('discount', 10, 2)->default(0);

            // Remarks
            $table->text('discount_remark')->nullable();

            // Audit

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};