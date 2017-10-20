<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use App\Mail\VerifyAccountMail;
use Mail, Auth, DB;

class EmailChangeTest extends TestCase
{
  use DatabaseTransactions;

  const URI_PREFERENCES = '/preferences';
  const URI_PARAMS      = '?view=account';
  const URI_EMAIL       = '/preferences/account/email';
  const NEW_EMAIL       = 'newEmail@example.com';
  const PASSWORD        = 'testtest';

  /**
    * prepares session and testing env
    *
    * @return User (the user that is currently logged in)
    */
  protected function prepareSession()
  {
    # prepare session and mail
    session()->start();
    Mail::fake();

    # log test user in
    $user = User::getTestUser();
    Auth::login($user);
    $this->assertTrue(Auth::check());

    # make GET request to preferences
    $response = $this->get(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertStatus(200);

    return $user;
  }

  /**
    * assert wheter the email was changed or not
    * asserts database, auth, mail
    *
    * @param boolean $successfully: if the function should assume that the email was changed successfully
    */
  protected function assertEmailChanged($successfully)
  {
    # get updated user obj
    $user = User::getTestUser();

    if($successfully) {

      # assert user got logged out
      $this->assertFalse(Auth::check());

      # assert email has been changed and account is deverified
      $this->assertEquals($user->email, self::NEW_EMAIL);
      $this->assertNull($user->verified);

      # assert mail has been sent (+ sent to correct address)
      Mail::assertQueued(VerifyAccountMail::class, function ($mail) use ($user) {
          return $mail->hasTo($user->email);
      });

    } else {

      # assert user didn't got logged out
      $this->assertTrue(Auth::check());

      # assert email has NOT been changed and account is deverified
      $this->assertNotEquals($user->email, self::NEW_EMAIL);
      $this->assertNotNull($user->verified);

      # assert mail has NOT been sent (+ sent to correct address)
      Mail::assertNotQueued(VerifyAccountMail::class);
    }
  }

  /**
    * tests change of email uses constant credentials (@see top)
    *
    * asserts response and email changed
    */
  public function testChangeEmailSuccessfully()
  {
    $this->prepareSession();

    # make POST request to change email
    $response = $this->call('POST', self::URI_EMAIL, [
      '_token'    => csrf_token(),
      'email'     => self::NEW_EMAIL,
      'password'  => self::PASSWORD,
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect('/');
    $response->assertSessionHas(['notification-success']);

    # assert database, mail
    $this->assertEmailChanged(true);
  }

  /**
    * tests change of email uses constant credentials (@see top)
    *
    * asserts response and email changed
    */
  public function testChangeEmailNoEmailBefore()
  {
    $user = $this->prepareSession();

    # remove email from testuser
    $user->email = NULL;
    $user->update();

    # make POST request to change email
    $response = $this->call('POST', self::URI_EMAIL, [
      '_token'    => csrf_token(),
      'email'     => self::NEW_EMAIL,
      'password'  => self::PASSWORD,
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect('/');
    $response->assertSessionHas(['notification-success']);

    # assert database, mail
    $this->assertEmailChanged(true);
  }


  /**
    * tests change of email using a wrong password
    *
    * asserts response and email changed
    */
  public function testWrongPassword()
  {
    $this->prepareSession();

    # make POST request to change email
    $response = $this->call('POST', self::URI_EMAIL, [
      '_token'    => csrf_token(),
      'email'     => self::NEW_EMAIL,
      'password'  => 'wrongPass',
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertSessionHasErrors(['password'], null, 'email');

    $this->assertEmailChanged(false);
  }

  /**
    * tests change of email using invalid email address (but correct password)
    *
    * asserts response and email changed
    */
  public function testInvalidEmail()
  {
    $this->prepareSession();

    # make POST request to change email
    $response = $this->call('POST', self::URI_EMAIL, [
      '_token'    => csrf_token(),
      'email'     => 'someInvalidEmail',
      'password'  => self::PASSWORD,
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertSessionHasErrors(['email'], null, 'email');

    $this->assertEmailChanged(false);
  }

  /**
    * tests change of email using no input
    *
    * asserts response and email changed
    */
  public function testNoInput()
  {
    $this->prepareSession();

    # make POST request to change email
    $response = $this->call('POST', self::URI_EMAIL, [
      '_token'    => csrf_token(),
      'email'     => '',
      'password'  => '',
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertSessionHasErrors(['email', 'password'], null, 'email');

    $this->assertEmailChanged(false);
  }
}
