<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AdminLoggable;
class Appointment extends Model
{
    use HasFactory, AdminLoggable;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'doctor_schedule_id',
        'schedule_date',
        'schedule_time',
        'type',
        'status',
        'price',
        'notes',
        'reminder_24h_sent_at',
        'reminder_12h_sent_at',
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'schedule_time' => 'datetime:H:i',
        'price' => 'decimal:2',
        'reminder_24h_sent_at' => 'datetime',
        'reminder_12h_sent_at' => 'datetime',
    ];

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function doctorSchedule()
    {
        return $this->belongsTo(DoctorSchedule::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }



    // Reminders
    // Check if 24h reminder should be sent
    public function shouldSend24HourReminder()
    {
        if ($this->reminder_24h_sent_at || $this->status === 'cancelled') {
            return false;
        }
        $appointmentDateTime = $this->schedule_date->setTimeFromTimeString($this->schedule_time);
        $hoursDifference = now()->diffInHours($appointmentDateTime, false);
        return $hoursDifference >= 23 && $hoursDifference <= 25;
    }


     // Check if 12h reminder should be sent
    public function shouldSend12HourReminder()
    {
        if ($this->reminder_12h_sent_at || $this->status === 'cancelled') {
            return false;
        }
        $appointmentDateTime = $this->schedule_date->setTimeFromTimeString($this->schedule_time);
        $hoursDifference = now()->diffInHours($appointmentDateTime, false);
        return $hoursDifference >= 11 && $hoursDifference <= 13;
    }



     // Mark 24h reminder as sent
    public function mark24HourReminderSent()
    {
        $this->update(['reminder_24h_sent_at' => now()]);
    }


    // Mark 12h reminder as sent
    public function mark12HourReminderSent()
    {
        $this->update(['reminder_12h_sent_at' => now()]);
    }

}
