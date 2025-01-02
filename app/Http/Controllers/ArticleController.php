<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateArticleRequest;
use App\Http\Requests\DeleteArticleRequest;
use App\Http\Requests\EditArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function create() {
        return view('articles/create');
    }

    public function store(CreateArticleRequest $request, Article $article) {
        $input = $request->validated();
    
        Article::create([
            'body' => $input['body'],
            'user_id' => Auth::id()
        ]);
        
        return redirect()->route('articles.index');
    }

    public function index() {
        $articles = Article::with('user')
            ->withCount('comments')
            ->withExists(['comments as recent_comments_exists' => function($query) {
                $query->where('created_at', '>', Carbon::now()->subDay());
            }])
            ->latest()
            ->paginate(5);

        return view(
            'articles.index', ['articles' => $articles]
        );
    }

    public function show(Article $article) {
        $article->load('comments.user');
        $article->loadCount('comments');
        return view('articles.show', ['article' => $article]);
    }

    public function edit(EditArticleRequest $request, Article $article) {
        return view('articles.edit', ['articles' => $article]);
    }

    public function update(UpdateArticleRequest $request, Article $article) {
        $input = $request->validated();
        $article->body = $input['body'];
        $article->save();
    
        return redirect()->route('articles.index');
    }

    public function destroy(DeleteArticleRequest $request, Article $article) {
        $article->delete();

        return redirect()->route('articles.index');
    }
}
