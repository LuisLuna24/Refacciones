<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BoxButSale extends Pivot
{
    protected $table = 'box_but_sales';

    public $incrementing = true;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'box_but_id',
        'sale_id',
    ];

}
