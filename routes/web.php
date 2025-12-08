<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('try');
})->name('try');

Route::get('/one', function () {
    return view('try2');
})->name('try1');
