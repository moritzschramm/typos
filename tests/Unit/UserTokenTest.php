<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\User;
use DB;

use App\Traits\CreateUserToken;
use App\Traits\ValidateUserToken;
use App\Traits\DeleteUserToken;

class UserTokenTest extends TestCase
{
  use CreateUserToken, ValidateUserToken, DeleteUserToken;
  use DatabaseTransactions;

  /**
   * test CreateUserToken, ValidateUserToken, DeleteUserToken traits
   *
   * asserts database
   */
  public function testTraits()
  {
    # CreateUserToken test
    $user = factory(User::class)->make();
    $user->save();

    $token = $this->createUserToken($user);

    $this->assertEquals($token, DB::table('users')->where('uuid', $user->uuid)->first()->token);

    # ValidateUserToken test
    $tmpUser = $this->validateUserToken($user->uuid, $token);

    $this->assertNotNull($tmpUser);
    $this->assertEquals($tmpUser->uuid, $user->uuid);

    # DeleteUserToken test
    $this->deleteUserToken($user);

    $this->assertNull(DB::table('users')->where('uuid', $user->uuid)->first()->token);
  }

}
