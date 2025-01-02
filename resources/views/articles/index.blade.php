<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            글목록
        </h2>
    </x-slot>
    <div class="container p-5">
        @foreach ($articles as $article)

            <div class="background-white border rounded mb-3 p-3">
                <p>{{ $article->body }}</p>
                <p>{{ $article->user->name }}</p>
                <p><a href="{{ route('articles.show', ['article' => $article]) }}">{{ $article->created_at->diffForHumans() }}</a></p>
                <div class="flex flex-row space-x-1">
                    @can('update', $article)
                    <p class="mt-2"><a href="{{ route('articles.edit', ['article' => $article]) }}" class="button rounded bg-blue-500 px-2 py-1 text-xs text-white">수정</a></p>
                    @endcan
                    @can('delete', $article)
                    <form action="{{ route('articles.destroy', ['article' => $article->id])}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <p class="mt-2"><button class="button rounded bg-red-500 px-2 py-1 text-xs text-white">삭제</button></p>
                    </form>
                    @endcan
                </div>
            </div>

        @endforeach
    </div>
    <div class="container p-5">
        {{ $articles->links() }}    
    </div>
</x-app-layout>