@extends('layouts.app')

@section('title', 'AnimeSide — твой мир аниме')

@section('content')
{{-- Hero --}}
<section class="text-center py-16 md:py-24">
    <h1 class="text-5xl md:text-7xl font-extrabold mb-6">
        Добро пожаловать в <span class="text-purple-400">AnimeSide</span>
    </h1>
    <p class="text-gray-400 text-lg md:text-xl max-w-2xl mx-auto mb-8">
        Твой персональный каталог аниме. Следи за новинками, оценивай любимые тайтлы и делись впечатлениями в блоге.
    </p>
    <div class="flex flex-wrap justify-center gap-4">
        <a href="{{ route('anime.index') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-8 rounded-xl transition shadow-lg shadow-purple-600/30 text-lg">
            🔍 Смотреть каталог
        </a>
        <a href="{{ route('posts.index') }}" class="bg-gray-800 hover:bg-gray-700 text-white font-semibold py-3 px-8 rounded-xl transition border border-gray-700 text-lg">
            📝 Читать блог
        </a>
    </div>
</section>

{{-- Блоки с аниме --}}
@if ($latestAnime->count())
<section class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">🆕 Последние добавления</h2>
        <a href="{{ route('anime.index') }}" class="text-purple-400 hover:text-purple-300 transition text-sm">Все →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ($latestAnime as $anime)
            <a href="{{ route('anime.show', $anime) }}" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden hover:border-gray-700 transition group">
                <div class="aspect-[2/3] bg-gray-800">
                    @if ($anime->image_url)
                        <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl text-gray-600 group-hover:text-purple-500 transition">🎬</div>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="font-semibold text-sm truncate group-hover:text-purple-400 transition">{{ $anime->title }}</h3>
                    <p class="text-xs text-gray-500 mt-1">⭐ {{ $anime->rating }} | {{ $anime->type }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

@if ($topAnime->count())
<section class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">🏆 Топ по рейтингу</h2>
        <a href="{{ route('anime.index', ['sort' => 'rating']) }}" class="text-purple-400 hover:text-purple-300 transition text-sm">Все →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach ($topAnime as $anime)
            <a href="{{ route('anime.show', $anime) }}" class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden hover:border-gray-700 transition group">
                <div class="aspect-[2/3] bg-gray-800">
                    @if ($anime->image_url)
                        <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl text-gray-600 group-hover:text-yellow-500 transition">🏆</div>
                    @endif
                </div>
                <div class="p-3">
                    <h3 class="font-semibold text-sm truncate group-hover:text-purple-400 transition">{{ $anime->title }}</h3>
                    <p class="text-xs text-yellow-500 mt-1">⭐ {{ $anime->rating }}</p>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- Последние посты --}}
@if ($latestPosts->count())
<section class="mb-12">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold">📝 Последние посты</h2>
        <a href="{{ route('posts.index') }}" class="text-purple-400 hover:text-purple-300 transition text-sm">Все →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($latestPosts as $post)
            <a href="{{ route('posts.show', $post) }}" class="bg-gray-900 border border-gray-800 rounded-xl p-5 hover:border-gray-700 transition group">
                <h3 class="font-semibold mb-2 group-hover:text-purple-400 transition">{{ $post->title }}</h3>
                <p class="text-gray-500 text-sm mb-3">{{ Str::limit($post->body, 100) }}</p>
                <span class="text-xs text-gray-600">{{ $post->created_at->format('d.m.Y') }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- Статистика --}}
<section class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ \App\Models\Anime::count() }}</div>
        <div class="text-gray-500 text-sm mt-1">Тайтлов в каталоге</div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ \App\Models\Anime::where('status', 'ongoing')->count() }}</div>
        <div class="text-gray-500 text-sm mt-1">Сейчас выходит</div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ \App\Models\Post::count() }}</div>
        <div class="text-gray-500 text-sm mt-1">Постов в блоге</div>
    </div>
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ number_format(\App\Models\Anime::avg('rating') ?? 0, 1) }}</div>
        <div class="text-gray-500 text-sm mt-1">Средний рейтинг</div>
    </div>
</section>
@endsection