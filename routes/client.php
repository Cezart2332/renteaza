<?php

use App\Http\Controllers\Client\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard', [ClientController::class, 'dashboard'])
    ->name('dashboard');
