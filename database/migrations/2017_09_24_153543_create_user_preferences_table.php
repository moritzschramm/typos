<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->increments('id_user_preferences');
            $table->integer('id_user')->unsigned()->index();
            $table->string('keyboard');
            $table->integer('xp_goal')->unsigned();
            $table->boolean('show_assignment');
            $table->boolean('show_divider');
            $table->boolean('show_keyboard');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_preferences');
    }
}
