<?php

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

/**
 * @var \Laravel\Lumen\Routing\Router $router
 */
//個人資料編輯
$router->group(['namespace'=>'Account','prefix' => 'api/v1/user','middleware' => ['auth','role:normal,normal']], function () use ($router) {
    $router->get('/','UserController@userProfile');
    $router->patch('/edit','UserController@editUserProfile');
    $router->post('phone-bind','UserController@phoneBind');
    $router->post('account-bind','UserController@accountBind');
});

