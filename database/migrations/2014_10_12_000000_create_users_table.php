<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->bigInteger('facebook_id')->unique()->unsigned()->nullable();
            $table->bigInteger('google_id', 30)->unique()->unsigned()->nullable();
            $table->string('firebase_token', 300)->nullable();
            $table->string('password', 60);
            $table->binary('avatar')->nullable();
            $table->integer('cover_picture')->unsigned();
            $table->foreign('cover_picture')->references('id')->on('movie_images');
            $table->boolean('activated')->default(1);
            $table->integer('user_level')->default(0);
            $table->bigInteger('time_watched')->unsigned();
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
