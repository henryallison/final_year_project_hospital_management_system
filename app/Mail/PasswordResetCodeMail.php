<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PasswordResetCodeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $code;
    public $expiresAt;
    public $appName;
    public $supportEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, string $expiresAt = null)
    {
        $this->code = $code;
        // Ensure expiration time is in Kigali timezone
        $this->expiresAt = $expiresAt ?? Carbon::now('Africa/Kigali')->addMinutes(15)->format('F j, Y, g:i a T');
        $this->appName = config('app.name');
        $this->supportEmail = config('mail.support_email', 'hyallison5050@gmail.com');
    }


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->appName . ' Password Reset Verification Code',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.password-reset-code',
            with: [
                'code' => $this->code,
                'expiresAt' => $this->expiresAt,
                'appName' => $this->appName,
                'supportEmail' => $this->supportEmail
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Failed to send password reset email: ' . $exception->getMessage(), [
            'exception' => $exception,
            'code' => $this->code,
            'recipient' => $this->to[0]['address'] ?? 'unknown',
            'time' => now()->toDateTimeString()
        ]);
    }

    /**
     * Set the queue connection.
     */
    public function viaConnection(): string
    {
        return config('queue.default', 'sync');
    }

    /**
     * Set the queue name.
     */
    public function viaQueue(): string
    {
        return 'emails';
    }
}
