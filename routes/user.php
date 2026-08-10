<?php

use App\Http\Controllers\ClientBookingController;
use App\Http\Controllers\ClientReviewController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\Owner\OwnerBookingController;
use App\Http\Controllers\Owner\OwnerCarController;
use App\Http\Controllers\Owner\OwnerController;
use App\Http\Controllers\Owner\OwnerProfileController;
use App\Http\Controllers\Owner\OwnerReviewController;
use App\Http\Controllers\OwnerBankAccountController;
use App\Http\Controllers\OwnerCalendarBookingsController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StripeConnectController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Owner & Client Routes (același fișier — amândoi sunt "users")
|--------------------------------------------------------------------------
| Structură:
| 1) Owner Dashboard
| 2) Owner Cars (CRUD + documente)
| 3) Owner Profile (vizualizare + update + documente)
| 4) Owner Reviews (listă)
| 5) Owner Bookings (listă, upcoming, check-in, approve/reject)
| 6) Client Bookings (listă)
| 7) Client Reviews (listă + create/store)
| 8) Dashboard role (switch Client/Proprietar, doar pentru UI)
| 9) Calendar (owner)
| 10) Company (setări companie)
|
| NOTE:
| - Adaugă middleware-urile necesare (auth/verified/role) pe secțiuni.
| - Ține naming-ul consistent: prefix + name() pe group-uri.
*/

/* --------------------------------------------------------------------------
 * 1) OWNER DASHBOARD
 * -------------------------------------------------------------------------- */

Route::get('/dashboard', [OwnerController::class, 'dashboard'])
    ->name('dashboard'); // ex. owner.dashboard dacă vrei mai explicit

/* --------------------------------------------------------------------------
 * 2) OWNER CARS (management flota)
 * -------------------------------------------------------------------------- */
Route::prefix('cars')->name('cars.')->group(function () {
    Route::get('/', [OwnerCarController::class, 'index'])->name('index');
    Route::get('/create', [OwnerCarController::class, 'create'])->name('create');
    Route::post('/', [OwnerCarController::class, 'store'])->name('store');
    Route::get('/{carSlug}/edit', [OwnerCarController::class, 'edit'])->name('edit');
    Route::put('/{carSlug}', [OwnerCarController::class, 'update'])->name('update');
    Route::delete('/{car}', [OwnerCarController::class, 'destroy'])->name('destroy');

    // Documente mașină (RCA, ITP, etc.)
    Route::get('/documents/{carSlug}', [OwnerCarController::class, 'documents'])->name('documents');
    Route::post('/upload-documents', [OwnerCarController::class, 'uploadDocuments'])->name('upload-documents');
});

/* --------------------------------------------------------------------------
 * 3) OWNER PROFILE (profil proprietar + documente proprietar)
 * -------------------------------------------------------------------------- */
Route::prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [OwnerProfileController::class, 'show'])->name('show');
    Route::get('/edit', [OwnerProfileController::class, 'edit'])->name('edit');
    Route::put('/update', [OwnerProfileController::class, 'updateProfile'])->name('update');

    // Documente proprietar (permis, etc.)
    Route::get('/edit/documents', [OwnerProfileController::class, 'editDocuments'])->name('edit-documents');
    Route::post('/documents', [OwnerProfileController::class, 'uploadDocuments'])->name('upload-documents');
});

/* --------------------------------------------------------------------------
 * 4) OWNER REVIEWS (recenzii primite de proprietar)
 * -------------------------------------------------------------------------- */
Route::prefix('reviews')->name('reviews.')->group(function () {
    Route::get('/', [OwnerReviewController::class, 'index'])->name('index');
});

/* --------------------------------------------------------------------------
 * 5) OWNER BOOKINGS (gestionare rezervări pentru proprietar)
 * -------------------------------------------------------------------------- */
