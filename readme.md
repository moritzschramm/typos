# Typos

Web app to learn touch typing build with the Laravel PHP Framework


## Development setup
### Requirements
 - everything needed for laravel (see https://laravel.com/docs/5.4/installation)

If you want to use docker for development, there is a prepared docker-compose.yml file. Of course you can use a VM like Homestead, you just have to change some settings in the .env file.

### Setup (when using docker and docker-compose)
Start by copying the .env.example file to .env in the projects root directory. After that, run <code>composer update</code>and create an application key with the command <code>php artisan key:generate</code>

<code>
docker-compose up -d    # start docker containers
</code>
To access the application now, go to http://localhost via your browser

<code>
docker-compose down     # stop docker containers
</code>

### Running tests
Since the docker containers run on their own network, we can't run <code>phpunit</code> directly
(we have to run it from within the php docker container).
There is a little bash script to make things easier:<br>
<code>
./start-test
</code><br>
This is basically just an alias for <code>phpunit</code>.

### Migrations
Again, since the docker containers are on their own network, running <code>php artisan migrate</code> doesn't work as expected, and, again, there is a bash script called remote-artisan, which just runs <code>php artisan</code> from within the php docker container.<br>
<code>
./remote-artisan
</code>
<br>
So to migrate, run:<br>
<code>
./remote-artisan migrate
</code>
<br>
