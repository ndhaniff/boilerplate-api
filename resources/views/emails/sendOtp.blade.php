@component('mail::message')
Sign Up Verification Code
Please use the verification code below to complete the signup process.

{{ $otp }}

@component('mail::subcopy')
This OTP will expire in 10 minutes.
You can choose to ignore this email, no further action is required.
@endcomponent

@endcomponent