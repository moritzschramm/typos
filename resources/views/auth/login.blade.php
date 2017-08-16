@extends('layouts.main')

@section('title')
@lang('auth.login.title')
@endsection

@section('header')
<link href="/css/login.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

  <div class="container" style="min-height: 100vh; padding-bottom: 20px; padding-top: 60px;">

    <h1 class="text-center">@lang('auth.login.title')</h1>

    <form role="form" method="POST" action="{{ url('/login') }}">
      {{ csrf_field() }}

      <div class="form-group has-feedback">
        <input type="text" class="form-control" id="email" name="email" placeholder="@lang('info.email')" value="{{ old('email') }}">
        <span id="email_error" class="glyphicon glyphicon-remove form-control-feedback gone"></span>
      </div>
      @if($errors->has('email'))
        <div class="alert alert-danger">@lang($errors->first('email'))</div>
      @endif

      <div class="form-group has-feedback">
        <input type="password" class="form-control" id="password" name="password" placeholder="@lang('info.password')">
        <span id="pwd_error" class="glyphicon glyphicon-remove form-control-feedback gone"></span>
      </div>
      @if($errors->has('password'))
        <div class="alert alert-danger">@lang($errors->first('password'))</div>
      @endif

      <div class="checkbox text-left">
        <label class="unselectable">
          <input class="check_box" type="checkbox" id="remember_me" name="remember_me">
          @lang('auth.login.remember_me')
        </label>
      </div>

      @unless($errors->has('email') || $errors->has('password'))

        @foreach($errors->all() as $message)
          <div class="alert alert-danger">@lang($message, ['sec' => session('retryAfter')])</div>
        @endforeach

      @endunless

      <button type="submit" class="btn btn-default btn-main btn-login"><span>@lang('auth.login.title')</span></button>

    <a href="{{ url('/password/forgot') }}">@lang('auth.login.password_link')</a><br><br>
      <span class="register"><a href="{{ url('/register') }}">@lang('auth.login.register_link')</a></span>
    </form>

  </div>


@endsection
