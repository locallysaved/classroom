<?php

use App\Http\Controllers\ClassesController;
use App\Http\Controllers\JoinClassController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TasksController;
use App\Http\Middleware\IsTeacher;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Join class (students) — before /classes/{class} to avoid wildcard conflict
    Route::get('/classes/join', fn() => view('classes.join'))->name('classes.join.form');
    Route::post('/classes/join', [JoinClassController::class, 'store'])->name('classes.join');

    // QR deep link — scanning QR prefills the code
    Route::get('/join/{code}', function ($code) {
        return view('classes.join', ['prefill' => $code]);
    })->name('classes.join.show');

    // Teachers & Admins only — must be before /classes/{class}
    Route::middleware(IsTeacher::class)->group(function () {
        Route::get('/classes/create', [ClassesController::class, 'create'])->name('classes.create');
        Route::post('/classes', [ClassesController::class, 'store'])->name('classes.store');

        Route::get('/tasks/create', [TasksController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TasksController::class, 'store'])->name('tasks.store');
    });

    // Classes — after all explicit /classes/* routes
    Route::get('/classes', [ClassesController::class, 'index'])->name('classes.index');
    Route::get('/classes/{class}', [ClassesController::class, 'show'])->name('classes.show');

});

require __DIR__.'/auth.php';