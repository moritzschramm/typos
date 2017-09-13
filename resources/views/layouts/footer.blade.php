<footer>
  <div class="container">
    <div class="text-center" style="font-size: 16px; margin: 20px 0px;">Typos &copy; <?= date("Y") ?></div>
    <div class="row">
      <div class="col-md-2 col-md-offset-3 text-center">
        <p class="footer-heading"><strong></strong></p>
        <ul class="footer-list">
          <li><a href="{{ url('/') }}" class="footer-link"></a></li>
        </ul>
      </div>
      <div class="col-md-2 text-center">
        <p class="footer-heading"><strong></strong></p>
        <ul class="footer-list">
          <li><a href="{{ url('/login') }}" class="footer-link">@lang('layout.login')</a></li>
          <li><a href="{{ url('/register') }}" class="footer-link">@lang('layout.register')</a></li>
        </ul>
      </div>
      <div class="col-md-2 text-center">
        <p class="footer-heading"><strong></strong></p>
        <ul class="footer-list">
        </ul>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</footer>
