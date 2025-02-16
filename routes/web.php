<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PointageController;
use App\Http\Controllers\AdminController;


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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route::get('/pointage', [PointageController::class, 'index'])->name('pointage.index');
    // Route::post('/pointage', [PointageController::class, 'store'])->name('pointage.store');
    // Route::post('/retour-sortie', [PointageController::class, 'retourSortie'])->name('pointage.retour');

    Route::get('/qr/generate', [QrCodeController::class, 'generate'])->name('qr.generate');
    Route::get('/qr/{qr}', [QrCodeController::class, 'show'])->name('qr.show');

    Route::get('/pointage', [PointageController::class, 'index'])->name('pointage.index');
    Route::post('/pointage', [PointageController::class, 'store'])->name('pointage.store');
    Route::post('/retour-sortie', [PointageController::class, 'retourSortie'])->name('pointage.retour');
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/employes/{id}', [AdminController::class, 'details'])->name('admin.details');
    
});

require __DIR__.'/auth.php';



