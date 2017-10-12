@extends('layouts.main')

@section('title')
@lang('auth.register.title')
@endsection

@section('header')
<link href="/css/register.min.css" rel="stylesheet" type="text/css">

{{-- ReCaptcha --}}
<script src='https://www.google.com/recaptcha/api.js?hl={{ App::getLocale() }}'></script>
<script>function onSubmit(token){document.getElementById("register-form").submit();}</script>
@endsection

@section('content')

  <div class="container" style="min-height: 100vh;">

      <div class="box-container" style="padding: 40px 0px;">

        <div style="margin: 0px auto; width: 80%;">

        <h2>@lang('auth.register.title')</h2>

        <form id="register-form" role="form" action="{{ url('/register') }}" method="POST">
          {{ csrf_field() }}

          <div id="username_form" class="form-group">
            <input type="text" class="form-control" id="username" name="username" placeholder="@lang('info.username')" value="{{ old('username') }}">
          </div>
          @if($errors->has('username'))
            <div class="alert alert-danger">@lang($errors->first('username'))</div>
          @endif

          <div id="email_form" class="form-group">
            <input type="email" class="form-control" id="email" name="email" placeholder="@lang('info.email') @lang('auth.register.email_info')" value="{{ old('email') }}">
          </div>
          @if($errors->has('email'))
            <div class="alert alert-danger">@lang($errors->first('email'))</div>
          @endif

          <div id="password_form" class="form-group">
            <input type="password" class="form-control" id="password" name="password" placeholder="@lang('info.password')">
          </div>
          @if($errors->has('password'))
            <div class="alert alert-danger">@lang($errors->first('password'))</div>
          @endif

          <div id="confirm_form" class="form-group">
            <input type="password" class="form-control" id="confirm" name="confirm" placeholder="@lang('info.confirm')">
          </div>
          @if($errors->has('confirm'))
            <div class="alert alert-danger">@lang($errors->first('confirm'))</div>
          @endif

          @lang('preferences.keyboardLayout'):
          <select name="keyboard" id="keyboard">
            <option value="de-de">Deutsch (Deutschland)</option>
            <option value="en-us">English (US)</option>
          </select>
          @if($errors->has('keyboard'))
            <div class="alert alert-danger">@lang($errors->first('keyboard'))</div>
          @endif

          <div class="checkbox text-left">
            <label class="unselectable">
              <input class="check_box" type="checkbox" name="checkbox" id="checkbox">
              @lang('auth.register.statement', ['url' => url('/privacy')])
             </label>
          </div>
          @if($errors->has('checkbox'))
            <div class="alert alert-danger">@lang($errors->first('checkbox'))</div>
          @endif

          @if($errors->has('captcha'))
            <div class="alert alert-danger">@lang($errors->first('captcha'))</div>
          @endif

          <div style="text-align: right">
            <button type="submit"
                    type="submit" class="g-recaptcha btn btn-default btn-main btn-register"
                    data-sitekey="{{ env('RECAPTCHA_PUBLIC') }}"
                    data-callback="onSubmit">
                    <span>@lang('auth.register.title')</span>
            </button>
          </div>

        </form>
      </div>
    </div>
  </div>

@endsection
