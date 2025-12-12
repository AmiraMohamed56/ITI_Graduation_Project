<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'model_id',
        'description',
        'ip_address'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($log) {
            if (empty($log->description)) {
                $userName = $log->user ? $log->user->name : 'System';

                $desc = "{$userName} performed '{$log->action}' on {$log->model}";

                if (!empty($log->model_id)) {
                    $desc .= " #{$log->model_id}";
                }

                if (isset($log->changes_data) && is_array($log->changes_data)) {
                    $changes = [];
                    foreach ($log->changes_data as $field => $value) {
                        if ($field === 'updated_at') continue;
                        $val = is_null($value) ? 'null' : $value;
                        $changes[] = "$field = '$val'";
                    }
                    if ($changes) {
                        $desc .= " | Changes: " . implode(', ', $changes);
                    }
                }

                $log->description = $desc;
            }
        });
    }
}
