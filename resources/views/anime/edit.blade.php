@extends('layouts.app')

@section('title', 'Редактировать: ' . $anime->title)

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Редактировать: {{ $anime->title }}</h1>

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-900/50 border border-red-700 text-red-300 rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('anime.update', $anime) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Название *</label>
            <input type="text" name="title" value="{{ old('title', $anime->title) }}"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Жанр * (через запятую)</label>
            <input type="text" name="genre" value="{{ old('genre', $anime->genre) }}" placeholder="Экшен, Приключения, Фэнтези"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Тип</label>
                <select name="type"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">Выберите тип</option>
                    <option value="Сериал" {{ old('type', $anime->type) === 'Сериал' ? 'selected' : '' }}>Сериал</option>
                    <option value="Фильм" {{ old('type', $anime->type) === 'Фильм' ? 'selected' : '' }}>Фильм</option>
                    <option value="OVA" {{ old('type', $anime->type) === 'OVA' ? 'selected' : '' }}>OVA</option>
                    <option value="ONA" {{ old('type', $anime->type) === 'ONA' ? 'selected' : '' }}>ONA</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Статус *</label>
                <select name="status"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="ongoing" {{ old('status', $anime->status) === 'ongoing' ? 'selected' : '' }}>🟢 Выходит</option>
                    <option value="completed" {{ old('status', $anime->status) === 'completed' ? 'selected' : '' }}>🔵 Завершён</option>
                    <option value="announced" {{ old('status', $anime->status) === 'announced' ? 'selected' : '' }}>🟡 Анонс</option>
                </select>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Серий</label>
                <input type="number" name="episodes" value="{{ old('episodes', $anime->episodes) }}" min="0"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Год</label>
                <input type="number" name="year" value="{{ old('year', $anime->year) }}" min="1950" max="{{ date('Y') + 1 }}"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Рейтинг</label>
                <input type="number" name="rating" value="{{ old('rating', $anime->rating) }}" step="0.01" min="0" max="10"
                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Студия</label>
            <input type="text" name="studio" value="{{ old('studio', $anime->studio) }}"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Постер (JPEG, PNG, WebP, до 2 МБ)</label>
            @if ($anime->image_url)
                <img src="{{ $anime->image_url }}" alt="{{ $anime->title }}" class="w-24 rounded-lg mb-2">
            @endif
            <input type="file" name="image" accept="image/*"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:text-sm file:font-medium hover:file:bg-purple-700 transition focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            @error('image')
                <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Описание</label>
            <textarea name="description" rows="5"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description', $anime->description) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-6 rounded-lg transition shadow-lg shadow-purple-600/20">
                💾 Сохранить изменения
            </button>
            <a href="{{ route('anime.index') }}" class="text-gray-400 hover:text-white py-2.5 transition">← Назад</a>
        </div>
    </form>
</div>
@endsection