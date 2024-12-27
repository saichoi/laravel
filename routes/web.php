<?php

use App\Http\Controllers\ProfileController;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

Route::get('/articles/create', function() {
    return view('articles/create');
});


Route::post('/articles', function(Request $request) {
    $input = $request->validate([
        'body' => [
            'required',
            'string', 
            'max:255',
        ],
    ]);

    Article::create([
        'body' => $input['body'],
        'user_id' => Auth::id()
    ]);

    return 'hello';
});

Route::get('/articles', function(Request $request) {
    $articles = Article::with('user')
        ->select('body', 'user_id', 'created_at')
        ->latest()
        ->paginate(5);

    return view(
        'articles.index', ['articles' => $articles,]
    );
});