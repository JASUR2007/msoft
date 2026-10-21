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
            $table->foreignId('batch_line_id')->constrained('batch_lines')
                ->restrictOnDelete();
            $table->string('type', 30);
            $table->integer('quantity_change');
            $table->timestamp('occurred_at');
            $table->timestamps();
            $table->index(['batch_line_id', 'occurred_at']);
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
