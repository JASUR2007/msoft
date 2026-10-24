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
        Schema::create('batch_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->index()->constrained('batches')->restrictOnDelete();
            $table->foreignId('product_id')->index()->constrained('products')->restrictOnDelete();
            $table->foreignId('storage_id')->index()->constrained('storages')->restrictOnDelete();
            $table->integer('quantity');
            $table->integer('available_quantity');
            $table->decimal('purchase_price', 18, 2);
            $table->decimal('sale_price', 18, 2);
            $table->timestamps();
            $table->index(['product_id', 'created_at', 'id'], 'batch_lines_fifo_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_lines');
    }
};
