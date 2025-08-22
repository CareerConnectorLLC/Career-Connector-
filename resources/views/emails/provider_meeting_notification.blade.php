@component('mail::message')
# Meeting Confirmed

Hello {{ $providerName }},

Your meeting is confirmed. You can join the meeting using the link below:

@component('mail::button', ['url' => route('frontend.meeting.show', ['booking' => $booking->id, 'token' => $providerJoinToken])])
Join Meeting
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent