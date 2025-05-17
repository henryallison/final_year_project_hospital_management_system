<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'nurse_id',
        'title',
        'description',
        'status',
        'due_date',
        'completed_at',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }

    public function scopeForDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeForNurse($query, $nurseId)
    {
        return $query->where('nurse_id', $nurseId);
    }

    public function scopeForPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }
public function scopeOverdue($query)
{
    return $query->where('due_date', '<', now())->where('status', '!=', 'completed');
}


public function getStatusBadgeColorAttribute()
{
    return match($this->status) {
        'pending' => 'secondary',
        'in_progress' => 'primary',
        'completed' => 'success',
        'failed' => 'danger',
        default => 'secondary',
    };
}

}
