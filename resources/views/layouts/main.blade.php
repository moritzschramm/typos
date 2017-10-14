<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
  <title>@yield('title') - Typos</title>
  <meta charset="utf-8">
  @include('layouts.favicon-meta')
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href='https://fonts.googleapis.com/css?family=Arvo|Montserrat|Raleway|Lato|Open+Sans:400,600' rel='stylesheet' type='text/css'>
  <link href="/res/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="/res/css/main.min.css" rel="stylesheet" type="text/css">

  @yield('header')
</head>

<body @yield('bodyattr')>

  @include('layouts.notification-bar')

  @include('layouts.navbar')

  @yield('content')

  @include('layouts.footer')

  <script src="/res/js/jquery.min.js"></script>
	<script src="/res/js/bootstrap.min.js"></script>
  <script>
  $(document).ready(function() {
    $(".notification-close").on("click", function() {
      $(".notification").fadeOut();
    });
  });
  </script>

  @yield('footer')

</body>

</html>
