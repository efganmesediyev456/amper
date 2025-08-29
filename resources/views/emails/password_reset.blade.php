<!-- resources/views/emails/password_reset.blade.php -->
@component('mail::message')
# {{ __('api.Reset Password') }}

{{ __('api.Please click the link below and set a new password') }}:

@component('mail::button', ['url' => $resetUrl, 'color' => 'primary'])
{{ __('api.Reset Password') }}
@endcomponent

{{ __('api.If you did not request a password reset, no further action is required.') }}

{{ __('api.Regards') }},<br>
{{ config('app.name') }}
@endcomponent