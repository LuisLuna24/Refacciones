<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VinilType extends Model
{
    protected $fillable = ['name', 'pressure', 'unity', 'price_default'];

    public function prices()
    {
        return $this->hasMany(VinilPrice::class);
    }
}
