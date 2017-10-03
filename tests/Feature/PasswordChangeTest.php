<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use Auth;

class PasswordChangeTest extends TestCase
{
  use DatabaseTransactions;

  const URI_PREFERENCES   = '/preferences';
  const URI_PARAMS        = '?view=security';
  const URI_PASSWORD      = '/preferences/security/password';
  const CURRENT_PASSWORD  = 'testtest';
  const NEW_PASSWORD      = 'testtest2';

  /**
    * prepares session and testing env
    *
    * @return User (the user that is currently logged in)
    */
  protected function prepareSession()
  {
    # prepare session and mail
    session()->start();

    # log test user in
    $user = User::getTestUser();
    Auth::login($user);
    $this->assertTrue(Auth::check());

    # make GET request to preferences
    $response = $this->get(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertStatus(200);

    return $user;
  }

  protected function assertPasswordChanged($successfully, $oldHash)
  {
    # get updated user obj
    $user = User::getTestUser();

    if($successfully) {

      $this->assertNotEquals($user->password, $oldHash);

    } else {

      $this->assertEquals($user->password, $oldHash);
    }
  }

  /**
   * test changing password successfully
   *
   */
  public function testChangePasswordSuccessfully()
  {
    $user = $this->prepareSession();

    $oldHash = $user->password;

    $response = $this->call('POST', self::URI_PASSWORD, [
      '_token'          => csrf_token(),
      'currentPassword' => self::CURRENT_PASSWORD,
      'newPassword'     => self::NEW_PASSWORD,
      'confirm'         => self::NEW_PASSWORD,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertSessionHas(['notification-success']);

    $this->assertPasswordChanged(true, $oldHash);
  }

  public function testWrongPassword()
  {
    $user = $this->prepareSession();

    $oldHash = $user->password;

    $response = $this->call('POST', self::URI_PASSWORD, [
      '_token'          => csrf_token(),
      'currentPassword' => 'wrongPass',
      'newPassword'     => self::NEW_PASSWORD,
      'confirm'         => self::NEW_PASSWORD,
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertSessionHasErrors(['credentials']);

    $this->assertPasswordChanged(false, $oldHash);
  }

  public function testConfirmPasswordDiffers()
  {
    $user = $this->prepareSession();

    $oldHash = $user->password;

    $response = $this->call('POST', self::URI_PASSWORD, [
      '_token'          => csrf_token(),
      'currentPassword' => self::CURRENT_PASSWORD,
      'newPassword'     => self::NEW_PASSWORD,
      'confirm'         => self::NEW_PASSWORD . 'asdf',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertSessionHasErrors(['confirm']);

    $this->assertPasswordChanged(false, $oldHash);
  }

  public function testPasswordTooWeak()
  {
    $user = $this->prepareSession();

    $oldHash = $user->password;

    $response = $this->call('POST', self::URI_PASSWORD, [
      '_token'          => csrf_token(),
      'currentPassword' => self::CURRENT_PASSWORD,
      'newPassword'     => '12345678',
      'confirm'         => '12345678',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertSessionHasErrors(['password']);

    $this->assertPasswordChanged(false, $oldHash);
  }

  public function testNoInput()
  {
    $user = $this->prepareSession();

    $oldHash = $user->password;

    $response = $this->call('POST', self::URI_PASSWORD, [
      '_token'          => csrf_token(),
      'currentPassword' => '',
      'newPassword'     => '',
      'confirm'         => '',
    ]);

    $response->assertStatus(302);
    $response->assertRedirect(self::URI_PREFERENCES . self::URI_PARAMS);
    $response->assertSessionHasErrors(['currentPassword', 'newPassword', 'confirm']);

    $this->assertPasswordChanged(false, $oldHash);
  }
}
