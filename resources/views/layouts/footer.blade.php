<footer>
  <div class="container">
    <div class="text-center" style="font-size: 16px; margin: 20px 0px;">Typos &copy; <?= date("Y") ?></div>
    <div class="row">
      <div class="col-md-2 col-md-offset-3 text-center">
        <p class="footer-heading"><strong>INFO</strong></p>
        <ul class="footer-list">
          <li><a href="{{ url('/') }}" class="footer-link">Startseite</a></li>
        </ul>
      </div>
      <div class="col-md-2 text-center">
        <p class="footer-heading"><strong>ANMELDEN</strong></p>
        <ul class="footer-list">
          <li><a href="{{ url('/login') }}" class="footer-link">Login</a></li>
          <li><a href="{{ url('/register') }}" class="footer-link">Registrierung</a></li>
        </ul>
      </div>
      <div class="col-md-2 text-center">
        <p class="footer-heading"><strong>WEITERES</strong></p>
        <ul class="footer-list">
          <li><a href="{{ url('/kontakt') }}" target="_blank" class="footer-link">Kontakt</a></li>
          <li><a href="{{ url('/impressum') }}" target="_blank" class="footer-link">Impressum</a></li>
          <li><a href="{{ url('/datenschutz') }}" target="_blank" class="footer-link">Datenschutz</a></li>
        </ul>
      </div>
      <div class="col-md-3"></div>
    </div>
  </div>
</footer>
