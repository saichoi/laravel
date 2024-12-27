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
        <h1 class="text-2xl">글쓰기</h1>
        <form action="{{ route('articles.store' )}}" method="POST" class="mt-3">
            <!-- <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>" /> -->
            @csrf
            <input type="text" name="body" class="block w-full mb-2 rounded" value="{{ old('body') }}">
            @error('body')
                <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
            @enderror
            <button type="submit" class="py-1 px-3 bg-black text-white rounded text-xs">저장하기</button>
        </form>
    </div>
</body>
</html>