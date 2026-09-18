<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LearningController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth.session')->name('logout');

Route::middleware('auth.session')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/modulos/{module}', [ModuleController::class, 'show'])->name('modules.show');
    Route::get('/modulos/{module}/evaluacion', [EvaluationController::class, 'show'])->name('evaluation.show');
    Route::post('/modulos/{module}/evaluacion', [EvaluationController::class, 'submit'])->name('evaluation.submit');
    Route::get('/resultado/{attempt}', [EvaluationController::class, 'result'])->name('evaluation.result');
    Route::get('/refuerzo/{module}', [LearningController::class, 'reinforcement'])->name('learning.reinforcement');
    Route::get('/siguiente-modulo/{module}', [LearningController::class, 'next'])->name('learning.next');
});
