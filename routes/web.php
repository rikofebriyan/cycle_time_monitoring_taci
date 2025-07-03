<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\TabelController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/Home', function () {
    return Inertia::render('Home');
})->middleware(['auth', 'verified'])->name('Home');

Route::get('/WireCutting', function () {
    return Inertia::render('WireCutting');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/CrimpingEyelet', function () {
    return Inertia::render('CrimpingEyelet');
})->middleware(['auth', 'verified'])->name('CrimpingEyelet');

Route::get('/CrimpingConnector', function () {
    return Inertia::render('CrimpingConnector');
})->middleware(['auth', 'verified'])->name('CrimpingConnector');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::get('/tabel_ct', function () {
    return Inertia::render('Table');
})->middleware(['auth', 'verified'])->name('table');

Route::get('/getdata', [TabelController::class, 'getData']);
Route::get('/cutting-lead-wire', [TabelController::class, 'cuttingLeadWire']);
Route::get('/api_crimpingEyelet', [TabelController::class, 'api_crimpingEyelet']);
Route::get('/api_crimpingConnector', [TabelController::class, 'api_crimpingConnector']);
Route::get('/total_data_01', [TabelController::class, 'total_data_01']);
Route::get('/total_data_02', [TabelController::class, 'total_data_02']);
Route::get('/total_data_03', [TabelController::class, 'total_data_03']);

require __DIR__ . '/auth.php';
