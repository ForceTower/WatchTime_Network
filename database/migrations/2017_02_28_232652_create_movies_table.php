<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('movies', function(Blueprint $table) {
            $table->increments('id');
			$table->string('name');
			$table->string('overview', 4096)->nullable();
			$table->string('tag_line', 1024)->nullable();

			$table->string('imdb')->unique()->nullable();
			$table->integer('tmdb')->unique();

			$table->integer('runtime')->nullable();
			$table->string('status')->nullable();
			$table->date('release_date')->nullable();

			$table->bigInteger('budget')->nullable();
			$table->bigInteger('revenue')->nullable();

			$table->decimal('popularity')->default(0);
			$table->bigInteger('vote_count')->default(0);
			$table->decimal('vote_average')->default(0);

			$table->bigInteger('community_count')->default(0);
			$table->decimal('community_average')->default(0);

			$table->string('homepage', 512)->nullable();
			$table->string('poster_path')->nullable();
			$table->string('backdrop_path')->nullable();

			$table->boolean('activated')->default(1);
			$table->boolean('details_loaded')->default(0);

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
		Schema::drop('movies');
	}

}
