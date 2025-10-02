<?php

use App\Http\Controllers\ContactController;
use App\Http\Middleware\Honeypot;
use Illuminate\Support\Facades\Route;

Route::post('/contact', [ContactController::class, 'storeApi'])
    ->name('contact')
    ->middleware([Honeypot::class, 'throttle:5,1']);
