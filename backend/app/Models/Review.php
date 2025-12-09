<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLoggable;
class Review extends Model
{
    use HasFactory, AdminLoggable;

    protected $fillable = [
      'patient_id'
    , 'doctor_id'
    , 'rating'
    , 'comment'
    ,'status'
    ,'reviewed_at'
];

protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    // Relationships
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }


     // for the reviews status
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
