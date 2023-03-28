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
    /*$router->group(['middleware'=>'auth'],function () use ($router)
    {
        //ruta para cerrar sesion
        $router->post('/logout','AuthController@logout');
        //listado de todos los usuarios
        $router->get('/users', 'UserController@index');

        //rutas para las categorias
        $router->get('/', 'CategoryController@index');
        $router->get('/{id}', 'CategoryController@show');
        $router->post('/', 'CategoryController@store');
        $router->put('/{id}', 'CategoryController@update');
        $router->delete('/{id}', 'CategoryController@destroy');

        //rutas de los items
        $router->post('/{categoryId}/items','ItemController@store');
        $router->get('/{categoryId}/items','ItemController@index');
        $router->get('/{categoryId}/items/{itemId}','ItemController@show');
        $router->put('/{categoryId}/items/{itemId}','ItemController@update');
        $router->delete('/{categoryId}/items/{itemId}','ItemController@destroy');

    });*/

    //ruta para cerrar sesion
    $router->post('/logout','AuthController@logout');
    //listado de todos los usuarios
    $router->get('/users', 'UserController@index');

    //rutas para las categorias
    $router->get('/', 'CategoryController@index');
    $router->get('/{id}', 'CategoryController@show');
    $router->post('/', 'CategoryController@store');
    $router->put('/{id}', 'CategoryController@update');
    $router->delete('/{id}', 'CategoryController@destroy');

    //rutas de los items
    $router->post('/{categoryId}/items','ItemController@store');
    $router->get('/{categoryId}/items','ItemController@index');
    $router->get('/{categoryId}/items/{itemId}','ItemController@show');
    $router->put('/{categoryId}/items/{itemId}','ItemController@update');
    $router->delete('/{categoryId}/items/{itemId}','ItemController@destroy');




    //$router->post('/users', 'UserController@store');


});
