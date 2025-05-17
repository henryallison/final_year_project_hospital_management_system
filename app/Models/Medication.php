<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'dosage',
        'frequency',
        'start_date',
        'due_date',
    ];

    // This connects medication back to its patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
