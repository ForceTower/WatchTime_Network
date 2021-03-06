<?php
/**
 * Created by PhpStorm.
 * User: joaop
 * Date: 05/03/2017
 * Time: 13:01
 */

namespace WatchTime\Http\Controllers\API;


use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use WatchTime\Http\Controllers\Controller;
use WatchTime\Http\Requests\HasIndexTmdbRequest;
use WatchTime\Repositories\MovieRepository;
use WatchTime\Repositories\UserMoviesWatchedRepository;
use WatchTime\Repositories\UserMovieWatchListRepository;
use WatchTime\Repositories\UserRepository;

class UserProfileController extends Controller{
    private $userRepository;
    private $movieRepository;
    private $moviesWatchedRepository;
    private $moviesWatchlistRepository;

    public function __construct(UserRepository $userRepository, MovieRepository $movieRepository, UserMoviesWatchedRepository $moviesWatchedRepository, UserMovieWatchListRepository $moviesWatchlistRepository) {
        $this->userRepository = $userRepository;
        $this->movieRepository = $movieRepository;
        $this->moviesWatchedRepository = $moviesWatchedRepository;
        $this->moviesWatchlistRepository = $moviesWatchlistRepository;
    }

    public function markAsWatched(HasIndexTmdbRequest $request) {
        $userID = Authorizer::getResourceOwnerId();
        $user = $this->userRepository->find($userID);
        if (!$user)
            return ['error' => true, 'error_description' => 'User not logged', 'error_code' => -1];

        $tmdb = $request->all()['tmdb'];

        $movie = $this->movieRepository->findWhere(['tmdb' => $tmdb])->first();
        if (!$movie)
            return ['error' => true, 'error_description' => 'Movie does not exist', 'error_code' => 0];

        $on_watchlist = $this->moviesWatchlistRepository->skipPresenter(true)->findWhere(['user_id' => $userID, 'movie_id' => $movie['id']])->first();
        if($on_watchlist) {
            $on_watchlist->delete();
        }

        $existing = $this->moviesWatchedRepository->findWhere(['user_id' => $userID, 'movie_id' => $movie['id']])->first();

        if ($existing)
            return ['error' => true, 'error_description' => 'Movie Already Watched', 'error_code' => 1];

        $this->moviesWatchedRepository->create([
            'user_id' => $userID,
            'movie_id' => $movie['id'],
        ]);

        $time = 0;
        $movies = $this->moviesWatchedRepository->findWhere(['user_id' => $userID]);
        foreach($movies as $m) {
            $time += $m->movie->runtime;
        }

        //$time = $user['time_watched'] + $movie['runtime'];

        $user->time_watched = $time;
        $user->save();

        return ['success' => 'Movie marked as Watched', 'success_code' => 1];

    }

    public function addMovieToWatchlist(HasIndexTmdbRequest $request) {
        $userID = Authorizer::getResourceOwnerId();
        $user = $this->userRepository->find($userID);
        if (!$user)
            return ['error' => true, 'error_description' => 'User not logged', 'error_code' => -1];

        $tmdb = $request->all()['tmdb'];

        $movie = $this->movieRepository->findWhere(['tmdb' => $tmdb])->first();
        if (!$movie)
            return ['error' => true, 'error_description' => 'Movie does not exist', 'error_code' => 0];

        $existing = $this->moviesWatchlistRepository->skipPresenter(true)->findWhere(['user_id' => $userID, 'movie_id' => $movie['id']])->first();
        if ($existing)
            return ['error' => true, 'error_description' => 'Movie Already on Watchlist', 'error_code' => 1];

        $this->moviesWatchlistRepository->create([
            'user_id' => $userID,
            'movie_id' => $movie['id'],
        ]);

        return ['success' => 'Movie Added to Watchlist', 'success_code' => 1];
    }

    public function removeMovieFromWatchlist(HasIndexTmdbRequest $request) {
        $userID = Authorizer::getResourceOwnerId();
        $user = $this->userRepository->find($userID);
        if (!$user)
            return ['error' => true, 'error_description' => 'User not logged', 'error_code' => -1];

        $tmdb = $request->all()['tmdb'];

        $movie = $this->movieRepository->findWhere(['tmdb' => $tmdb])->first();
        if (!$movie)
            return ['error' => true, 'error_description' => 'Movie does not exist', 'error_code' => 0];

        $on_watchlist = $this->moviesWatchlistRepository->skipPresenter(true)->findWhere(['user_id' => $userID, 'movie_id' => $movie['id']])->first();
        if($on_watchlist) {
            $on_watchlist->delete();
            return ['success' => 'Item removed'];
        }

        return ['error' => true, 'error_code' => 1];
    }

    public function getWatchlist() {
        $userID = Authorizer::getResourceOwnerId();
        $user = $this->userRepository->find($userID);
        if (!$user)
            return ['error' => true, 'error_description' => 'User not logged', 'error_code' => -1];

        $watchlist = $this->moviesWatchlistRepository->skipPresenter(false)->findWhere(['user_id' => $userID]);
        return $watchlist;

    }

}