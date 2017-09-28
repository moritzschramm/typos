@extends('layouts.main')

@section('title')
  @lang('layout.home')
@endsection

@section('nav1')
  class="active"
@endsection

@section('header')
<link href="/css/public_index.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="splash"></div>
<div class="container outercontainer">
  <div class="splash_container">
    <span id="splash_text">Schnell und einfach das Zehnfingersystem lernen.</span><br>
    <a href="{{ url('/trial') }}" class="btn-splash unselectable" id="splash_btn">Kostenlos ausprobieren »<span></span><span></span></a>
  </div>
</div>

<div id="jumbotron" class="jumbotron text-center" style="background-color: #fff; margin-bottom: 0; padding: 40px 60px; box-shadow: 0px 0px 30px #000;">
  <p style="margin-bottom: 0; font-size: 25px; font-weight: 600; color: #292929;">
    Typos ist ein Online-Trainer, mit dem Du das Zehnfingersystem lernst.
  </p>
</div>

<div class="container-fluid" style="background-color: #f6f6f6;">

  <div class="row" style="padding: 80px 0px;">
    <div class="col-md-6 text-right">
      <img alt="preview" src="/imgs/splash_foreground1.gif" class="img-preview unselectableimg"/>
    </div>
    <div class="col-md-4 text-center">
      <h2 style="font-family: Arvo, serif;">Intelligenter Schreibtrainer</h2>
      <p style="font-size: 20px; color: #575757;">Neben der anfängerfreundlichen Oberfläche, merkt sich der Trainer Deine falsch eingegebenen Wörter sowie Tasten und übt diese häufiger.</p>
    </div>
    <div class="col-md-2"></div>
  </div>

</div>

<div class="container-fluid" style="background-color: #fff;">

  <div class="row" style="padding: 80px 0px;">

    <div class="col-md-6 col-md-push-6 text-left">
      <img alt="preview" src="/imgs/splash_foreground2.jpg" class="img-preview unselectableimg"/>
    </div>

    <div class="col-md-4 col-md-pull-4 text-center">
      <h2 style="font-family: Arvo, serif;">Maßgeschneiderte Lektionen</h2>
      <p style="font-size: 20px; color: #575757;">Die Lektionen werden Dir Schritt für Schritt das Schreiben mit 10 Fingern beibringen. Danach perfektioniert Du das Zehnfingersystem mit Hilfe des Trainings und Deinen eigenen Lektionen.</p>
    </div>
  </div>

</div>

<div class="container-fluid" style="background-color: #f6f6f6;">

  <div class="row" style="padding: 80px 0px;">
    <div class="col-md-6 text-right">
      <img alt="preview" src="/imgs/splash_foreground3.jpg" class="img-preview unselectableimg"/>
    </div>
    <div class="col-md-4 text-center">
      <h2 style="font-family: Arvo, serif;">Den Fortschritt im Überblick</h2>
      <p style="font-size: 20px; color: #575757;">Du kannst deinen Fortschritt immer in den Statistiken nachverfolgen. Dort kannst Du z.B. Deine Tippgeschwindigkeit oder Deine Fehlerquote abrufen.</p>
    </div>
    <div class="col-md-2"></div>
  </div>

</div>

<div class="container-fluid text-center" style="background-color: #fff; padding: 80px 0px;">

  <div class="row" style="margin: 0;">
    <div class="col-sm-4 col-sm-offset-4">
      <p style="font-size: 18px;">
        Damit Du Typos nutzen kannst, musst Du dich registrieren.
        Das ist erforderlich, um deinen Fortschritt zu speichern.
        So hast Du immer Zugriff auf deine Lektionen und Statistiken.
        <br>
        Natürlich kannst du den Online-Trainer auch unverbindlich ausprobieren.
      </p>
      <a href="{{ url('/trial') }}" class="btn-action">Kostenlos ausprobieren »<span></span><span></span></a><br>
    </div>
  </div>

</div>

@endsection

@section('footer')
<script>
function setSplashText(){function t(){return s===e.length?void $("#splash_btn").animate({top:"10px",opacity:"1"},60):(n+=e.charAt(s),o.html(n),s++,setTimeout(t,l),void 0)}var e="Schnell und einfach das Zehnfingersystem lernen.",n="",o=$("#splash_text"),s=0,l=50;setTimeout(t,l)}function clickMore(){$("html, body").animate({scrollTop:$("#jumbotron").offset().top-50},"slow")}$(document).ready(function(){$("#splash_text").html(""),setSplashText()});
</script>
@endsection
