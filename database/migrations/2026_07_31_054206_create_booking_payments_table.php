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
        Schema::create('booking_payments', function (Blueprint $table) {

            $table->id();

            // Booking
            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            // Payment
            $table->decimal('amount', 10, 2);

            $table->enum('payment_type', [
                'advance',
                'partial',
                'final',
                'refund',
            ]);

            $table->enum('payment_method', [
                'cash',
                'upi',
                'card',
                'bank_transfer',
            ]);

            // Optional Reference
            $table->string('transaction_no')->nullable();

            // Optional Notes
            $table->text('remarks')->nullable();

            // Received By
            $table->foreignId('received_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Payment Date
            $table->timestamp('paid_at')->useCurrent();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_payments');
    }
};