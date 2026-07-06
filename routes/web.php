<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Judge\JudgeController;
use App\Http\Controllers\ProblemController;
use Illuminate\Support\Facades\Route;

// Root → login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Pending approval page (public — no auth required)
Route::get('/pending', function () {
    return view('auth.pending');
})->name('auth.pending');

// Dashboard — requires login AND approved status
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'approved', 'verified'])
    ->name('dashboard');

// Profile routes — requires login AND approved status
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes — requires login + Admin role
Route::prefix('admin')->name('admin.')->middleware(['auth', 'approved', 'role:Admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/approve', [UserManagementController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [UserManagementController::class, 'reject'])->name('users.reject');
});

// Judge / ProblemSetter routes — requires login + ProblemSetter role
Route::prefix('judge')->name('judge.')->middleware(['auth', 'approved', 'role:ProblemSetter'])->group(function () {
    Route::get('/', [JudgeController::class, 'dashboard'])->name('dashboard');
});

// Problems routes
Route::resource('problems', ProblemController::class)
    ->only(['create', 'store'])
    ->middleware(['auth', 'approved', 'role:ProblemSetter']);

Route::resource('problems', ProblemController::class)
    ->only(['edit', 'update', 'destroy'])
    ->middleware(['auth', 'approved', 'role:Admin,ProblemSetter']);

Route::resource('problems', ProblemController::class)->only(['index', 'show']);

require __DIR__.'/auth.php';
