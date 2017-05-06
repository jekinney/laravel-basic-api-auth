<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'v1'], function() {
	Route::get('/user', 'Users\AuthController@user');
	Route::post('/login', 'Users\AuthController@login');
	Route::post('/register', 'Users\AuthController@register');
});
