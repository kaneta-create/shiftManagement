<?php

use App\Http\Controllers\DefaultShiftController;
use App\Http\Controllers\ActualShiftController;
use App\Http\Controllers\CreateShift;
use App\Http\Controllers\RequestShiftController;
// use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\passwordSet;
use App\Http\Controllers\DayShift;
use App\Http\Controllers\passwordSetController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/passwordSets', [passwordSet::class, 'index'])->name('passwordSets.index');
Route::put('/passwordSets', [passwordSet::class, 'storePassword'])->name('passwordSets.storePassword');

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::resource('admins', EmployeeController::class)->middleware(['auth', 'checkRole:3']);
// Route::resource('employees', EmployeeController::class)->middleware(['auth', 'checkRole:3']);

Route::resource('defaultShifts', DefaultShiftController::class)->middleware(['auth', 'checkRole:2']);
Route::resource('requestShifts', RequestShiftController::class)->middleware(['auth', 'checkRole:1']);
Route::resource('actualShifts', ActualShiftController::class)->middleware(['auth', 'checkRole:1']);
Route::get('createShifts', [CreateShift::class, 'index'])->name('createShifts.index')->middleware(['auth', 'checkRole:2']);
Route::put('createShifts/{createShift}', [CreateShift::class, 'store'])->name('createShifts.store')->middleware(['auth', 'checkRole:2']);
Route::get('dayShifts', [DayShift::class, 'index'])->name('dayShifts.index')->middleware(['auth', 'checkRole:1']);

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
