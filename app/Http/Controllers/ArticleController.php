<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function create() {
        return view('articles/create');
    }

    public function store(Request $request) {
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
    }

    public function index() {
        $articles = Article::with('user')
            ->latest()
            ->paginate(5);

        return view(
            'articles.index', ['articles' => $articles]
        );
    }

    public function show(Article $article) {
        return view('articles.show', ['article' => $article]);
    }

    public function edit(Article $article) {
        return view('articles.edit', ['articles' => $article]);
    }

    public function update(Request $request, Article $article) {
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
    }

    public function destory(Article $article) {
        $article->delete();

        return redirect()->route('articles.index');
    }
}
