<?php
//******************************************************************************
//* Web Routes
//******************************************************************************

Route::get('/',
    function() {
        return view('home');
    });

Route::post('/', ['uses' => 'ImdbController@search']);
Route::get('/search',
    function() {
        return view('search');
    });

Route::post('/search', ['uses' => 'ImdbController@search']);
