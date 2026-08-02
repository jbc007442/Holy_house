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
        Schema::create('items', function (Blueprint $table) {
            $table->id();

            // Item Information
            $table->string('item_name');

            // Category
            $table->string('category');

            // Unit
            $table->string('unit')->default('Nos');

            // Purchase Price
            $table->decimal('purchase_price', 10, 2)->default(0);

            // Stock
            $table->unsignedInteger('opening_stock')->default(0);
            $table->unsignedInteger('minimum_stock')->default(0);

            // Status
            $table->boolean('status')->default(true);

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
        Schema::dropIfExists('items');
    }
};