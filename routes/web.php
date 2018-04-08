<?php

Route::get('/', 'WelcomeController@index');

Route::get('/index', 'PointController@index');
Route::resource('index', 'PointController');

Route::get('/register', 'RegisterController@index');
Route::resource('register', 'RegisterController');

Route::get('/login', 'LoginController@index');
Route::resource('login', 'LoginController');
Route::get('/logout', 'LoginController@logout');

Route::get('/profile', 'ProfileController@index');

Route::get('/adminPage', 'AdminController@index');

Route::post('/adminSubmit/{id}', ['uses' =>'AdminController@submitPoint']);
Route::get('/adminSubmit/{id}', ['uses' =>'AdminController@submitPoint']);

Route::post('comment', 'CommentController@store');

Route::get('/events', 'EventController@index');
Route::get('/newEvent', 'EventController@newEvent');
Route::post('events', 'EventController@store');
Route::post('joinEvent', 'EventController@joinEvent');
Route::get('events/{id}', array('uses' => 'EventController@show'));
