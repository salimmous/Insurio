<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use App\Models\Contract;
use App\Models\Employe;
use App\Models\CommissionEmploye;
use App\Models\Dossier;

class SuccursaleScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (!auth()->check()) {
            return;
        }

        $user = auth()->user();

        // 1. agency-admin sees everything (no scope applied)
        if ($user->hasRole('agency-admin')) {
            return;
        }

        // 2. Load the linked employee profile without global scopes to prevent recursion
        $employe = Employe::withoutGlobalScope(SuccursaleScope::class)
            ->where('user_id', $user->id)
            ->first();

        if (!$employe) {
            return;
        }

        $succursaleId = $employe->succursale_id;
        $employeId = $employe->id;

        // 3. Apply scoping based on role
        if ($user->hasRole('responsable-succursale')) {
            if ($model instanceof Contract || $model instanceof Employe || $model instanceof Dossier) {
                $builder->where($model->getTable() . '.succursale_id', $succursaleId);
            } elseif ($model instanceof CommissionEmploye) {
                $builder->whereHas('employe', function ($q) use ($succursaleId) {
                    $q->where('succursale_id', $succursaleId);
                });
            }
        } elseif ($user->hasRole('agent-commercial')) {
            if ($model instanceof Contract || $model instanceof CommissionEmploye) {
                $builder->where($model->getTable() . '.employe_id', $employeId);
            } elseif ($model instanceof Employe) {
                $builder->where($model->getTable() . '.id', $employeId);
            } elseif ($model instanceof Dossier) {
                $builder->where($model->getTable() . '.assigned_employee_id', $employeId);
            }
        }
    }
}
