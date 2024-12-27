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
    // $page = $request->input('page', 1);
    $perPage = $request->input('per_page', 5);
    // $skip = ($page - 1) * $perPage;

    $articles = Article::select('body', 'created_at')
    // ->skip($skip)
    // ->take($perPage)
    ->latest()
    // ->get();
    ->paginate($perPage);

    // $articles->withQueryString(); // 쿼리스트링을 모두 표시하기 위함
    // $articles->append(['filter' => 'name']); // 기존에 없는 쿼리스트링 추가

    // $totalCount = Article::count();
    
    return view(
        'articles.index', 
        [
            'articles' => $articles,
            // 'totalCount' => $totalCount,
            // 'page' => $page,
            // 'perPage' => $perPage
        ]
    );
    // return view('articles.index')->with('articles', $articles);
});