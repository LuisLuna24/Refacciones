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
        Schema::table('users', function (Blueprint $table) {
            // El orden correcto es: ID -> ¿Es nulo? -> Colocación -> Restricción
            $table->foreignId('warehouse_id')
                ->after('type_user_id')
                ->nullable()
                ->constrained('warehouses')
                ->nullOnDelete(); // Opcional: pone en null si se borra el almacén
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Es mejor eliminar primero la restricción de llave foránea y luego la columna
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });
    }
};
