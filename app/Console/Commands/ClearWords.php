<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Schema;
use DB;

class ClearWords extends Command
{
    /**$filename
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'unload:words {--name=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Delete all words from specified tables.
Use 'php artisan unload:words' to clear all wordlist tables.";

    /**
     * The directory of the wordlists
     *
     * @var string
     */
    protected $wordlists_dir = __DIR__ . '/../../../resources/assets/wordlists';

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
      $wordlists = $this->option('name');

      if(is_null($wordlists) || count($wordlists) === 0) {

        // find names of all wordlists
        $wordlists = $this->find_wordlists();
      }

      $this->info('Table(s) to be cleared:');
      $this->info(implode("\n", $wordlists));

      if($this->confirm('Are you sure you want to delete all rows of these table(s)?')) {

        $bar = $this->output->createProgressBar(count($wordlists));

        foreach($wordlists as $wordlist) {

          if(Schema::hasTable($wordlist)) {

            DB::table($wordlist)->truncate();

            $this->info('Table ' . $wordlist . ' cleared.');


          } else {

            $this->info('Table ' . $wordlist . ' does not exist, skipping.');
          }

          $bar->advance();
        }

        $bar->finish();

        $this->info("\nTables cleared");

      } else {

        $this->info('Aborted');
      }
    }

    protected function find_wordlists() {

      $dir_contents = scandir($this->wordlists_dir);
      $wordlists = [];

      foreach($dir_contents as $filename) {

        if( ! is_dir($filename) && $filename !== 'readme.md') {

          $wordlists[] = $filename;
        }
      }

      return $wordlists;
    }
}
