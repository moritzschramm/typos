@extends('layouts.main')

@section('title')
  Dashboard
@endsection

@section('nav1')
  class="active"
@endsection

@section('header')
<link href="/css/training.css" rel="stylesheet" type="text/css">
<link href="/css/training-lections.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="margin-top: 30px; min-height: 100vh;">

  <div class="lection-nav">
    <a href="/dashboard?view=lections" style="text-decoration:none">
      <div id="item-lections" class="nav-item unselectable @echoIf($view == 'lections', 'item-active')">
        @lang('dashboard.lection')
      </div>
    </a>
    <a href="{{ url('/dashboard?view=exercises') }}" style="text-decoration:none">
      <div id="item-exercises" class="nav-item unselectable @echoIf($view == 'exercises', 'item-active')">
        @lang('dashboard.exercise')
      </div>
    </a>
  </div>
  <div></div> {{-- to fix css issue with float… --}}

  <div class="lection-panel">

    @if($view == 'lections')
    <div id="container-lections">{{-- container for lections --}}

      @foreach($lections as $lection)
        <div class="lection-item">
          <div class="lection-num">@lang('dashboard.lection') {{ $lection->external_id }}</div>
          <div class="lection-title">{{ $lection->title }}</div>
          <div class="lection-footer">
            <a href="{{ url("/lection/$lection->external_id") }}"><span class="lection-link">@lang('dashboard.start')</span></a>
          </div>
        </div>
      @endforeach

    </div>

    @elseif($view == 'exercises')
    <div id="container-exercises">

      <p style="padding-left: 32px; margin-top: -18px;">
        @lang('dashboard.exerciseInfo')
      </p>

      @foreach ($exercises as $exercise)
        <div class="lection-item">
          <div class="lection-title">{{ $exercise->title }}</div>
          <div class="lection-footer">
            <a href="{{ url("/exercise/$exercise->id_exercise") }}"><span class="lection-link">@lang('dashboard.start')</span></a>
          </div>
        </div>
      @endforeach

      <a href="{{ url('/exercise') }}" class="lection-item-add"><span class="glyphicon glyphicon-plus"></span></a>

    </div>
    @endif

  </div>

  <div class="extra-panel">

    <div>
      <a href="{{ url('/training') }}" class="btn btn-default btn-main btn-training" style="font-size: 17px;"><span><span class="glyphicon glyphicon-education"></span> @lang('dashboard.training')</span></a>
    </div>

    <div class="circle-container" data-toggle="tooltip" title="heutige XP">
      <div style="position: relative;">
        <canvas id="xp-graph" height="200" width="200"></canvas>
        <div style="position: absolute; top: 40%; text-align: center; width: 100%; font-size: 24px; font-family: Montserrat; cursor: default;" class="unselectable">
          {{ $xp }} / {{ $xp_goal }} XP
        </div>
      </div>
    </div>

    <div data-toggle="tooltip" title="XP der letzten Woche">
      <canvas id="graph" width="160" height="100"></canvas>
    </div>

  </div>

</div>

<div id="modal_delete" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Lektion löschen?</h4>
      </div>
      <div class="modal-body">
        <p>Die Aktion kann nicht rückgängig gemacht werden. Soll die Lektion wirklich gelöscht werden?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" onclick="commitDelete();">Löschen</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
      </div>
    </div>

  </div>
</div>
<div id="modal_publish" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Lektion veröffentlichen?</h4>
      </div>
      <div class="modal-body">
        <p>Wenn du die Lektion veröffentlichen willst, musst du bestimmte Regeln einhalten.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="commitPublish();">Veröffentlichen</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Abbrechen</button>
      </div>
    </div>

  </div>
</div>

@endsection
