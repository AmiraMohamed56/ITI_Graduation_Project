<?php

namespace App\Traits;

use App\Models\AdminLog;
use Illuminate\Support\Facades\Auth;

trait AdminLoggable
{
    public static function bootAdminLoggable()
    {
        static::created(function ($model) {
            $model->logAdminAction('created');
        });

        static::updated(function ($model) {
            $model->logAdminAction('updated');
        });

        static::deleted(function ($model) {
            $model->logAdminAction('deleted');
        });
    }

    public function logAdminAction($action)
    {
        $user = Auth::user();

        $desc = ($user ? $user->name : 'System') . " performed '{$action}' on " . class_basename($this);

        if ($action === 'updated') {
            $changes = $this->getChanges();
            unset($changes['updated_at']);
            if (!empty($changes)) {
                $desc .= " | Changes: ";
                foreach ($changes as $field => $value) {
                    $val = is_null($value) ? 'null' : $value;
                    $desc .= "$field = '$val', ";
                }
                $desc = rtrim($desc, ', ');
            }
        }

        AdminLog::create([
            'user_id'    => $user?->id,
            'action'     => $action,
            'model'      => class_basename($this),
            'model_id'   => $this->id,
            'description' => $desc,
            'ip_address' => request()->ip(),
        ]);
    }
}
