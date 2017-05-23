<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Webs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('webs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->float('current_value');
            $table->float('previous_value');
            $table->float('share_count');
            $table->integer('alexa_rank');
            $table->integer('alexa_globalrank');
            $table->float('alexa_reach');
            $table->float('alexa_pageviewsmillion');
            $table->float('alexa_pageviewsuser');
            $table->string('name');
            $table->text('description');
            $table->string('url');
            $table->boolean('visited')->default(0);
            $table->integer('links')->default(0);
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
        Schema::dropIfExists('webs');
    }
}
