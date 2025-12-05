<?php

namespace App\Traits;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;

trait AdminLoggable
{
    public static function bootAdminLoggable()
    {
        static::created(function ($model) {
            self::logAction('created', $model);
        });

        static::updated(function ($model) {
            self::logAction('updated', $model);
        });

        static::deleted(function ($model) {
            self::logAction('deleted', $model);
        });
    }

    protected static function logAction(string $action, $model)
    {
        AdminLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'model'       => get_class($model),
            'model_id'    => $model->id,
            'description' => json_encode($model->getChanges()),
            'ip_address'  => request()->ip(),
        ]);
    }
}
