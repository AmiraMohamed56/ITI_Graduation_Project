<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\AdminLoggable;
class Invoice extends Model
{
    use HasFactory, AdminLoggable;

    protected $fillable = ['appointment_id', 'total', 'pdf_path'];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // Relationships
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
