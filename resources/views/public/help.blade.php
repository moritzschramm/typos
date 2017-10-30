@extends('layouts.main')

@section('title')
  @lang('help.title')
@endsection

@section('header')
<link href='https://fonts.googleapis.com/css?family=Roboto+Mono' rel='stylesheet' type='text/css'>
<link href="/res/css/app.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height: 100vh;">

  <div class="box-container" style="padding: 40px 30px;">

    <h2 style="margin-top:0">@lang('help.title')</h2>

    <hr style="border-color:#d6d6d6">

    <div class="keyboard" id="keyboard">

      {{-- filled via js --}}

    </div>

  </div>

</div>

@endsection

@section('footer')
<script src="/res/js/app/keyboard.min.js"></script>
<script>
var app_modules = {keyboard: ""};
kb_layout = "{{ $keyboardLayout }}";

$(document).ready(function() {

  kb_init(function() {
    kb_highlightAllKeys();
  });
});
</script>
@endsection
