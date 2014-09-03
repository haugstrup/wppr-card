<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('uses' => 'HomeController@front', 'as' => 'front'));
Route::get('/search', array('uses' => 'CardController@search', 'as' => 'search'));
Route::get('/players', array('uses' => 'CardController@index', 'as' => 'all_cards'));
Route::get('/p/{id}/claim', array('uses' => 'CardController@claim', 'as' => 'claim'));
Route::get('/claim', array('uses' => 'CardController@claim', 'as' => 'claim_bare'));
Route::post('/claim', array('uses' => 'CardController@check_claim', 'as' => 'check_claim'));
Route::get('/p/{id}', array('uses' => 'CardController@show', 'as' => 'show_by_id'));
Route::get('/{label}', array('uses' => 'CardController@show', 'as' => 'show'));
