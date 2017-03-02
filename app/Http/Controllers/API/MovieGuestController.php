<?php

namespace WatchTime\Http\Controllers\API;

use Illuminate\Http\Request;

use WatchTime\Http\Requests;
use WatchTime\Http\Controllers\Controller;
use WatchTime\Repositories\MovieCastRepository;
use WatchTime\Repositories\MovieCrewRepository;
use WatchTime\Repositories\MovieGenreRepository;
use WatchTime\Repositories\MovieRepository;
use WatchTime\Repositories\MovieVideoRepository;
use WatchTime\Repositories\PersonRepository;

class MovieGuestController extends Controller {
    public static $todayPopular = [];

    private $movieRepository;
    private $movieGenreRepository;
    private $movieVideoRepository;
    private $movieCastRepository;
    private $personRepository;
    private $movieCrewRepository;

    public function __construct(MovieRepository $movieRepository,
                                MovieGenreRepository $movieGenreRepository,
                                MovieVideoRepository $movieVideoRepository,
                                MovieCastRepository $movieCastRepository,
                                PersonRepository $personRepository,
                                MovieCrewRepository $movieCrewRepository) {
        $this->movieRepository = $movieRepository;
        $this->movieGenreRepository = $movieGenreRepository;
        $this->movieVideoRepository = $movieVideoRepository;
        $this->movieCastRepository = $movieCastRepository;
        $this->personRepository = $personRepository;
        $this->movieCrewRepository = $movieCrewRepository;
    }

    public function details($id) {
        if (!is_numeric($id)) {
            return [
                'error' => '400',
                'error_description' => 'Movie ID must be numeric, but ' . $id . ' was received',
            ];
        }

        $movie = $this->movieRepository->with('genres')->findWhere(['tmdb' => $id])->first();

        if (!$movie || $movie['details_loaded'] == 0) {
            $response = json_decode(file_get_contents('https://api.themoviedb.org/3/movie/'.$id.'?api_key='.Controller::$API_TMDB_KEY.'&language=en-US&append_to_response=videos,credits'), true);
            $this->decodeAndSaveMovieDetail($response);
        }
        $movie = $this->movieRepository->skipPresenter(false)->with('genres')->findWhere(['tmdb' => $id]);
        return $movie;
    }

    public function listRelease($page) {

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

    private function decodeAndSaveMovieDetail($result) {
        $name = $result['title'];
        $tmdb = $result['id'];

        $already_added = $this->movieRepository->findWhere(['tmdb' => $tmdb])->first();

        $tag_line = null;
        if (isset($result['tagline']))
            $tag_line = $result['tagline'];

        $overview = '';
        if (isset($result['overview']))
            $overview = $result['overview'];

        $imdb = null;
        if (isset($result['imdb_id']))
            $imdb = $result['imdb_id'];

        $homepage = null;
        if (isset($result['homepage']))
            $homepage = $result['homepage'];

        $runtime = null;
        if (isset($result['runtime']))
            $runtime = $result['runtime'];

        $status = null;
        if(isset($result['status']))
            $status = $result['status'];

        $release_date = null;
        if (isset($result['release_date']))
            $release_date = $result['release_date'];

        $poster_path = null;
        if (isset($result['poster_path']))
            $poster_path = $result['poster_path'];

        $backdrop_path = null;
        if (isset($result['backdrop_path']))
            $backdrop_path = $result['backdrop_path'];

        $revenue = null;
        if (isset($result['revenue']))
            $revenue = $result['revenue'];

        $budget = null;
        if (isset($result['budget']))
            $budget = $result['budget'];

        $popularity = $result['popularity'];
        $vote_count = $result['vote_count'];
        $vote_average = $result['vote_average'];


        $movie_info = [
            'name' => $name,
            'overview' => $overview,
            'tag_line' => $tag_line,

            'imdb' => $imdb,
            'tmdb' => $tmdb,

            'runtime' => $runtime,
            'status' => $status,
            'release_date' => $release_date,

            'budget' => $budget,
            'revenue' => $revenue,

            'popularity' => $popularity,
            'vote_count' => $vote_count,
            'vote_average' => $vote_average,

            'homepage' => $homepage,
            'poster_path' => $poster_path,
            'backdrop_path' => $backdrop_path,
            'details_loaded' => 1,
        ];

        $movie = null;
        if ($already_added)
            $movie = $this->movieRepository->update($movie_info, $already_added['id']);
        else
            $movie = $this->movieRepository->create($movie_info);

        $movie_genres = $result['genres'];
        if ($movie_genres) {
            foreach($movie_genres as $genre) {
                $genre_id = $genre['id'];

                $exists = $this->movieGenreRepository->findWhere(['movie_id' => $movie['id'], 'genre_id' => $genre_id])->first();
                if (!$exists) {
                    $this->movieGenreRepository->updateOrCreate([
                        'movie_id' => $movie['id'],
                        'genre_id' => $genre_id,
                    ]);
                }
            }
        }


        $movie_videos = $result['videos'];
        if ($movie_videos) {
            $movie_videos = $movie_videos['results'];
            foreach($movie_videos as $video) {
                $name = $video['name'];
                $key = $video['key'];
                $site = $video['site'];
                $type = $video['type'];

                if ($site === 'YouTube') {
                    $this->movieVideoRepository->create([
                        'movie_id' => $movie['id'],
                        'name' => $name,
                        'key' => $key,
                        'type' => $type,
                    ]);
                }
            }
        }

        $movie_credits = $result['credits'];

        $movie_cast = $movie_credits['cast'];
        foreach ($movie_cast as $cast) {
            $name = $cast['name'];
            $tmdb = $cast['id'];
            $profile = $cast['profile_path'];
            $char = $cast['character'];

            if (strpos($char, '(uncredited)') !== false)
                continue;

            $person = $this->personRepository->findWhere(['tmdb' => $tmdb])->first();
            if (!$person) {
                $person = $this->personRepository->create([
                        'name' => $name,
                        'tmdb' => $tmdb,
                        'profile_path' => $profile,
                    ]
                );
            }

            $this->movieCastRepository->create([
                'movie_id' => $movie['id'],
                'person_id' => $person['id'],
                'character' => $char,
            ]);
        }

        $movie_crew = $movie_credits['crew'];

        foreach($movie_crew as $crew) {
            $name = $crew['name'];
            $tmdb = $crew['id'];
            $profile = $crew['profile_path'];
            $job = $crew['job'];

            if ($job === "Director" || $job === "Screenplay") {
                $person = $this->personRepository->findWhere(['tmdb' => $tmdb])->first();
                if (!$person) {
                    $person = $this->personRepository->create([
                            'name' => $name,
                            'tmdb' => $tmdb,
                            'profile_path' => $profile,
                        ]
                    );
                }

                $this->movieCrewRepository->create([
                    'movie_id' => $movie['id'],
                    'person_id' => $person['id'],
                    'job' => $job,
                ]);
            }
        }

        return $movie;
    }
}
