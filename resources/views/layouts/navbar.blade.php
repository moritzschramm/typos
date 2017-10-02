@if(Auth::check())

  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
  				<span class="icon-bar"></span>
  				<span class="icon-bar"></span>
  				<span class="icon-bar"></span>
  			</button>
  			<a class="navbar-brand" href="{{ url('/') }}">Typos</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
  			<ul class="nav navbar-nav navbar-left">
  				<li @yield('nav1')><a href="{{ url('/dashboard') }}">@lang('layout.dashboard')</a></li>
  				<li @yield('nav2')><a href="{{ url('/statistics') }}">@lang('layout.stats')</a></li>
  				<li @yield('nav3')><a href="{{ url('/support') }}">@lang('layout.support')</a></li>
  			</ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a class="dropdown-toggle btn-account" data-toggle="dropdown" href="#"></a>
              <ul class="dropdown-menu">
                <li style="color: #333; padding: 3px 20px;">{{ Auth::user()->username }}</li>
                  <li class="divider"></li>
                  <li><a href="{{ url('/preferences') }}">@lang('layout.settings')</a></li>
                  <li><a href="{{ url('/help') }}">@lang('layout.help')</a></li>
                 <li class="divider"></li>
                <li>
                  <a href="{{ url('/logout') }}">@lang('layout.logout')</a>
                </li>
              </ul>
            </li>
          </ul>
  		</div>
    </div>
  </nav>

@else

  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="{{ url('/') }}"><!--span class="logo-part">T</span-->Typos</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav navbar-left">
          <li @yield('nav1')><a href="{{ url('/') }}">@lang('layout.home')</a></li>
          <li @yield('nav3')><a href="{{ url('/support') }}">@lang('layout.support')</a></li>
          <li id="github-link"><a href="https://github.com/moritzschramm/typos" target="_blank">GitHub</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="{{ url('/register') }}">@lang('layout.register')</a></li>
          <li><a href="{{ url('/login') }}" id="item-login">@lang('layout.login') <span class="glyphicon glyphicon-log-in"></span></a></li>
        </ul>
      </div>
    </div>
  </nav>

@endif

<div style="height:50px;"></div>{{-- margin for content --}}
