<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\AuditLogger;
use Illuminate\Support\Facades\Log;

class Patient extends Model
{
    use HasFactory, SoftDeletes, AuditLogger;

    protected $fillable = [
        'name',
        'date_of_birth',
        'gender',
        'contact_number',
        'address',
        'medical_history',
        'allergies',
        'current_medications',
        'encrypted_data',
        'doctor_id',
        'nurse_id',
        'status',
        'admission_date',
        'discharge_date',
        'image'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'discharge_date' => 'date',
        'deleted_at' => 'datetime'
    ];

    protected $appends = [
        'age',
        'is_active',
        'image_path'
    ];

    /**
     * Relationships
     */
    public function medications()
    {
        return $this->hasMany(Medication::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')->withDefault([
            'name' => 'Unassigned'
        ]);
    }

    public function nurse()
    {
        return $this->belongsTo(User::class, 'nurse_id')->withDefault([
            'name' => 'Unassigned'
        ]);
    }

    /**
     * Accessors & Mutators
     */
    protected function age(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->date_of_birth ? Carbon::parse($this->date_of_birth)->age : null
        );
    }

    protected function isActive(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'active'
        );
    }

    protected function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image
                ? asset('storage/patients/' . $this->image)
                : asset('images/default-avatar.png')
        );
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCritical($query)
    {
        return $query->where('status', 'critical');
    }

    public function scopeDischarged($query)
    {
        return $query->where('status', 'discharged');
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByNurse($query, $nurseId)
    {
        return $query->where('nurse_id', $nurseId);
    }

    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('updated_at', 'desc')->limit($limit);
    }

    /**
     * Business Logic
     */
    public function discharge()
    {
        $this->update([
            'status' => 'discharged',
            'discharge_date' => now()
        ]);

        Log::info("Patient ID {$this->id} discharged by " . (auth()->user() ? auth()->user()->email : 'System'));
    }

    public function transferToDoctor($newDoctorId)
    {
        $this->update([
            'doctor_id' => $newDoctorId,
            'status' => 'transferred'
        ]);

        Log::info("Patient ID {$this->id} transferred to doctor {$newDoctorId} by " . (auth()->user() ? auth()->user()->email : 'System'));
    }

    /**
     * Custom logging for specific patient events
     */
    public function logCustomEvent($event, $details = null)
    {
        $user = auth()->user() ? auth()->user()->email : 'System';
        $message = "{$user} performed {$event} on Patient ID: {$this->id}";

        if ($details) {
            $message .= " - Details: " . json_encode($details);
        }

        Log::info($message);
    }
}
