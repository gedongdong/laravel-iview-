<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('api')->get('/store', 'CategoryController@store');

Route::middleware('api')->post('/get_token', 'AuthController@getToken');
Route::middleware('api')->post('/get_user', 'UserController@getUser');
Route::middleware('api')->post('/token_check', 'TokenController@tokenCheck');
Route::middleware('api')->post('/delete_token', 'TokenController@deleteToken');

Route::middleware('api')->get('category', 'CategoryController@index');
Route::middleware('api')->get('category_info', 'CategoryController@getCategoryByID');
Route::middleware('api')->post('category', 'CategoryController@store');
// Route::middleware('api')->delete('category', 'CategoryController@delete');

Route::middleware('api')->get('article', 'ArticleController@index');
Route::middleware('api')->get('article_info', 'ArticleController@getArticleByID');
Route::middleware('api')->post('article', 'ArticleController@store');

Route::middleware('api')->post('upload', 'UploadController@upload');
