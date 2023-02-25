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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/prueba',function() use ($router){
    return 'pudrete flanders';
});

$router->group(['prefix'=>'api'], function() use ($router){
    //ruta para iniciar sesion
    $router->post('/login','AuthController@login');
    //ruta para probar registrar nuevo usuario
    $router->post('/register','AuthController@register');

    //grupo de rutas para probar que funciona la autorizacion de passport
    $router->group(['middleware'=>'auth'],function () use ($router)
    {
        //ruta para cerrar sesion
        $router->post('/logout','AuthController@logout');
        //listado de todos los usuarios
        $router->get('/users', 'UserController@index');

        $router->get('/', 'CategoryController@index');
        $router->get('/{id}', 'CategoryController@show');
        $router->post('/', 'CategoryController@store');
        $router->put('/{id}', 'CategoryController@update');
        $router->delete('/{id}', 'CategoryController@destroy');

    });




    //$router->post('/users', 'UserController@store');
    //rutas para las categorias

    //rutas de los items
    $router->post('/{id}/items','ItemsController@store');
    $router->get('/{id}/items','ItemsController@index');
    $router->get('/{id}/items/{item_id}','ItemsController@show');
    $router->put('/{id}/items/{item_id}','ItemsController@update');
    $router->delete('/{id}/items/{item_id}','ItemsController@destroy');
});
