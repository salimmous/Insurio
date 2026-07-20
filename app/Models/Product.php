<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'code',
        'nom',
        'description',
        'statut',
    ];

    public function contrats()
    {
        return $this->hasMany(ContratAuto::class, 'product_id');
    }
}
