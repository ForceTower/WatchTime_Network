<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieCrewsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('movie_crews', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('movie_id')->unsigned();
			$table->foreign('movie_id')->references('id')->on('movies');
			$table->integer('person_id')->unsigned();
			$table->foreign('person_id')->references('id')->on('people');
			$table->string('job');
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
		Schema::drop('movie_crews');
	}

}
