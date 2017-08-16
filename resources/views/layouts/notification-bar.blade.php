@if(session()->has('notification'))
  <div class="notification notification-info">@lang(session('notification'))</div>
@endif

@if(session()->has('notification-success'))
  <div class="notification notification-success">@lang(session('notification-success'))</div>
@endif

@if(session()->has('notification-error'))
  <div class="notification notification-error">@lang(session('notification-error'))</div>
@endif
