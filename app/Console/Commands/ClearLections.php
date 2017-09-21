<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;

class ClearLections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unload:lections {--locale=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $locales = $this->option('locale');

      if(count($locales) === 0) {

        if($this->confirm('Do you really want to delete _ALL_ lections?')) {

          DB::table('lections')->delete();
        }
        $this->info('Deleted.');
        return;

      } else {

        if($this->confirm('Do you really want to delete all lections with these locales:' . "\n" . implode($locales, "\n"))) {

          foreach($locales as $locale) {

            DB::table('lections')->where('locale', $locale)->delete();
          }

        }
        $this->info('Deleted.');
        return;
      }

      $this->info('Aborted.');
    }
}
