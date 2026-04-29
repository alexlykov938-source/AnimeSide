@extends('layouts.app')

@section('title', 'Массовое добавление серий')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Массовое добавление серий: {{ $anime->title }}</h1>

    <form action="{{ route('episodes.bulk.store', $anime) }}" method="POST" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Сезон</label>
            <input type="number" name="season" value="{{ old('season', 1) }}" min="1"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Данные серий</label>
            <p class="text-gray-500 text-xs mb-2">Формат: номер | название | видео | озвучка (по одной серии на строку)</p>
            <textarea name="data" rows="15" placeholder="1 | Падение Стен | https://youtube.com/watch?v=abc | Студийная банда | https://vk.com/123, AniLibria | https://youtube.com/xyz&#10;2 | Титаны наступают | https://youtube.com/watch?v=def |&#10;3 | Свет во тьме | https://youtube.com/watch?v=ghi | Субтитры | https://crunchyroll.com/123"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent font-mono text-sm">{{ old('data') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-6 rounded-lg transition">💾 Добавить серии</button>
            <a href="{{ route('episodes.index', $anime) }}" class="text-gray-400 hover:text-white py-2.5 transition">← Назад</a>
        </div>
    </form>
</div>
@endsection