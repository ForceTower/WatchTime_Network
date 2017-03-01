<?php

namespace WatchTime\Http\Controllers\API;

use Illuminate\Http\Request;

use WatchTime\Http\Requests;
use WatchTime\Http\Controllers\Controller;
use WatchTime\Repositories\MovieGenreRepository;
use WatchTime\Repositories\MovieRepository;

class MovieGuestController extends Controller {
    public static $todayPopular = [];
    private $movieRepository;
    private $movieGenreRepository;

    public function __construct(MovieRepository $movieRepository, MovieGenreRepository $movieGenreRepository) {
        $this->movieRepository = $movieRepository;
        $this->movieGenreRepository = $movieGenreRepository;
    }

    public function tester() {
        $response = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/popular?api_key='.Controller::$API_TMDB_KEY.'&language=en-US&page=1'), true);
        dd($response);
    }

    public function details($id) {
        $movie = $this->movieRepository->findWhere(['tmdb' => $id])->first();

        if (!$movie || $movie['details_loaded'] == 0) {
            $response = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/'.$id.'?api_key='.Controller::$API_TMDB_KEY.'&language=en-US'), true);
            return $this->decodeAndSaveMovieDetail($response);
        }

        return $movie;
    }

    public function listPopular($page){
        if (isset(MovieGuestController::$todayPopular[$page])) {
            return MovieGuestController::$todayPopular[$page];
        }

        $response = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/popular?api_key='.Controller::$API_TMDB_KEY.'&language=en-US&page='.$page), true);
        return $this->decodeAndSaveResponse($page, $response);
    }

    private function decodeAndSaveResponse($page, $response) {
        if (isset($response['results'])) {
            $results = $response['results'];
            $today = [];

            foreach ($results as $result) {
                if (!isset($result['id'])) {
                    continue;
                }

                $exists = $this->movieRepository->findWhere(['tmdb' => $result['id']])->first();
                if ($exists) {
                    array_push($today, $exists);
                    continue;
                }

                $name = $result['title'];
                $tmdb = $result['id'];

                $overview = '';
                if (isset($result['overview']))
                    $overview = $result['overview'];

                $release_date = null;
                if (isset($result['release_date']))
                    $release_date = $result['release_date'];

                $poster_path = null;
                if (isset($result['poster_path']))
                    $poster_path = $result['poster_path'];

                $backdrop_path = null;
                if (isset($result['backdrop_path']))
                    $backdrop_path = $result['backdrop_path'];

                $popularity = $result['popularity'];
                $vote_count = $result['vote_count'];
                $vote_average = $result['vote_average'];


                $movie_info = [
                    'tmdb' => $tmdb,
                    'name' => $name,
                    'overview' => $overview,
                    'release_date' => $release_date,
                    'poster_path' => $poster_path,
                    'backdrop_path' => $backdrop_path,
                    'popularity' => $popularity,
                    'vote_count' => $vote_count,
                    'vote_average' => $vote_average,
                ];

                $movie = $this->movieRepository->updateOrCreate($movie_info);
                array_push($today, $movie);

                $movie_genres = $result['genre_ids'];
                foreach ($movie_genres as $genre) {

                    $this->movieGenreRepository->create([
                        'movie_id' => $movie['id'],
                        'genre_id' => $genre,
                    ]);
                }
            }

            MovieGuestController::$todayPopular[$page] = $today;
            return $today;
        }

        return [];
    }

    private function decodeAndSaveMovieDetail($response) {

    }
}
