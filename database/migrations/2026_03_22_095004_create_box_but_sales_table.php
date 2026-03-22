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
        Schema::create('box_but_sales', function (Blueprint $table) {
            $table->id();

            $table->foreignId('box_but_id')
                ->constrained('box_buts')
                ->onDelete('cascade');

            $table->foreignId('sale_id')
                ->constrained('sales')
                ->onDelete('cascade');

            // Evita que una venta se repita en el mismo corte
            $table->unique(['box_but_id', 'sale_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_but_sales');
    }
};
