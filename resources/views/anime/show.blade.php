@extends('layouts.app')

@section('title', $anime->title)

@section('content')
<a href="{{ route('anime.index') }}" class="text-purple-400 hover:text-purple-300 transition text-sm mb-4 inline-block">← Назад в каталог</a>

<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
    <div class="md:col-span-1">
        @if ($anime->image_url)
            <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-full rounded-xl shadow-lg">
        @else
            <div class="w-full aspect-[2/3] bg-gray-800 rounded-xl flex items-center justify-center text-6xl text-gray-600">🎬</div>
        @endif
    </div>
    <div class="md:col-span-2 space-y-4">
        <h1 class="text-4xl font-bold">{{ $anime->title }}</h1>
        
        <div class="flex flex-wrap gap-2">
            <span class="bg-purple-900/50 text-purple-300 px-3 py-1 rounded-full text-sm font-medium">⭐ {{ $anime->rating }}/10</span>
            <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-sm">{{ $anime->type }}</span>
            <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-sm">{{ $anime->episodes }} эп.</span>
            <span class="bg-gray-800 text-gray-300 px-3 py-1 rounded-full text-sm">{{ $anime->status === 'ongoing' ? '🟢 Выходит' : '🔵 Завершён' }}</span>
        </div>
        
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div><span class="text-gray-500">🎭 Жанр:</span> <span class="text-gray-300">{{ $anime->genre }}</span></div>
            <div><span class="text-gray-500">📅 Год:</span> <span class="text-gray-300">{{ $anime->year ?? '—' }}</span></div>
            <div><span class="text-gray-500">🏢 Студия:</span> <span class="text-gray-300">{{ $anime->studio ?? '—' }}</span></div>
        </div>

        <div>
            <h3 class="text-lg font-semibold mt-4 mb-2">Описание</h3>
            <p class="text-gray-400 leading-relaxed">{{ $anime->description ?? 'Описание отсутствует.' }}</p>
        </div>
        @auth
            @if (auth()->user()->isAdmin())
                <div class="mt-6 flex gap-3">
                    <a href="{{ route('anime.edit', $anime) }}" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        ✏ Редактировать
                    </a>
                    <form action="{{ route('anime.destroy', $anime) }}" method="POST" onsubmit="return confirm('Удалить {{ $anime->title }}?')">
                        @csrf @method('DELETE')
                        <button class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg transition">
                            🗑 Удалить
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</div>
@endsection