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
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->string('guest_name', 100)->nullable()->after('customer_id');
            $table->string('guest_phone', 20)->nullable()->after('guest_name');
            $table->string('guest_email', 100)->nullable()->after('guest_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->dropColumn('guest_name');
            $table->dropColumn('guest_phone');
            $table->dropColumn('guest_email');
        });
    }
};
