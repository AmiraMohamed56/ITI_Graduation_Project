<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLoggable;
class Review extends Model
{
    use HasFactory, AdminLoggable;

    protected $fillable = ['patient_id', 'doctor_id', 'rating', 'comment'];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
