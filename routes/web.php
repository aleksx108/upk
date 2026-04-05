<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DashboardBirthdaysController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\OccupationController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/birthdays', DashboardBirthdaysController::class)->middleware('verified')->name('dashboard.birthdays');
    Route::resource('personnel', PersonnelController::class);
    Route::resource('companies', CompanyController::class);
    Route::resource('occupations', OccupationController::class);
    Route::get('personnel/{personnel}/pdf', [PersonnelController::class, 'pdf'])->name('personnel.pdf');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

