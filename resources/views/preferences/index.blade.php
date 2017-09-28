@extends('layouts.main')

@section('title')
  @lang('preferences.title')
@endsection

@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="/css/preferences.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container" style="min-height: 100vh;">

  <div class="settingscontainer">

    <h3 style="padding: 8px 20px;">@lang('preferences.title')</h3>
    <hr style="margin-bottom: 0;">

    <div class="navcontainer">

      <ul>

        <a href="{{ url('/preferences?view=account') }}">
          <li class="@echoIf($view == 'account', 'item-active') unselectable">@lang('preferences.account')</li>
        </a>
        <a href="{{ url('/preferences?view=security') }}">
          <li class="@echoIf($view == 'security', 'item-active') unselectable">@lang('preferences.security')</li>
        </a>
        <a href="{{ url('/preferences?view=app') }}">
          <li class="@echoIf($view == 'app', 'item-active') unselectable">@lang('preferences.app')</li>
        </a>

      </ul>

    </div>


    <div class="content">

      @if($view == 'account')

        <h4>@lang('preferences.account')</h4>

        <h5>@lang('preferences.changeEmail')</h5>

        <form role="form" method="POST" action="{{ url('/preferences/account/email') }}">
          {{ csrf_field() }}

          <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-4">
              <input id="password" name="password" class="form-control" type="password" placeholder="@lang('preferences.currentPassword')">
              @if($errors->has('password'))
                <br>
                <div class="alert alert-danger">@lang($errors->first('password'))</div>
              @endif
            </div>
          </div>
          <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-4">
              <input id="email" name="email" class="form-control" type="text" placeholder="@lang('preferences.newEmail')">
              @if($errors->has('email'))
                <br>
                <div class="alert alert-danger">@lang($errors->first('email'))</div>
              @endif
            </div>
          </div>

          @if($errors->has('credentials'))
            <div class="alert alert-danger">@lang($errors->first('credentials'))</div>
          @endif

          <p>@lang('preferences.changeEmailInfo')</p>

          <button type="submit" class="btn btn-default btn-simple" style="width: 150px;">@lang('info.save')</button>
        </form>

        <br><br>

        <div class="panel panel-danger">
          <div class="panel-heading">@lang('preferences.deleteTitle'):</div>
          <div class="panel-body">
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_stats">@lang('preferences.deleteStats')</button>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_acc">@lang('preferences.deleteAccount')</button>
          </div>
        </div>

      @elseif($view == 'security')

        <h4>@lang('preferences.security')</h4>

        <form role="form" method="POST" action="{{ url('/preferences/security/password') }}">
          {{ csrf_field() }}

          <div class="row" style="margin-bottom: 10px;">

            <div class="col-xs-4">
              @lang('preferences.currentPassword'):
              <input id="currentPassword" name="currentPassword" class="form-control" type="password" placeholder="@lang('preferences.currentPassword')">
              @if($errors->has('currentPassword'))
                <br>
                <div class="alert alert-danger">@lang($errors->first('currentPassword'))</div>
              @endif
            </div>

          </div>
          <div class="row" style="margin-bottom: 10px;">

            <div class="col-xs-4">
              @lang('preferences.newPassword'):
              <input id="newPassword" name="newPassword" class="form-control" type="password" placeholder="@lang('preferences.newPassword')">
              @if($errors->has('newPassword'))
                <br>
                <div class="alert alert-danger">@lang($errors->first('newPassword'))</div>
              @endif
              @if($errors->has('password'))
                <br>
                <div class="alert alert-danger">@lang($errors->first('password'))</div>
              @endif
            </div>

          </div>
          <div class="row" style="margin-bottom: 10px;">

            <div class="col-xs-4">
              @lang('info.confirm'):
              <input id="confirm" name="confirm" class="form-control" type="password" placeholder="@lang('info.confirm')">
              @if($errors->has('confirm'))
                <br>
                <div class="alert alert-danger">@lang($errors->first('confirm'))</div>
              @endif
            </div>

          </div>

          @if($errors->has('credentials'))
            <br>
            <div class="alert alert-danger">@lang($errors->first('credentials'))</div>
          @endif

          <br>
          <button type="submit" class="btn btn-default btn-simple" style="width: 150px;">@lang('info.save')</button>
        </form>

      @elseif($view == 'app')

        <h4>@lang('preferences.app')</h4>

        <form role="form" method="POST" action="{{ url('/preferences/app') }}">
          {{ csrf_field() }}

          <div class="row" style="margin-bottom: 10px;">
            <div class="col-xs-4">
              @lang('preferences.xpGoal'):
              <input value="{{ $xp_goal }}" id="xp_goal" name="xp_goal" class="form-control" type="text" placeholder="@lang('preferences.xpGoal')">
              @if($errors->has('xp_goal'))
                <br>
                <div class="alert alert-danger">@lang($errors->first('xp_goal'))</div>
              @endif
            </div>
          </div>

          <div class="checkbox unselectable">
            <label>
              <input type="checkbox" id="setting_assignment" name="setting_assignment" @echoIf($setting['assignment'], 'checked')>
              @lang('preferences.showAssignment')
            </label>
          </div>
          <div class="checkbox unselectable">
            <label>
              <input type="checkbox" id="setting_divider" name="setting_divider" @echoIf($setting['divider'], 'checked')>
              @lang('preferences.showDivider')
            </label>
          </div>
          <div class="checkbox unselectable">
            <label>
              <input type="checkbox" id="setting_keyboard" name="setting_keyboard" @echoIf($setting['keyboard'], 'checked')>
              @lang('preferences.showKeyboard')
            </label>
          </div>

          @lang('preferences.keyboardLayout'):
          <select name="keyboard_layout" id="keyboard_layout">
            <option value="de-de">Deutsch (Deutschland)</option>
            <option value="en-us">English (US)</option>
          </select>
          @if($errors->has('keyboard_layout'))
            <div class="alert alert-danger">@lang($errors->first('keyboard_layout'))</div>
          @endif

          {{-- changing option of select element (does only work via js, at least for firefox) --}}
          <script>document.getElementById("keyboard_layout").value = "{{ $keyboardLayout }}";</script>

          <br>
          <br>
          <button type="submit" class="btn btn-default btn-simple" style="width: 150px;">@lang('info.save')</button>
        </form>

      @endif

    </div>


    {{-- modals --}}

    <div id="modal_stats" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">@lang('preferences.modal.deleteStats.title')</h4>
          </div>
          <div class="modal-body">
            <p>@lang('preferences.modal.deleteStats.content')</p>
          </div>
          <div class="modal-footer">
            <form method="POST" action="{{ url('/preferences/account/reset') }}">
              {{ csrf_field() }}
              <button type="button" class="btn btn-default" data-dismiss="modal">@lang('info.back')</button>
              <button type="submit" class="btn btn-danger" data-dismiss="modal">@lang('preferences.reset')</button>
            </form>
          </div>
        </div>

      </div>
    </div>

    <div id="modal_acc" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">@lang('preferences.modal.deleteAccount.title')</h4>
          </div>
          <div class="modal-body">
            <p>@lang('preferences.modal.deleteAccount.content')</p>
          </div>
          <div class="modal-footer">
            <form method="POST" action="{{ url('/preferences/account/delete') }}">
              {{ csrf_field() }}
              <button type="button" class="btn btn-default" data-dismiss="modal">@lang('info.back')</button>
              <button type="submit" class="btn btn-danger" data-dismiss="modal">@lang('preferences.delete')</button>
            </form>
          </div>
        </div>

      </div>
    </div>

  </div>

</div>
@endsection
