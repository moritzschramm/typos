<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use DB, Mail;
use App\Mail\VerifyAccountMail;

class RegisterTest extends TestCase
{
  use DatabaseTransactions;

  const URI = '/register';
  const USERNAME = 'someuser';
  const EMAIL = 'someMail@example.com';
  const PASSWORD = 'testtest1234'; # absolutely safe password
  const LOCALE = 'en';

  /**
   * tests registration with valid input
   *
   * asserts redirect, database and mail
   */
  public function testSuccessfulRegistration()
  {
    session()->start();
    Mail::fake();

    # make simple GET request so that back() works as expected
    $response = $this->call('GET', self::URI);
    $response->assertStatus(200);

    $response = $this->call('POST', self::URI, [
      '_token'    => csrf_token(),
      'username'  => self::USERNAME,
      'email'     => self::EMAIL,
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
      'locale'    => self::LOCALE,
      'checkbox'  => true,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect('/register/success');

    $user = DB::table('users')->where('email', self::EMAIL)->get();

    $this->assertEquals(1, $user->count());
    $user = $user->first();
    $this->assertNull($user->verified);

    Mail::assertSent(VerifyAccountMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
  }

  /**
    * tests registration attempts that should fail
    *
    * asserts redirects, mail and session errors
    */
  public function testFailedRegistration()
  {
    session()->start();
    Mail::fake();

    $response = $this->call('GET', self::URI);
    $response->assertStatus(200);

    # test if 'required' validation works as expected
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => '',
      'email'     => '',
      'password'  => '',
      'confirm'   => '',
      'locale'    => '',
      'checkbox'  => true,
    ], ['email', 'password', 'confirm']);

    # test 'email' validation and 'username' alpha_dash
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => 'invalidUsernameßäöü',
      'email'     => '',
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
      'locale'    => self::LOCALE,
      'checkbox'  => true,
    ], ['email', 'username']);

    # test 'unique' validation
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => 'testuser',
      'email'     => 'test@example.com',
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
      'locale'    => self::LOCALE,
      'checkbox'  => true,
    ], ['email', 'username']);

    # test 'weak password' validation
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => self::USERNAME,
      'email'     => self::EMAIL,
      'password'  => 'password',
      'confirm'   => 'password',
      'locale'    => self::LOCALE,
      'checkbox'  => true,
    ], ['password']);

    # test 'password !== confirm' validation
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => self::USERNAME,
      'email'     => self::EMAIL,
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD . 'something',
      'locale'    => self::LOCALE,
      'checkbox'  => true,
    ], ['confirm']);

    # test 'locale' validation
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => self::USERNAME,
      'email'     => self::EMAIL,
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
      'locale'    => 'invalidLocale',
      'checkbox'  => true,
    ], ['locale']);
  }

  private function failedRegisterPostRequest($params, $errors) {

    $response = $this->call('POST', self::URI, $params);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors();

    Mail::assertNotSent(VerifyAccountMail::class);
  }
}
