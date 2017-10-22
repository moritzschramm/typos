<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrialResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trial_results', function (Blueprint $table) {
            $table->increments('id_trial_result');
            $table->string('nickname')->nullable();
            $table->float('velocity', 8, 2);                  // 8 digits in total, 2 after decimal point
            $table->integer('keystrokes')->unsigned();
            $table->integer('errors')->unsigned();
            $table->integer('score')->unsigned();
            $table->boolean('is_public')->default(0);
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
        Schema::dropIfExists('trial_results');
    }
}
