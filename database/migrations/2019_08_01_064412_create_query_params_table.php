<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueryParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('query_params', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('type')->default('param');
            $table->integer('client_id')->unsigned();
            $table->foreign('client_id')
                ->references('id')->on('clients')
                ->onDelete('cascade');
            $table->integer('index_number')->unsigned();
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
        Schema::dropIfExists('query_params');
    }
}
