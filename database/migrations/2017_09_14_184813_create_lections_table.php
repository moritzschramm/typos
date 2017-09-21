<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lections', function (Blueprint $table) {
            $table->increments('id_lection');
            $table->integer('external_id')->unsigned();
            $table->string('title');
            $table->text('content');
            $table->integer('character_amount')->unsigned();
            $table->string('locale');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lections');
    }
}
