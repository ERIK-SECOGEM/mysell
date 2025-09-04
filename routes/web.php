<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PublicVehicleController;

Route::get('/', function () {
    return view('welcome');
});

//Rutas planes y suscripción para usuarios no suscritos
Route::get('/plans', [SubscriptionController::class, 'index'])->name('plans.index')->middleware('auth');
Route::post('/subscription/create', [SubscriptionController::class, 'create'])->name('subscription.create')->middleware('auth');

Route::middleware(['auth', 'verified'])->group(function () {
    //Rutas perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'check.subscription'])->group(function () {
    //Ruta dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //Rutas suscripción
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
    Route::post('/subscription/cancel-now', [SubscriptionController::class, 'cancelNow'])->name('subscription.cancel.now');
    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');
    Route::post('/subscription/update-payment', [SubscriptionController::class, 'updatePayment'])->name('subscription.update.payment');

    //Rutas vehículos
    Route::resource('/vehicles', VehicleController::class);
    Route::post('/vehicles/{vehicle}/publish', [VehicleController::class, 'publish'])->name('vehicles.publish');
    Route::get('/vehicles/{vehicle}/qr', [VehicleController::class, 'qr'])->name('vehicles.qr');
    Route::get('/vehicles/{vehicle}/qr-download', [VehicleController::class, 'qrDownload'])->name('vehicles.qr.download');
});

Route::get('/p/{slug}', [PublicVehicleController::class, 'show'])->name('vehicles.public.show');

require __DIR__.'/auth.php';
