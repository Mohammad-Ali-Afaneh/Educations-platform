<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\MarkController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// Password Reset Routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
// Dashboard Routes
Route::get('/dashboard', [CourseController::class, 'index'])->name('dashboard');
Route::get('/student/dashboard', [StudentController::class, 'dashboard'])->name('student.dashboard');
Route::get('/courses/all', [CourseController::class, 'allCourses'])->name('courses.all');

// Course Routes
Route::resource('courses', CourseController::class)->only(['create', 'store', 'edit', 'update', 'destroy']);

// Student Routes
Route::get('/students', [StudentController::class, 'index'])->name('students.index');

// Material Routes
Route::get('/students/{student_id}/materials', [MaterialController::class, 'index'])->name('materials.index');
Route::get('/students/{student_id}/add-material', [MaterialController::class, 'create'])->name('materials.create');
Route::post('/students/{student_id}/add-material', [MaterialController::class, 'store'])->name('materials.store');
Route::delete('/materials/{material_id}', [MaterialController::class, 'destroy'])->name('materials.destroy');

// Mark Routes
Route::get('/materials/{material_id}/marks/{student_id}', [MarkController::class, 'create'])->name('marks.create');
Route::post('/materials/{material_id}/marks/{student_id}', [MarkController::class, 'store'])->name('marks.store');

// Broadcast Auth Routes
Broadcast::routes(['middleware' => ['auth.session']]);

// Chat Routes
Route::group(['middleware' => 'auth.session'], function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [ChatController::class, 'store'])->name('chat.send');
});