<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Webhistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('webs_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('web_id');
            $table->integer('alexa_rank');
            $table->integer('alexa_globalrank');
            $table->float('alexa_reach');
            $table->float('alexa_pageviewsmillion');
            $table->float('alexa_pageviewsuser');
            $table->string('url');
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
        //
    }
}
