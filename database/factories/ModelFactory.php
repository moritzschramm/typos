<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'email'           => $faker->unique()->safeEmail,
        'password'        => $password ?: $password = bcrypt('testtest'),
        'uuid'            => uuidv4(),
        'remember_token'  => str_random(10),
        'locale'          => 'de',
    ];
});

$factory->state(App\Models\User::class, 'testEmail', function($faker) {
  return [
    'email' => 'test@example.com',
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
