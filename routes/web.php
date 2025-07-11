<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\HistoryPengeluaranController;
use App\Http\Controllers\HistoryPesanController;
use App\Http\Controllers\JenisKostController;
use App\Http\Controllers\KategoriPengeluaranController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\OccupancyReportController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PengelolaController;
use App\Http\Controllers\PengelolaPropertiController;
use App\Http\Controllers\PengeluaranKostController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\PeraturanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertiController;
use App\Http\Controllers\RoomController;

// authentication
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('auth.dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class);
});

Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
        Route::get('/change-password', [ProfileController::class, 'changePasswordForm'])->name('change-password');
        Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('change-password');
    });
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');
});


// master data resources
Route::resource('bookings', BookingController::class);
Route::resource('fasilitas', FasilitasController::class);
Route::resource('history_pengeluarans', HistoryPengeluaranController::class);
Route::resource('history_pesans', HistoryPesanController::class);
Route::resource('jeniskosts', JenisKostController::class);
Route::resource('kategori_pengeluarans', KategoriPengeluaranController::class);
Route::resource('metode_pembayarans', MetodePembayaranController::class);
Route::resource('payments', PaymentController::class);
Route::resource('pengelolas', PengelolaController::class);
Route::resource('pengelola_properties', PengelolaPropertiController::class);
Route::resource('penyewas', PenyewaController::class);
Route::resource('peraturans', PeraturanController::class);
Route::resource('properties', PropertiController::class);
Route::resource('rooms', RoomController::class);
Route::resource('User', UserController::class);

Route::get('send',[MessageController::class,'send']);

// landing
Route::get('/', [LandingController::class, 'index'])->name('index');
Route::post('booking_proses', [LandingController::class, 'booking_proses'])->name('booking_proses');
Route::get('/payments/{payment}/invoice', [PaymentController::class, 'invoice'])->name('payments.invoice');
Route::get('tagih-penyewa/{id}',[PenyewaController::class,'tagih'])->name('tagih.penyewa');

Route::patch('/payments/{payment}/approve', [PaymentController::class, 'approve'])
    ->name('payments.approve')
    ->middleware(['auth', 'permission:payment-approve']);

Route::patch('/payments/{payment}/pay', [PaymentController::class, 'processPayment'])
    ->name('payments.pay')
    ->middleware(['auth', 'permission:payment-pay']);
// Route untuk mendapatkan metode pembayaran berdasarkan properti
Route::get('/payments/{payment}/payment-methods', [PaymentController::class, 'getPaymentMethods'])
    ->name('payments.payment-methods')
    ->middleware(['auth']);

Route::get('/laporan-okupansi', [OccupancyReportController::class, 'index'])->name('laporan.okupansi');

Route::post('/laporan/okupansi/export-pdf', [OccupancyReportController::class, 'exportPdf'])->name('laporan.okupansi.export.pdf');

Route::get('/pengeluaran-kost/select-data', [PengeluaranKostController::class, 'getSelectData'])
    ->name('pengeluaran-kost.select-data');

Route::get('/pengeluaran-kost/export', [PengeluaranKostController::class, 'export'])->name('pengeluaran-kost.export');
Route::get('/pengeluaran-kost/export-pdf', [PengeluaranKostController::class, 'exportPdf'])->name('pengeluaran-kost.export.pdf');


Route::resource('pengeluaran-kost', PengeluaranKostController::class);
// LETAKKAN DI ATAS yang pakai wildcard {pengeluaranKost}
// Route::get('pengeluaran-kost/select-data', [PengeluaranKostController::class, 'getSelectData'])->name('pengeluaran-kost.select-data');

// // Baru wildcard di bawahnya
// Route::get('pengeluaran-kost/{pengeluaranKost}', [PengeluaranKostController::class, 'show']);


// Route tambahan untuk export
// Route::get('/pengeluaran-kost/export', [PengeluaranKostController::class, 'export'])
//     ->name('pengeluaran-kost.export');
Route::post('pengeluaran-kost-bulk-delete', [PengeluaranKostController::class, 'bulkDelete'])->name('pengeluaran-kost.bulk-delete');

// Route::get('/pengeluaran-kost/select-data/{type?}', [PengeluaranKostController::class, 'getSelectData'])
//     ->name('pengeluaran-kost.select-data');
// Route::get('/pengeluaran-kost/select-data', [PengeluaranKostController::class, 'getSelectData'])
//     ->name('pengeluaran-kost.select-data');

Route::get('/pengeluaran-kost/detail/{id}', [PengeluaranKostController::class, 'show'])
    ->name('pengeluaran-kost.detail');

Route::get('list-kamar', [LandingController::class, 'all_rooms'])->name('all_rooms');
Route::get('all_kost', [LandingController::class, 'all_kost'])->name('all_kost');
Route::get("chat_bot", function () {
    return view("chat_bot");
})->name('chat_bot');
Route::get('/kost/{id_properti}', [PropertiController::class, 'detailKost'])
    ->name('guest_or_user.detailkost');

Route::get("guest_or_user/about-us", function () {
    return view("guest_or_user.about-us");
})->name('about-us');