<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property \Illuminate\Support\Carbon|null $date_effet
 * @property \Illuminate\Support\Carbon|null $date_echeance
 * @property \Illuminate\Support\Carbon|null $date_production
 * @property \Illuminate\Support\Carbon|null $date_resiliation
 * @property \Illuminate\Support\Carbon|null $date_mise_circulation
 */
class ContratAuto extends Contract
{
    // Inherits all generic properties, polymorphic detail bindings, and compatibility mappings
}
