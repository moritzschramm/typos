@extends('layouts.main')

@section('title')
  @lang('auth.register.success.title')
@endsection

@section('header')
  <link href="/css/register.min.css" rel="stylesheet" type="text/css">
@endsection

@section('content')

<div class="container success-outer">

  <div class="row">

    <div class="col-md-6 col-md-offset-3 success-container">

      <div class="alert alert-success" style="margin-bottom: 0;">
        @lang('auth.register.success.message', ['email' => $email, 'url' => url('/login')])
      </div>

    </div>

  </div>

</div>

@endsection
