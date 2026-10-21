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
            $table->foreignId('batch_id');
            $table->foreignId('product_id');
            $table->foreignId('storage_id');
            $table->integer('quantity');
            $table->integer('available_quanity');
            $table->integer('purchase_price');
            $table->integer('sale_price');
            $table->timestamps();
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
