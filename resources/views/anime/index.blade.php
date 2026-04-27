@extends('layouts.app')

@section('title', 'Каталог аниме')

@section('content')
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
    <h1 class="text-3xl font-bold">🎬 Каталог аниме</h1>
    @auth
        @if (auth()->user()->isAdmin())
            <a href="{{ route('anime.create') }}" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-5 rounded-lg transition shadow-lg shadow-purple-600/20">
                + Добавить аниме
            </a>
        @endif
    @endauth
</div>

{{-- Фильтры --}}
<div class="bg-gray-900 p-5 rounded-xl border border-gray-800 mb-8">
    <form action="{{ route('anime.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Жанр</label>
            <select name="genre" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="">Все жанры</option>
                @foreach ($genres as $genre)
                    <option value="{{ $genre }}" {{ request('genre') === $genre ? 'selected' : '' }}>{{ $genre }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Тип</label>
            <select name="type" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="">Все типы</option>
                @foreach ($types as $type)
                    <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex flex-col gap-1">
            <label class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Статус</label>
            <select name="status" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="">Все статусы</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Выходит</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Завершён</option>
                <option value="announced" {{ request('status') === 'announced' ? 'selected' : '' }}>Анонс</option>
            </select>
        </div>

        <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition">🔍 Применить</button>
        <a href="{{ route('anime.index') }}" class="text-gray-400 hover:text-white text-sm py-2 transition">Сбросить</a>
    </form>
</div>

<p class="text-gray-400 mb-6">Найдено: <span class="text-white font-semibold">{{ $animes->count() }}</span> тайтлов</p>

{{-- Список --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @forelse ($animes as $anime)
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden flex flex-col sm:flex-row hover:border-gray-700 transition shadow-lg">
            <div class="sm:w-40 h-52 sm:h-auto bg-gray-800 flex-shrink-0">
                @if ($anime->image_url)
                    <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-4xl text-gray-600">🎬</div>
                @endif
            </div>
            <div class="p-5 flex flex-col justify-between flex-1">
                <div>
                    <h2 class="text-xl font-bold mb-1">
                        <a href="{{ route('anime.show', $anime) }}" class="hover:text-purple-400 transition">{{ $anime->title }}</a>
                    </h2>
                    <div class="flex items-center gap-2 text-sm text-gray-400 mb-2 flex-wrap">
                        <span>⭐ {{ $anime->rating }}</span>
                        <span>•</span>
                        <span>{{ $anime->type }}</span>
                        <span>•</span>
                        <span>{{ $anime->episodes }} эп.</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">🎭 {{ $anime->genre }}</p>
                    <p class="text-sm text-gray-500 mb-3">🏢 {{ $anime->studio ?? 'Неизвестно' }}</p>
                    <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $anime->status === 'ongoing' ? 'bg-green-900 text-green-300' : 'bg-blue-900 text-blue-300' }}">
                        {{ $anime->status === 'ongoing' ? '🟢 Выходит' : ($anime->status === 'completed' ? '🔵 Завершён' : '🟡 Анонс') }}
                    </span>
                </div>
                @auth
                    @if (auth()->user()->isAdmin())
                        <div class="flex gap-3 mt-3">
                            <a href="{{ route('anime.edit', $anime) }}" class="text-sm text-purple-400 hover:text-purple-300 transition">✏</a>
                            <form action="{{ route('anime.destroy', $anime) }}" method="POST" onsubmit="return confirm('Удалить {{ $anime->title }}?')">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-400 hover:text-red-300 transition">🗑</button>
                            </form>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    @empty
        <div class="col-span-full text-center py-12 text-gray-500">
            Ничего не найдено. <a href="{{ route('anime.index') }}" class="text-purple-400 underline">Сбросить фильтры</a>
        </div>
    @endforelse
</div>
@endsection