<!-- resources/views/emails/verify_email.blade.php -->

<p>Hello {{ $user_name }},</p>

<p>Please click the following link to verify your email:</p>
<a href="{{ $verificationLink }}">Reset Password</a>
<br>
<p>Verify Token: {{ $token }}</p>

<p>If you didn't request this verification, you can ignore this email.</p>
