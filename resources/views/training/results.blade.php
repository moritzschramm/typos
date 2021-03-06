@extends('layouts.main')

@section('title')
@lang('training.results.title')
@endsection

@section('header')
<link href="/res/css/results.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height: 100vh;">

  <div class="box-container text-center" style="padding: 30px 40px;">

    <h1>@lang('training.results.title')</h1>

    <div style="margin: 20px auto; width: 20%" data-toggle="tooltip" data-placement="right" title="@lang('training.results.todaysXP')">
      <canvas id="graph" width="800" height="800"></canvas>
    </div>


    <div class="info">
      <h2 id="heading_goal"></h2>

      @if($cheated)
        <h2>@lang('training.results.cheated')</h2>
      @endif

      <h3>XP: {{ $xp }}</h3>

      <form class="form-horizontal">

        <div class="form-group" style="margin-bottom:0">
          <label class="control-label col-sm-6">@lang('training.results.amountCorrects'):</label>
          <div class="col-sm-6 text-left">
            <p class="form-control-static">{{ $keystrokes }}</p>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:0">
          <label class="control-label col-sm-6">@lang('training.results.amountErrors'):</label>
          <div class="col-sm-6 text-left">
            <p class="form-control-static">{{ $error_amount }}</p>
          </div>
        </div>

        <div class="form-group" style="margin-bottom:0">
          <label class="control-label col-sm-6">@lang('training.results.avgSpeed'):</label>
          <div class="col-sm-6 text-left">
            <p class="form-control-static">{{ $velocity }} @lang('training.velocityUnit')</p>
          </div>
        </div>

      </form>


    </div>

    <div class="text-right">

      @if(Auth::check())
        <a href="{{ url('/dashboard') }}" class="btn btn-default btn-main btn-continue"><span>@lang('info.continue')</span></a>
      @else
        <a href="{{ url('/trial') }}" class="btn btn-default btn-main btn-continue"><span>@lang('info.repeat')</span></a>
      @endif
    </div>

  </div>
</div>

@endsection

@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.min.js"></script>
<script>
var xp = {{ $xp }};
var goal = {{ $xp_goal }};

$(document).ready(function() {

  // bootstrap tooltips
  $('[data-toggle="tooltip"]').tooltip();

  // chart
  var ctx = document.getElementById("graph");
  var dif = Math.max(0, goal - xp);

  Chart.defaults.global.legend.display = false;
  Chart.defaults.global.defaultFontFamily = "'Montserrat', 'Arial', 'sans-serif'";

  var data = {
      labels: [
          "@lang('training.results.currentXP')",
          "@lang('training.results.missingXP')"
      ],
      datasets: [
          {
              data: [xp, dif],
              backgroundColor: [
                  "#ff5722",
                  "#ffab91"
              ],
              borderColor: [
                  "#ff5722",
                  "#ffab91"
              ],
              hoverBackgroundColor: [
                  "#ff5722",
                  "#ffab91"
              ]
          }]
  };

  var chart = new Chart(ctx, {
      type: 'doughnut',
      data: data,
      animation:{
            animateScale: true
      },
      options: {
        cutoutPercentage: 70
      }
    });
  });
</script>
@endsection
