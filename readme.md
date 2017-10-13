# Typos

[![Build Status](https://travis-ci.org/moritzschramm/typos.svg?branch=master)](https://travis-ci.org/moritzschramm/typos)

Web app to learn touch typing build with the Laravel PHP Framework


## Development setup
### Requirements
 - everything needed for laravel (see https://laravel.com/docs/5.5/installation)

### Setup

Start by copying the <code>.env.example</code> file to <code>.env</code> in the project's root directory.
After that, install all dependencies by running <code>composer install</code> and
generate an application key with <code>php artisan key:generate</code>
If you want to use docker for development, there is a prepared docker-compose.yml file (intended only for development).
However, if you haven't installed docker and docker-compose, it's probably easier to use
a VM like Homestead, you just have to change some settings in your <code>.env</code> file
(to be specific, <code>DB_HOST</code> and <code>REDIS_HOST</code>).
A guide for using homestead: https://laravel.com/docs/5.5/homestead


#### Using docker and docker-compose

Start docker containers
<br>
<code>docker-compose up -d</code>
<br>
To access the application now, go to http://localhost via your browser
<br>
<br>
Stop docker containers
<br>
<code>docker-compose down</code>

### Running php artisan on docker container
Since the docker containers run on their own network, 
some artisan commands that require a database connection, 
or any other connection to a container, 
will fail if called by <code>php artisan</code> via your local terminal. 
In order to make these commands work, you have to run them on the PHP docker container.<br>
There is a little php script to make things easier:<br>
<code>php remote-artisan</code>
<br>
This script will simply execute <code>php artisan</code> on the PHP docker container.<br>

### Running tests

Run <code>phpunit</code> or <code>./vendor/bin/phpunit</code>

#### Using docker
Again, since the docker containers are on their own network, running <code>phpunit</code> doesn't work as expected, and, again, there is a php script called start-test, which just runs <code>phpunit</code> from within the php docker container.<br>
<code>php start-test</code><br>


### Migrations
Run <code>php artisan migrate --seed</code> (this will automatically seed the database with a test user).
<br>
You may also want to upload the wordlists and lections used by the 'training mode' of the app
(<code>php artisan load:words</code> and <code>php artisan load:lections</code>). For more info, read the readme located in ./resources/assets/wordlists.

#### Using docker

<code>php remote-artisan migrate --seed</code>
<br>

## License

The Laravel framework as well as Typos is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
