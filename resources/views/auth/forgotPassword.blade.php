@extends('layouts.main')

@section('title')
  @lang('auth.password.forgot.title')
@endsection

@section('header')
<link href="/res/css/reset.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height: 100vh; padding-bottom: 20px; padding-top: 60px;">

  <h1 class="text-center">@lang('auth.password.forgot.title')</h1>

  <form role="form" action="{{ url('/password/forgot') }}" method="POST">
    {{ csrf_field() }}

    <p class="text-left">
      @lang('auth.password.forgot.instructions')
    </p>

    <div id="email_form" class="form-group has-feedback">
      <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" value="{{ old('email') }}">
      <span id="email_error" class="glyphicon glyphicon-remove form-control-feedback gone"></span>
    </div>

    @if($errors->has('email'))
      <div class="alert alert-danger">@lang($errors->first('email'))</div>
    @endif

    @unless($errors->has('email'))

      @foreach($errors->all() as $message)
        <div class="alert alert-danger">@lang($message, ['sec' => session('retryAfter')])</div>
      @endforeach

    @endunless

    <button type="submit" class="btn btn-default btn-main btn-login">
      <span>@lang('info.send')</span>
    </button>

  </form>

</div>

@endsection
