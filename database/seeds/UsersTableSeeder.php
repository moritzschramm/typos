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
      $user = factory(User::class)->states('testuser', 'verified')->make();
      $user->save();

      $preference = factory(UserPreference::class)->make();
      $preference->id_user = $user->id_user;
      $preference->save();
    }
}
