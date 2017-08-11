<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use Auth;

class LogoutTest extends TestCase
{
  const URI   = '/logout';
  const EMAIL = 'test@example.com';

  /**
   * test logout
   *
   * asserts redirect and Auth::check()
   */
  public function testLogout()
  {
    session()->start();

    $user = User::where('email', self::EMAIL)->first();

    Auth::login($user);

    $this->assertTrue(Auth::check());

    $response = $this->call('GET', self::URI);

    $response->assertStatus(302);
    $response->assertRedirect('/');

    $this->assertFalse(Auth::check());
   }
}
