<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLectionResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lection_results', function (Blueprint $table) {
            $table->increments('id_lection_result');
            $table->integer('id_user')->unsigned()->index();
            $table->integer('id_lection')->nullable();
            $table->integer('id_exercise')->nullable();
            $table->float('velocity', 8, 2);                  // 8 digits in total, 2 after decimal point
            $table->integer('keystrokes')->unsigned();
            $table->integer('errors')->unsigned();
            $table->integer('xp')->unsigned();
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
        Schema::dropIfExists('lection_results');
    }
}
