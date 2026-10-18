<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InlineImageController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/statuses', [DashboardController::class, 'statuses'])->name('statuses');
    Route::get('/posts/create', [DashboardController::class, 'create'])->name('posts.create');
    Route::post('/posts/inline-images', [InlineImageController::class, 'store'])->name('posts.inline-images.store');
    Route::post('/post-autosaves', [DashboardController::class, 'autosave'])->name('post-autosaves.store');
    Route::get('/posts/{post}/preview', [DashboardController::class, 'preview'])->name('posts.preview');
    Route::post('/posts', [DashboardController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [DashboardController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [DashboardController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [DashboardController::class, 'destroy'])->name('posts.destroy');
    Route::patch('/posts/{post}/status', [DashboardController::class, 'updateStatus'])->name('posts.status.update');
});

Route::get('/home', [PostsController::class, 'index'])->name('home');

Route::get('/about', [PostsController::class, 'about'])->name('about');

Route::get('/post/{slug}', [PostsController::class, 'show'])->name('post');

Route::get('/contact', [PostsController::class, 'contact'])->name('contact');
