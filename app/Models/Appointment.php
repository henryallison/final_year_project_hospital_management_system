<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'purpose',
        'description',
        'status',
        'created_by'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    protected $appends = [
        'formatted_date',
        'is_past'
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::updating(function ($appointment) {
            $original = $appointment->getOriginal();
            $changes = [];

            foreach ($appointment->getDirty() as $attribute => $newValue) {
                if ($attribute !== 'updated_at') {
                    $oldValue = $original[$attribute] ?? null;
                    $changes[] = "$attribute: $oldValue → $newValue";
                }
            }

            if (!empty($changes)) {
                $changer = auth()->user() ? auth()->user()->email : 'System';
                Log::info("Appointment #{$appointment->id} updated by $changer: " . implode(', ', $changes));
            }
        });

        static::created(function ($appointment) {
            $creator = auth()->user() ? auth()->user()->email : 'System';
            Log::info("Appointment #{$appointment->id} created by $creator for patient {$appointment->patient_id}");
        });

        static::deleted(function ($appointment) {
            $deleter = auth()->user() ? auth()->user()->email : 'System';
            $action = $appointment->isForceDeleting() ? 'permanently deleted' : 'soft-deleted';
            Log::warning("Appointment #{$appointment->id} $action by $deleter");
        });
    }

    /**
     * Relationships
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Accessors & Mutators
     */
    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->appointment_date->format('M d, Y h:i A')
        );
    }

    protected function isPast(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->appointment_date->isPast()
        );
    }

    /**
     * Scopes
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>', now())
            ->orderBy('appointment_date');
    }

    public function scopePast($query)
    {
        return $query->where('appointment_date', '<=', now())
            ->orderByDesc('appointment_date');
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Business Logic
     */
    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
        Log::info("Appointment #{$this->id} cancelled by ".auth()->user()?->email);
        return $this;
    }

    public function reschedule($newDate)
    {
        $this->update([
            'appointment_date' => $newDate,
            'status' => 'rescheduled'
        ]);
        Log::info("Appointment #{$this->id} rescheduled by ".auth()->user()?->email);
        return $this;
    }

    public function complete()
    {
        $this->update(['status' => 'completed']);
        Log::info("Appointment #{$this->id} marked completed by ".auth()->user()?->email);
        return $this;
    }

    /**
     * Status Check Helpers
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isUpcoming()
    {
        return !$this->isPast() && !$this->isCancelled() && !$this->isCompleted();
    }
}
