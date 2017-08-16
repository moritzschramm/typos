<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Auth;

class LoginTest extends TestCase
{
  const URI = '/login';
  const EMAIL = 'test@example.com';
  const PASSWORD = 'testtest';

  /**
    * tests login, uses constant credentials (@see top)
    *
    * asserts redirect and Auth::check()
    */
  public function testSuccessfulLogin()
  {
    session()->start();

    $response = $this->call('POST', self::URI, [
      'email'     => self::EMAIL,
      'password'  => self::PASSWORD,
      '_token'    => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(\App\Http\Controllers\Auth\LoginController::$redirectTo);

    $this->assertTrue(Auth::check());
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
      'email'     => self::EMAIL,
      'password'  => 'definitelyTheWrongPass',
      '_token'    => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors(['credentials']);

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
      'email'     => '',
      'password'  => '',
      '_token'    => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors(['email', 'password']);

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
        'email'     => self::EMAIL,
        'password'  => 'definitelyTheWrongPass',
        '_token'    => csrf_token(),
      ]);

      $response->assertStatus(302);
      $response->assertRedirect(self::URI);
      $response->assertSessionHasErrors(['credentials']);

      $this->assertFalse(Auth::check());
    }

    $response = $this->call('POST', self::URI, [
      'email'     => self::EMAIL,
      'password'  => 'definitelyTheWrongPass',
      '_token'    => csrf_token(),
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors(['tooManyAttempts']);

    $this->assertFalse(Auth::check());
  }
}
