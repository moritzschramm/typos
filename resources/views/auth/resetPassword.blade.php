@extends('layouts.main')

@section('title')
  @lang('auth.password.reset.title')
@endsection

@section('header')
<link href="/css/reset.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height: 100vh; padding-bottom: 20px; padding-top: 60px;">

  <h1 class="text-center">@lang('auth.password.reset.title')</h1>

  <form role="form" action="{{ url('/password/reset') }}" method="POST">
    {{ csrf_field() }}

    <p class="text-left">
      @lang('auth.password.reset.instructions')
    </p>

    <div id="password_form" class="form-group has-feedback">
      <input type="password" class="form-control" id="password" name="password" placeholder="Neues Passwort">
      <span id="password_error" class="glyphicon glyphicon-remove form-control-feedback gone"></span>
    </div>
    @if($errors->has('password'))
      <div class="alert alert-danger">@lang($errors->first('password'))</div>
    @endif

    <div id="confirm_form" class="form-group has-feedback">
      <input type="password" class="form-control" id="confirm" name="confirm" placeholder="Passwort bestätigen">
      <span id="confirm_error" class="glyphicon glyphicon-remove form-control-feedback gone"></span>
    </div>
    @if($errors->has('confirm'))
      <div class="alert alert-danger">@lang($errors->first('confirm'))</div>
    @endif

    @unless($errors->has('password') || $errors->has('confirm'))
      @foreach($errors->all() as $message)
        <div class="alert alert-danger">@lang($message)</div>
      @endforeach
    @endunless

    <button type="submit" class="btn btn-default btn-main btn-login">
      <span>@lang('auth.password.reset.action')</span>
    </button>

  </form>

</div>

@endsection
