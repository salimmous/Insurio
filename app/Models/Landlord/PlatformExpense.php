<?php

namespace App\Models\Landlord;

use Illuminate\Database\Eloquent\Model;

class PlatformExpense extends Model
{
    protected $table = 'platform_expenses';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if (!app()->runningUnitTests()) {
            $this->connection = 'landlord';
        }
    }

    protected $fillable = [
        'title',
        'category',
        'amount',
        'expense_date',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
    ];
}
