<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BoxCut extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_id',
        'warehouse_id',
        'voucher_type',
        'serie',
        'correlative',
        'opened_at',
        'closed_at',
        'type',
        'opening_amount',
        'total_sales',
        'total_returns',
        'final_amount',
        'observation',
        'status',
    ];

    /**
     * Casts para fechas y precisión decimal.
     */
    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
        'opening_amount' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_returns' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'status' => 'integer',
    ];

    /**
     * El cajero que abrió la caja.
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * El administrador que supervisa o cierra.
     */
    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * El almacén o sucursal.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Relación con las ventas asociadas a este corte.
     * Asumiendo que tu tabla intermedia se llama 'box_cut_sale'
     * (siguiendo el cambio de nombre de la tabla principal).
     */
    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Sale::class, 'box_cut_sale')
                    ->withTimestamps();
    }

    public function isOpen(): bool
    {
        return $this->status === 1 && is_null($this->closed_at);
    }

    /**
     * Calcula la diferencia entre lo esperado (ventas + fondo) y lo reportado.
     */
    public function getDifferenceAttribute()
    {
        $expected = $this->opening_amount + $this->total_sales - $this->total_returns;
        return $this->final_amount - $expected;
    }
}
