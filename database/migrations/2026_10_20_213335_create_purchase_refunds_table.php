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
        Schema::create('purchase_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_line_id')->index()->constrained('batch_lines')->restrictOnDelete();
            $table->decimal('amount', 18, 2);
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_refunds');
    }
};
