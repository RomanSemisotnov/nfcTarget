<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUidsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uids', function (Blueprint $table) {
            $table->increments('id');
            $table->string('value')->unique();

            $table->integer('record_id')->unsigned();
            $table->foreign('record_id')
                ->references('id')->on('records')
                ->onDelete('cascade');

            $table->integer('patternlink_id')->unsigned();
            $table->foreign('patternlink_id')
                ->references('id')->on('pattern_links')
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
        Schema::dropIfExists('uids');
    }
}
