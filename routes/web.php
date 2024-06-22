<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// Route to display the application version
$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Route to handle user login
$router->post('/login', 'AuthController@login');

// Route to handle gateway requests for movies, music, and books
$router->post('/gateway', 'GatewayController@handleRequest');

// Route to add a favorite item (requires JWT authentication)
$router->post('addtofavorites', ['middleware' => 'jwt.auth', 'uses' => 'FavoriteController@addToFavorites']);

// Route to remove a favorite item
$router->delete('/removefavorite', 'FavoriteController@removeFavorite');

// Route to get all favorite items
$router->get('/favorites', 'FavoriteController@getAllFavorites');

// Route to mark an action as done
$router->post('/mark-as-done', 'DoneController@markAsDone');

// Route to get all titles marked as done
$router->get('/done-titles', 'DoneController@getDoneTitles');
