<?php

use App\Http\Controllers\BelanjaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index', [
        'title' => 'Data Belanja',
        'active' => 'belanja'
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/belanja', [BelanjaController::class, 'index']);
Route::post('/belanja', [BelanjaController::class, 'store']);
Route::get('/belanja/{belanja:kode_belanja}', [BelanjaController::class, 'show']);
Route::delete('/belanja/{belanja:id}', [BelanjaController::class, 'destroy']);

Route::get('/voucher', function () {
    return view('voucher', [
        'title' => 'Data Voucher',
        'active' => 'voucher'
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/datavoucher', [VoucherController::class, 'index']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
