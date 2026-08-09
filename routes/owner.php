<?php


use App\Http\Controllers\Owner\OwnerProfileController;
use App\Http\Controllers\Owner\OwnerReviewController;
use App\Http\Controllers\Owner\OwnerCarController;
use Illuminate\Support\Facades\Route;

Route::get('/cars', [OwnerCarController::class, 'index'])
    ->name('cars.index');

Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [OwnerProfileController::class, 'show'])->name('show');
    Route::get('/edit', [OwnerProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [OwnerProfileController::class, 'updateProfile'])->name('update');
    Route::get('/edit/documents', [OwnerProfileController::class, 'editDocuments'])->name('edit-documents');
    Route::post('/documents', [OwnerProfileController::class, 'uploadDocuments'])->name('upload-documents');
});

Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/', [OwnerReviewController::class, 'index'])->name('index');
});