Route::prefix('bookings')->name('bookings.')->group(function () {
    // Listare generală
    Route::get('/', [OwnerBookingController::class, 'index'])->name('index');

    // Rezervări viitoare (upcoming)
    Route::get('/upcoming', [OwnerBookingController::class, 'upcomingBookings'])->name('upcomingBooking');

    // Fereastra Check-In (UI) pentru o rezervare
    Route::get('/upcoming/{bookingId}/checkin', [OwnerBookingController::class, 'checkIn'])->name('checkin');

    // Upload poze Check-In (CORECTAT path-ul: fără /bookings/ în față, fiind deja în grupul /bookings)
    Route::post('/{booking}/check-in/photos', [OwnerBookingController::class, 'storeCheckInPhotos'])
        ->name('checkin.store');

    // Fereastra Checkout (UI) pentru o rezervare
    Route::get('{booking}/checkout', [OwnerBookingController::class, 'checkOut'])->name('checkout');

    // Upload poze Check-Out
    Route::post('{booking}/checkout/photos', [OwnerBookingController::class, 'storeCheckOutPhotos'])
        ->name('checkout.store');

    // Confirmă & pornește check-inul (approve)
    Route::post('{bookingId}/approve', [OwnerBookingController::class, 'approve'])->name('approve');

    // Respinge rezervarea (înainte de check-in)
    Route::post('{bookingId}/reject', [OwnerBookingController::class, 'reject'])->name('reject');

    Route::get('/{booking}/contract', [ContractController::class, 'show'])
        ->middleware(['can:viewContract,booking'])
        ->name('contract.show');

    Route::post('/{booking}/contract/sign', [ContractController::class, 'sign'])
        ->middleware(['can:signContract,booking'])
        ->name('contract.sign');

    Route::get('/{booking}/payment', [PaymentController::class, 'show'])
        ->name('payment.show');

    Route::get('/{booking}/payment/success', [PaymentController::class, 'success'])
        ->name('payment.success');
});

/* --------------------------------------------------------------------------
 * 6) CLIENT BOOKINGS (rezervările clientului curent)
 * -------------------------------------------------------------------------- */
Route::prefix('client_bookings')->name('client_bookings.')->group(function () {
    Route::get('/', [ClientBookingController::class, 'index'])->name('index');
    Route::get('/{booking}', [ClientBookingController::class, 'show'])->name('show');
});

/* --------------------------------------------------------------------------
 * 7) CLIENT REVIEWS (recenzii scrise de client)
 * -------------------------------------------------------------------------- */
Route::prefix('client_reviews')->name('client_reviews.')->group(function () {
    Route::get('/', [ClientReviewController::class, 'index'])->name('index');
    Route::get('/create/{vehicleSlug}', [ClientReviewController::class, 'create'])->name('create');
    Route::post('/store', [ClientReviewController::class, 'store'])->name('store');
});

/* --------------------------------------------------------------------------
 * 8) STRIPE CONNECT (conectare cont Stripe pentru plăți către proprietar)
 * -------------------------------------------------------------------------- */
Route::get('/owner/payments/connect',  [StripeConnectController::class, 'start'])->name('payments.connect.start');
Route::get('/owner/payments/return',   [StripeConnectController::class, 'return'])->name('payments.connect.return');
Route::get('/owner/payments/refresh',  [StripeConnectController::class, 'refresh'])->name('payments.connect.refresh');

Route::prefix('payments')->name('payments.')->group(function () {
    Route::get('/bank-account',  [OwnerBankAccountController::class, 'show'])->name('bank.show');
    Route::post('/bank-account', [OwnerBankAccountController::class, 'store'])->name('bank.store');
});

/* --------------------------------------------------------------------------
 * 9) DASHBOARD ROLE SWITCH (doar pt. UI, setează rolul activ în sesiune)
 * -------------------------------------------------------------------------- */
Route::post('/dashboard/role', function () {
    $role = request()->string('role')->toString();
    if (!in_array($role, ['Client', 'Proprietar'], true)) {
        abort(422, 'Rol invalid.');
    }
    session(['dashboard_role' => $role]);
    return back();
})->name('dashboard.setRole');

/* --------------------------------------------------------------------------
 * 9) CALENDAR (owner) — disponibilitate, prețuri, rezervări pe vehicul
 * -------------------------------------------------------------------------- */
Route::get('/calendar/vehicle/{vehicleSlug}', [OwnerCalendarBookingsController::class, 'show'])->name('calendar.show');
Route::get('/calendar/vehicle/{vehicleSlug}/price', [OwnerCalendarBookingsController::class, 'getPrice'])->name('calendar.prices.show');
Route::post('/calendar/vehicle/{vehicleSlug}/price', [OwnerCalendarBookingsController::class, 'setPrice'])->name('calendar.prices.set');
Route::post('/calendar/vehicle/{vehicleSlug}/availability', [OwnerCalendarBookingsController::class, 'setAvailability'])->name('calendar.availability.set');
Route::get('/calendar/vehicle/{vehicleSlug}/bookings/{bookingId}', [OwnerCalendarBookingsController::class, 'showBooking'])->name('calendar.bookings.show');

/* --------------------------------------------------------------------------
 * 10) COMPANY (setări companie)
 * -------------------------------------------------------------------------- */
