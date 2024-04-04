<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return Inertia::render('Home', [
        'user' => auth()->user()
    ]);
});


require __DIR__ . '/auth.php';
