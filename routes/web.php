<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PelatihController;
use App\Http\Controllers\Admin\KostumController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;

Route::get('/', [FrontController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

   Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('pelatih', PelatihController::class);
        Route::resource('kostum', KostumController::class);

        Route::get('booking', [BookingController::class, 'index'])->name('booking.index');
        Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::patch('booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('booking.update-status');

    });


    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
        Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
        Route::post('/calculate', [CartController::class, 'calculate'])->name('calculate');
    });

    /*
    |--------------------------------------------------------------------------
    | Payment Routes (Authenticated)
    |--------------------------------------------------------------------------
    */
    
    // Checkout from cart
    Route::post('/payment/checkout', [PaymentController::class, 'processCheckout'])
        ->name('payment.checkout');
    
    // Show payment page
    Route::get('/payment/{booking}', [PaymentController::class, 'show'])
        ->name('payment.show');
    
    // Check payment status (AJAX)
    Route::get('/payment/{booking}/check-status', [PaymentController::class, 'checkStatus'])
        ->name('payment.check-status');
    
    // Booking history
    Route::get('/my-bookings', [PaymentController::class, 'history'])
        ->name('payment.history');

    // Payment finish (redirect from Midtrans)
    Route::get('/payment/finish', [PaymentController::class, 'finish'])
        ->name('payment.finish');
});

Route::post('/midtrans/callback', [PaymentController::class, 'callback'])
    ->name('midtrans.callback');

require __DIR__.'/auth.php';
