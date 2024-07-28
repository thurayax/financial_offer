<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OfferController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
Route::post('/admin/save-client', [AdminController::class, 'saveClient'])->name('admin.saveClient');
Route::get('/admin/edit-client/{id}', [AdminController::class, 'editClient'])->name('admin.editClient');
Route::post('/admin/update-client/{id}', [AdminController::class, 'updateClient'])->name('admin.updateClient');
Route::delete('/admin/delete-client/{id}', [AdminController::class, 'deleteClient'])->name('admin.deleteClient');

Route::get('/offer', [OfferController::class, 'index'])->name('offer.index');
Route::get('/offer/{client_id}', [OfferController::class, 'show'])->name('offer.show');
Route::post('/offer/save', [OfferController::class, 'save'])->name('offer.save');
Route::get('/offer/{client_id}/export-pdf', [OfferController::class, 'exportPdf'])->name('offer.exportPdf');

// Add the missing route here
Route::post('/offer/{client_id}/update-payment-policy', [OfferController::class, 'updatePaymentPolicy'])->name('offer.updatePaymentPolicy');