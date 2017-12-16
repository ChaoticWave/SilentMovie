<?php
//******************************************************************************
//* Web Routes
//******************************************************************************

use Illuminate\Support\Facades\Route;

Route::get('/',
    function() {
        return view('home');
    });

Route::post('/', ['uses' => 'ImdbController@peopleSearch']);

Route::get('/search',
    function() {
        return view('search');
    });

Route::get('/people/search',
    function() {
        return view('search');
    });

Route::get('/title/search',
    function() {
        return view('search');
    });

Route::post('/search', ['uses' => 'ImdbController@peopleSearch']);
Route::post('/people/search', ['uses' => 'ImdbController@peopleSearch']);
Route::post('/title/search', ['uses' => 'ImdbController@titleSearch']);

