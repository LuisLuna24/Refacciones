<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('productables', function (Blueprint $table) {
            $table->boolean('ck_pakage')->default(false)->before('quantity');
            $table->integer('quantity_pacage')->nullable()->after('ck_pakage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productables', function (Blueprint $table) {
            $table->dropColumn('ck_pakage');
            $table->dropColumn('quantity_pacage');
        });
    }
};
