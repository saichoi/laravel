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