<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AdminLoggable;
class Specialty extends Model
{
    use HasFactory, AdminLoggable;

    protected $fillable = ['name'];

    // Relationships
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
