<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Traits\PasswordCheck;

class PasswordCheckTest extends TestCase
{
  use PasswordCheck;

  /**
   * tests the PasswordCheck trait
   *
   * asserts is_weak_password()
   */
  public function testPasswordCheck()
  {
    $weak_passwords = [
      'password', '1234567', '12345678', 'qwerty123',
    ];

    $not_weak_but_acceptable_passwords = [
      'somethingElse1234', 'whatever1'
    ];

    for($i = 0; $i < count($weak_passwords); $i++) {

      $this->assertTrue($this->is_weak_password($weak_passwords[$i]));
    }

    for($i = 0; $i < count($not_weak_but_acceptable_passwords); $i++) {

      $this->assertFalse($this->is_weak_password($not_weak_but_acceptable_passwords[$i]));
    }
  }
}
