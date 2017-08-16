@extends('layouts.main')

@section('title')
@lang('auth.register.title')
@endsection

@section('header')
<link href="/css/register.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

  <div class="container regouter">

      <div class="regcontainer">

        <div style="margin: 0px auto; width: 80%;">

        <h2>@lang('auth.register.title')</h2>

        <form role="form" action="{{ url('/register') }}" method="POST">
          {{ csrf_field() }}

          <div id="email_form" class="form-group has-feedback">
            <input type="email" class="form-control" id="email" name="email" placeholder="@lang('info.email')" value="{{ old('email') }}">
            <span id="email_error" class="glyphicon glyphicon-remove form-control-feedback gone"></span>
          </div>
          @if($errors->has('email'))
            <div class="alert alert-danger">@lang($errors->first('email'))</div>
          @endif

          <div id="password_form" class="form-group has-feedback">
            <input type="password" class="form-control" id="password" name="password" placeholder="@lang('info.password')">
            <span id="password_error" class="glyphicon glyphicon-remove form-control-feedback gone"></span>
          </div>
          @if($errors->has('password'))
            <div class="alert alert-danger">@lang($errors->first('password'))</div>
          @endif

          <div id="confirm_form" class="form-group has-feedback">
            <input type="password" class="form-control" id="confirm" name="confirm" placeholder="@lang('info.confirm')">
            <span id="confirm_error" class="glyphicon glyphicon-remove form-control-feedback gone"></span>
          </div>
          @if($errors->has('confirm'))
            <div class="alert alert-danger">@lang($errors->first('confirm'))</div>
          @endif

          <div class="checkbox text-left">
            <label class="unselectable">
              <input class="check_box" type="checkbox">
              @lang('auth.register.statement', ['url' => url('/privacy')])
             </label>
          </div>

          <div style="text-align: right">
            <button type="submit" class="btn btn-default btn-main btn-register"><span>@lang('auth.register.title')</span></button>
          </div>

        </form>
      </div>
    </div>
  </div>

@endsection
