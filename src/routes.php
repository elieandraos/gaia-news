<?php

/*
|--------------------------------------------------------------------------
| News Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function()
{
   Route::get('/news', ['as' => 'admin.news.list', 'uses' => 'Gaia\News\NewsController@index']);
   Route::get('/news/create', ['as' => 'admin.news.create', 'uses' => 'Gaia\News\NewsController@create']);
   Route::get('/news/{id}/edit', ['as' => 'admin.news.edit', 'uses' => 'Gaia\News\NewsController@edit']);
   Route::post('/news/store', ['as' => 'admin.news.store', 'uses' => 'Gaia\News\NewsController@store']);
   Route::post('/news/{id}/update', ['as' => 'admin.news.update', 'uses' => 'Gaia\News\NewsController@update']);
   Route::post('/news/{id}/delete', ['as' => 'admin.news.delete', 'uses' => 'Gaia\News\NewsController@destroy']);
});
