<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class coordsMigration extends Migration
{

    public function up()
    {
        Schema::create('coords', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first');
            $table->string('second');
            $table->string('ip');

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
        Schema::dropIfExists('coords');
    }

}