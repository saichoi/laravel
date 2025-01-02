<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $articles = Article::with('user')
        ->withCount('comments')
        ->withExists(['comments as recent_comments_exists' => function($query) {
            $query->where('created_at', '>', Carbon::now()->subDay());
        }])
        ->when(Auth::check(), function(Builder $query) {
            $query->whereHas('user', function($query) {
                $query->whereIn('id', Auth::user()->followings->pluck('id')->push(Auth::id()));
            });
        })
        ->latest()
        ->paginate(5);

    return view(
        'articles.index', ['articles' => $articles]
    );
    }
}
