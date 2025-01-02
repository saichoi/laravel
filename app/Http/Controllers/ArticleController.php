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

    public function store(Request $request, Article $article) {
        // User 모델을 활용하여 어플리케이션의 권한 확인 => 권한이 없을 때 동작을 지정.
        if (!Auth::user()->can('update', $article)) {
            abort(403);
        }

        // 컨트롤러 헬퍼를 활용하여 어플리케이션의 권한 확인 => 권한이 없으면 자동으로 응답 만들어줌.
        // $this->authorize('update', $article);

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
        if (!Auth::user()->can('update', $article)) {
            abort(403);
        }
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

    public function destroy(Article $article) {
        if (!Auth::user()->can('update', $article)) {
            abort(403);
        }

        $article->delete();

        return redirect()->route('articles.index');
    }
}
