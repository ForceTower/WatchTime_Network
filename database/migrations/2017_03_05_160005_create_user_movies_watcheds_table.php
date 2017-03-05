<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMoviesWatchedsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_movies_watcheds', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('movie_id')->unsigned();
			$table->foreign('movie_id')->references('id')->on('movies');
			$table->integer('user_id')->unsigned();
			$table->foreign('user_id')->references('id')->on('users');
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
		Schema::drop('user_movies_watcheds');
	}

}
