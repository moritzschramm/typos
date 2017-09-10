# Typos

Web app to learn touch typing build with the Laravel PHP Framework


## Development setup
### Requirements
 - everything needed for laravel (see https://laravel.com/docs/5.5/installation)

### Setup

Start by copying the <code>.env.example</code> file to <code>.env</code> in the projects root directory.
After that, update composer by running <code>composer update</code> and
generate an application key with <code>php artisan key:generate</code>
If you want to use docker for development, there is a prepared docker-compose.yml file.
However, if you haven't installed docker and docker-compose, it's propably easier to use
a VM like Homestead, you just have to change some settings in your .env file
(to be specific, <code>DB_HOST</code> and <code>REDIS_HOST</code>).
A guide for using homestead: https://laravel.com/docs/5.5/homestead


#### Using docker and docker-compose

Start docker containers
<br>
<code>
docker-compose up -d
</code>
<br>
To access the application now, go to http://localhost via your browser
<br>
Stop docker containers
<br>
<code>
docker-compose down
</code>

### Running tests

Run <code>phpunit</code> or <code>./vendor/bin/phpunit</code>

#### Using docker
Since the docker containers run on their own network, we can't run <code>phpunit</code> directly
(we have to run it from within the php docker container).
There is a little bash script to make things easier:<br>
<code>
./start-test
</code><br>
This is basically just an alias for <code>phpunit</code>.


### Migrations
Run <code>php artisan migrate --seed</code> (this will automatically seed the database with a test user).
<br>
You may also want to upload the wordlists used by the 'training mode' of the app
(<code>php artisan words:upload all</code>). For more info, read the readme located in ./resources/assets/wordlists.

#### Using docker
Again, since the docker containers are on their own network, running <code>php artisan migrate</code> doesn't work as expected, and, again, there is a bash script called remote-artisan, which just runs <code>php artisan</code> from within the php docker container.<br>
<code>
./remote-artisan
</code>
<br>
So to migrate, run:<br>
<code>
./remote-artisan migrate --seed
</code>
<br>

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
