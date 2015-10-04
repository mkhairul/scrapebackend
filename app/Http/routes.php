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

use Request;

Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('/', function () {
    return view('login');
});

Route::get('/main', function () {
    return view('main');
});

Route::post('/product', function(Request $request){
  $keyword = $request->input('keyword');

  $result = Product::with('packages')->where('article_id', 'LIKE', $keyword)->orWhere('name', 'LIKE', $keyword)->get();
  if(!$result){
    return response()->json(['status' => 'error', 'message' => 'not found']);
  }
  
  return response()->json($result);
});

Route::get('/product/{keyword}', function($keyword){

  $result = Product::with('packages')->where('article_id', 'LIKE', $keyword)->orWhere('name', 'LIKE', $keyword)->get();
  if(!$result){
    return response()->json(['status' => 'error', 'message' => 'not found']);
  }
  
  return response()->json($result);
});