<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Auth;
use App\Models\User;

class AddExerciseTest extends TestCase
{
  use DatabaseTransactions;

  const URI_CREATE = '/exercise';

  /**
    * start session and log user in
    */
  protected function prepareSession()
  {
    session()->start();

    $user = User::getTestUser();
    Auth::login($user);
    $this->assertTrue(Auth::check());

    $response = $this->get(self::URI_CREATE);
    $response->assertStatus(200);

    return $user;
  }

  /**
   * test create exercise
   *
   * assert response and database
   */
  public function testCreateExerciseSuccessfully()
  {
    $user = $this->prepareSession();
    $faker = \Faker\Factory::create();

    $title = $faker->realText(50);
    $content = $faker->realText(300);

    $response = $this->call('POST', self::URI_CREATE, [
      '_token'  => csrf_token(),
      'title'   => $title,
      'content' => $content,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('/dashboard?view=exercises');
    $response->assertSessionHas('notification-success');

    $this->assertDatabaseHas('exercises', [
      'id_user'           => $user->id_user,
      'title'             => $title,
      'content'           => $content,
      'character_amount'  => strlen($content),
      'is_public'         => NULL,
    ]);
  }

  /**
    * test validation
    */
  public function testCreateExerciseInvalidInput()
  {
    $user = $this->prepareSession();
    $faker = \Faker\Factory::create();

    ## test no input ##
    $response = $this->call('POST', self::URI_CREATE, [
      '_token'  => csrf_token(),
      'title'   => '',
      'content' => '',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI_CREATE);
    $response->assertSessionHasErrors([
      'title'   => 'errors.required',
      'content' => 'errors.required',
    ]);

    ## test title too long ##
    $title = generateSecureString(config('database.stringLength') + 1);
    $content = $faker->realText(300);

    $response = $this->call('POST', self::URI_CREATE, [
      '_token'  => csrf_token(),
      'title'   => $title,
      'content' => $content,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI_CREATE);
    $response->assertSessionHasErrors([
      'title'   => 'errors.max',
    ]);
  }
}
