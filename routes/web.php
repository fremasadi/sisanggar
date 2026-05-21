<?php

use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\HasilUjianKelompokController;
use App\Http\Controllers\Admin\JadwalKelompokController;
use App\Http\Controllers\Admin\KelompokController;
use App\Http\Controllers\Admin\KelompokPesertaController;
use App\Http\Controllers\Admin\KostumController;
use App\Http\Controllers\Admin\PelatihController;
use App\Http\Controllers\Admin\PresensiController;
use App\Http\Controllers\Admin\PresensiDetailController;
use App\Http\Controllers\Admin\SppController as AdminSppController;
use App\Http\Controllers\Admin\UjianKelompokController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\GuestBookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Pelatih\HasilUjianKelompokController as PelatihHasilUjianKelompokController;
use App\Http\Controllers\Pelatih\KelompokController as PelatihKelompokController;
use App\Http\Controllers\Pelatih\PresensiController as PelatihPresensiController;
use App\Http\Controllers\Pelatih\PresensiDetailController as PelatihPresensiDetailController;
use App\Http\Controllers\Pelatih\UjianKelompokController as PelatihUjianKelompokController;
use App\Http\Controllers\Peserta\KelompokController as PesertaKelompokController;
use App\Http\Controllers\Peserta\PresensiController as PesertaPresensiController;
use App\Http\Controllers\Peserta\SppController as PesertaSppController;
use App\Http\Controllers\Peserta\UjianKelompokController as PesertaUjianKelompokController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/calculate', [CartController::class, 'calculate'])->name('calculate');
});

Route::post('/guest-booking', [GuestBookingController::class, 'store'])->name('guest-booking.store');
Route::get('/booking/history', [GuestBookingController::class, 'historyForm'])->name('guest-booking.history');
Route::post('/booking/history', [GuestBookingController::class, 'historySearch'])->name('guest-booking.history.search');

Route::post('/midtrans/callback', [PaymentController::class, 'callback'])->name('midtrans.callback');
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::get('/payment/unfinish', [PaymentController::class, 'unfinish'])->name('payment.unfinish');
Route::get('/payment/error', [PaymentController::class, 'error'])->name('payment.error');

Route::middleware('auth')->group(function () {
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('pelatih', PelatihController::class);
        Route::resource('kostum', KostumController::class);
        Route::resource('kelompok', KelompokController::class);
        Route::get('presensi', [PresensiController::class, 'index'])->name('presensi.index');
        Route::get('presensi/{presensi}', [PresensiController::class, 'show'])->name('presensi.show');
        Route::patch('presensi/{presensi}', [PresensiController::class, 'update'])->name('presensi.update');

        Route::get('booking', [BookingController::class, 'index'])->name('booking.index');
        Route::get('booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
        Route::patch('booking/{booking}/status', [BookingController::class, 'updateStatus'])->name('booking.update-status');
        Route::patch('booking/{booking}/verification', [BookingController::class, 'updateVerification'])->name('booking.update-verification');

        Route::get('spp', [AdminSppController::class, 'index'])->name('spp.index');
        Route::post('spp/generate', [AdminSppController::class, 'generate'])->name('spp.generate');

        Route::post('kelompok/{kelompok}/peserta', [KelompokPesertaController::class, 'store'])->name('kelompok-peserta.store');
        Route::patch('kelompok-peserta/{anggota}', [KelompokPesertaController::class, 'update'])->name('kelompok-peserta.update');
        Route::delete('kelompok-peserta/{anggota}', [KelompokPesertaController::class, 'destroy'])->name('kelompok-peserta.destroy');

        Route::post('kelompok/{kelompok}/jadwal', [JadwalKelompokController::class, 'store'])->name('jadwal-kelompok.store');
        Route::patch('jadwal-kelompok/{jadwal}', [JadwalKelompokController::class, 'update'])->name('jadwal-kelompok.update');
        Route::delete('jadwal-kelompok/{jadwal}', [JadwalKelompokController::class, 'destroy'])->name('jadwal-kelompok.destroy');

        Route::post('kelompok/{kelompok}/presensi', [PresensiController::class, 'store'])->name('presensi.store');
        Route::patch('presensi-detail/{detail}', [PresensiDetailController::class, 'update'])->name('presensi-detail.update');

        Route::post('kelompok/{kelompok}/ujian', [UjianKelompokController::class, 'store'])->name('ujian-kelompok.store');
        Route::get('ujian-kelompok/{ujian}', [UjianKelompokController::class, 'show'])->name('ujian-kelompok.show');
        Route::post('ujian-kelompok/{ujian}/promote', [UjianKelompokController::class, 'promote'])->name('ujian-kelompok.promote');
        Route::patch('hasil-ujian-kelompok/{hasil}', [HasilUjianKelompokController::class, 'update'])->name('hasil-ujian-kelompok.update');
    });

    Route::middleware(['role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
        Route::get('spp', [PesertaSppController::class, 'index'])->name('spp.index');
        Route::get('spp/{tagihan}', [PesertaSppController::class, 'show'])->name('spp.show');
        Route::post('spp/{tagihan}/pay', [PesertaSppController::class, 'pay'])->name('spp.pay');

        Route::get('kelompok', [PesertaKelompokController::class, 'show'])->name('kelompok.show');
        Route::get('presensi', [PesertaPresensiController::class, 'index'])->name('presensi.index');
        Route::get('ujian', [PesertaUjianKelompokController::class, 'index'])->name('ujian.index');
    });

    Route::middleware(['role:pelatih'])->prefix('pelatih')->name('pelatih.')->group(function () {
        Route::get('kelompok', [PelatihKelompokController::class, 'index'])->name('kelompok.index');
        Route::get('kelompok/{kelompok}', [PelatihKelompokController::class, 'show'])->name('kelompok.show');

        Route::get('presensi', [PelatihPresensiController::class, 'index'])->name('presensi.index');
        Route::post('kelompok/{kelompok}/presensi', [PelatihPresensiController::class, 'store'])->name('presensi.store');
        Route::get('presensi/{presensi}', [PelatihPresensiController::class, 'show'])->name('presensi.show');
        Route::patch('presensi/{presensi}', [PelatihPresensiController::class, 'update'])->name('presensi.update');
        Route::patch('presensi-detail/{detail}', [PelatihPresensiDetailController::class, 'update'])->name('presensi-detail.update');

        Route::get('ujian', [PelatihUjianKelompokController::class, 'index'])->name('ujian.index');
        Route::get('ujian/{ujian}', [PelatihUjianKelompokController::class, 'show'])->name('ujian.show');
        Route::patch('hasil-ujian/{hasil}', [PelatihHasilUjianKelompokController::class, 'update'])->name('hasil-ujian.update');
    });

    Route::post('/payment/checkout', [PaymentController::class, 'processCheckout'])
        ->name('payment.checkout');

    Route::get('/payment/{booking}', [PaymentController::class, 'show'])
        ->name('payment.show');

    Route::get('/payment/{booking}/check-status', [PaymentController::class, 'checkStatus'])
        ->name('payment.check-status');

    Route::get('/my-bookings', [PaymentController::class, 'history'])
        ->name('payment.history');
});

require __DIR__.'/auth.php';
