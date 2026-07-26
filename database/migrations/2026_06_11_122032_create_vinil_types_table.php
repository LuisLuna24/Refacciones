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
        Schema::create('vinil_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('pressure', 10, 2)->nullable();
            $table->decimal('price_default', 10, 2)->nullable();
            $table->string('unity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vinil_types');
    }
};
