@component('mail::message')
# {{ __('api.Email Verification') }}

{{ __('api.Your OTP code is:') }}

@component('mail::panel')
**{{ $otp }}**
@endcomponent

{{ __('api.This code is valid for :minutes minutes.', ['minutes' => 2]) }}

{{ __('api.Best regards,') }}<br>
{{ config('app.name') }}
@endcomponent