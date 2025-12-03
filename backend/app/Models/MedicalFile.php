<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFile extends Model
{
    use HasFactory;

    protected $fillable = ['medical_record_id', 'file_path'];

    // Relationships
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    // Accessor for file URL
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }

    // Accessor for file name
    public function getNameAttribute(): string
    {
        return basename($this->file_path);
    }

    // Accessor for file extension
    public function getExtensionAttribute(): string
    {
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION));
    }
}
