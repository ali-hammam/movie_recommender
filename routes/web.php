<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieUserController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\RatingsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/addMoviesToDb', [TestController::class, 'moveMoviesToDb']);
Route::get('/addUsersToDb', [TestController::class, 'moveUsersToDb']);
Route::get('/addOccupationToDb', [TestController::class, 'moveOccupationsToDb']);
Route::get('/addMovieClustersToDb', [TestController::class, 'moveMovieClustersToDb']);
Route::get('/addUserClustersToDb', [TestController::class, 'moveUserClustersToDb']);
Route::get('/addImagesToMovies', [TestController::class, 'addImagesToMovies']);

Route::get('/topuserrating', [RatingsController::class, 'Top10UserRatings']);
Route::Post('/addRating', [RatingsController::class, 'addRating']);
Route::put('/updateRating', [RatingsController::class, 'updateRating']);
Route::delete('/removeRating', [RatingsController::class, 'removeRating']);
Route::get('/movies', [RatingsController::class, 'getAllMoviesWithRatings']);

Route::get('/users', [MovieUserController::class, 'getCurrentListOfUsers']);
Route::get('/list_of_genres', [MovieController::class, 'getListOfGenres']);
Route::get('/movies_by_genre', [MovieController::class, 'getMoviesByGenre']);
