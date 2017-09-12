<!DOCTYPE html>
<html lang="{{ App::getLocale() }}">
<head>
  <title>@yield('title')</title>
  <meta charset="utf-8">
  <link rel="icon" href="/favicon.ico" type="image/vnd.microsoft.icon">
  <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href='https://fonts.googleapis.com/css?family=Arvo|Montserrat|Raleway|Lato|Open+Sans:400,600' rel='stylesheet' type='text/css'>
  <link href="/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="/css/main.min.css" rel="stylesheet" type="text/css">

  @yield('header')
</head>

<body>

  @include('layouts.notification-bar')

  @include('layouts.navbar')

  @yield('content')

  @include('layouts.footer')

  <script src="/js/jquery.min.js"></script>
	<script src="/js/bootstrap.min.js"></script>

  @yield('footer')

</body>

</html>
