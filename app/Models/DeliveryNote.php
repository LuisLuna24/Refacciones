<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryNote extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id',
        'guest_name',
        'guest_phone',
        'guest_email',
        'warehouse_id',
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'total',
        'installment',
        'observation',
        'status',
    ];
    protected $casts = [
        'date' => 'datetime',
        'total' => 'decimal:2',
        'installment' => 'decimal:2',
        'status' => 'integer',
    ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function items()
    {
        return $this->hasMany(DeliveryNoteItem::class);
    }
}
