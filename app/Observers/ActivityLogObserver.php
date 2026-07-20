<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        $newValues = $model->getAttributes();
        // Remove sensitive fields
        unset($newValues['password'], $newValues['remember_token']);
        
        ActivityLog::writeLog('created', $model, null, $newValues);
    }

    public function updated(Model $model): void
    {
        $oldValues = [];
        $newValues = [];

        foreach ($model->getDirty() as $key => $newValue) {
            if (in_array($key, ['password', 'remember_token', 'updated_at'])) {
                continue;
            }
            $oldValues[$key] = $model->getOriginal($key);
            $newValues[$key] = $newValue;
        }

        if (!empty($newValues)) {
            ActivityLog::writeLog('updated', $model, $oldValues, $newValues);
        }
    }

    public function deleted(Model $model): void
    {
        $oldValues = $model->getAttributes();
        unset($oldValues['password'], $oldValues['remember_token']);
        
        ActivityLog::writeLog('deleted', $model, $oldValues, null);
    }
}
