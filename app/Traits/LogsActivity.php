<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('create', $model);
        });

        static::updated(function ($model) {
            self::logActivity('update', $model);
        });

        static::deleted(function ($model) {
            self::logActivity('delete', $model);
        });
    }

    protected static function logActivity($action, $model)
    {
        $userId = Auth::id() ?? 1; // Default ke 1 jika tidak ada user (misal dari seeder)
        $modelName = class_basename($model);
        $description = "{$action} {$modelName} ID #{$model->id}";

        AuditLog::create([
            'user_id' => $userId,
            'action' => $action,
            'model' => $modelName,
            'description' => $description,
            'created_at' => now(),
        ]);
    }
}