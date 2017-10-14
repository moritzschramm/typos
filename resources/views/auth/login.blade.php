@extends('layouts.main')

@section('title')
@lang('auth.login.title')
@endsection

@section('header')
<link href="/res/css/login.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

  <div class="container" style="min-height: 100vh; padding-bottom: 20px; padding-top: 60px;">

    <h1 class="text-center">@lang('auth.login.title')</h1>

    <form role="form" method="POST" action="{{ url('/login') }}">
      {{ csrf_field() }}

      <div class="form-group">
        <input type="text" class="form-control" id="emailOrUsername" name="emailOrUsername" placeholder="@lang('info.email')/@lang('info.username')" value="{{ old('emailOrUsername') }}">
      </div>
      @if($errors->has('emailOrUsername'))
        <div class="alert alert-danger">@lang($errors->first('emailOrUsername'))</div>
      @endif

      <div class="form-group has-feedback">
        <input type="password" class="form-control" id="password" name="password" placeholder="@lang('info.password')">
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

      @unless($errors->has('emailOrUsername') || $errors->has('password'))

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
