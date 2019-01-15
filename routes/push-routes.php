<?php

use Illuminate\Support\Facades\Route;

// Routes here
Route::post('/devices/register', '\\Newestapps\\Push\\Http\\Controllers\\DeviceController@registerDevice')->name('registerDevice');

// TODO = MobileRequest::class