<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
      \Illuminate\Support\Facades\Schema::defaultStringLength(config('database.stringLength'));

      Blade::directive('echoIf', function ($arguments) {

        list($statement, $output) = explode(',', str_replace(['(',')',' '], '', $arguments));

        return "<?php if($statement) echo($output); ?>";
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
