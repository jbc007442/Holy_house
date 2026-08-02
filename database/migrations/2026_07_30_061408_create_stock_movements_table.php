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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            // Inventory Item
            $table->foreignId('item_id')
                ->constrained()
                ->cascadeOnDelete();

            // Movement Type
            $table->enum('type', [
                'out',
                'adjustment',
            ]);

            // Quantity
            $table->unsignedInteger('quantity');

            // Reference
            $table->string('reference')->nullable();

            // Remarks
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('stock_movements');
    }
};