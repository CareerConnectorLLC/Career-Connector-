<x-mail::message>
# Introduction

The OTP is {{ $otp }}.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
