<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomAnalysis extends Model
{
    use HasFactory;

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
