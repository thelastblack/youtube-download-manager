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

Route::get('/', [
  'as' => 'list',
  'uses' => 'YoutubeController@index'
]);

Route::post('/video', [
  'as' => 'download',
  'uses' => 'YoutubeController@download'
]);

Route::get('/video/{video}/delete', [
  'as' => 'delete',
  'uses' => 'YoutubeController@delete'
]);
