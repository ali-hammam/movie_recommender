<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MovieUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movie_users', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unique();
            $table->string('gender');
            $table->integer('age');
            $table->integer('occupation_id');
            $table->foreign('occupation_id')->references('occupation_id')->on('occupations');
            $table->string('zip');
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
