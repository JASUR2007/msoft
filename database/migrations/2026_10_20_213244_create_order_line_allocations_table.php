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
        Schema::create('order_line_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_line_id')->index()->constrained('order_lines')->restrictOnDelete();
            $table->foreignId('batch_line_id')->index()->constrained('batch_lines')->restrictOnDelete();
            $table->integer('quantity');
            $table->decimal('sale_price', 18, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_line_allocations');
    }
};
