<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\AdminLoggable;
class Contact extends Model
{
    use HasFactory, AdminLoggable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'replied',
    ];
}
