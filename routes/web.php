<?php

use App\Enums\CarType;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ClientBookingController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Owner\OwnerProfileController;
use App\Http\Controllers\ProfileController;
use App\Models\Location;
use App\Models\RentalType;
use App\Models\Vehicle;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Landing', [
        'rentalTypes' => RentalType::select('id', 'label')->get(),
        'locations' => Location::select('id', 'name')->get(),
        'carTypes' => CarType::values(),
        'vehicles' => Vehicle::query()
            ->with(['locations', 'rentalTypes', 'fuelType', 'transmission'])
            // ->whereDoesntHave('bookings', function ($q) {
            //     $q->where('start_date', '<=', now())
            //         ->where('end_date', '>=', now());
            // })
            ->get(),
    ]);
})->name('landing');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', function () {
    return Inertia::render('Contact');
})->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/faq', function () {
    return Inertia::render('FAQ', [
        'faqCategories' => \App\Models\FaqCategory::with('faqs')->get(),
    ]);
})->name('faq');


Route::get('/car/{slug}', [CarController::class, 'show'])->name('car.show');
Route::get('/cars', [CarController::class, 'index'])->name('car.index');
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/car/{vehicle}/book', [ClientBookingController::class, 'book'])->name('car.book');
});
Route::get('/companies/{companyId}', [CompanyController::class, 'show'])->name('companies.show');

Route::get('/documents/view/{path}', [DocumentController::class, 'view'])
    ->where('path', '.*')
    ->middleware('auth')
    ->name('documents.view');

Route::get('/vehicle-documents/view/{path}', [DocumentController::class, 'viewDocumentsVehicles'])
    ->where('path', '.*')
    ->middleware('auth')
    ->name('vehicle-documents.view');

require __DIR__ . '/auth.php';
