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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            // Building
            $table->foreignId('building_id')
                ->constrained()
                ->cascadeOnDelete();

            // Room Details
            $table->string('room_number');
            $table->string('floor')->nullable();

            // A room number must be unique within a building
            $table->unique(['building_id', 'room_number']);

            // Capacity
            $table->unsignedTinyInteger('capacity')->default(2);

            // Base Price
            $table->decimal('base_price', 10, 2);

            // Room Status
            $table->enum('status', [
                'available',
                'running',
                'blocked',
                'maintenance',
            ])->default('available');

            // Description
            $table->text('description')->nullable();

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
        Schema::dropIfExists('rooms');
    }
};