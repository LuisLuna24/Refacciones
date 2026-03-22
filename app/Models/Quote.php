<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $table = "quotes";

    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'customer_id',
        'warehouse_id',
        'total',
        'observation',
        'status',
        'payment_method'
    ];

    protected $casts = [
        'date' => 'date',
        'payment_method' => PaymentMethod::class,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->hasOne(Sale::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')
            ->withPivot([
                'quantity',
                'price',
                'subtotal',
                'ck_pakage',       // Agregado
                'quantity_pacage'  // Agregado
            ])
            ->withTimestamps();
    }
}
