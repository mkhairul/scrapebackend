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

use Illuminate\Http\Request;


Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', function(){
    Auth::logout();
    return response()->json(['status' => 'ok']);
});
Route::get('auth/user', function(){
    return response()->json(Auth::user());
});

Route::post('logistic/create', 'LogisticController@createCourier');
Route::post('logistic/update', 'LogisticController@updateCourier');
Route::get('logistic', 'LogisticController@getAll');

Route::get('/', function () {
    return view('login');
});

Route::match(['GET','POST'], '/product/availability', function(Request $request){
    $article_id = ($request->input('id')) ? $request->input('id'):'30298792';
    //$contents = file_get_contents('http://www.ikea.com/my/en/iows/catalog/availability/' . $article_id);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,'http://www.ikea.com/my/en/iows/catalog/availability/' . $article_id);
    curl_setopt($ch, CURLOPT_FAILONERROR,1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    $contents = curl_exec($ch);
    curl_close($ch);

    $xml = simplexml_load_string($contents) or die("Error: Cannot create object");
    return response()->json($xml->availability->localStore);
});

Route::get('/categories', function(){
    $result = Category::get();
    return response()->json($result);
});

Route::get('/main', ['middleware' => 'auth', function () {
    return view('main');
}]);

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