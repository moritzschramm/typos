@extends('layouts.main')

@section('title')
  @lang('stats.title')
@endsection

@section('nav2')
class="active"
@endsection

@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
<link href="/res/css/stats.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height:100vh">

  <div class="statscontainer">

    <h3 style="padding: 8px 20px;">@lang('stats.title')</h3>
    <hr style="margin-bottom: 0;">

    <div class="navcontainer">

      <ul>

        <a href="{{ url('/statistics?view=velocity') }}">
          <li class="@echoIf($view == 'velocity', 'item-active') unselectable">@lang('stats.velocity.title')</li>
        </a>
        <a href="{{ url('/statistics?view=xp') }}">
          <li class="@echoIf($view == 'xp', 'item-active') unselectable">@lang('stats.xp.title')</li>
        </a>
        <a href="{{ url('/statistics?view=keystrokes') }}">
          <li class="@echoIf($view == 'keystrokes', 'item-active') unselectable">@lang('stats.keystrokes.title')</li>
        </a>

      </ul>

    </div>

    <div class="content">

    @if($view == 'velocity')

      <p>
        @lang('stats.velocity.description')
      </p>

      @lang('stats.exercises'):
      <select class="selectpicker">
        <option value="limit-5">@lang('stats.last') 5</option>
        <option value="limit-10">@lang('stats.last') 10</option>
        <option value="all">@lang('stats.all')</option>
      </select>

      <br><br>

      <canvas id="graph" width="160" height="90"></canvas>

      <p>@lang('stats.velocity.record'):
        <span id="highest-velocity">0.0</span>
        @lang('stats.velocity.unit')
      </p>

    @elseif($view == 'xp')

      <p>@lang('stats.xp.description')</p>

      @lang('stats.period'):
      <select class="selectpicker">
        <option value="last-7">@lang('stats.lastWeek')</option>
        <option value="last-30">@lang('stats.lastMonth')</option>
        <option value="all">@lang('stats.all')</option>
      </select>

      <br><br>

      <canvas id="graph" width="160" height="90"></canvas>

    @elseif($view == 'keystrokes')

      <p>@lang('stats.keystrokes.description')</p>

      @lang('stats.period')/@lang('stats.exercises'):
      <select class="selectpicker">
        <option value="last-7">@lang('stats.lastWeek')</option>
        <option value="last-30">@lang('stats.lastMonth')</option>
        <option value="limit-5">@lang('stats.last') 5</option>
        <option value="limit-10">@lang('stats.last') 10</option>
        <option value="all">@lang('stats.all')</option>
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
<script src="/res/js/stats.min.js"></script>
<script>
view = "{{ $view }}";
lang.velocity   = "@lang('stats.velocity.title')";
lang.xp         = "@lang('stats.xp.title')";
lang.keystrokes = "@lang('stats.keystrokes.title')";
lang.errors     = "@lang('training.errors')";
</script>
@endsection
