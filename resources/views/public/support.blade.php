@extends('layouts.main')

@section('title')
  @lang('layout.support')
@endsection

@section('nav3')
class="active"
@endsection

@section('content')

<div class="container" style="min-height: 100vh;">

  <div class="box-container" style="padding: 30px 40px;">

    <h3>@lang('layout.support')</h3>

    <form role="form" method="POST" action="{{ url('/support') }}">
      {{ csrf_field() }}

  		<div id="email_form" class="form-group">
  			<label for="email">@lang('info.email'):</label>

        @php
        $value = '';
        if(old('email') != '') {
          $value = old('email');
        } else if(Auth::check()) {
          $value = Auth::user()->email;
        }
        @endphp

        <input type="email" placeholder="@lang('info.email')" name="email" id="email" class="form-control" value="{{ $value }}">

  		</div>
      @if($errors->has('email'))
        <div class="alert alert-danger">@lang($errors->first('email'))</div>
      @endif

  		<div id="message_form" class="form-group">
  			<label for="message">@lang('info.message'):</label>
  			<textarea class="form-control" placeholder="@lang('info.message')" name="message" id="message" rows="5" style="resize: vertical;"></textarea>
  		</div>
      @if($errors->has('message'))
        <div class="alert alert-danger">@lang($errors->first('message'))</div>
      @endif

      @if($errors->has('tooManyAttempts'))
        <div class="alert alert-danger">@lang($errors->first('tooManyAttempts'), ['sec' => session('retryAfter')])</div>
      @endif

  		<div class="text-right" style="position: relative;">
  			<div class="loader gone" style="position: absolute; left: 0;"></div>
  			<button type="submit" class="btn btn-default btn-main" style="width: 200px;">
          <span>@lang('info.send') <span class="glyphicon glyphicon-send"></span></span>
        </button>
  		</div>
  	</form>

  </div>

</div>

@endsection
