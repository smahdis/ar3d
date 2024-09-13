<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
//    return view('welcome');
    return Redirect::to('/admin');
});

Route::get('logout', function () {
    Auth::logout();
    return redirect()->back();
});
