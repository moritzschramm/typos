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
  const CONTENT_REGEX_TEST = "abzABZ123°!\"§$%&/()= ?\ß+#*'~äüö.,;:-_<>|@€µ\nhello\thi\nhow are you?";

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

  protected function makeRequestAndAssertSuccess($user, $title, $content)
  {
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

    # "normal" input
    $this->makeRequestAndAssertSuccess($user, $title, $content);

    # test regex
    $this->makeRequestAndAssertSuccess($user, $title, self::CONTENT_REGEX_TEST);
  }

  protected function makeRequestAndAssertFailure($title, $content, $errors)
  {
    $response = $this->call('POST', self::URI_CREATE, [
      '_token'  => csrf_token(),
      'title'   => $title,
      'content' => $content,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI_CREATE);
    $response->assertSessionHasErrors($errors);
  }

  /**
    * test validation
    */
  public function testCreateExerciseInvalidInput()
  {
    $user = $this->prepareSession();
    $faker = \Faker\Factory::create();

    ## test no input ##
    $this->makeRequestAndAssertFailure('', '', [
      'title'   => 'errors.required',
      'content' => 'errors.required',
    ]);

    ## test title too long ##
    $title = generateSecureString(config('database.stringLength') + 1);
    $content = $faker->realText(300);

    $this->makeRequestAndAssertFailure($title, $content, ['title' => 'errors.max']);

    ## test invalid characters ##
    $title = $faker->realText(50);
    $content = self::CONTENT_REGEX_TEST . '¹²³ł';

    $this->makeRequestAndAssertFailure($title, $content, [
      'characters' => 'exercise.errors.invalidChars',
    ]);
  }
}
