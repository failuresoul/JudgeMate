<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Judge\JudgeController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\TestCaseController;
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
    Route::get('/test-cases', [TestCaseController::class, 'index'])->name('test-cases.index');
    Route::get('/problems/{problem}/test-cases', [TestCaseController::class, 'show'])->name('test-cases.show');
});

// Problems routes
Route::resource('problems', ProblemController::class)
    ->only(['create', 'store'])
    ->middleware(['auth', 'approved', 'role:ProblemSetter']);

Route::resource('problems', ProblemController::class)
    ->only(['edit', 'update', 'destroy'])
    ->middleware(['auth', 'approved', 'role:ProblemSetter']);

Route::resource('problems', ProblemController::class)->only(['index', 'show']);

use App\Http\Controllers\SubmissionController;

// Test Cases routes
Route::post('problems/{problem}/test-cases', [TestCaseController::class, 'store'])
    ->name('problems.test-cases.store')
    ->middleware(['auth', 'approved', 'role:ProblemSetter']);

Route::delete('test-cases/{test_case}', [TestCaseController::class, 'destroy'])
    ->name('test-cases.destroy')
    ->middleware(['auth', 'approved', 'role:ProblemSetter']);

// Submissions routes
Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('submissions', [SubmissionController::class, 'index'])->name('submissions.index');
    Route::get('problems/{problem}/submit', [SubmissionController::class, 'create'])->name('problems.submit');
    Route::post('problems/{problem}/submissions', [SubmissionController::class, 'store'])->name('problems.submissions.store');
    Route::get('submissions/{submission}/status', [SubmissionController::class, 'status'])->name('submissions.status');
});

require __DIR__.'/auth.php';
