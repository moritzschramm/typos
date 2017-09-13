@extends('layouts.main')

@section('title')
@lang('training.results.title')
@endsection

@section('header')
<link href="/css/results.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height: 100vh;">

  <div class="completecontainer text-center">

    <h1>@lang('training.results.title')</h1>

    <div style="margin: 20px auto; width: 20%" data-toggle="tooltip" data-placement="right" title="heutige XP">
      <canvas id="graph" width="800" height="800"></canvas>
    </div>


    <div class="info">
      <h2 id="heading_goal"></h2>

      <h3>XP: {{ $xp }}</h3>
      <p>@lang('training.results.amountCorrects'): {{ $keystrokes - $errors }}</p>
      <p>@lang('training.results.amountErrors'): {{ $errors }}</p>
      <p>@lang('training.results.avgSpeed'): {{ $velocity }} @lang('training.velocityUnit')</p>
    </div>

    <div class="text-right">
      <a href="{{ url('/dashboard') }}" class="btn btn-default btn-main btn-continue"><span>@lang('info.continue')</span></a>
    </div>

  </div>
</div>

@endsection

@section('footer')
<script>
var xp = {{ $xp }};
var goal = 30;

$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip();
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.6/Chart.bundle.min.js"></script>
<script src="/scripts/complete_chart.js"></script>
@endsection
