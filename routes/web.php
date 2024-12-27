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
})->name('articles.create');


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
    
    return redirect()->route('articles.index');
})->name('articles.index');

Route::get('/articles', function(Request $request) {
    $articles = Article::with('user')
        ->latest()
        ->paginate(5);

    return view(
        'articles.index', ['articles' => $articles,]
    );
})->name('articles.store');

// Route::get('/articles/{id}', function($id) {
//     $article = Article::find($id);

//     return view('articles.show', ['article' => $article]);
// });

Route::get('/articles/{article}', function(Article $article) {
    return view('articles.show', ['article' => $article]);
})->name('articles.show');

Route::get('articles/{article}/edit', function(Article $article) {
    return view('articles.edit', ['articles' => $article]);
})->name('articles.edit');

Route::patch('articles/{article}', function(Request $request, Article $article) {
    $input = $request->validate([
        'body' => [
            'required',
            'string', 
            'max:255',
        ],
    ]);

    $article->body = $input['body'];
    $article->save();

    return redirect()->route('articles.index');
})->name('articles.update');

Route::delete('articles/{article}', function(Article $article) {
    $article->delete();

    return redirect()->route('articles.index');
})->name('articles.delete');