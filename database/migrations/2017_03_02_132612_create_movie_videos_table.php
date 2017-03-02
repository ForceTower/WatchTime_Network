<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovieVideosTable extends Migration
{

	/*
	 *  "key": "co9SNfJNDN8",
        "name": "MOVIECLIPS",
        "site": "YouTube",
        "size": 720,
        "type": "Trailer"
	 */

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('movie_videos', function(Blueprint $table) {
            $table->increments('id');
			$table->integer('movie_id')->unsigned();
			$table->foreign('movie_id')->references('id')->on('movies');
			$table->string('key');
			$table->string('type')->nullable();
			$table->string('name');
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
		Schema::drop('movie_videos');
	}

}
