<?php

use App\Http\Controllers\CompanyOwnerController;
use Illuminate\Support\Facades\Route;

Route::get('/profile', [CompanyOwnerController::class, 'editProfile'])
    ->name('profile.edit');
Route::post('/profile/update/{companyId}', [CompanyOwnerController::class, 'updateProfile'])
    ->name('profile.update');