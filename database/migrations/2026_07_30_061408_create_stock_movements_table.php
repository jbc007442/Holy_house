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

            // Building
            $table->foreignId('building_id')
                ->nullable()
                ->constrained('buildings')
                ->nullOnDelete();

            // Building Floor
            $table->foreignId('building_floor_id')
                ->nullable()
                ->constrained('building_floors')
                ->nullOnDelete();

            // Room
            $table->foreignId('room_id')
                ->nullable()
                ->constrained('rooms')
                ->nullOnDelete();

            // Kitchen
            $table->boolean('kitchen')
                ->default(false);

            // Other Property
            $table->string('other_property')
                ->nullable();

            // Movement Type
            $table->enum('type', [
                'out',
                'adjustment',
            ]);

            // Quantity
            $table->unsignedInteger('quantity');

            // Remarks
            $table->text('remarks')
                ->nullable();

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