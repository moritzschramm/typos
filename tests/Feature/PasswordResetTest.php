<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DB, Mail;
use App\Models\User;
use App\Traits\CreateUserToken;
use App\Mail\PasswordResetMail;

class PasswordResetTest extends TestCase
{
  use CreateUserToken;
  use DatabaseTransactions;

  const requestURI = '/password/forgot';
  const resetURI = '/password/reset';

  /**
   * test request password reset
   *
   * assert database, redirect and mail
   */
  public function testSuccessfulRequestPasswordReset()
  {
    session()->start();
    Mail::fake();

    $user = User::find(1);

    $response = $this->call('POST', self::requestURI, [
      '_token' => csrf_token(),
      'email' => $user->email,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(LoginTest::URI);
    $response->assertSessionHas('notification');

    Mail::assertQueued(PasswordResetMail::class, function($mail) use ($user) {
      return $mail->hasTo($user->email);
    });

    $this->assertNotNull(DB::table('users')->where('uuid', $user->uuid)->first()->token);
  }

  /**
    * helper function for testFailedRequestPasswordReset()
    * test validation of request password reset
    *
    * assert session errors, redirect and mail
    */
  private function failedRequestPasswordReset($params, $errors)
  {
    session()->start();
    Mail::fake();

    $response = $this->call('GET', self::requestURI);
    $response->assertStatus(200);

    $response = $this->call('POST', self::requestURI, $params);

    $response->assertStatus(302);
    $response->assertRedirect(self::requestURI);
    $response->assertSessionHasErrors($errors);

    Mail::assertNotQueued(PasswordResetMail::class);
  }
  public function testFailedRequestPasswordReset()
  {
    # test 'required' validation
    $this->failedRequestPasswordReset(['_token' => csrf_token(), 'email' => ''], ['email']);

    # test 'email' validation
    $this->failedRequestPasswordReset(['_token' => csrf_token(), 'email' => 'thisIsNotAnEmail'], ['email']);

    # test 'exists' validation
    $user = User::find(1);
    $this->failedRequestPasswordReset(['_token' => csrf_token(), 'email' => 'wrong' . $user->email], ['email']);

    # test throttling (only 2 more requests needed, 3 already made)
    $this->failedRequestPasswordReset(['_token' => csrf_token(), 'email' => 'wrong'], ['email']);
    $this->failedRequestPasswordReset(['_token' => csrf_token(), 'email' => 'wrong'], ['email']);
    $this->failedRequestPasswordReset(['_token' => csrf_token(), 'email' => 'wrong'], ['tooManyAttempts']);
  }

  public function testSuccessfulReset()
  {
    session()->start();

    $user = User::find(1);
    $old_pw_hash = $user->password;
    $token = $this->createUserToken($user);

    $response = $this->call('GET', self::resetURI, [
      'uid' => $user->uuid,
      'token' => $token,
    ]);

    $response->assertStatus(200);
    $response->assertSessionHas(['uuid', 'token']);

    $response = $this->call('POST', self::resetURI, [
      '_token' => csrf_token(),
      'password' => 'testtest1234',
      'confirm' => 'testtest1234',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(LoginTest::URI);
    $response->assertSessionHas('notification-success');

    $new_pw_hash = DB::table('users')->where('uuid', $user->uuid)->first()->password;

    $this->assertNotEquals($old_pw_hash, $new_pw_hash);
  }

  public function testUnauthorizedPasswordReset()
  {
    session()->start();

    $response = $this->call('POST', self::resetURI, [
      '_token' => csrf_token(),
      'password' => 'testtest1234',
      'confirm' => 'testtest1234',
    ]);

    $response->assertStatus(403);
  }

  /**
    * helper function for testValidationPasswordReset()
    * test validation for password reset
    *
    * asserts redirect, session errors and database
    */
  private function validationPasswordReset($params, $errors)
  {
    session()->start();

    $user = User::find(1);
    $old_pw_hash = $user->password;
    $token = $this->createUserToken($user);

    $uri = self::resetURI . '?token=' . $token . '&uid=' . $user->uuid;

    $response = $this->call('GET', $uri);

    $response->assertStatus(200);
    $response->assertSessionHas(['uuid', 'token']);

    $response = $this->call('POST', self::resetURI, $params);

    $response->assertStatus(302);
    $response->assertRedirect($uri);
    $response->assertSessionHasErrors($errors);

    $new_pw_hash = DB::table('users')->where('uuid', $user->uuid)->first()->password;

    $this->assertEquals($old_pw_hash, $new_pw_hash);
  }
  public function testValidationPasswordReset()
  {
    # test 'required' validation
    $this->validationPasswordReset([
      '_token' => csrf_token(),
       'password' => '',
       'confirm' => '',
     ], ['password', 'confirm']);

     # test 'confirm !== password' validation
     $this->validationPasswordReset([
       '_token' => csrf_token(),
        'password' => 'testtest12345',
        'confirm' => '123453afddsafds',
      ], ['confirm']);

      # test 'confirm !== password' validation
      $this->validationPasswordReset([
        '_token' => csrf_token(),
         'password' => 'tooshor',
         'confirm' => 'tooshor',
       ], ['password']);
  }
}
