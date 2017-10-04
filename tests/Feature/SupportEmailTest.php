<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Mail\SupportMail;
use Mail, Auth;

class SupportEmailTest extends TestCase
{
  const URI_SUPPORT = '/support';
  const EMAIL = 'test@example.com';
  const MESSAGE = 'This is a test message';

  protected function prepareSession($withAuth)
  {
    session()->start();
    Mail::fake();

    $user = User::getTestUser();

    if($withAuth) {

      Auth::login($user);
      $this->assertTrue(Auth::check());
    }

    $response = $this->get(self::URI_SUPPORT);
    $response->assertStatus(200);

    return $user;
  }

  /**
   * test if support mail is sent when user is logged OUT
   *
   * assert response and mail
   */
  public function testSendSupportEmailWithoutAuth()
  {
    $user = $this->prepareSession(false);

    $response = $this->call('POST', self::URI_SUPPORT, [
      '_token'  => csrf_token(),
      'email'   => self::EMAIL,
      'message' => self::MESSAGE,
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect(self::URI_SUPPORT);
    $response->assertSessionHas('notification-success');

    # assert mail was sent
    Mail::assertSent(SupportMail::class, function ($mail) {
        return $mail->hasTo(config('mail.from.support'));
    });
  }

  /**
   * test if support mail is sent when user is logged IN
   *
   * assert response and mail
   */
  public function testSendSupportEmailWithAuth()
  {
    $user = $this->prepareSession(true);

    $response = $this->call('POST', self::URI_SUPPORT, [
      '_token'  => csrf_token(),
      'email'   => self::EMAIL,
      'message' => self::MESSAGE,
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect(self::URI_SUPPORT);
    $response->assertSessionHas('notification-success');

    # assert mail was sent
    Mail::assertSent(SupportMail::class, function ($mail) {
        return $mail->hasTo(config('mail.from.support'));
    });
  }

  /**
   * test if support mail is sent when user specifies invalid email
   *
   * assert response and mail
   */
  public function testSendSupportEmailInvalidEmail()
  {
    $user = $this->prepareSession(true);

    $response = $this->call('POST', self::URI_SUPPORT, [
      '_token'  => csrf_token(),
      'email'   => 'invalidEmail',
      'message' => self::MESSAGE,
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect(self::URI_SUPPORT);
    $response->assertSessionHasErrors(['email']);

    # assert mail was sent
    Mail::assertNotSent(SupportMail::class);
  }

  /**
   * test if support mail is sent when user enters no input
   *
   * assert response and mail
   */
  public function testSendSupportEmailNoInput()
  {
    $user = $this->prepareSession(true);

    $response = $this->call('POST', self::URI_SUPPORT, [
      '_token'  => csrf_token(),
      'email'   => '',
      'message' => '',
    ]);

    # assert response
    $response->assertStatus(302);
    $response->assertRedirect(self::URI_SUPPORT);
    $response->assertSessionHasErrors(['email', 'message']);

    # assert mail was sent
    Mail::assertNotSent(SupportMail::class);
  }
}
