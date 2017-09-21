<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Lection;

class UploadLections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:lections {--name=*} {--locale=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload lections from ./resources/assets/lections to database.
You can upload specific lections with the --name=<lection_name> option (example of lection name: de/1.json).
';

    /**
     * The directory of the wordlists
     *
     * @var string
     */
    protected $lections_dir = __DIR__ . '/../../../resources/assets/lections';

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
      $lections = $this->option('name');
      $locales  = $this->option('locale');

      if(count($lections) === 0 && count($locales) === 0) {   # if no options are set, find all lections

        // find all lections
        $lections = $this->find_all_lections();

      } else if(count($lections) === 0) {   # locales set, find only lections with specified locale

        $lections = [];

        // scan every given directory
        foreach($locales as $locale) {

          $lections[] = $this->scan_locale_dir($locale);
        }

      } // else: $lections is set and will be used; NOTE: $locales will be ignored

      $this->info('Lections(s) to be uploaded:');
      $this->info(implode("\n", $lections));

      if($this->confirm('Do you want to proceed?')) {

        $this->info('Proceeding');

        foreach($lections as $lection) {

          $this->info('Starting to upload \'' . $lection. '\'');
          $this->upload_lection($lection);
        }


      } else {

        $this->info('Aborted');
      }
    }

    /**
      * looks through $lections_dir and finds all lection files
      *
      * @return array (lection filenames relative to $lections_dir)
      */
    public function find_all_lections()
    {
      $dir_contents = scandir($this->lections_dir);

      $lections = [];

      foreach($dir_contents as $filename) {

        if(is_dir($this->lections_dir . '/' . $filename) && $filename !== '.' && $filename !== '..') {
          # only open directories, except . and ..

          $lections = array_merge($lections, $this->scan_locale_dir($filename));
        }
      }

      return $lections;
    }

    /**
      * looks through a specific locale directory and returns lections
      *
      * @param string $locale: the name of the directory (relative to $lections_dir)
      * @return array (lection filenames relative to $lections_dir)
      */
    public function scan_locale_dir($locale)
    {
      $dir_contents = glob($this->lections_dir . '/' . $locale . '/*.json');

      $lections = [];

      foreach($dir_contents as $filename) {

        $parts = explode('/', $filename);
        $len = count($parts);

        $lections[] = $parts[$len - 2] . '/' . $parts[$len - 1];
      }

      return $lections;
    }

    /**
      * uploads a lection to database
      *
      * @param string $lection: name of the lection file (relative to $lection_dir)
      * @return void
      */
    public function upload_lection($lection)
    {
      $path = $this->lections_dir . '/' . $lection;
      $locale = explode('/', $lection)[0];

      // parse json
      $contents = file_get_contents($path);
      $lectionJSON = json_decode($contents);

      if( ! $this->lection_exists($lectionJSON->id, $locale)) {

        $lection = new Lection([
          'external_id'       => $lectionJSON->id,
          'title'             => $lectionJSON->title,
          'content'           => implode($lectionJSON->content, "\n"),
          'character_amount'  => strlen(implode($lectionJSON->content, "\n")),
          'locale'            => $locale,
        ]);

        $lection->save();

        $this->info('Upload complete' . "\n");

      } else {

        $this->info('Skipping: Lection already exists in database.');
      }
    }

    /**
      * checks if a lection already exists in database
      *
      * @param integer $external_id
      * @param string $locale
      * @return boolean $exists
      */
    public function lection_exists($external_id, $locale)
    {
      $lection = Lection::where([
        ['external_id', '=', $external_id],
        ['locale',      '=', $locale],
      ])->first();

      return $lection ? true : false;
    }
}
