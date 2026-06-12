<?php

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\JoinClassController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaskFileController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\IsTeacher;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('classes.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar');

    // Join class — before /classes/{class}
    Route::get('/classes/join', fn() => view('classes.join'))->name('classes.join.form');
    Route::post('/classes/join', [JoinClassController::class, 'store'])->name('classes.join');
    Route::get('/join/{code}', function ($code) {
        return view('classes.join', ['prefill' => $code]);
    })->name('classes.join.show');

    // Teachers & Admins only — before /classes/{class}
    Route::middleware(IsTeacher::class)->group(function () {
        Route::get('/classes/create', [ClassesController::class, 'create'])->name('classes.create');
        Route::post('/classes', [ClassesController::class, 'store'])->name('classes.store');

        Route::get('/tasks/create', [TasksController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TasksController::class, 'store'])->name('tasks.store');
    });

    // Classes
    Route::delete('/classes/{class}', [ClassesController::class, 'destroy'])->name('classes.destroy');
    Route::get('/classes', [ClassesController::class, 'index'])->name('classes.index');
    Route::get('/classes/{class}', [ClassesController::class, 'show'])->name('classes.show');

    // Task files
    Route::post('/tasks/{tasks}/files', [TaskFileController::class, 'store'])->name('task-files.store');
    Route::get('/task-files/{taskFile}/download', [TaskFileController::class, 'download'])->name('task-files.download');
    Route::delete('/task-files/{taskFile}', [TaskFileController::class, 'destroy'])->name('task-files.destroy');
    Route::delete('/tasks/{task}', [TasksController::class, 'destroy'])->name('tasks.destroy');

    Route::get('/tasks/{task}', [TasksController::class, 'show'])->name('tasks.show');
    Route::post('/tasks/{task}/solution', [TasksController::class, 'submitSolution'])->name('tasks.submitSolution');
    Route::post('/tasks/{task}/comment', [TasksController::class, 'storeComment'])->name('tasks.comment');
    Route::get('/solutions/{solution}/download', [TasksController::class, 'downloadSolution'])->name('solutions.download');
    Route::patch('/solutions/{solution}/grade', [TasksController::class, 'gradeSolution'])->name('tasks.solutions.grade');
    Route::delete('/solutions/{solution}', [TasksController::class, 'destroySolution'])->name('solutions.destroy');

    // Admin only
    Route::middleware(IsAdmin::class)->group(function () {
        Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
        Route::patch('/admin/users/{user}/role', [AdminController::class, 'updateRole'])->name('admin.users.role');
        Route::delete('/admin/users/{user}', [AdminController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/admin/activity', [AdminController::class, 'activity'])->name('admin.activity');
    });

});

require __DIR__.'/auth.php';