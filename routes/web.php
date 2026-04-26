<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfilePageController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\Admin\FaqCategoryController;
use App\Http\Controllers\Admin\FaqAdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BossController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteBossController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('/settings/profile', [ProfileController::class, 'destroy'])->name('settings.profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
});

Route::get('/profile/{user}', [ProfilePageController::class, 'show'])->name('profile.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile/{user}/edit', [ProfilePageController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/{user}', [ProfilePageController::class, 'update'])->name('profile.update');
});

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('news', AdminNewsController::class);
});

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('faq-categories', FaqCategoryController::class);
    Route::resource('faqs', FaqAdminController::class);
});

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'submit'])
    ->middleware('throttle:3,1')
    ->name('contact.submit');

Route::get('/bosses', [BossController::class, 'index'])->name('bosses.index');
Route::get('/bosses/{boss}', [BossController::class, 'show'])->name('bosses.show');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('bosses', \App\Http\Controllers\Admin\BossController::class);
});

Route::middleware(['auth', 'throttle:10,1'])->group(function () {
    Route::post('/news/{news}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
});

Route::middleware(['auth', 'throttle:30,1'])->group(function () {
    Route::post('/bosses/{boss}/favorite', [FavoriteBossController::class, 'toggle'])->name('bosses.favorite.toggle');
    Route::get('/favorites', [FavoriteBossController::class, 'index'])->name('favorites.index');
});

require __DIR__.'/auth.php';
