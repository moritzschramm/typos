<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'username'        => $faker->unique()->userName,
        'email'           => $faker->unique()->safeEmail,
        'password'        => $password ?: $password = bcrypt('testtest'),
        'uuid'            => uuidv4(),
        'remember_token'  => str_random(10),
        'locale'          => 'de',
    ];
});

$factory->state(App\Models\User::class, 'no-email-user', function($faker) {
  return [
    'username'  => 'NoMail',
    'email'     => NULL,
  ];
});

$factory->state(App\Models\User::class, 'unverified-user', function($faker) {
  return [
    'username'  => 'UnverfiedUser',
    'email'     => 'unverfied@example.com',
    'verified'  => NULL,
  ];
});

$factory->state(App\Models\User::class, 'testuser', function($faker) {
  return [
    'username'  => 'testuser',
    'email'     => 'test@example.com',
  ];
});

$factory->state(App\Models\User::class, 'verified', function($faker) {
  return [
    'verified' => date('Y-m-d H:i:s'),
  ];
});

$factory->state(App\Models\User::class, 'unverified', function($faker) {
  return [
    'verified' => NULL,
  ];
});

$factory->state(App\Models\User::class, 'locale-en', function($faker) {
  return [
    'locale' => 'en',
  ];
});

$factory->define(App\Models\UserPreference::class, function (Faker\Generator $faker) {

    return App\Models\UserPreference::defaults();
});
