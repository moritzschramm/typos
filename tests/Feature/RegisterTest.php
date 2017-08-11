<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DB;

class RegisterTest extends TestCase
{
  use DatabaseTransactions;

  const URI = '/register';
  const EMAIL = 'someMail@example.com';
  const PASSWORD = 'testtest1234'; # absolutely safe password

  /**
   * tests registration with valid input
   *
   * asserts redirect and database
   */
  public function testSuccessfulRegistration()
  {
    session()->start();

    # make simple GET request so that back() works as expected
    $response = $this->call('GET', self::URI);
    $response->assertStatus(200);

    $response = $this->call('POST', self::URI, [
      '_token'    => csrf_token(),
      'email'     => self::EMAIL,
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('/register/success');

    $user = DB::table('users')->where('email', self::EMAIL)->get();

    $this->assertEquals(1, $user->count());
    $user = $user->first();
    $this->assertEquals(0, $user->verified);
  }
}
