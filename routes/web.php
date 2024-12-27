<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::controller(ArticleController::class)->group(function(){
    Route::get('articles/create',  'create')->name('articles.create');
    Route::post('articles',  'index')->name('articles.index');
    Route::get('articles',  'store')->name('articles.store');
    Route::get('articles/{article}',  'show')->name('articles.show');
    Route::get('articles/{article}/edit',  'edit')->name('articles.edit');
    Route::patch('articles/{article}',  'update')->name('articles.update');
    Route::delete('articles/{article}',  'destory')->name('articles.delete');
});
