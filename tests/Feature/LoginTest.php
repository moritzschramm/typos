<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Auth;

class LoginTest extends TestCase
{
  const URI                 = '/login';
  const USERNAME            = 'testuser';
  const EMAIL               = 'test@example.com';
  const PASSWORD            = 'testtest';
  const USERNAME_NOMAIL     = 'NoMail';
  const USERNAME_UNVERIFIED = 'UnverfiedUser';


  /**
    * tests login, uses constant credentials (@see top)
    *
    * asserts redirect and Auth::check()
    */
  public function testSuccessfulLogin()
  {
    session()->start();

    # test email
    $response = $this->call('POST', self::URI, [
      'emailOrUsername' => self::EMAIL,
      'password'        => self::PASSWORD,
      '_token'          => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(\App\Http\Controllers\Auth\LoginController::$redirectTo);

    $this->assertTrue(Auth::check());

    Auth::logout();
    $this->assertFalse(Auth::check());

    # test username
    $response = $this->call('POST', self::URI, [
      'emailOrUsername' => self::USERNAME,
      'password'        => self::PASSWORD,
      '_token'          => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(\App\Http\Controllers\Auth\LoginController::$redirectTo);

    $this->assertTrue(Auth::check());

    # test username without email
    $response = $this->call('POST', self::URI, [
      'emailOrUsername' => self::USERNAME,
      'password'        => self::PASSWORD,
      '_token'          => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(\App\Http\Controllers\Auth\LoginController::$redirectTo);

    $this->assertTrue(Auth::check());
  }

  /**
    * tests login using unverified user
    *
    * asserts redirect, session errors and Auth::check()
    */
  public function testLoginWithUnverifiedUser()
  {
    session()->start();

    $response = $this->call('GET', self::URI);
    $response->assertStatus(200);

    $response = $this->call('POST', self::URI, [
      'emailOrUsername' => self::USERNAME_UNVERIFIED,
      'password'        => self::PASSWORD,
      '_token'          => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors(['unverified' => 'errors.unverified']);

    $this->assertFalse(Auth::check());
  }

  /**
    * tests login, uses wrong credentials
    *
    * asserts redirect, session errors and Auth::check()
    */
  public function testLoginWithWrongCredentials()
  {
    session()->start();

    $response = $this->call('GET', self::URI);
    $response->assertStatus(200);

    $response = $this->call('POST', self::URI, [
      'emailOrUsername' => self::EMAIL,
      'password'        => 'definitelyTheWrongPass',
      '_token'          => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors(['credentials' => 'errors.credentials']);

    $this->assertFalse(Auth::check());
  }

  /**
    * test if validation works
    *
    * asserts redirect, session errors and Auth::check()
    */
  public function testLoginNoInput()
  {
    session()->start();

    $response = $this->call('GET', self::URI);
    $response->assertStatus(200);

    $response = $this->call('POST', self::URI, [
      'emailOrUsername' => '',
      'password'        => '',
      '_token'          => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors(['emailOrUsername' => 'errors.required', 'password' => 'errors.required']);

    $this->assertFalse(Auth::check());
  }

  /**
    * tests if the throttle middleware works properly
    *
    * asserts redirect, session errors and Auth::check()
    */
  public function testLoginThrottle()
  {
    session()->start();

    $response = $this->call('GET', self::URI);
    $response->assertStatus(200);

    # simulate 5 failed login attempts (after that, login should be limited)
    for($i = 0; $i < 5; $i++) {

      $response = $this->call('POST', self::URI, [
        'emailOrUsername' => self::EMAIL,
        'password'        => 'definitelyTheWrongPass',
        '_token'          => csrf_token(),
      ]);

      $response->assertStatus(302);
      $response->assertRedirect(self::URI);
      $response->assertSessionHasErrors(['credentials' => 'errors.credentials']);

      $this->assertFalse(Auth::check());
    }

    # login should be limited
    $response = $this->call('POST', self::URI, [
      'emailOrUsername' => self::EMAIL,
      'password'        => 'definitelyTheWrongPass',
      '_token'          => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors(['tooManyAttempts' => 'errors.throttle']);

    $this->assertFalse(Auth::check());
  }
}
