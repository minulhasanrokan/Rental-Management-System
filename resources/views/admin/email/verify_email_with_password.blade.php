<!-- resources/views/emails/verify_email.blade.php -->

<p>Hello {{ $user_name }},</p>

<p>Please click the following link to verify your email:</p>
<a href="{{ $verificationLink }}">Verify Email</a>
<p>After Verify Your Account to Login In Your Account Use This Password: {{$password}}</p>
<p>After Login Please Change Your Password</p>

<p>If you didn't request this verification, you can ignore this email.</p>
