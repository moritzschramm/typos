<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Schema;
use DB;

class UploadWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'words:upload {filenames*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Uploads wordlists from /resources/assets/wordlists to the database.
Expects locale of wordlists to upload (Like 'php artisan words:upload words-de.txt words-en.txt').
You can also call 'php artisan words:upload all' to upload
all wordlists from the wordlist directory.";

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
      $wordlists = $this->argument('filenames');

      if(is_null($wordlists) || count($wordlists) === 0) {

        $this->error("Please specify a wordlist to be uploaded. See 'php artisan help words:upload' for help");
        return;
      }

      if($wordlists[0] === 'all') {

        // find names of all wordlists
        $wordlists = $this->find_wordlists();

      }

      $this->info('Wordlist(s) to be uploaded:');
      $this->info(implode("\n", $wordlists));

      if($this->confirm('Do you want to proceed?')) {

        $this->info('Proceeding');

        foreach($wordlists as $wordlist) {

          $this->info('Starting to upload \'' . $wordlist . '\'');
          $this->upload_wordlist($wordlist);
        }


      } else {

        $this->info('Aborted');
      }
    }

    protected function find_wordlists() {

      $dir_contents = scandir($this->wordlists_dir);
      $wordlists = [];

      foreach($dir_contents as $content) {

        if( ! is_dir($content) && $content !== 'readme.md') {     // filter directories and readme out

          $wordlists[] = $content;
        }
      }

      return $wordlists;
    }

    protected function upload_wordlist($wordlist) {

      $table = $wordlist;

      if( ! Schema::hasTable($table)) {       // check if table exists; abort if not

        $this->error('Table \'' . $table. '\' does not exist. Please create table first (migrate)');
        return;
      }

      $this->info('Using table name \'' . $table . '\'');

      $handle = fopen($this->wordlists_dir . '/' . $wordlist, 'r');

      if ($handle) {

        $id = 1;
        $words = [];

        $bar = $this->create_progressbar($handle);

        while (($word = fgets($handle)) !== false) {  // read file line for line

          $word = str_replace("\n", '', $word);

          if($word !== '')                          // dont insert empty words
            $words[] = ['id' => $id, 'word' => $word];  // prepare table row

          if(count($words) > 100) {                 // insert every 100 words

            DB::table($table)->insert($words);
            $words = [];
          }

          $id++;
          $this->advance_progressbar($bar);
        }

        if(count($words) > 0) {                  // insert remaining words

          DB::table($table)->insert($words);
          $words = [];
        }

        $this->finish_progressbar($bar);

        if (!feof($handle)) {
          $this->error('Unexpected error: fgets(), wordlist: ' . $wordlist);
        }

        fclose($handle);
      }
    }

    protected function count_lines_of_wordlist($handle) {

      $linecount = 0;

      while(!feof($handle)) {

        fgets($handle);         // set file pointer one line ahead
        $linecount++;
      }

      rewind($handle);        // set file pointer to beginning of file

      return $linecount;
    }

    protected function create_progressbar($handle) {

      return $this->output->createProgressBar($this->count_lines_of_wordlist($handle));
    }

    protected function advance_progressbar($bar) {

      if( ! is_null($bar)) {

        $bar->advance();
      }
    }

    protected function finish_progressbar($bar) {

      if( ! is_null($bar)) {

        $bar->finish();
      }
    }
}
