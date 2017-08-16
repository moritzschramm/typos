@component('mail::message')
# @lang('mail.password_reset.title')

@lang('mail.password_reset.message')

@component('mail::button', ['url' => $resetUrl])
  @lang('mail.password_reset.action')
@endcomponent

@lang('mail.signature')
@endcomponent
