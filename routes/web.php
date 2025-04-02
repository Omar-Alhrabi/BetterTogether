<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\DonationController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {

    if (Auth::user() && (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin')) {
       
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])
    ->can('access', User::class)
    ->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('dashboard')->middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('users', UserController::class);

    Route::resource('activities', ActivityController::class);

    Route::resource('posts', PostController::class);
    Route::get('posts/{post}/comments', [PostController::class, 'comments'])->name('posts.comments');
    Route::delete('comments/{id}', [PostController::class, 'deleteComment'])->name('posts.comments.delete');

    Route::resource('comments', CommentController::class);
 
    Route::resource('donations', DonationController::class);
    Route::get('donation-statistics', [DonationController::class, 'statistics'])->name('donations.statistics');

})
->can('access', User::class);
require __DIR__ . '/auth.php';