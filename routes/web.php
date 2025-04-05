<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')
        ->name('settings.profile');
    Volt::route('settings/password', 'settings.password')
        ->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')
        ->name('settings.appearance');
    Volt::route('admin/students', 'admin.students.index')
        ->name('admin.students');
    Volt::route('admin/school-classes', 'admin.school_classes.index')
        ->name('admin.school_classes');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
