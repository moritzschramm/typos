@extends('layouts.main')

@section('title')
Statistiken
@endsection

@section('nav2')
class="active"
@endsection

@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
<link href="/css/stats.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height:100vh">

  <div class="statscontainer">

    <h3 style="padding: 8px 20px;">@lang('stats.title')</h3>
    <hr style="margin-bottom: 0;">

    <div class="navcontainer">

      <ul>

        <a href="{{ url('/statistics?view=velocity') }}">
          <li class="@echoIf($view == 'velocity', 'item-active') unselectable">Geschwindigkeit</li>
        </a>
        <a href="{{ url('/statistics?view=xp') }}">
          <li class="@echoIf($view == 'xp', 'item-active') unselectable">XP</li>
        </a>
        <a href="{{ url('/statistics?view=keystrokes') }}">
          <li class="@echoIf($view == 'keystrokes', 'item-active') unselectable">Fehlerquote</li>
        </a>

      </ul>

    </div>

    <div class="content">

    @if($view == 'velocity')

      <p>Der Graph zeigt die durchschnittliche Geschwindigkeit in Anschlägen pro Minute am jeweiligen Tag an.</p>

      Übungen:
      <select class="selectpicker">
        <option value="limit-5">letzte 5</option>
        <option value="limit-10">letzte 10</option>
        <option value="all">Alle</option>
      </select>

      <br><br>

      <canvas id="graph" width="160" height="90"></canvas>

      <p>Höchste Geschwindigkeit: A/min</p>

    @elseif($view == 'xp')

      <p>Der Graph zeigt die gesammelte XP am jeweiligen Tag an.</p>

      Zeitraum:
      <select class="selectpicker">
        <option value="last-7">letzte Woche</option>
        <option value="last-30">letzten 30 Tage</option>
        <option value="all">Alle</option>
      </select>

      <br><br>

      <canvas id="graph" width="160" height="90"></canvas>

    @elseif($view == 'keystrokes')

      <p>Der Graph zeigt die Anzahl der richtig getippten Zeichen (grün) und die Anzahl der falsch getippten Zeichen (rot) im Verhältnis.</p>

      Zeitraum:
      <select class="selectpicker">
        <option value="last-7">letzte Woche</option>
        <option value="last-30">letzten 30 Tage</option>
        <option value="all">Alle</option>
      </select>

      <br><br>

      <canvas id="graph" width="160" height="90"></canvas>

      <p id="rate"></p>

    @endif

    </div>

    <div class="clearfix"></div>

  </div>

</div>

@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<script src="/js/stats.js"></script>
<script>
view = "{{ $view }}";
</script>
@endsection
