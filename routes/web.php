<?php

Route::get('/', 'WelcomeController@index');

Route::get('/index', 'PointController@index');

Route::get('/register', 'RegisterController@index');

Route::resource('register', 'RegisterController');

Route::get('/login', 'LoginController@index');

Route::resource('login', 'LoginController');

Route::get('/logout', 'LoginController@logout');

Route::get('/profile', 'ProfileController@index');
