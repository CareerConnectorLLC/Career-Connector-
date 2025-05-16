<h1>Forget Password Email</h1>

<p>
    You can reset password from below link:
</p>

<p>
    <a href="{{ route('frontend.password.reset', ['token' => $token, 'email' => $email]) }}">
        Reset Password
    </a>
</p>