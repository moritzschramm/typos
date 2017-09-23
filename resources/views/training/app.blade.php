@extends('layouts.main')

@section('title')
@lang('training.title')
@endsection

@section('header')
<link href='https://fonts.googleapis.com/css?family=Roboto+Mono' rel='stylesheet' type='text/css'>
<link href="/css/app.min.css" rel="stylesheet" type="text/css">
@endsection

@section('bodyattr')
  @unless($trial)
    onbeforeunload="return '@lang('training.beforeUnloadContent')';"
  @endunless
@endsection

@section('content')

<div class="container-fluid" style="min-height: 100vh">

<div class="container kb">

  <div onclick="back();" class="back unselectable" title="@lang('info.back')">&times;</div>

  <div class="settings" title="Einstellungen">
  <a class="dropdown-toggle" data-toggle="dropdown" href="" >
    <span class="glyphicon glyphicon-cog settings-icon unselectable"></span>
  </a>
    <ul class="dropdown-menu dropdown-menu-right">
      <li><a class="settings-item" onclick="toggleAssignment();"><span id="assignment-state" class="glyphicon glyphicon-unchecked"></span> @lang('training.showAssignment')</a></li>
      <li><a class="settings-item" onclick="toggleDivider();"><span id="divider-state" class="glyphicon glyphicon-unchecked"></span> @lang('training.showDivider')</a></li>
      <li><a class="settings-item" onclick="toggleKeyboard();"><span id="keyboard-state" class="glyphicon glyphicon-unchecked"></span> @lang('training.showKeyboard')</a></li>
    </ul>
  </div>

  <div class="charsequence">
    <div id="loader" class="loader" style="position: absolute; top: 50%; margin-top: -20px; left: 50%; margin-left: -20px;"></div>
    <span class="green" id="green"></span><span class="red" id="red"></span><span class="normal" id="normal"></span>
  </div>

  <div class="progress" style="width: 80%; margin: 30px auto;">
   <div class="progress-bar progress-bar-striped" role="progressbar" id="progressbar"
   aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
   </div>
   <div class="progress_sequence" id="progress_sequence"></div>
 </div>

  <div class="keyboard" id="keyboard">

    <div id="divider" class="kbdivider gone">
      <div class="divider-item-vertical"    style="top:     0%; left: 46.2%; height: 18.75%"></div>
      <div class="divider-item-horizontal"  style="top: 18.75%; left: 43.25%; width: 3.15%;"></div>
      <div class="divider-item-vertical"    style="top: 18.75%; left: 43.25%; height: 20%;"></div>
      <div class="divider-item-horizontal"  style="top: 38.75%; left: 43.25%; width: 1.354%;"></div>
      <div class="divider-item-vertical"    style="top: 38.75%; left: 44.45%; height: 20%"></div>
      <div class="divider-item-horizontal"  style="top: 58.75%; left: 44.45%; width: 2.499%;"></div>
      <div class="divider-item-vertical"    style="top: 58.75%; left: 46.85%; height: 20%;"></div>
    </div>

    {{-- keyboard keys will be inserted here (via js) --}}
    {{-- <div id="KEYID" class="key" style="top: 0%; left: 0%;">KEYNAME</div> --}}

    {{-- <canvas class="hud" id="hud" width="960" height="320"></canvas> --}}

  </div>

  <div class="extraPanel gone" id="fingerInfo">
    <div class="extraInfoSmall">
      <span style="color: #ffc107;">@lang('training.pinkieFinger')</span>
    </div>
    <div class="extraInfoSmall">
      <span style="color: #f44336;">@lang('training.ringFinger')</span>
    </div>
    <div class="extraInfoSmall">
      <span style="color: #00c853;">@lang('training.middleFinger')</span>
    </div>
    <div class="extraInfoSmall">
      <span style="color: #673ab7;">@lang('training.indexFinger')</span>
    </div>
    <div class="extraInfoSmall">
      <span style="color: #03a9f4;">@lang('training.thumbFinger')</span>
    </div>
  </div>

  <div class="extraPanel">
    <div class="extraInfo" data-toggle="tooltip" data-placement="bottom" title="@lang('training.velocityInfo')">
      <span id="velocity">0.0</span> @lang('training.velocityUnit')
    </div>
    <div class="extraInfo">
      @lang('training.errors'): <span id="errorCount">0</span>
    </div>
    <div class="extraInfo">
      @lang('training.errorRatio'): <span id="errorRatio">0.0</span>%
    </div>
  </div>
</div>

<div id="modal_back" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">@lang('training.modal.title')</h4>
      </div>
      <div class="modal-body">
        <p>@lang('training.modal.content')</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="backConfirm();">@lang('training.modal.ok')</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('training.modal.abort')</button>
      </div>
    </div>

  </div>
</div>
</div>

@endsection

@section('footer')

<script src="/js/app/app.min.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

kb_layout   = "{{ $keyboardLayout }}";
sq_dataURI  = "{{ $dataURI }}";

misc_assignmentVisible  = {{ $setting['assignment'] }};
misc_dividerVisible     = {{ $setting['divider'] }};
misc_keyboardVisible    = {{ $setting['keyboard'] }};

@if($trial)
misc_backURI = "/";
@endif

kb_init(app_moduleCallback);

$(document).ready(function() {

  sq_init(app_moduleCallback);
  dp_init(app_moduleCallback);
  ib_init(app_moduleCallback);
  tm_init(app_moduleCallback);

  setTimeout(function() {
    misc_init(app_moduleCallback);
  }, 100);

  $('[data-toggle="tooltip"]').tooltip();
});

</script>

@endsection
