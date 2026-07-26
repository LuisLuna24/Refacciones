<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VinilPrice extends Model
{
    protected $fillable = ['vinil_type_id', 'name', 'price'];

    public function vinilType()
    {
        return $this->belongsTo(VinilType::class);
    }
}
