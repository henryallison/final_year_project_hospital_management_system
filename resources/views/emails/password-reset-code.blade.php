@component('mail::message')
<style>
    .header {
        color: #2d3748;
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    .code-container {
        background: #f7fafc;
        border: 1px dashed #cbd5e0;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        margin: 25px 0;
    }
    .verification-code {
        font-family: 'Courier New', monospace;
        font-size: 28px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #4299e1;
        padding: 8px 16px;
        background: white;
        border-radius: 4px;
        display: inline-block;
    }
    .divider {
        border-top: 1px solid #e2e8f0;
        margin: 25px 0;
    }
    .footer {
        color: #718096;
        font-size: 14px;
        margin-top: 30px;
    }
    .support-link {
        color: #4299e1;
        text-decoration: none;
        font-weight: 500;
    }
</style>

<div class="header">{{ $appName }} - Password Reset</div>

<p style="color: #4a5568; line-height: 1.5;">Hey there!</p>

<p style="color: #4a5568; line-height: 1.5;">You are receiving this email because we received a password reset request for your account.</p>

<div class="code-container">
    <div style="color: #718096; font-size: 14px; margin-bottom: 8px;">Your verification code is:</div>
    <div class="verification-code">{{ $code }}</div>
</div>


<div class="divider"></div>

<p style="color: #4a5568; line-height: 1.5;">If you didn't request a password reset, please ignore this email or contact our support team at
    <a href="mailto:{{ $supportEmail }}" class="support-link">{{ $supportEmail }}</a>.
</p>

<div class="footer">
    Thanks,<br>
    The {{ $appName }} Team
</div>
@endcomponent
