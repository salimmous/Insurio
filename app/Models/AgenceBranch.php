<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgenceBranch extends Model
{
    protected $table = 'agence_branches';
    
    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'responsable',
        'statut',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'branch_id');
    }
}
