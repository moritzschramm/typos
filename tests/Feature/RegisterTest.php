<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Auth, DB, Mail;
use App\Mail\VerifyAccountMail;

class RegisterTest extends TestCase
{
  use DatabaseTransactions;

  const URI      = '/register';
  const USERNAME = 'someuser';
  const EMAIL    = 'someMail@example.com';
  const PASSWORD = 'testtest1234'; # absolutely safe password
  const KEYBOARD = 'en-us';

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
      'keyboard'  => self::KEYBOARD,
      'checkbox'  => true,
    ]);

    $this->assertFalse(Auth::check());
    $response->assertStatus(200);

    $user = DB::table('users')->where('email', self::EMAIL)->get();

    $this->assertEquals(1, $user->count());
    $user = $user->first();
    $this->assertNull($user->verified);

    Mail::assertQueued(VerifyAccountMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
  }

  /**
   * tests registration with valid input but WITHOUT email
   *
   * asserts redirect, database and mail
   */
  public function testSuccessfulRegistrationWithoutEmail()
  {
    session()->start();
    Mail::fake();

    # make simple GET request so that back() works as expected
    $response = $this->call('GET', self::URI);
    $response->assertStatus(200);

    $response = $this->call('POST', self::URI, [
      '_token'    => csrf_token(),
      'username'  => self::USERNAME,
      // 'email'     => self::EMAIL,
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
      'keyboard'  => self::KEYBOARD,
      'checkbox'  => true,
    ]);

    $this->assertTrue(Auth::check());

    $response->assertStatus(302);
    $response->assertRedirect('/dashboard');

    $user = DB::table('users')->where('username', self::USERNAME)->get();

    $this->assertEquals(1, $user->count());
    $user = $user->first();
    $this->assertNotNull($user->verified);

    Mail::assertNotQueued(VerifyAccountMail::class);
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
      'keyboard'  => '',
      'checkbox'  => true,
    ], ['username' => 'errors.required', 'password' => 'errors.required', 'confirm' => 'errors.required', 'keyboard' => 'errors.required']);

    # test 'email' validation and 'username' alpha_dash
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => '###invalidUsername',
      'email'     => 'sdafdsafdsaf',
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
      'keyboard'  => self::KEYBOARD,
      'checkbox'  => true,
    ], ['email' => 'errors.email', 'username' => 'errors.alpha_dash']);

    # test 'unique' validation
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => 'testuser',
      'email'     => 'test@example.com',
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
      'keyboard'  => self::KEYBOARD,
      'checkbox'  => true,
    ], ['email' => 'errors.uniqueEmail', 'username' => 'errors.uniqueUsername']);

    # test 'weak password' validation
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => self::USERNAME,
      'email'     => self::EMAIL,
      'password'  => 'password',
      'confirm'   => 'password',
      'keyboard'  => self::KEYBOARD,
      'checkbox'  => true,
    ], ['password' => 'errors.weak_password']);

    # test 'password !== confirm' validation
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => self::USERNAME,
      'email'     => self::EMAIL,
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD . 'something',
      'keyboard'  => self::KEYBOARD,
      'checkbox'  => true,
    ], ['confirm' => 'errors.differs']);

    # test 'locale' validation
    $this->failedRegisterPostRequest([
      '_token'    => csrf_token(),
      'username'  => self::USERNAME,
      'email'     => self::EMAIL,
      'password'  => self::PASSWORD,
      'confirm'   => self::PASSWORD,
      'keyboard'  => 'invalidKeyboard',
      'checkbox'  => true,
    ], ['keyboard' => 'preferences.keyboardUnavailable']);
  }

  private function failedRegisterPostRequest($params, $errors) {

    $response = $this->call('POST', self::URI, $params);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI);
    $response->assertSessionHasErrors($errors);

    Mail::assertNotQueued(VerifyAccountMail::class);
  }
}
