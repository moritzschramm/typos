@extends('layouts.main')

@section('title')
  404
@endsection

@section('content')

<div class="container" style="min-height:100vh">

  <h1>404 - Page not found</h1>
  <p>Please check the URL or go to the <a href="{{ url('/') }}">index page</a>.</p>

</div>

@endsection
