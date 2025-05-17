<?php

// app/Models/Doctor.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    // Allow mass assignment for the specified fields
    protected $fillable = [
        'name',
        'specialization', // ✅ use 'specialization' instead of 'specialty'
        'contact_number',
        'address',
    ];
}
