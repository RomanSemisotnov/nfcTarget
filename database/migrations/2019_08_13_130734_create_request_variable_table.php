<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestVariableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_variable', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('correctrequest_id')->unsigned();
            $table->foreign('correctrequest_id')
                ->references('id')->on('correct_requests')
                ->onDelete('cascade');

            $table->integer('paramvariable_id')->unsigned();
            $table->foreign('paramvariable_id')
                ->references('id')->on('param_variables')
                ->onDelete('cascade');
        });
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
        Schema::dropIfExists('request_variable');
        Schema::dropIfExists('coords');
    }
}
