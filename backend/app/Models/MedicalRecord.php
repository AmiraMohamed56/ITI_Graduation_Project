<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\AdminLoggable;
class MedicalRecord extends Model
{
    use HasFactory, SoftDeletes, AdminLoggable;

    protected $fillable = [
        'appointment_id', 'doctor_id', 'patient_id', 'symptoms', 'diagnosis', 'medication'
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicalFiles()
    {
        return $this->hasMany(MedicalFile::class);
    }


    // alias for consistency with the blade file
    public function files()
    {
        return $this->medicalFiles();
    }
}
