<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Support\Facades\Log;

class PasswordResetCode extends Model
{
    use HasFactory;

    protected $fillable = ['email', 'code'];
    public $timestamps = false;

    protected static function booted()
    {
        static::created(function ($model) {
            Log::info("Password reset code generated for {$model->email}");
        });

        static::deleted(function ($model) {
            Log::info("Password reset code cleared for {$model->email}");
        });
    }

    /**
     * Generate a new 4-digit code
     */
    public static function generateCode(): string
    {
        $code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        Log::debug("Generated new password reset code: {$code}");
        return $code;
    }

    /**
     * Create and send a new verification code
     */
    public static function createAndSendCode(string $email): ?self
    {
        try {
            Log::info("Attempting to create password reset code for {$email}");

            // First, delete any existing reset codes for the email
            self::deleteExistingCodes($email);

            // Generate a new reset code
            $code = self::generateCode();

            // Set the created_at to the current time in Kigali time zone
            $createdAt = Carbon::now('Africa/Kigali');

            // Create a new password reset record with Kigali time zone
            $resetCode = self::create([
                'email' => $email,
                'code' => $code,
                'created_at' => $createdAt
            ]);

            Log::info("Password reset code created for {$email}");

            // Send the reset code email
            Mail::to($email)->send(new PasswordResetCodeMail($code));
            Log::info("Password reset code sent to {$email}");

            return $resetCode;
        } catch (\Exception $e) {
            Log::error("Failed to send password reset code to {$email}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete all existing codes for an email
     */
    public static function deleteExistingCodes(string $email): void
    {
        $count = self::where('email', $email)->delete();
        Log::info("Cleared {$count} existing password reset codes for {$email}");
    }

    /**
     * Resend the verification code
     */
    public function resend(): bool
    {
        try {
            Log::info("Resending password reset code to {$this->email}");

            // Resend the reset code email
            Mail::to($this->email)->send(new PasswordResetCodeMail($this->code));
            Log::info("Password reset code resent to {$this->email}");

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to resend password reset code to {$this->email}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify the code
     */
    public static function verifyCode(string $email, string $code): bool
    {
        $isValid = self::where('email', $email)
            ->where('code', $code)
            ->where('created_at', '>=', Carbon::now('Africa/Kigali')->subMinutes(30))
            ->exists();

        if ($isValid) {
            Log::info("Password reset code verified for {$email}");
        } else {
            Log::warning("Invalid password reset code attempt for {$email}");
        }

        return $isValid;
    }
}
