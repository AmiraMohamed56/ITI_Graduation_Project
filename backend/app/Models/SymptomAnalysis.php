<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLoggable;
class SymptomAnalysis extends Model
{
    use HasFactory, AdminLoggable;

    protected $fillable = [
        'user_id',
        'symptoms',
        'ai_response',
        'ip'
    ];

    protected $casts = [
        'ai_response' => 'array'
    ];
}
