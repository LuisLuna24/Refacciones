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
        Schema::create('box_buts', function (Blueprint $table) {
            $table->id();
            // Quién abrió la caja y quién la supervisó/cerró
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('users'); // Nullable si aún no se cierra

            $table->foreignId('warehouse_id')->constrained('warehouses');

            // Identificadores del documento de corte
            $table->string('voucher_type', 50)->default('CORTE');
            $table->string('serie', 10)->nullable();
            $table->string('correlative', 20)->nullable();

            // Tiempos
            $table->timestamp('opened_at')->useCurrent();
            $table->timestamp('closed_at')->nullable(); // En lugar de periodo_fin
            $table->enum('type', ['diario', 'rango'])->default('diario');

            // Totales y Auditoría de efectivo
            $table->decimal('opening_amount', 14, 2)->default(0); // Fondo inicial
            $table->decimal('total_sales', 14, 2)->default(0);
            $table->decimal('total_returns', 14, 2)->default(0);
            $table->decimal('final_amount', 14, 2)->default(0); // Lo que el cajero dice que hay

            $table->text('observation')->nullable();
            $table->tinyInteger('status')->default(1); // 1: Abierto, 2: Cerrado, 0: Anulado

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_buts');
    }
};
