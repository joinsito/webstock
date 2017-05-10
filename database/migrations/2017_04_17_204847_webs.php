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
            $table->string('name');
            $table->text('description');
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
        Schema::dropIfExists('webs');
    }
}
