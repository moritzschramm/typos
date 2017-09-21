@if(session()->has('notification'))
  <div class="notification notification-info">
    @lang(session('notification'))
    <span class="notification-close" title="@lang('notifications.close')">&times;</span>
  </div>
@endif

@if(session()->has('notification-success'))
  <div class="notification notification-success">
    @lang(session('notification-success'))
    <span class="notification-close" title="@lang('notifications.close')">&times;</span>
  </div>
@endif

@if(session()->has('notification-error'))
  <div class="notification notification-error">
    @lang(session('notification-error'))
    <span class="notification-close" title="@lang('notifications.close')">&times;</span>
  </div>
@endif
