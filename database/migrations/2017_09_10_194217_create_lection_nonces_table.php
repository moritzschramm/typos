<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLectionNoncesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lection_nonces', function (Blueprint $table) {
            $table->increments('id_lection_nonce');
            $table->integer('id_user')->unsigned()->index();
            $table->string('nonce')->index();
            $table->integer('character_amount');
            $table->boolean('is_lection')->default(0);
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
        Schema::dropIfExists('lection_nonces');
    }
}
