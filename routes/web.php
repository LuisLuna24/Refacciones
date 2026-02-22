<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/nosotros', function () {
    return view('nosotros');
})->name('nosotros');

Route::get('/refacciones', function () {
    //return view('Modules.Home.products');
    return view('Home.Refacciones.index');
})->name('refacciones.index');

Route::get('/contacto', function () {
    return view('contacto');
})->name('contacto');


Route::get('/lonas', function () {
    return view('Home.Lonas.index');
})->name('lonas');
