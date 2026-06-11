<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VinilType extends Model
{
    protected $fillable = ['name', 'pressure'];

    public function prices()
    {
        return $this->hasMany(VinilPrice::class);
    }
}
