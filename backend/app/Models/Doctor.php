<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\AdminLoggable;
class Doctor extends Model
{
    use HasFactory, SoftDeletes, AdminLoggable;

    protected $fillable = [
        'user_id', 'specialty_id', 'bio', 'education', 'years_experience',
        'gender', 'consultation_fee', 'rating', 'available_for_online'
    ];

    protected $casts = [
        'available_for_online' => 'boolean',
        'rating' => 'float',
        'consultation_fee' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
