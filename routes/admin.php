<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUsersController;
use App\Http\Controllers\Admin\AdminCheckinReviewController; // <-- adăugat
use App\Http\Controllers\AdminPayoutsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes (dashboard + users management)
|--------------------------------------------------------------------------
| Structură pe secțiuni:
| 1) Admin Dashboard
| 2) Users: listare, editare, update, ștergere
| 3) Check-in Review (submissions) — moderare poze check-in
| 4) Users -> Documente personale (vizualizare)
| 5) Users -> Vehiculele unui user (vizualizare)
| 6) Users -> Documentele unui vehicul al userului (vizualizare)
| 7) Comments: pe documente personale & pe documente vehicul
| 8) Status update: user & vehicul
|
| NOTE:
| - Rutele sunt deja prefixate la nivel superior (nu mai adăugăm prefix aici).
| - Adaugă middleware-urile potrivite (ex: ['auth','can:access-admin']) la nivel de grup/sus.
*/

/* --------------------------------------------------------------------------
 * 1) ADMIN DASHBOARD
 * -------------------------------------------------------------------------- */

Route::get('/dashboard', [AdminController::class, 'dashboard'])
    ->name('dashboard'); // ex: admin.dashboard în zona admin

/* --------------------------------------------------------------------------
 * 1b) VEHICULE — listă globală, punctul de plecare pentru aprobări
 * -------------------------------------------------------------------------- */
Route::get('/vehicles', [AdminController::class, 'vehicles'])
    ->name('vehicles.index');

/* --------------------------------------------------------------------------
 * 2) USERS MANAGEMENT (listare, editare, update, ștergere)
 * -------------------------------------------------------------------------- */
Route::get('/users', [AdminUsersController::class, 'index'])
    ->name('users.index'); // listă utilizatori

Route::get('/users/{id}', [AdminUsersController::class, 'edit'])
    ->name('users.edit'); // pagină edit user (id = user id)

Route::put('/users/{id}', [AdminUsersController::class, 'update'])
    ->name('users.update'); // submit update user

Route::delete('/users/{id}', [AdminUsersController::class, 'destroy'])
    ->name('users.destroy'); // șterge user

/* --------------------------------------------------------------------------
 * 3) CHECK-IN REVIEW (submissions) — Admin moderează pozele de check-in
 * -------------------------------------------------------------------------- */
// Listare submissions pending/în lucru
Route::get('/checkins', [AdminCheckinReviewController::class, 'index'])
    ->name('checkins.index');
// Aprobare check-in
Route::post('/checkins/{submission}/approve', [AdminCheckinReviewController::class, 'approve'])
    ->name('checkins.approve');
// Respingere check-in (cu motiv în request: reason)
Route::post('/checkins/{submission}/reject', [AdminCheckinReviewController::class, 'reject'])
    ->name('checkins.reject');

/* --------------------------------------------------------------------------
 * 4) USERS -> DOCUMENTE PERSONALE (vizualizare)
 * -------------------------------------------------------------------------- */
Route::get('/users/{id}/personal-documents', [AdminUsersController::class, 'showPersonalUserDocuments'])
    ->name('users.personal-documents.show'); // doc. personale ale userului {id}

/* --------------------------------------------------------------------------
 * 5) USERS -> VEHICULE (vizualizare lista vehicule user)
 * -------------------------------------------------------------------------- */
Route::get('/users/{id}/vehicles', [AdminUsersController::class, 'showUserVehicles'])
    ->name('users.vehicles.show'); // vehiculele userului {id}

/* --------------------------------------------------------------------------
 * 6) USERS -> DOCUMENTE VEHICUL (vizualizare documentele unui vehicul al userului)
 * -------------------------------------------------------------------------- */
Route::get('/users/{userId}/vehicle/{vehicleId}/documents', [AdminUsersController::class, 'showVehicleUserDocuments'])
    ->name('users.vehicles-documents.show'); // doc. pentru vehiculul {vehicleId} al userului {userId}

/* --------------------------------------------------------------------------
 * 7) COMMENTS (pe documente personale & pe documente de vehicul)
 * -------------------------------------------------------------------------- */
Route::post('/users/{user}/documents/comments', [AdminUsersController::class, 'storeComments'])
    ->name('users.documents.comment'); // adaugă comentariu pe doc. personale ale userului {user}

Route::post('/users/{vehicle}/vehicle-documents/comments', [AdminUsersController::class, 'storeCommentsForVehiclesDocuments'])
    ->name('users.vehicle-documents.comment'); // adaugă comentariu pe doc. vehiculului {vehicle}

/* --------------------------------------------------------------------------
 * 8) STATUS UPDATE (user & vehicul)
 * -------------------------------------------------------------------------- */
Route::put('/users/{id}/status', [AdminUsersController::class, 'updateStatus'])
    ->name('users.status.update'); // schimbă statusul userului {id}

Route::put('/users/{vehicle}/vehicle-status', [AdminUsersController::class, 'updateStatusVehicle'])
    ->name('users.vehicle-status.update'); // schimbă statusul vehiculului {vehicle}

/* --------------------------------------------------------------------------
    * 9) PAYOUTS MANAGEMENT (listă plăți de făcut, marcare ca plătit)
    * -------------------------------------------------------------------------- */
Route::prefix('payouts')->name('payouts.')->middleware(['auth'/*, 'can:access-admin'*/])->group(function () {
    Route::get('/', [AdminPayoutsController::class, 'index'])->name('index');          // listă plăți de făcut
    Route::get('/export', [AdminPayoutsController::class, 'export'])->name('export');  // CSV
    Route::post('/{booking}/mark-paid', [AdminPayoutsController::class, 'markPaid'])->name('markPaid');
});
