<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParamVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('param_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('QueryParam_id')->unsigned();
            $table->foreign('QueryParam_id')
                ->references('id')->on('query_params')
                ->onDelete('cascade');
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
        Schema::dropIfExists('param_variables');
    }
}
