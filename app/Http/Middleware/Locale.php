<?php

namespace App\Http\Middleware;

use Closure;

use App, Auth;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if(!session()->has('app_locale') || !in_array(session('app_locale'), config('app.available_locales'))) {

        if(Auth::check()) {

          $lang = Auth::user()->locale;

        } else if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {

          $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
          # since we currently only have 2 supported languages,
          # we only check if the first language is 'de', if not, fallback to 'en'

        } else {

          $lang = 'en';
        }

        switch ($lang) {

          case 'de':

            session(['app_locale' => 'de']);
            break;

          default: # case 'en':             // fallback to locale 'en'

            session(['app_locale' => 'en']);
            break;
        }

      }

      App::setLocale(session('app_locale'));

      return $next($request);
    }
}
