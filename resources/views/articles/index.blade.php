<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container p-5">
        <h1 class="text-2xl mb-5">글목록</h1>
        @foreach ($articles as $article)
            @auth
            <div class="background-white border rounded mb-3 p-3">
                <p>{{ $article->body }}</p>
                <p>{{ $article->user->name }}</p>
                <p><a href="{{ route('articles.show', ['article' => $article]) }}">{{ $article->created_at->diffForHumans() }}</a></p>
                <div class="flex flex-row space-x-1">
                    <p class="mt-2"><a href="{{ route('articles.edit', ['article' => $article]) }}" class="button rounded bg-blue-500 px-2 py-1 text-xs text-white">수정</a></p>
                    <form action="{{ route('articles.delete', ['article' => $article->id])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <p class="mt-2"><button class="button rounded bg-red-500 px-2 py-1 text-xs text-white">삭제</button></p>
                    </form>
                </div>
            </div>
            @endauth
        @endforeach
    </div>
    <div class="container p-5">
        {{ $articles->links() }}    
    </div>
</body>
</html>