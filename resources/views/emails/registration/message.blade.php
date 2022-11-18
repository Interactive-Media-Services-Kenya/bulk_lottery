@component('mail::message')
# {{ $details['subject'] }}
{{ $details['body'] }}
Thank You,<br>
{{ env('APP_NAME') }}
@endcomponent
