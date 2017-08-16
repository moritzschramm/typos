@component('mail::message')
# @lang('mail.verification.title')

@lang('mail.verification.message')

@component('mail::button', ['url' => $verifyUrl])
  @lang('mail.verification.action')
@endcomponent

@lang('mail.signature')
@endcomponent
