<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLoggable;
class Payment extends Model
{
    use HasFactory, AdminLoggable;

    protected $fillable = [
        'appointment_id',
        'patient_id',
        'amount',
        'status',
        'method',
        'transaction_id',
        'payment_proof'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getPaymentProofUrlAttribute()
    {
        return $this->payment_proof ? asset('storage/' . $this->payment_proof) : null;
    }
}
