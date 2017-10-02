@component('mail::message')
# {{ $title }}
<br>
Sent by: {{ $email }}<br>
@echoIf($userId != -1, "With user ID: $userId<br>")
<br>

{{ $msg }}

Regards,<br>
{{ config('app.name') }}
@endcomponent
