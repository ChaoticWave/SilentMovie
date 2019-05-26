<?php
//******************************************************************************
//* Web Routes
//******************************************************************************

use Illuminate\Support\Facades\Route;

Route::get('/', ['uses' => 'SiteController@index']);
Route::get('/search', ['uses' => 'SiteController@search']);
Route::get('/people/search', ['uses' => 'SiteController@search']);
Route::get('/title/search', ['uses' => 'SiteController@search']);

Route::post('/', ['uses' => 'ImdbController@peopleSearch']);
Route::post('/search', ['uses' => 'ImdbController@peopleSearch']);
Route::post('/people/search', ['uses' => 'ImdbController@peopleSearch']);
Route::post('/title/search', ['uses' => 'ImdbController@titleSearch']);

