<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Judge\JudgeController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\TestCaseController;
use App\Http\Controllers\LeaderboardController;
use Illuminate\Support\Facades\Route;

// Public leaderboard route
Route::get('leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

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
    Route::get('/profile/show/{user?}', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes — requires login + Admin role
Route::prefix('admin')->name('admin.')->middleware(['auth', 'approved', 'role:Admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
    // Admin Blog routes
    Route::get('/blogs', [\App\Http\Controllers\Admin\BlogController::class, 'index'])->name('blogs.index');
    Route::patch('/blogs/{blog}', [\App\Http\Controllers\Admin\BlogController::class, 'update'])->name('blogs.update');
});

// Judge / ProblemSetter routes — requires login + ProblemSetter role
Route::prefix('judge')->name('judge.')->middleware(['auth', 'approved', 'role:ProblemSetter'])->group(function () {
    Route::get('/', [JudgeController::class, 'dashboard'])->name('dashboard');
    Route::get('/test-cases', [TestCaseController::class, 'index'])->name('test-cases.index');
    Route::get('/problems/{problem}/test-cases', [TestCaseController::class, 'show'])->name('test-cases.show');
    
    // Judge Blog routes
    Route::resource('blogs', \App\Http\Controllers\Judge\BlogController::class)->except(['show', 'edit', 'update']);
});

// Public Blog (Inspiration) route
Route::get('blogs', [\App\Http\Controllers\BlogController::class, 'index'])->name('blogs.index')->middleware(['auth', 'approved']);

// Problems routes
Route::resource('problems', ProblemController::class)
    ->only(['create', 'store'])
    ->middleware(['auth', 'approved', 'role:ProblemSetter']);

Route::resource('problems', ProblemController::class)
    ->only(['edit', 'update', 'destroy'])
    ->middleware(['auth', 'approved', 'role:ProblemSetter']);

Route::resource('problems', ProblemController::class)->only(['index', 'show']);

use App\Http\Controllers\ContestController;
use App\Http\Controllers\SubmissionController;

// Contests routes
Route::middleware(['auth', 'approved'])->group(function () {
    Route::post('contests/{contest}/approve', [ContestController::class, 'approve'])
        ->name('contests.approve')
        ->middleware('role:Admin');
    Route::post('contests/{contest}/register', [ContestController::class, 'register'])
        ->name('contests.register');
    Route::post('contests/{contest}/join', [ContestController::class, 'join'])
        ->name('contests.join');
    Route::get('contests/{contest}/scoreboard', [\App\Http\Controllers\ScoreboardController::class, 'show'])
        ->name('contests.scoreboard');
    Route::get('contests/{contest}/scoreboard/data', [\App\Http\Controllers\ScoreboardController::class, 'data'])
        ->name('contests.scoreboard.data')
        ->middleware('throttle:scoreboard');
    Route::get('contests/{contest}/scoreboard/pdf', [\App\Http\Controllers\ScoreboardController::class, 'downloadPdf'])
        ->name('contests.scoreboard.pdf');
    Route::resource('contests', ContestController::class);
});

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
    Route::patch('/problems/{problem}/toggle-publish', [ProblemController::class, 'togglePublish'])
        ->name('problems.toggle-publish')
        ->middleware(['role:Admin']);
    Route::post('problems/{problem}/submissions', [SubmissionController::class, 'store'])->name('problems.submissions.store');
    Route::get('submissions/{submission}', [SubmissionController::class, 'show'])->name('submissions.show');
    Route::get('submissions/{submission}/status', [SubmissionController::class, 'status'])->name('submissions.status');

    // Notifications routes
    Route::get('notifications/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('notifications/mark-read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('notifications.mark-read');
    Route::get('notifications/{id}/redirect', [\App\Http\Controllers\NotificationController::class, 'redirect'])->name('notifications.redirect');
});

require __DIR__.'/auth.php';
