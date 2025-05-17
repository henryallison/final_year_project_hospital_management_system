<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\AuditLogger;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AuditLogger;

    const PROFILE_IMAGE_MAX_SIZE = 2048; // 2MB
    const PROFILE_IMAGE_WIDTH = 500;
    const PROFILE_IMAGE_HEIGHT = 500;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'license_number',
        'phone',
        'address',
        'date_of_birth',
        'gender',
        'profile_image',
        'last_login_at',
        'last_login_ip',
        'is_active',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'date_of_birth' => 'date',
    ];

    protected $appends = ['profile_image_url'];

    public static function getDefaultAvatarUrl()
    {
        return asset('images/default-avatar.png');
    }

    public static function validateProfileImage()
{
    return [
        'nullable',
        'image',
        'mimes:jpeg,png,jpg,gif',
        'max:'.self::PROFILE_IMAGE_MAX_SIZE,
        'dimensions:min_width='.self::PROFILE_IMAGE_WIDTH.',min_height='.self::PROFILE_IMAGE_HEIGHT
    ];
}

    public function deleteProfileImage()
{
    try {
        if ($this->profile_image) {
            // Only delete if it's a local file (not a URL)
            if (!filter_var($this->profile_image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($this->profile_image);
            }
            $this->update(['profile_image' => null]);
        }
    } catch (\Exception $e) {
        Log::error("Failed to delete profile image for user {$this->id}: " . $e->getMessage());
        // Consider throwing the exception if you want calling code to handle it
        // throw $e;
    }
}

    protected static function booted()
{
    static::updating(function ($user) {
        try {
            // Handle profile image changes
            if ($user->isDirty('profile_image')) {
                $originalImage = $user->getOriginal('profile_image');

                // Delete old image if it exists and is a local file
                if ($originalImage && !filter_var($originalImage, FILTER_VALIDATE_URL)) {
                    Storage::disk('public')->delete($originalImage);
                }
            }

            // Keep all your existing audit logging logic
            $original = $user->getOriginal();
            $changes = [];
            $sensitiveFields = ['password', 'remember_token'];

            foreach ($user->getDirty() as $attribute => $newValue) {
                if (in_array($attribute, ['updated_at', 'last_login_at'])) {
                    continue;
                }

                $oldValue = $original[$attribute] ?? null;

                if (in_array($attribute, $sensitiveFields)) {
                    $changes[] = "$attribute: [redacted]";
                } else {
                    $displayOld = is_null($oldValue) ? 'null' : (strlen($oldValue) > 50 ? substr($oldValue, 0, 50).'...' : $oldValue);
                    $displayNew = is_null($newValue) ? 'null' : (strlen($newValue) > 50 ? substr($newValue, 0, 50).'...' : $newValue);
                    $changes[] = "$attribute: $displayOld → $displayNew";
                }
            }

            if (!empty($changes)) {
                $changer = auth()->user() ? auth()->user()->email : 'System';
                Log::channel('user_activity')->info("User {$user->email} updated by $changer", [
                    'changes' => $changes,
                    'ip' => request()->ip()
                ]);
            }

        } catch (\Exception $e) {
            Log::channel('user_activity')->error("Failed to process user update for {$user->email}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    });

    static::deleting(function ($user) {
        try {
            // Handle profile image deletion
            if ($user->profile_image && !filter_var($user->profile_image, FILTER_VALIDATE_URL)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // Keep all your existing deletion logging logic
            $deleter = auth()->user() ? auth()->user()->email : 'System';
            Log::channel('user_activity')->warning("User {$user->email} deleted by $deleter", [
                'ip' => request()->ip(),
                'last_login' => $user->last_login_at,
                'role' => $user->role
            ]);

            if (method_exists($user, 'doctorPatients')) {
                $user->doctorPatients()->update(['doctor_id' => null]);
            }
            if (method_exists($user, 'nursePatients')) {
                $user->nursePatients()->update(['nurse_id' => null]);
            }

        } catch (\Exception $e) {
            Log::channel('user_activity')->error("Failed to process user deletion for {$user->email}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if (app()->environment('production')) {
                throw $e;
            }
        }
    });
}
    public function getProfileImageUrlAttribute()
{
    // Return default if no image
    if (empty($this->profile_image)) {
        return asset('images/default-avatar.png');
    }

    // Return as-is if already a full URL
    if (filter_var($this->profile_image, FILTER_VALIDATE_URL)) {
        return $this->profile_image;
    }

    // Generate storage URL for local files
    return asset('storage/'.$this->profile_image);
}

    public function roleBadgeColor()
    {
        return match($this->role) {
            'admin' => 'dark',
            'doctor' => 'success',
            'nurse' => 'warning',
            default => 'secondary',
        };
    }

    public function canEditPatient($patient)
{
    return $this->isAdmin() ||
           $this->id === $patient->doctor_id;
}

    public function doctorPatients()
    {
        return $this->hasMany(Patient::class, 'doctor_id');
    }

    public function nursePatients()
    {
        return $this->hasMany(Patient::class, 'nurse_id');
    }

    public function isDoctor()
    {
        return $this->role === 'doctor';
    }

    public function isNurse()
    {
        return $this->role === 'nurse';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function recordLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip()
        ]);

        Log::info("User {$this->email} logged in from IP: ".request()->ip());
    }
}
