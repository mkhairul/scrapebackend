<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use App\Product;
use App\Package;
use App\Category;
use App\User;

use Illuminate\Http\Request;

Route::get('/', function () { return view('login'); });
Route::get('/main', ['middleware' => 'auth', function () { return view('main'); }]);

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', function(){
    Auth::logout();
    return response()->json(['status' => 'ok']);
});
Route::get('auth/user', function(){
    return response()->json(Auth::user());
});

Route::get('users', 'UserController@getAll');
Route::post('user/create', 'UserController@create');
Route::post('user/update', 'UserController@update');
Route::post('user/remove', ['middleware' => 'auth', 'uses' => 'UserController@remove']);

Route::post('logistic/create', 'LogisticController@createCourier');
Route::post('logistic/update', 'LogisticController@updateCourier');
Route::get('logistic', 'LogisticController@getAll');
Route::post('logistic/remove', ['middleware' => 'auth', 'uses' => 'LogisticController@remove']);
Route::get('logistic/countries', 'LogisticController@countries');

Route::post('/product/availability', 'ProductController@availability');
Route::get('/categories', 'ProductController@productCategories'); 
Route::post('/product', 'ProductController@search');
Route::get('/product/{keyword}', 'ProductController@searchGet');