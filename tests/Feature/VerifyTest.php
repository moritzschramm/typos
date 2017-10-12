<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use DB;
use App\Traits\CreateUserToken;

class VerifyTest extends TestCase
{
  use DatabaseTransactions, CreateUserToken;

  const URI = '/verify';

  /**
   * test verification process
   *
   * asserts database and redirect
   */
  public function testSuccessfulVerification()
  {
    # database preparations
    session()->start();

    $user = factory(User::class)->states('unverified')->make();
    $user->save();

    $token = $this->createUserToken($user);

    $this->assertNull($user->verified);

    # the actual request
    $response = $this->call('GET', self::URI . '/' . $user->uuid . '/' . $token);

    $response->assertStatus(302);
    $response->assertRedirect(LoginTest::URI);
    $response->assertSessionHas('notification-success');

    $this->assertNotNull(DB::table('users')->where('uuid', $user->uuid)->first()->verified);
  }
}
