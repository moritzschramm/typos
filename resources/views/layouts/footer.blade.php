<footer>
  <div class="container">
    <div class="text-center" style="font-size: 16px; margin: 20px 0px;">Typos &copy; <?= date("Y") ?></div>
    <div class="row">
      <div class="col-md-2 col-md-offset-3 text-center">
        <p class="footer-heading"><strong>@lang('layout.shortcuts')</strong></p>
        <ul class="footer-list">
          <li><a href="{{ url('/') }}" class="footer-link">@lang('layout.home')</a></li>
          <li><a href="{{ url('/preferences') }}" class="footer-link">@lang('layout.settings')</a></li>
          <li><a href="{{ url('/help') }}" class="footer-link">@lang('layout.help')</a></li>
        </ul>
      </div>
      <div class="col-md-2 text-center">
        <p class="footer-heading"><strong>@lang('layout.locale')</strong></p>
        <ul class="footer-list">
          <li><a href="{{ url('/locale/en') }}" class="footer-link">English</a></li>
          <li><a href="{{ url('/locale/de') }}" class="footer-link">Deutsch</a></li>
        </ul>
      </div>
      <div class="col-md-2 text-center">
        <p class="footer-heading"><strong>@lang('layout.others')</strong></p>
        <ul class="footer-list">
          <li><a href="{{ url('/notice') }}" class="footer-link">@lang('layout.notice')</a></li>
          <li><a href="{{ url('/privacy') }}" class="footer-link">@lang('layout.privacy')</a></li>
          <li><a href="{{ url('/contact') }}" class="footer-link">@lang('layout.contact')</a></li>
        </ul>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</footer>
