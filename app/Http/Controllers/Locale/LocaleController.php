<?php

namespace App\Http\Controllers\Locale;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App;

class LocaleController extends Controller
{

  /**
    * change locale (if in availableLocales)
    * keeps session flash memory
    *
    * @param Request: the request object
    * @param $locale: the locale
    *
    * @return redirect(): back to previous page
    */
  public function setLocale(Request $request, $locale)
  {
    session()->reflash();

    if( ! in_array($locale, config('app.available_locales'))) {

      $locale = 'en';
    }

    App::setLocale($locale);
    session(['app_locale' => $locale]);   # App::setLocale is not persistent, middleware will set locale for every request

    return redirect()->back()->with('notification-success', 'notifications.locale.changed');
  }
}
