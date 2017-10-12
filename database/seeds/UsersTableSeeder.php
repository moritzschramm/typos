<?php

use Illuminate\Database\Seeder;

use App\Models\User, App\Models\UserPreference;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      # create "normal" test user
      $user = factory(User::class)->states('testuser', 'verified')->make();
      $user->save();

      $preference = factory(UserPreference::class)->make();
      $preference->id_user = $user->id_user;
      $preference->save();

      # create user account without email
      $user = factory(User::class)->states('no-email-user', 'verified')->make();
      $user->save();

      $preference = factory(UserPreference::class)->make();
      $preference->id_user = $user->id_user;
      $preference->save();

      # create user account that hasn't been verified yet
      $user = factory(User::class)->states('unverified-user')->make();
      $user->save();

      $preference = factory(UserPreference::class)->make();
      $preference->id_user = $user->id_user;
      $preference->save();
    }
}
