@component('mail::message')
Reset Password Code
You are receiving this email because we received a password reset request for your account.

{{ $otp }}

@component('mail::subcopy')
This OTP will expire in 10 minutes.
If you did not request a password reset, no further action is required.
@endcomponent

@endcomponent