@extends('layouts.app')

@section('title', 'Добавить серию')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Добавить серию: {{ $anime->title }}</h1>

    @if ($errors->any())
        <div class="mb-4 p-4 bg-red-900/50 border border-red-700 text-red-300 rounded-lg">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('episodes.store', $anime) }}" method="POST" class="space-y-5">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Сезон</label>
                <input type="number" name="season" value="{{ old('season', 1) }}" min="1" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-300 mb-1">Номер серии</label>
                <input type="number" name="episode_number" value="{{ old('episode_number') }}" min="1" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Название</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Ссылка на видео *</label>
            <input type="text" name="video_url" value="{{ old('video_url') }}" placeholder="https://..." class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Озвучка (через запятую, если несколько ссылок)</label>
            <input type="text" name="dubbing" value="{{ old('dubbing') }}" 
                placeholder="Студийная банда | ссылка, AniLibria | ссылка"
                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <p class="text-gray-500 text-xs mt-1">Формат: Название озвучки | ссылка_на_видео, Название2 | ссылка2</p>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-300 mb-1">Описание</label>
            <textarea name="description" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description') }}</textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2.5 px-6 rounded-lg transition">💾 Сохранить</button>
            <a href="{{ route('episodes.index', $anime) }}" class="text-gray-400 hover:text-white py-2.5 transition">← Назад</a>
        </div>
    </form>
</div>
@endsection