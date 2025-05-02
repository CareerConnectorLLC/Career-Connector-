<x-mail::message>
# Hello {{ $name }},

We appreciate you contacting **Career-Connector**.

---

### 📨 **Our Response To Your Query/Suggestions:**

<x-mail::panel>
{{ $reply }}
</x-mail::panel>

---

If you have any additional questions or need further assistance, feel free to reply to this email. We're here to help.

<x-mail::subcopy>
If you're not expecting this email or need help, please contact our support team at [{{ config('mail.from.address') }}](mailto:{{ config('mail.from.address') }}).
</x-mail::subcopy>

Thanks again,<br>
<span style="color: #174dba; font-weight: bold;">The Career-Connector Team</span> <br>
<img src="{{ asset('admin_assets/logo/logo.png') }}" alt="Career-Connector Logo" style="height: 50px; margin-top: 20px;">


</x-mail::message>
